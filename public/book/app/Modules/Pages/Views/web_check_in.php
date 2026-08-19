<section id="breadcrumbs" class="breadcrumbs">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center">
            <h2>Web Check-In</h2>
            <ol>
                <li>
                    <a href="<?php echo site_url(); ?>">Home</a>
                </li>
                <li>Web Check-In</li>
            </ol>
        </div>

    </div>
</section>

<?php if (isset($web_check_in) && !empty($web_check_in)) { ?>
    <section class="web_check">
        <div class="container">
            <div class="home_heading">
                <h2 style="text-align: center;">Select Airline</h2>
            </div>
            <div class="row gy-4">
                <?php foreach ($web_check_in as $webcheckin) {
                    ?>
                    <div class="col-lg-3 col-md-6 col-12">
                        <div class="card">

                            <div class="image">
                                <img src="<?php echo root_url . 'uploads/web-check-in-images/thumbnail/' . $webcheckin['image'] ?>"
                                    alt="<?php echo $webcheckin['airline_name'] ?>" class="card-img-top">
                            </div>
                            <h5 class="card-title"><?php echo $webcheckin['airline_name'] ?>
                            </h5>
                            <a class="btn btn-outline-primary"
                                href="<?php echo isset($webcheckin['url']) && !empty($webcheckin['url']) ? $webcheckin['url'] : 'javascript:void(0);'; ?>">
                                Go To Airline </a>


                        </div>
                    </div>
                    <?php
                }
                ?>
            </div>
        </div>
    </section>
<?php } ?>