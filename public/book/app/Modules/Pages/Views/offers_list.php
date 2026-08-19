<section id="breadcrumbs" class="breadcrumbs">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center">
            <h2>Offer</h2>
            <ol>
                <li>
                    <a href="<?php echo site_url(); ?>">Home</a>
                </li>
                <li>Offer List</li>
            </ol>
        </div>
    </div>
</section>
<section class="offer_page">
    <div class="container">

        <div class="place-nav">
            <ul class="nav justify-content-center" id="myTab" role="tablist">
                <li>
                    <a href="javascript:void(0);" class="nav-link active" id="all-tab" data-bs-toggle="tab" data-bs-target="#all-tab-pane" role="tab" aria-controls="all-tab-pane" aria-selected="true">All</a>
                </li>
                <?php foreach ($offers_list as $servicekey => $offers_data) { ?>
                    <li>
                        <a href="javascript:void(0);" class="nav-link" id="<?= $servicekey ?>-tab" data-bs-toggle="tab" data-bs-target="#<?= $servicekey ?>-tab-pane" role="tab" aria-controls="<?= $servicekey ?>-tab-pane" aria-selected="<?= ($servicekey == 'hotdeal') ? 'true' : 'false' ?>"><?= $servicekey ?></a>
                    </li>
                <?php } ?>
            </ul>
        </div>

        <div class="tab-content" id="myTabContent">

            <div class="tab-pane fade show active" id="all-tab-pane" role="tabpanel" aria-labelledby="all-tab" tabindex="0">
                <div class="row">
                    <?php foreach ($offers_list as $servicekey => $offers_data) { ?>
                        <?php foreach ($offers_data as $offer) { ?>
                            <div class="col-lg-4 mb-3">
                                <div class="item-box">
                                    <div class="offer-box">
                                        <div class="imgsection">
                                            <img src="<?php echo root_url . 'uploads/offers/thumbnail/' . $offer['image'] ?>" class="card-img">
                                        </div>
                                        <div class="offer-content">
                                            <div class="offer-label">
                                                <p><?= $servicekey ?></p>
                                            </div>
                                            <div class="offer-height">
                                                <div class="offeritemDesc">
                                                    <h3><?= $offer['title'] ?></h3>
                                                    <p><?= $offer['description'] ?></p>
                                                </div>
                                                <div class="bookingsection">
                                                    <a target="_blank" href="<?= $offer['url'] ?>">Book Now</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php } ?>
                    <?php } ?>
                </div>
            </div>
            <?php foreach ($offers_list as $servicekey => $offers_data) { ?>
                <div class="tab-pane fade" id="<?= $servicekey ?>-tab-pane" role="tabpanel" aria-labelledby="<?= $servicekey ?>-tab" tabindex="0">
                    <div class="row">
                        <?php foreach ($offers_data as $offer) { ?>
                            <div class="col-lg-4">
                                <div class="item-box">
                                    <div class="offer-box">
                                        <div class="imgsection">
                                            <img src="<?php echo root_url . 'uploads/offers/thumbnail/' . $offer['image'] ?>" class="card-img">
                                        </div>
                                        <div class="offer-content">
                                            <div class="offer-label">
                                                <p><?= $servicekey ?></p>
                                            </div>
                                            <div class="offer-height">
                                                <div class="offeritemDesc">
                                                    <h3><?= $offer['title'] ?></h3>
                                                    <p><?= $offer['description'] ?></p>
                                                </div>
                                                <div class="bookingsection">
                                                    <a target="_blank" href="<?= $offer['url'] ?>">Book Now</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php } ?>
                    </div>
                </div>
            <?php } ?>
        </div>
    </div>
</section>