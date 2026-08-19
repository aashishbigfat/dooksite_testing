<section class="breadcrumbs">
   <div class="container">
      <ol>
         <li><a href="index.html">Home</a></li>
         <li>Trending Holidays Destinations</li>
      </ol>
      <h2>Trending International Holidays Destinations</h2>
   </div>
</section>
<?php if (isset($InternationalHolidayDestinations) && !empty($InternationalHolidayDestinations) && $InternationalHolidayDestinations) : ?>
   <!------International destinations------->
   <section class="destinations sections-bg">
      <div class="container">
         <div class="tranding-destinations row">
            <?php foreach ($InternationalHolidayDestinations as $InternationalHolidayData) :  ?>
               <?php $filename =  root_url . 'uploads/holiday/thumbnail/' . $InternationalHolidayData['destination_image']; ?>
               <div class="col-md-3 mb-3">
                  <div class="destination-item">
                     <div class="destination-img">
                        <a href="<?php echo site_url('holiday/destinations/') . $InternationalHolidayData['destination_slug']; ?>">
                           <?php if (UR_exists($filename)) : ?>
                              <img src="<?php echo $filename; ?>" alt="<?= ucwords($InternationalHolidayData['destination_name']); ?>" class="img-fluid">
                           <?php else : ?>
                              <img class="img-fluid" src="<?php echo site_url('webroot/img/user.png') ?>" />
                           <?php endif ?>
                        </a>
                     </div>
                     <div class="destination-info">
                        <h4 class="destination-title"><?= ucwords($InternationalHolidayData['destination_name']); ?></h4>
                        <div>
                           <span>Starting Price</span>
                           <p><?= $InternationalHolidayData['CurrencySymbol'] . " " .  $InternationalHolidayData['starting_price'] ?></p>
                        </div>
                     </div>
                     <a href="<?php echo site_url('holiday/destinations/') . $InternationalHolidayData['destination_slug']; ?>" class="destination-btn stretched-link"></a>
                  </div>
               </div>
            <?php endforeach ?>
         </div>
         <div class="pagination d-flex justify-content-between align-items-center">
            <p>Page <?= $pager->getCurrentPage() ?>
               of <?= $pager->getPageCount() ?>, total <?= $pager->getTotal() ?> records found
            </p>
            <?php if ($pager) : ?>
               <?= $pager->links() ?>
            <?php endif ?>
         </div>
      </div>
   </section>
   <!------ International destinations end------->
<?php endif ?>