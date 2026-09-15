@extends('frontend.layouts.master')

@push('title') CIS & Central Asia DMC for European Travel Agents | Dook International @endpush

@push('meta_tag')
<meta name="description" content="B2B CIS & Central Asia DMC for European travel agents. Request customized FIT, group & MICE programmes, hotels, transfers, sightseeing and B2B quotations.">
<meta name="keywords" content="CIS DMC, Central Asia DMC, CIS DMC for European travel agents, B2B CIS tour packages, Central Asia B2B travel packages, Kazakhstan DMC, Uzbekistan DMC, Azerbaijan DMC, Georgia DMC, Kyrgyzstan DMC">
@endpush

@push('css')
<style>
/* Palette and components mirror public/assets/style.css (:root --primary, .stat-card,
   .section-pretitle, .view-all-btn, .page-title, .breadcrumb-nav) so this page reads as
   part of the main site. */
.b2b-page{
  --b2b-primary:#d71921;
  --b2b-primary-dark:#b5141b;
  --b2b-grad:linear-gradient(135deg,#d71921 0%,#ff4444 100%);
  --b2b-ink:#1f2937;
  --b2b-muted:#6b7280;
  --b2b-line:#e5e7eb;
  --b2b-tint:#fff4f4;
  --b2b-pink:#ffdede;
  --b2b-soft:linear-gradient(135deg,#fef2f2 0%,#fee2e2 100%);
  color:var(--b2b-ink);
  font-family:"Outfit",sans-serif;
  font-size:16px;
  line-height:1.65;
}
.b2b-page *{box-sizing:border-box;}
.b2b-wrap{max-width:1180px;margin:0 auto;padding:0 20px;}
.b2b-section{padding:64px 0;background:#fff;}
.b2b-section--tint{background:var(--b2b-tint);}
.b2b-eyebrow{
  display:inline-block;font-size:.75rem;letter-spacing:2px;text-transform:uppercase;
  font-weight:600;color:var(--b2b-primary);margin-bottom:10px;
}
.b2b-h2{
  position:relative;font-size:clamp(26px,3.3vw,38px);line-height:1.2;font-weight:800;
  letter-spacing:-.5px;color:var(--b2b-ink);margin:0 0 26px;padding-bottom:14px;
}
.b2b-h2::after{
  content:"";position:absolute;left:0;bottom:0;width:60px;height:4px;border-radius:2px;
  background:linear-gradient(45deg,#dc3545,#c0392b);
}
.b2b-sub{font-size:17px;color:var(--b2b-muted);max-width:780px;margin:0 0 36px;}
.b2b-grid{display:grid;gap:22px;}
.b2b-grid--2{grid-template-columns:repeat(auto-fit,minmax(300px,1fr));}
.b2b-grid--3{grid-template-columns:repeat(auto-fit,minmax(262px,1fr));}
.b2b-grid--4{grid-template-columns:repeat(auto-fit,minmax(215px,1fr));}

/* cards - same hover language as the homepage .stat-card */
.b2b-card,.b2b-dest,.b2b-pkg{
  position:relative;overflow:hidden;background:#fff;border:2px solid var(--b2b-line);
  border-radius:.75rem;transition:border-color .3s ease, box-shadow .3s ease, transform .3s ease;
}
.b2b-card::after,.b2b-pkg::after{
  content:"";position:absolute;left:0;right:0;bottom:0;height:3px;background:var(--b2b-primary);
  transform:scaleX(0);transition:transform .3s ease;
}
.b2b-card:hover,.b2b-dest:hover,.b2b-pkg:hover{
  border-color:var(--b2b-primary);box-shadow:0 .5rem 1.5rem rgba(215,25,33,.12);transform:translateY(-.25rem);
}
.b2b-card:hover::after,.b2b-pkg:hover::after{transform:scaleX(1);}
.b2b-card{padding:26px;height:100%;}
.b2b-card h3{font-size:18px;font-weight:700;color:var(--b2b-ink);margin:0 0 9px;}
.b2b-card p{margin:0;color:var(--b2b-muted);font-size:15px;}
.b2b-card p strong{color:var(--b2b-ink);}

/* buttons - pill + gradient, as .view-all-btn / .btn-explore */
.b2b-btn{
  display:inline-flex;align-items:center;justify-content:center;gap:8px;
  background:var(--b2b-grad);color:#fff;font-weight:700;font-size:15px;letter-spacing:.02em;
  padding:14px 32px;border-radius:50px;border:0;text-decoration:none;cursor:pointer;
  box-shadow:0 6px 20px rgba(215,25,33,.25);
  transition:transform .3s ease, box-shadow .3s ease, background .3s ease, color .3s ease;
}
.b2b-btn:hover{color:#fff;text-decoration:none;transform:translateY(-3px);box-shadow:0 12px 36px rgba(215,25,33,.35);}
.b2b-btn:focus-visible{outline:3px solid rgba(215,25,33,.35);outline-offset:3px;}
.b2b-btn--ghost{background:#fff;border:2px solid var(--b2b-primary);color:var(--b2b-primary);box-shadow:none;}
.b2b-btn--ghost:hover{background:var(--b2b-primary);color:#fff;}
.b2b-btn--block{display:flex;width:100%;}
.b2b-btn[disabled]{opacity:.65;cursor:not-allowed;transform:none;box-shadow:none;}

/* hero - pink band like the homepage destinations section */
.b2b-hero{
  position:relative;overflow:hidden;color:var(--b2b-ink);padding:60px 0 68px;
  background:linear-gradient(135deg,var(--b2b-pink) 0%,var(--b2b-tint) 60%,#fff 100%);
}
.b2b-hero::before{
  content:"";position:absolute;top:-140px;right:-140px;width:460px;height:460px;border-radius:50%;
  background:radial-gradient(circle,rgba(215,25,33,.08) 0%,transparent 70%);pointer-events:none;
}
.b2b-hero .b2b-wrap{position:relative;z-index:1;}
.b2b-hero__grid{display:grid;grid-template-columns:1.08fr .92fr;gap:48px;align-items:start;}
.b2b-hero h1{
  font-size:clamp(30px,4.1vw,46px);line-height:1.12;font-weight:800;letter-spacing:-1px;
  margin:0 0 18px;color:var(--b2b-ink);
}
.b2b-hero__promise{
  display:inline-block;background:#fff;color:var(--b2b-primary);border:2px solid rgba(215,25,33,.12);
  box-shadow:0 8px 25px rgba(220,53,69,.1);padding:7px 18px;border-radius:50px;
  font-size:13px;font-weight:700;margin-bottom:20px;
}
.b2b-hero__grid > div > p{font-size:16.5px;color:var(--b2b-muted);margin:0 0 26px;max-width:560px;}
.b2b-hero__grid > div > .b2b-hero__line{font-size:15px;font-weight:600;color:var(--b2b-primary);margin:0 0 18px;}
.b2b-hero__ctas{display:flex;gap:14px;flex-wrap:wrap;margin-bottom:30px;}
.b2b-trust{display:flex;flex-wrap:wrap;gap:14px;border-top:1px solid rgba(215,25,33,.15);padding-top:22px;}
.b2b-trust div{flex:1;min-width:118px;}
.b2b-trust strong{display:block;font-size:26px;font-weight:900;color:var(--b2b-primary);line-height:1.2;}
.b2b-trust span{font-size:12px;font-weight:600;text-transform:uppercase;letter-spacing:.03em;color:var(--b2b-muted);}

/* form */
.b2b-form{background:#fff;border-radius:16px;border:2px solid var(--b2b-line);box-shadow:0 22px 55px rgba(215,25,33,.12);overflow:hidden;}
.b2b-form__head{background:var(--b2b-grad);color:#fff;padding:18px 26px;}
.b2b-form__head h2{font-size:17px;font-weight:700;margin:0;letter-spacing:.04em;text-transform:uppercase;color:#fff;}
.b2b-form__head p{font-size:13px;color:rgba(255,255,255,.88);margin:5px 0 0;}
.b2b-form__body{padding:24px 26px 28px;}
.b2b-field{margin-bottom:15px;}
.b2b-field label{display:block;font-size:12.5px;font-weight:700;color:var(--b2b-ink);margin-bottom:6px;letter-spacing:.02em;}
.b2b-field .req{color:var(--b2b-primary);}
.b2b-field input,.b2b-field select,.b2b-field textarea{
  width:100%;padding:11px 13px;border:1px solid #ddd;border-radius:8px;font-size:14.5px;
  font-family:inherit;color:var(--b2b-ink);background:#fff;transition:border-color .2s ease, box-shadow .2s ease;
}
.b2b-field input::placeholder,.b2b-field textarea::placeholder{color:#bfbfbf;opacity:1;}
.b2b-field input:focus,.b2b-field select:focus,.b2b-field textarea:focus{
  outline:none;border-color:var(--b2b-primary);box-shadow:0 0 0 3px rgba(215,25,33,.12);
}
.b2b-field textarea{resize:vertical;min-height:74px;}
.b2b-form__row{display:grid;grid-template-columns:1fr 1fr;gap:13px;}
.b2b-form__note{font-size:12px;color:var(--b2b-muted);margin:13px 0 0;text-align:center;line-height:1.5;}
.b2b-form__msg{display:none;margin-top:13px;padding:11px 14px;border-radius:8px;font-size:14px;}
.b2b-form__msg.ok{display:block;background:#e7f6ec;color:#1b6b38;border:1px solid #b7e3c6;}
.b2b-form__msg.err{display:block;background:#fdecea;color:#9c2c20;border:1px solid #f5c2bc;}

/* destinations */
.b2b-dest{padding:22px 24px;border-left:4px solid var(--b2b-primary);}
.b2b-dest h3{font-size:17px;font-weight:800;color:var(--b2b-ink);margin:0 0 4px;}
.b2b-dest .cities{font-size:13.5px;font-weight:500;color:var(--b2b-primary);font-style:italic;margin:0 0 8px;}
.b2b-dest p{margin:0;font-size:14.5px;color:var(--b2b-muted);}

/* process - numbered badges styled like .stat-icon */
.b2b-step{display:flex;gap:18px;align-items:flex-start;padding:20px 0;border-bottom:1px solid var(--b2b-line);}
.b2b-step:last-child{border-bottom:0;}
.b2b-step__num{
  flex:0 0 50px;height:50px;width:50px;border-radius:50%;background:var(--b2b-soft);color:var(--b2b-primary);
  display:flex;align-items:center;justify-content:center;font-weight:800;font-size:15px;
  transition:background .3s ease, color .3s ease, transform .3s ease;
}
.b2b-step:hover .b2b-step__num{background:var(--b2b-primary);color:#fff;transform:scale(1.05);}
.b2b-step h3{font-size:16.5px;font-weight:700;color:var(--b2b-ink);margin:0 0 5px;}
.b2b-step p{margin:0;color:var(--b2b-muted);font-size:15px;}

/* checklist */
.b2b-check{list-style:none;padding:0;margin:0;display:grid;grid-template-columns:repeat(auto-fit,minmax(268px,1fr));gap:11px;}
.b2b-check li{
  position:relative;padding:12px 16px 12px 46px;font-size:15px;color:var(--b2b-ink);
  background:#fff;border:1px solid var(--b2b-line);border-radius:10px;
}
.b2b-check li::before{
  content:"";position:absolute;left:14px;top:50%;width:20px;height:20px;margin-top:-10px;
  border-radius:50%;background:var(--b2b-soft);
}
.b2b-check li::after{
  content:"";position:absolute;left:20px;top:50%;width:9px;height:5px;margin-top:-4px;
  border-left:2px solid var(--b2b-primary);border-bottom:2px solid var(--b2b-primary);transform:rotate(-45deg);
}

/* packages */
.b2b-pkg{padding:24px;display:flex;flex-direction:column;height:100%;}
.b2b-pkg__country{
  align-self:flex-start;font-size:11px;font-weight:800;letter-spacing:.12em;text-transform:uppercase;
  color:var(--b2b-primary);background:var(--b2b-soft);padding:4px 12px;border-radius:50px;margin-bottom:12px;
}
.b2b-pkg h3{font-size:18px;font-weight:700;color:var(--b2b-ink);margin:0 0 8px;}
.b2b-pkg p{font-size:14.5px;color:var(--b2b-muted);margin:0 0 18px;flex-grow:1;}
.b2b-pkg a{font-size:14px;font-weight:700;color:var(--b2b-primary);text-decoration:none;transition:color .2s ease;}
.b2b-pkg a:hover{color:var(--b2b-primary-dark);text-decoration:underline;}

/* markets */
.b2b-market{padding:16px 0;border-bottom:1px solid rgba(215,25,33,.12);}
.b2b-market:last-child{border-bottom:0;}
.b2b-market strong{color:var(--b2b-primary);font-weight:700;}
.b2b-market span{color:var(--b2b-muted);}

/* faq */
.b2b-faq details{
  background:#fff;border:2px solid var(--b2b-line);border-radius:12px;margin-bottom:11px;overflow:hidden;
  transition:border-color .3s ease, box-shadow .3s ease;
}
.b2b-faq details[open]{border-color:rgba(215,25,33,.35);box-shadow:0 .5rem 1.5rem rgba(215,25,33,.08);}
.b2b-faq summary{
  padding:17px 22px;font-weight:700;font-size:16px;color:var(--b2b-ink);cursor:pointer;
  list-style:none;position:relative;padding-right:60px;transition:color .2s ease;
}
.b2b-faq summary:hover,.b2b-faq details[open] summary{color:var(--b2b-primary);}
.b2b-faq summary::-webkit-details-marker{display:none;}
.b2b-faq summary::after{
  content:"+";position:absolute;right:18px;top:50%;transform:translateY(-50%);
  width:28px;height:28px;border-radius:50%;background:var(--b2b-soft);color:var(--b2b-primary);
  display:flex;align-items:center;justify-content:center;font-size:20px;font-weight:500;line-height:1;
}
.b2b-faq details[open] summary::after{content:"\2013";background:var(--b2b-primary);color:#fff;}
.b2b-faq .b2b-faq__a{padding:0 22px 19px;color:var(--b2b-muted);font-size:15px;margin:0;}

/* final cta - red band, white pill button (as .btn-contact) */
.b2b-final{position:relative;overflow:hidden;background:var(--b2b-grad);color:#fff;text-align:center;padding:70px 0;}
.b2b-final::before,.b2b-final::after{
  content:"";position:absolute;border-radius:50%;background:rgba(255,255,255,.08);pointer-events:none;
}
.b2b-final::before{width:380px;height:380px;top:-190px;left:-120px;}
.b2b-final::after{width:300px;height:300px;bottom:-170px;right:-80px;}
.b2b-final .b2b-wrap{position:relative;z-index:1;}
.b2b-final h2{font-size:clamp(26px,3.4vw,40px);font-weight:800;letter-spacing:-.5px;margin:0 0 14px;color:#fff;}
.b2b-final p{font-size:17px;color:rgba(255,255,255,.9);max-width:660px;margin:0 auto 12px;}
.b2b-final .lead{font-size:19px;font-weight:600;color:#fff;margin-bottom:20px;}
.b2b-final .b2b-btn{margin-top:16px;background:#fff;color:var(--b2b-primary);box-shadow:0 10px 30px rgba(0,0,0,.18);}
.b2b-final .b2b-btn:hover{background:#fff;color:var(--b2b-primary-dark);box-shadow:0 15px 40px rgba(0,0,0,.25);}

/* misc */
.b2b-note{
  background:#fff;border:2px solid rgba(215,25,33,.12);border-left:4px solid var(--b2b-primary);
  border-radius:10px;padding:18px 22px;font-size:14.5px;color:var(--b2b-muted);
}
.b2b-note strong{color:var(--b2b-ink);}
.b2b-contact{font-size:15px;color:rgba(255,255,255,.9);margin-top:22px;}
.b2b-contact a{color:#fff;font-weight:700;text-decoration:underline;}

@media (max-width:900px){
  .b2b-hero__grid{grid-template-columns:1fr;gap:34px;}
  .b2b-section{padding:48px 0;}
}
@media (max-width:520px){
  .b2b-form__row{grid-template-columns:1fr;}
  .b2b-trust strong{font-size:22px;}
  .b2b-btn{width:100%;text-align:center;}
}
@media (prefers-reduced-motion:reduce){
  .b2b-page *{transition:none !important;}
  .b2b-card:hover,.b2b-dest:hover,.b2b-pkg:hover,.b2b-btn:hover{transform:none;}
}
</style>
@endpush

@section('content')
<div class="b2b-page">

  {{-- ========== HERO + RFQ FORM ========== --}}
  <section class="b2b-hero" id="b2b-top">
    <div class="b2b-wrap">
      <div class="b2b-hero__grid">
        <div>
          <span class="b2b-hero__promise">You bring the client. We handle the destination.</span>
          <h1>B2B CIS &amp; Central Asia Travel Partner for European Travel Agents</h1>
          <p class="b2b-hero__line">Competitive B2B Rates · FIT &amp; Groups · MICE · Hotels · Transfers · Sightseeing · Customized Programmes</p>
          <p>
            Build and sell CIS &amp; Central Asia travel programmes with a destination partner that can
            support your client requirements from itinerary design to ground operations. Share your
            destination, travel dates and passenger requirements and our B2B team can prepare a
            tailored proposal.
          </p>
          <div class="b2b-hero__ctas">
            <a href="#b2b-quote" class="b2b-btn">Request B2B Quote</a>
            <a href="#b2b-process" class="b2b-btn b2b-btn--ghost">Talk to a B2B Travel Specialist</a>
          </div>
          <div class="b2b-trust">
            <div><strong>13+</strong><span>Years Experience</span></div>
            <div><strong>1M+</strong><span>Travellers</span></div>
            <div><strong>500+</strong><span>Global Partners</span></div>
            <div><strong>50+</strong><span>Countries</span></div>
          </div>
        </div>

        <div class="b2b-form" id="b2b-quote">
          <div class="b2b-form__head">
            <h2>Get Your B2B CIS Quote</h2>
            <p>For travel trade professionals only.</p>
          </div>
          <div class="b2b-form__body">
            <form id="b2bQuoteForm" novalidate>
              @csrf
              <input type="hidden" name="type" value="APL">
              <input type="hidden" name="form_type" value="B2B Partnership Form">
              <input type="hidden" name="url" value="{{ url()->current() }}">
              <input type="hidden" name="fullurl" value="{{ url()->full() }}">
              <input type="hidden" name="browserName" id="b2bBrowserName">
              <input type="hidden" name="name" id="b2bFullName">

              <div class="b2b-form__row">
                <div class="b2b-field">
                  <label for="b2bFirstName">First Name <span class="req">*</span></label>
                  <input type="text" id="b2bFirstName" name="first_name" required autocomplete="given-name">
                </div>
                <div class="b2b-field">
                  <label for="b2bLastName">Last Name</label>
                  <input type="text" id="b2bLastName" name="last_name" autocomplete="family-name">
                </div>
              </div>

              <div class="b2b-form__row">
                <div class="b2b-field">
                  <label for="b2bEmail">Business Email <span class="req">*</span></label>
                  <input type="email" id="b2bEmail" name="email" required autocomplete="email">
                </div>
                <div class="b2b-field">
                  <label for="b2bPhone">WhatsApp / Phone <span class="req">*</span></label>
                  <input type="tel" id="b2bPhone" name="mobile" required autocomplete="tel" placeholder="+44 …">
                </div>
              </div>

              <div class="b2b-form__row">
                <div class="b2b-field">
                  <label for="b2bCompany">Company / Agency Name <span class="req">*</span></label>
                  <input type="text" id="b2bCompany" name="company_name" required autocomplete="organization">
                </div>
                <div class="b2b-field">
                  <label for="b2bCountry">Country <span class="req">*</span></label>
                  <input type="text" id="b2bCountry" name="agency_country" required autocomplete="country-name">
                </div>
              </div>

              <div class="b2b-form__row">
                <div class="b2b-field">
                  <label for="b2bCity">City</label>
                  <input type="text" id="b2bCity" name="agency_city">
                </div>
                <div class="b2b-field">
                  <label for="b2bTravelDate">Travel Dates / Month</label>
                  <input type="month" id="b2bTravelDate" name="travel_date" min="{{ now()->format('Y-m') }}" pattern="[0-9]{4}-[0-9]{2}" placeholder="YYYY-MM" title="Month and year, e.g. 2027-05">
                </div>
              </div>

              <div class="b2b-form__row">
                <div class="b2b-field">
                  <label for="b2bPax">Number of Travellers <span class="req">*</span></label>
                  <input type="number" id="b2bPax" name="no_of_traveler" min="1" required>
                </div>
                <div class="b2b-field">
                  <label for="b2bTravelType">Travel Type</label>
                  <select id="b2bTravelType" name="travel_type">
                    <option value="">Select</option>
                    <option value="FIT">FIT</option>
                    <option value="GIT">GIT / Group</option>
                    <option value="MICE">MICE</option>
                    <option value="Other">Other</option>
                  </select>
                </div>
              </div>

              <div class="b2b-field">
                <label for="b2bDestinations">Interested Destination(s) <span class="req">*</span></label>
                <input type="text" id="b2bDestinations" name="destination" required placeholder="e.g. Uzbekistan, Kazakhstan">
              </div>

              <div class="b2b-form__row">
                <div class="b2b-field">
                  <label for="b2bBudget">Budget Range / Client Category</label>
                  <input type="text" id="b2bBudget" name="budget_range">
                </div>
                <div class="b2b-field">
                  <label for="b2bHotel">Preferred Hotel Category</label>
                  <select id="b2bHotel" name="hotel_category">
                    <option value="">Select</option>
                    <option value="3 Star">3 Star</option>
                    <option value="4 Star">4 Star</option>
                    <option value="5 Star">5 Star</option>
                    <option value="Mixed">Mixed</option>
                  </select>
                </div>
              </div>

              <div class="b2b-field">
                <label for="b2bRequirement">Tell Us About Your Requirement</label>
                <textarea id="b2bRequirement" name="comment" placeholder="Routing, must-see attractions, meals, guide language, special requests…"></textarea>
              </div>

              <button type="submit" class="b2b-btn b2b-btn--block" id="b2bSubmitBtn">Get My B2B Proposal</button>
              <div class="b2b-form__msg" id="b2bFormMsg" role="status" aria-live="polite"></div>
              <p class="b2b-form__note">For travel trade professionals only. Share your requirement and our B2B team will contact you.</p>
            </form>
          </div>
        </div>
      </div>
    </div>
  </section>

  {{-- ========== PROBLEM ========== --}}
  <section class="b2b-section">
    <div class="b2b-wrap">
      <span class="b2b-eyebrow">The B2B Challenge</span>
      <h2 class="b2b-h2">Planning CIS &amp; Central Asia Tours Shouldn’t Mean Managing Multiple Suppliers</h2>
      <p class="b2b-sub">
        When an overseas client asks for a multi-city CIS or Central Asia programme, the challenge is
        rarely finding a destination. The real challenge is coordinating hotels, transportation, guides,
        sightseeing, meals, special requests, itinerary timing and local operations while keeping your
        client relationship under your agency’s control.
      </p>
      <div class="b2b-grid b2b-grid--3">
        <div class="b2b-card"><h3>Too Many Local Suppliers</h3><p>Coordinating different hotels, transport providers and guides can slow down the quotation process.</p></div>
        <div class="b2b-card"><h3>Unclear B2B Pricing</h3><p>Agents need commercially workable rates and a clear quotation structure before presenting a programme to clients.</p></div>
        <div class="b2b-card"><h3>Complex Multi-City Planning</h3><p>CIS and Central Asia programmes often involve several cities, transport legs and different service providers.</p></div>
        <div class="b2b-card"><h3>Last-Minute Changes</h3><p>Flight changes, hotel issues and client requests require responsive destination coordination.</p></div>
        <div class="b2b-card"><h3>Time-Consuming Quotations</h3><p>Every enquiry can require hours of supplier research, comparison and follow-up.</p></div>
        <div class="b2b-card"><h3>Client Relationship Risk</h3><p>B2B agents need a destination partner that works behind the scenes and supports the agency relationship.</p></div>
      </div>
    </div>
  </section>

  {{-- ========== SOLUTION ========== --}}
  <section class="b2b-section b2b-section--tint">
    <div class="b2b-wrap">
      <span class="b2b-eyebrow">The Solution</span>
      <h2 class="b2b-h2">You Sell the Journey. We Support the Destination.</h2>
      <p class="b2b-sub">
        Dook International can be positioned as the B2B destination partner behind your CIS and Central
        Asia programmes. Instead of building every component independently, travel professionals can send
        one requirement covering destination, dates, pax, hotel preference and service needs.
      </p>
      <div class="b2b-grid b2b-grid--3">
        <div class="b2b-card"><h3>One B2B Partner</h3><p>Coordinate multiple CIS and Central Asia requirements through one travel-trade relationship.</p></div>
        <div class="b2b-card"><h3>Destination Expertise</h3><p>Build programmes around destination highlights, practical routing and client preferences.</p></div>
        <div class="b2b-card"><h3>Custom Itineraries</h3><p>Adapt duration, hotel category, transport, sightseeing and experiences to the brief.</p></div>
        <div class="b2b-card"><h3>FIT &amp; Groups</h3><p>Support individual, family, couple, group and other programme formats where available.</p></div>
        <div class="b2b-card"><h3>MICE &amp; Corporate</h3><p>Meetings, incentives, events and corporate travel requirements where offered.</p></div>
        <div class="b2b-card"><h3>Operational Support</h3><p>Coordinate the destination-side services needed to deliver the confirmed itinerary.</p></div>
      </div>
    </div>
  </section>

  {{-- ========== DESTINATIONS ========== --}}
  <section class="b2b-section" id="b2b-destinations">
    <div class="b2b-wrap">
      <span class="b2b-eyebrow">Destination Coverage</span>
      <h2 class="b2b-h2">One B2B Partner. Multiple CIS &amp; Central Asia Destinations.</h2>
      <p class="b2b-sub">Send a single brief covering one country or a multi-country routing across the region.</p>
      <div class="b2b-grid b2b-grid--3">
        <div class="b2b-dest"><h3>Azerbaijan</h3><p class="cities">Baku, Gabala, Gobustan, Sheki</p><p>City breaks, culture, food, nature and Silk Road experiences.</p></div>
        <div class="b2b-dest"><h3>Kazakhstan</h3><p class="cities">Almaty, Astana, Shymbulak, Charyn Canyon</p><p>Modern cities, mountains, lakes, nature and adventure.</p></div>
        <div class="b2b-dest"><h3>Uzbekistan</h3><p class="cities">Tashkent, Samarkand, Bukhara, Khiva</p><p>Silk Road heritage, architecture, culture and culinary experiences.</p></div>
        <div class="b2b-dest"><h3>Georgia</h3><p class="cities">Tbilisi, Kazbegi, Batumi, Kakheti</p><p>Culture, mountains, wine regions, Black Sea and city experiences.</p></div>
        <div class="b2b-dest"><h3>Armenia</h3><p class="cities">Yerevan, Garni, Geghard, Lake Sevan</p><p>History, monasteries, landscapes and cultural travel.</p></div>
        <div class="b2b-dest"><h3>Kyrgyzstan</h3><p class="cities">Bishkek, Issyk-Kul, Karakol, Ala-Archa</p><p>Mountains, lakes, outdoor experiences and nature.</p></div>
        <div class="b2b-dest"><h3>Russia</h3><p class="cities">Moscow, St. Petersburg, Golden Ring</p><p>Heritage cities, architecture, culture and iconic landmarks.</p></div>
        <div class="b2b-dest"><h3>Tajikistan</h3><p class="cities">Dushanbe, Fann Mountains, Pamir</p><p>Mountain landscapes, culture and adventure programmes.</p></div>
        <div class="b2b-dest"><h3>Turkmenistan</h3><p class="cities">Ashgabat, Darvaza, Merv</p><p>Unique architecture, history and desert experiences.</p></div>
      </div>
      <p style="margin-top:32px;"><a href="#b2b-quote" class="b2b-btn">Request B2B Quote</a></p>
    </div>
  </section>

  {{-- ========== SERVICES ========== --}}
  <section class="b2b-section b2b-section--tint">
    <div class="b2b-wrap">
      <span class="b2b-eyebrow">B2B DMC Services</span>
      <h2 class="b2b-h2">Everything You Need to Build a Complete Client Programme</h2>
      <div class="b2b-grid b2b-grid--4">
        <div class="b2b-card"><h3>Hotels &amp; Accommodation</h3><p>Hotel sourcing and accommodation coordination across required destinations and categories.</p></div>
        <div class="b2b-card"><h3>Airport Transfers</h3><p>Arrival and departure transfers, including private or SIC options where available.</p></div>
        <div class="b2b-card"><h3>Transportation</h3><p>Intercity and local transportation planning for FITs and groups.</p></div>
        <div class="b2b-card"><h3>Sightseeing</h3><p>City tours, attractions, excursions and destination experiences.</p></div>
        <div class="b2b-card"><h3>Tour Guides</h3><p>Local guide coordination according to destination, language and programme requirements.</p></div>
        <div class="b2b-card"><h3>Meals &amp; Restaurants</h3><p>Meal arrangements and restaurant planning where included in the programme.</p></div>
        <div class="b2b-card"><h3>FIT Programmes</h3><p>Flexible programmes for couples, families, solo travellers and premium clients.</p></div>
        <div class="b2b-card"><h3>GIT Programmes</h3><p>Group itineraries with coordinated hotels, transport, sightseeing and operations.</p></div>
        <div class="b2b-card"><h3>MICE</h3><p>Meetings, incentives, conferences and corporate programmes where available.</p></div>
        <div class="b2b-card"><h3>Customized Itineraries</h3><p>Modify routing, duration, hotel category and experiences around the client brief.</p></div>
        <div class="b2b-card"><h3>Visa Assistance</h3><p>Information or assistance where Dook officially offers it. Visa approval is decided by the relevant authorities.</p></div>
        <div class="b2b-card"><h3>On-Trip Coordination</h3><p>Destination-side coordination for confirmed programmes according to the agreed service scope.</p></div>
      </div>
      <p style="margin-top:32px;"><a href="#b2b-quote" class="b2b-btn">Talk to a B2B Specialist</a></p>
    </div>
  </section>

  {{-- ========== PRODUCT TYPES ========== --}}
  <section class="b2b-section">
    <div class="b2b-wrap">
      <span class="b2b-eyebrow">Product Types</span>
      <h2 class="b2b-h2">Programme Formats We Can Build Around Your Brief</h2>
      <div class="b2b-grid b2b-grid--2">
        <div class="b2b-card"><h3>FIT Travel</h3><p>For couples, families, solo travellers and tailor-made clients. Build programmes around preferred hotels, sightseeing, transport, pace and experiences.</p></div>
        <div class="b2b-card"><h3>GIT / Group Travel</h3><p>For tour operators and agents handling groups. Present multi-city programmes with coordinated accommodation, transport, sightseeing and group operations.</p></div>
        <div class="b2b-card"><h3>MICE &amp; Corporate</h3><p>For meetings, incentives, conferences, corporate offsites and special events, within the services Dook can operationally deliver.</p></div>
        <div class="b2b-card"><h3>Luxury Travel</h3><p>Premium hotel categories, private transport, curated experiences and personalized programmes for high-value clients.</p></div>
        <div class="b2b-card"><h3>Cultural &amp; Heritage</h3><p>Silk Road cities, historic architecture, local culture, museums, monuments, food and heritage experiences.</p></div>
        <div class="b2b-card"><h3>Adventure &amp; Nature</h3><p>Mountains, lakes, canyons, deserts, outdoor activities and nature-focused programmes.</p></div>
        <div class="b2b-card"><h3>Multi-Country Programmes</h3><p>Combine multiple destinations in one routing. Feasibility depends on dates, border and entry requirements, transport and operational availability.</p></div>
      </div>
    </div>
  </section>

  {{-- ========== READY-TO-SELL PROGRAMMES ========== --}}
  <section class="b2b-section b2b-section--tint">
    <div class="b2b-wrap">
      <span class="b2b-eyebrow">Ready-to-Sell Programmes</span>
      <h2 class="b2b-h2">Start With a Proven Programme — Then Customize It</h2>
      <p class="b2b-sub">Use these as a starting structure. Every element can be adapted to your client brief, and rates are quoted against your specific requirement.</p>
      <div class="b2b-grid b2b-grid--3">
        <div class="b2b-pkg"><div class="b2b-pkg__country">Kazakhstan</div><h3>Almaty 4N/5D</h3><p>City sightseeing + mountain experience + transfers + hotel</p><a href="#b2b-quote">Request B2B Rate &rarr;</a></div>
        <div class="b2b-pkg"><div class="b2b-pkg__country">Uzbekistan</div><h3>Tashkent–Samarkand–Bukhara</h3><p>Silk Road heritage + hotels + intercity transport</p><a href="#b2b-quote">Request B2B Rate &rarr;</a></div>
        <div class="b2b-pkg"><div class="b2b-pkg__country">Azerbaijan</div><h3>Baku–Gabala–Gobustan</h3><p>City + nature + culture + sightseeing</p><a href="#b2b-quote">Request B2B Rate &rarr;</a></div>
        <div class="b2b-pkg"><div class="b2b-pkg__country">Georgia</div><h3>Tbilisi–Kazbegi–Batumi</h3><p>Culture + mountains + city + leisure</p><a href="#b2b-quote">Request B2B Rate &rarr;</a></div>
        <div class="b2b-pkg"><div class="b2b-pkg__country">Kyrgyzstan</div><h3>Bishkek–Issyk-Kul</h3><p>Nature + mountains + lake experiences</p><a href="#b2b-quote">Request B2B Rate &rarr;</a></div>
        <div class="b2b-pkg"><div class="b2b-pkg__country">Armenia</div><h3>Yerevan &amp; Surroundings</h3><p>Culture + heritage + landscapes</p><a href="#b2b-quote">Request B2B Rate &rarr;</a></div>
        <div class="b2b-pkg"><div class="b2b-pkg__country">Russia</div><h3>Moscow–St. Petersburg</h3><p>Heritage + city experiences + sightseeing</p><a href="#b2b-quote">Request B2B Rate &rarr;</a></div>
      </div>
    </div>
  </section>

  {{-- ========== WHY PARTNER ========== --}}
  <section class="b2b-section">
    <div class="b2b-wrap">
      <span class="b2b-eyebrow">Why Partner With Dook</span>
      <h2 class="b2b-h2">Built Around the Way Travel Agents Actually Work</h2>
      <div class="b2b-grid b2b-grid--3">
        <div class="b2b-card"><h3>Save Research Time</h3><p>Send the client brief instead of contacting multiple destination suppliers for every component.</p></div>
        <div class="b2b-card"><h3>Build Better Programmes</h3><p>Use destination knowledge to create practical routing and experience combinations.</p></div>
        <div class="b2b-card"><h3>Commercial Flexibility</h3><p>Request a proposal aligned with your client’s budget, hotel category and travel style.</p></div>
        <div class="b2b-card"><h3>Keep Your Agency in Control</h3><p>Present the final programme and commercial offer through your own agency relationship.</p></div>
        <div class="b2b-card"><h3>One Point of Coordination</h3><p>Reduce the number of destination-side contacts required for multi-service programmes.</p></div>
        <div class="b2b-card"><h3>Scale From FIT to Groups</h3><p>Use the same B2B relationship for individual enquiries, group series and larger programmes.</p></div>
      </div>
    </div>
  </section>

  {{-- ========== PROCESS ========== --}}
  <section class="b2b-section b2b-section--tint" id="b2b-process">
    <div class="b2b-wrap">
      <span class="b2b-eyebrow">How It Works</span>
      <h2 class="b2b-h2">How the B2B Process Works</h2>
      <div class="b2b-grid b2b-grid--2" style="align-items:start;">
        <div>
          <div class="b2b-step"><div class="b2b-step__num">01</div><div><h3>Send Your Brief</h3><p>Tell us destination, dates, pax, hotel category, travel style and special requirements.</p></div></div>
          <div class="b2b-step"><div class="b2b-step__num">02</div><div><h3>B2B Team Reviews</h3><p>Our team reviews the requirement and identifies the appropriate programme structure.</p></div></div>
          <div class="b2b-step"><div class="b2b-step__num">03</div><div><h3>Receive Proposal</h3><p>Get a proposed itinerary and commercial quotation based on the agreed requirement.</p></div></div>
        </div>
        <div>
          <div class="b2b-step"><div class="b2b-step__num">04</div><div><h3>Refine the Programme</h3><p>Request changes to hotels, routing, sightseeing, transport or experiences.</p></div></div>
          <div class="b2b-step"><div class="b2b-step__num">05</div><div><h3>Confirm Services</h3><p>Approve the final programme and proceed according to the agreed booking and payment terms.</p></div></div>
          <div class="b2b-step"><div class="b2b-step__num">06</div><div><h3>Travel &amp; Support</h3><p>Destination services are coordinated according to the confirmed itinerary.</p></div></div>
        </div>
      </div>
    </div>
  </section>

  {{-- ========== WHAT TO SEND ========== --}}
  <section class="b2b-section">
    <div class="b2b-wrap">
      <span class="b2b-eyebrow">Enquiry Checklist</span>
      <h2 class="b2b-h2">What Information Should an Agent Send?</h2>
      <p class="b2b-sub">The more of this you include, the closer the first proposal will be to what your client actually wants.</p>
      <ul class="b2b-check">
        <li>Destination or countries required</li>
        <li>Travel dates or approximate travel month</li>
        <li>Number of adults and children</li>
        <li>Hotel category / preferred board basis</li>
        <li>FIT, GIT, MICE or other travel type</li>
        <li>Approximate budget or client segment</li>
        <li>Preferred transport: SIC / private / mixed</li>
        <li>Must-see attractions or experiences</li>
        <li>Meal requirements</li>
        <li>Guide / language requirements</li>
        <li>Visa or entry-related questions</li>
        <li>Special occasion, mobility or dietary requirements</li>
      </ul>
    </div>
  </section>

  {{-- ========== EUROPEAN MARKETS ========== --}}
  <section class="b2b-section b2b-section--tint">
    <div class="b2b-wrap">
      <span class="b2b-eyebrow">European Market Relevance</span>
      <h2 class="b2b-h2">Supporting Travel Professionals Across Europe</h2>
      <div class="b2b-grid b2b-grid--2">
        <div>
          <div class="b2b-market"><strong>United Kingdom</strong> — <span>UK tour operators looking for ready-to-sell or customized CIS programmes.</span></div>
          <div class="b2b-market"><strong>Germany</strong> — <span>German agencies developing culture, city-break, adventure or multi-country itineraries.</span></div>
          <div class="b2b-market"><strong>France</strong> — <span>French travel companies seeking customized Central Asia and Silk Road programmes.</span></div>
          <div class="b2b-market"><strong>Italy</strong> — <span>Italian agents looking for Azerbaijan, Georgia, Kazakhstan and Uzbekistan experiences.</span></div>
        </div>
        <div>
          <div class="b2b-market"><strong>Sweden</strong> — <span>Nordic agencies looking for nature, adventure, culture and premium travel programmes.</span></div>
          <div class="b2b-market"><strong>Norway</strong> — <span>Travel companies seeking distinctive destinations and tailor-made experiences.</span></div>
          <div class="b2b-market"><strong>Denmark</strong> — <span>Agents developing emerging-destination programmes for leisure and group clients.</span></div>
        </div>
      </div>
    </div>
  </section>

  {{-- ========== TRUST / PROOF ==========
       NOTE: No testimonials, partner logos or review scores are rendered here on purpose.
       The brief is explicit: "Do not invent European testimonials, company names, agent
       photographs or review scores." This section uses the documented fallback —
       "Trusted Travel Trade Partner" proof, company facts and a clear B2B process —
       until Dook supplies verified quotes and logos. --}}
  <section class="b2b-section">
    <div class="b2b-wrap">
      <span class="b2b-eyebrow">Trust &amp; Credibility</span>
      <h2 class="b2b-h2">A Trusted Travel Trade Partner</h2>
      <p class="b2b-sub">Working with a destination partner is a commercial decision. These are the facts you can check before you send your first brief.</p>
      <div class="b2b-grid b2b-grid--4">
        <div class="b2b-card"><h3>13+ Years</h3><p>Experience operating international travel programmes.</p></div>
        <div class="b2b-card"><h3>1M+ Travellers</h3><p>Served across Dook International’s destination portfolio.</p></div>
        <div class="b2b-card"><h3>500+ Global Partners</h3><p>Travel trade relationships worldwide.</p></div>
        <div class="b2b-card"><h3>50+ Countries</h3><p>Destination coverage beyond CIS and Central Asia.</p></div>
      </div>
      <div class="b2b-note" style="margin-top:28px;">
        <strong>Verified partner testimonials and trade references are available on request.</strong>
        We publish named quotes, partner logos and case studies only with the partner’s written
        permission, so what you see here is limited to what we can substantiate.
      </div>
    </div>
  </section>

  {{-- ========== CASE STUDIES ==========
       NOTE: These are illustrative briefs describing service scope — no named clients,
       no invented companies, no fabricated outcomes. Replace with real, approved Dook
       case studies before the page is promoted. --}}
  <section class="b2b-section b2b-section--tint">
    <div class="b2b-wrap">
      <span class="b2b-eyebrow">Representative Briefs</span>
      <h2 class="b2b-h2">What a B2B Requirement Typically Looks Like</h2>
      <p class="b2b-sub">Illustrative examples of the kind of brief our B2B team works from, and the scope we cover in response.</p>
      <div class="b2b-grid b2b-grid--3">
        <div class="b2b-card">
          <h3>Multi-City FIT</h3>
          <p><strong>Brief:</strong> A European agency requires a 10-day Central Asia itinerary for a small private group.<br><br>
          <strong>Dook role:</strong> Itinerary design, hotel coordination, transport, sightseeing and destination operations.<br><br>
          <strong>Outcome:</strong> A customized programme delivered for the agency to present to its client.</p>
        </div>
        <div class="b2b-card">
          <h3>Group Programme</h3>
          <p><strong>Brief:</strong> A tour operator needs a group programme with fixed travel dates.<br><br>
          <strong>Dook role:</strong> Group hotel blocks, transport, sightseeing, guide coordination and operational planning.<br><br>
          <strong>Outcome:</strong> One coordinated B2B proposal covering the requested services.</p>
        </div>
        <div class="b2b-card">
          <h3>MICE / Incentive</h3>
          <p><strong>Brief:</strong> A corporate agency needs an incentive programme.<br><br>
          <strong>Dook role:</strong> Venue, accommodation, transport, experiences, group movement and event coordination, subject to actual service scope.<br><br>
          <strong>Outcome:</strong> An end-to-end proposal designed around the corporate brief.</p>
        </div>
      </div>
    </div>
  </section>

  {{-- ========== PARTNER-FIRST MESSAGING ==========
       NOTE: Framed as questions the B2B team will answer, NOT as policy claims.
       The brief forbids claiming "no client poaching", "white-label", "exclusive rates",
       "net rates", "commission" or "partner protection" without an official Dook policy.
       Replace with real policy statements once Dook confirms them. --}}
  <section class="b2b-section">
    <div class="b2b-wrap">
      <span class="b2b-eyebrow">Working Together</span>
      <h2 class="b2b-h2">How We Work With Your Agency</h2>
      <p class="b2b-sub">
        Every agency wants to know exactly how a destination partner will behave around its clients.
        These are the questions our B2B team will answer in writing before you commit to anything.
      </p>
      <ul class="b2b-check">
        <li>Do you work directly with travellers or only through agents?</li>
        <li>Can we add our own markup?</li>
        <li>Can itineraries be shared under our agency branding?</li>
        <li>Will your team contact our client directly?</li>
        <li>Who handles destination-side communication?</li>
        <li>Can we request specific hotels or suppliers?</li>
        <li>What happens if the client changes the itinerary?</li>
        <li>What support is available during travel?</li>
      </ul>
      <p style="margin-top:30px;"><a href="#b2b-quote" class="b2b-btn">Become a B2B Partner</a></p>
    </div>
  </section>

  {{-- ========== PRICING ========== --}}
  <section class="b2b-section b2b-section--tint">
    <div class="b2b-wrap">
      <span class="b2b-eyebrow">B2B Quotations</span>
      <h2 class="b2b-h2">Request a Tailored B2B Quote</h2>
      <p class="b2b-sub">
        Because hotel availability, seasonality, passenger count and service requirements vary,
        we quote against your brief rather than publishing fixed package prices. Submit your client
        requirement and receive a proposal built around the requested programme.
      </p>
      <p><a href="#b2b-quote" class="b2b-btn">Get Your B2B Proposal</a></p>
    </div>
  </section>

  {{-- ========== FAQ ========== --}}
  <section class="b2b-section">
    <div class="b2b-wrap">
      <span class="b2b-eyebrow">FAQs</span>
      <h2 class="b2b-h2">B2B Questions From Travel Agents</h2>
      <div class="b2b-faq">
        <details open>
          <summary>What is a CIS DMC?</summary>
          <p class="b2b-faq__a">A CIS DMC is a destination management company that coordinates destination services such as hotels, transfers, transport, sightseeing, guides and customized travel programmes for travel partners. The agent keeps the client relationship; the DMC handles the ground arrangements in the destination.</p>
        </details>
        <details>
          <summary>Does Dook International work with European travel agents?</summary>
          <p class="b2b-faq__a">Yes. This page is for travel agents, tour operators, wholesalers, group operators and MICE planners across Europe who need CIS and Central Asia destination support. Submit the B2B enquiry form and our team will respond with the next steps for your market.</p>
        </details>
        <details>
          <summary>Which CIS and Central Asia countries does Dook cover?</summary>
          <p class="b2b-faq__a">Azerbaijan, Kazakhstan, Uzbekistan, Georgia, Armenia, Kyrgyzstan, Russia, Tajikistan and Turkmenistan. Single-country and multi-country routings can both be quoted.</p>
        </details>
        <details>
          <summary>Does Dook offer B2B rates?</summary>
          <p class="b2b-faq__a">Rates are quotation-based. Because availability, seasonality, passenger count and service scope change the cost, agents submit a requirement and receive a tailored proposal rather than a fixed published price.</p>
        </details>
        <details>
          <summary>Can European agents request customized itineraries?</summary>
          <p class="b2b-faq__a">Yes. Routing, duration, hotel category, transport, sightseeing, meals and experiences can all be adapted to the brief, subject to destination, supplier and availability constraints.</p>
        </details>
        <details>
          <summary>Does Dook support FIT and group travel?</summary>
          <p class="b2b-faq__a">Both. FIT programmes cover couples, families, solo and tailor-made clients. GIT programmes cover group itineraries with coordinated hotels, transport, sightseeing and group operations.</p>
        </details>
        <details>
          <summary>Does Dook support MICE travel?</summary>
          <p class="b2b-faq__a">MICE and corporate requirements — meetings, incentives, conferences and events — can be quoted within the services Dook can operationally deliver in the requested destination. Share the brief and our team will confirm scope.</p>
        </details>
        <details>
          <summary>What services does a CIS DMC provide?</summary>
          <p class="b2b-faq__a">Hotels, transfers, transportation, sightseeing, guides, activities, meals and other destination services, depending on the programme.</p>
        </details>
        <details>
          <summary>How can a travel agent request a quotation?</summary>
          <p class="b2b-faq__a">Submit the B2B enquiry form on this page with destination, travel dates, passenger count and service requirements. The enquiry checklist above lists everything that helps us quote accurately first time.</p>
        </details>
        <details>
          <summary>Can agents request specific hotels?</summary>
          <p class="b2b-faq__a">Yes, where this is supported operationally. Final confirmation always depends on availability for your dates and passenger count.</p>
        </details>
        <details>
          <summary>Can programmes be customized for groups?</summary>
          <p class="b2b-faq__a">Yes. Group requirements can be adapted across routing, hotel blocks, transport, sightseeing and guide coordination. Share group size and fixed dates in your brief so the proposal reflects real operational planning.</p>
        </details>
        <details>
          <summary>Can Dook support multi-country itineraries?</summary>
          <p class="b2b-faq__a">Yes. Countries across the CIS and Central Asia region can be combined in one routing. Feasibility depends on travel dates, border and entry rules, and transport availability between the countries requested.</p>
        </details>
        <details>
          <summary>Can Dook help with visa-related requirements?</summary>
          <p class="b2b-faq__a">We can share destination entry information and provide assistance where Dook officially offers it. Visa approval is decided solely by the relevant authorities and cannot be guaranteed by any travel partner.</p>
        </details>
      </div>
    </div>
  </section>

  {{-- ========== FINAL CTA ========== --}}
  <section class="b2b-final">
    <div class="b2b-wrap">
      <h2>Ready to Sell CIS &amp; Central Asia?</h2>
      <p class="lead">Let Dook Help You Build the Right Programme for Your Client.</p>
      <p>Share your destination, travel dates, passenger count and service requirements. Our B2B team can work from your brief.</p>
      <a href="#b2b-quote" class="b2b-btn">Request Your B2B Quote</a>
      <p class="b2b-contact">
        Prefer to talk first? Email <a href="mailto:sales@dooktravels.com">sales@dooktravels.com</a>
        or call <a href="tel:+918368513675">+91 83685 13675</a>.
      </p>
    </div>
  </section>

</div>
@endsection

@push('script')
<script type="application/ld+json">
{!! json_encode([
  '@context' => 'https://schema.org',
  '@graph' => [
    [
      '@type' => 'WebPage',
      'name'  => 'CIS & Central Asia DMC for European Travel Agents',
      'url'   => url()->current(),
      'description' => 'B2B CIS & Central Asia DMC for European travel agents. Request customized FIT, group & MICE programmes, hotels, transfers, sightseeing and B2B quotations.',
    ],
    [
      '@type' => 'BreadcrumbList',
      'itemListElement' => [
        ['@type' => 'ListItem', 'position' => 1, 'name' => 'Home', 'item' => url('/')],
        ['@type' => 'ListItem', 'position' => 2, 'name' => 'B2B Partnerships', 'item' => url()->current()],
      ],
    ],
    [
      '@type' => 'FAQPage',
      'mainEntity' => collect([
        ['What is a CIS DMC?', 'A CIS DMC is a destination management company that coordinates destination services such as hotels, transfers, transport, sightseeing, guides and customized travel programmes for travel partners.'],
        ['Does Dook International work with European travel agents?', 'Yes. Dook International supports travel agents, tour operators, wholesalers, group operators and MICE planners across Europe with CIS and Central Asia destination services.'],
        ['Which CIS and Central Asia countries does Dook cover?', 'Azerbaijan, Kazakhstan, Uzbekistan, Georgia, Armenia, Kyrgyzstan, Russia, Tajikistan and Turkmenistan.'],
        ['Does Dook offer B2B rates?', 'Rates are quotation-based. Agents submit a requirement and receive a tailored proposal rather than a fixed published price.'],
        ['Can European agents request customized itineraries?', 'Yes. Routing, duration, hotel category, transport, sightseeing, meals and experiences can be adapted to the brief, subject to availability.'],
        ['Does Dook support FIT and group travel?', 'Both FIT and GIT programmes are supported, including coordinated hotels, transport, sightseeing and group operations.'],
        ['What services does a CIS DMC provide?', 'Hotels, transfers, transportation, sightseeing, guides, activities, meals and other destination services depending on the programme.'],
        ['How can a travel agent request a quotation?', 'Submit the B2B enquiry form with destination, travel dates, passenger count and service requirements.'],
        ['Can Dook support multi-country itineraries?', 'Yes. Countries across the region can be combined in one routing, depending on travel dates, entry rules and transport availability.'],
        ['Can Dook help with visa-related requirements?', 'Dook can share destination entry information and provide assistance where officially offered. Visa approval is decided by the relevant authorities.'],
      ])->map(function ($faq) {
        return [
          '@type' => 'Question',
          'name'  => $faq[0],
          'acceptedAnswer' => ['@type' => 'Answer', 'text' => $faq[1]],
        ];
      })->all(),
    ],
  ],
], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}
</script>

<script>
(function () {
  var form = document.getElementById('b2bQuoteForm');
  if (!form) return;

  var btn  = document.getElementById('b2bSubmitBtn');
  var msg  = document.getElementById('b2bFormMsg');
  var browserField = document.getElementById('b2bBrowserName');
  if (browserField) browserField.value = navigator.userAgent;

  function setMsg(text, kind) {
    msg.textContent = text;
    msg.className = 'b2b-form__msg ' + kind;
  }

  form.addEventListener('submit', function (e) {
    e.preventDefault();

    if (!form.checkValidity()) {
      form.reportValidity();
      return;
    }

    // InquiryController stores a single `name` column, so combine the two name fields.
    var first = document.getElementById('b2bFirstName').value.trim();
    var last  = document.getElementById('b2bLastName').value.trim();
    document.getElementById('b2bFullName').value = (first + ' ' + last).trim();

    btn.disabled = true;
    btn.textContent = 'Submitting…';
    msg.className = 'b2b-form__msg';

    fetch("{{ route('frontend.common_inquiry_store') }}", {
      method: 'POST',
      body: new FormData(form),
      headers: {
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
        'X-Requested-With': 'XMLHttpRequest'
      }
    })
    .then(function (res) {
      if (!res.ok) throw new Error('Request failed: ' + res.status);
      return res.json();
    })
    .then(function () {
      btn.textContent = 'Proposal Requested';
      setMsg('Thank you. Your requirement has reached our B2B team and we will be in touch shortly.', 'ok');
      form.reset();
    })
    .catch(function (err) {
      btn.disabled = false;
      btn.textContent = 'Get My B2B Proposal';
      setMsg('Sorry, we could not submit your enquiry. Please try again, or email sales@dooktravels.com.', 'err');
      console.error(err);
    });
  });
})();
</script>
@endpush
