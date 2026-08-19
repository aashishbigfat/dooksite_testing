<section class="breadcrumbs">
    <div class="container">
        <ol>
            <li><a href="index.html">Home</a></li>
            <li>Trending Holidays Destinations</li>
        </ol>
        <h2>Trending Indian Holidays Destinations</h2>
    </div>
</section>
<?php if (isset($DomesticHolidayDestinations) && !empty($DomesticHolidayDestinations) && $DomesticHolidayDestinations) : ?>
    <!------International destinations------->
    <section class="destinations sections-bg">
        <div class="container">
            <div class="row">
                <?php foreach ($DomesticHolidayDestinations as $DomesticHolidayData) :  ?>
                    <?php $filename =  root_url . 'uploads/holiday/thumbnail/' . $DomesticHolidayData['destination_image']; ?>
                    <div class="col-md-3 mb-3">
                        <div class="destination-item">
                            <div class="destination-img">
                                <a href="<?php echo site_url('holiday/destinations/') . $DomesticHolidayData['destination_slug']; ?>">
                                    <?php if (UR_exists($filename)) : ?>
                                        <img src="<?php echo $filename; ?>" alt="<?= ucwords($DomesticHolidayData['destination_name']); ?>" class="img-fluid">
                                    <?php else : ?>
                                        <img class="img-fluid" src="<?php echo site_url('webroot/img/user.png') ?>" />
                                    <?php endif ?>
                                </a>
                            </div>
                            <div class="destination-info">
                                <h4 class="destination-title"><?= ucwords($DomesticHolidayData['destination_name']); ?></h4>
                                <div>
                                    <span>Starting Price</span>
                                    <p> <?= $DomesticHolidayData['CurrencySymbol']." ".  $DomesticHolidayData['starting_price'] ?></p>
                                </div>
                            </div>
                            <a href="<?php echo site_url('holiday/destinations/') . $DomesticHolidayData['destination_slug']; ?>" class="destination-btn stretched-link"></a>
                        </div>
                    </div>
                <?php endforeach ?>
                <div class="pagiantion-item d-flex align-items-center justify-content-between">
                    <p class="pagiantion_text">Page <?= $pager->getCurrentPage() ?>
                        of <?= $pager->getPageCount() ?>, total <?= $pager->getTotal() ?> records found
                    </p>
                    <?php if ($pager) : ?>
                        <?= $pager->links() ?>
                    <?php endif ?>
                </div>
            </div>
        </div>
    </section>
    <!------ International destinations end------->
<?php endif ?>