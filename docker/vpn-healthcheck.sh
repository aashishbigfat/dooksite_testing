#!/usr/bin/env bash
# Confirms the database is actually reachable through the VPN tunnel, and
# restarts the tunnel if it is not.
#
# Why this exists beyond systemd's own Restart=always: systemd only sees
# whether the openvpn *process* is alive. A tunnel can be up, the interface
# present and the process healthy, while no traffic passes - a stale session
# after the server restarts, a revoked seat, a route that did not get pushed.
# systemd is content; the site is down.
#
# It is down in the worst possible way, too. The app cannot reach the
# database, Laravel throws, and bootstrap/app.php turns every exception into
# a 302 to the homepage - so the site looks like it is working. Nothing in
# the application logs says otherwise. This script is what notices.
#
# Install (see explainers/gcp_hosting_guide.md):
#   sudo install -m 755 docker/vpn-healthcheck.sh /usr/local/bin/vpn-healthcheck
#   sudo cp docker/dookwebsite-vpn-healthcheck.{service,timer} /etc/systemd/system/
#   sudo systemctl daemon-reload
#   sudo systemctl enable --now dookwebsite-vpn-healthcheck.timer

set -uo pipefail

DB_HOST="${DB_HOST:-192.168.4.7}"
DB_PORT="${DB_PORT:-3306}"
VPN_UNIT="${VPN_UNIT:-openvpn-client@dook.service}"
TUN_IF="${TUN_IF:-tun0}"

log() { logger -t vpn-healthcheck -- "$*"; echo "$*"; }

fail=""

# 1. Interface exists.
if ! ip link show "$TUN_IF" >/dev/null 2>&1; then
    fail="interface $TUN_IF is missing"

# 2. The database route actually goes through the tunnel. Catches the case
#    where the tunnel is up but the route was never pushed, so traffic would
#    silently leave via the default gateway and be dropped.
elif ! ip route get "$DB_HOST" 2>/dev/null | grep -q "dev $TUN_IF"; then
    fail="$DB_HOST does not route via $TUN_IF"

# 3. The port actually answers. This is the only check that proves traffic
#    is passing end to end rather than just that config looks right.
elif ! timeout 8 bash -c "</dev/tcp/$DB_HOST/$DB_PORT" 2>/dev/null; then
    fail="$DB_HOST:$DB_PORT did not accept a connection"
fi

if [ -z "$fail" ]; then
    exit 0
fi

log "UNHEALTHY: $fail - restarting $VPN_UNIT"
systemctl restart "$VPN_UNIT"

# Give the tunnel time to come up and routes to be installed before judging.
for _ in $(seq 1 12); do
    sleep 5
    if ip route get "$DB_HOST" 2>/dev/null | grep -q "dev $TUN_IF" \
       && timeout 8 bash -c "</dev/tcp/$DB_HOST/$DB_PORT" 2>/dev/null; then
        log "RECOVERED: $DB_HOST:$DB_PORT reachable via $TUN_IF"
        exit 0
    fi
done

log "STILL DOWN after restarting $VPN_UNIT - the site cannot reach the database."
log "Check: journalctl -u $VPN_UNIT -n 50"
exit 1
