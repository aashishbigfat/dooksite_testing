
<?php 
$adtdata=array();
$chddata=array();
if($hotel_detail) 
{
    if(isset($hotel_detail['room_data'][$room_counter]))
    {
        if($pax_type=="Adult") 
        {
            if(isset($hotel_detail['room_data'][$room_counter]['pax']['Adult']))
            {
                if(isset($hotel_detail['room_data'][$room_counter]['pax']['Adult'][$adt_counter]))
                {
                    $adtdata=$hotel_detail['room_data'][$room_counter]['pax']['Adult'][$adt_counter];
                }
            }
        }

        if($pax_type=="Child") 
        {
            if(isset($hotel_detail['room_data'][$room_counter]['pax']['Child']))
            {
                if(isset($hotel_detail['room_data'][$room_counter]['pax']['Child'][$chd_counter]))
                {
                    $chddata=$hotel_detail['room_data'][$room_counter]['pax']['Child'][$chd_counter];
                }
            }
        }
       
    }
} 
?>
  
  <?php if($pax_type=="Adult") { ?>
    <div class="row mb-3">
        <div class="col-md-2 fw-bold">Adult <?php echo $adt_counter; ?></div>
        <div class="col-md-2">
            <label> Title *</label>
            <select class="form-select form-control" name="room[<?php echo $room_counter; ?>][pax][Adult][<?php echo $adt_counter; ?>][title]">
                <option value="">Title</option>
                <option value="Mr" <?php if($adtdata) { if($adtdata['title']=="Mr") { echo "selected"; } } ?>>Mr</option>
                <option value="Ms" <?php if($adtdata) { if($adtdata['title']=="Ms") { echo "selected"; } } ?>>Ms</option>
                <option value="Mrs" <?php if($adtdata) { if($adtdata['title']=="Mrs") { echo "selected"; } } ?> >Mrs</option>
            </select>
        </div>

        
        <div class="col-md-2">
            <label> First Name *</label>
            <input type="text" class="form-control" name="room[<?php echo $room_counter; ?>][pax][Adult][<?php echo $adt_counter; ?>][first_name]" placeholder="First Name" value="<?php if($adtdata) { echo $adtdata['first_name']; } ?>"/>
        </div>
        <div class="col-md-2">
            <label> Last Name *</label>
            <input type="text" class="form-control" name="room[<?php echo $room_counter; ?>][pax][Adult][<?php echo $adt_counter; ?>][last_name]" placeholder="Last Name" value="<?php if($adtdata) { echo $adtdata['last_name']; } ?>"/>
        </div>
        <?php if($pan_required) { ?>
        <div class="col-md-2">
            <label> Pan Card *</label>
            <input type="text" class="form-control" name="room[<?php echo $room_counter; ?>][pax][Adult][<?php echo $adt_counter; ?>][pan_number]" placeholder="Pan Card" value="<?php if($adtdata) { echo $adtdata['pan_number']; } ?>"/>
        </div>
        <?php } ?>
    </div>
    <?php if($passport_required) { ?>
    <div class="row mb-3">
        <div class="col-md-2 fw-bold"></div>
        <div class="col-md-2">
            <label> Nationality *</label>
            <select class="form-select form-control select_search" name="room[<?php echo $room_counter; ?>][pax][Adult][<?php echo $adt_counter; ?>][nationality]">
                <option value="">Select </option>
                <?php $country_codes = get_countary_code();
                    if ($country_codes) {
                        foreach ($country_codes as $country_code) { ?>
                <option value="<?php echo $country_code['countrycode']; ?>" <?php if($adtdata) { if($adtdata['nationality']==$country_code['countrycode']) { echo "selected"; } } ?>>
                    <?php echo $country_code['countryname']; ?>
                </option>
                <?php }
                    } ?>
            </select>
        </div>
        <div class="col-md-2">
            <label> Passport Number *</label>
            <input type="text" class="form-control" name="room[<?php echo $room_counter; ?>][pax][Adult][<?php echo $adt_counter; ?>][passport_number]" placeholder="Passport Number" value="<?php if($adtdata) { echo $adtdata['passport_number']; } ?>"/>
        </div>
        <div class="col-md-2">
            <label> Issue Date *</label>
            <input type="text" class="form-control" name="room[<?php echo $room_counter; ?>][pax][Adult][<?php echo $adt_counter; ?>][issue_date]" placeholder="Issue Date" dob-calendor="true" value="<?php if($adtdata) { echo $adtdata['issue_date']; } ?>"/>
        </div>
        <div class="col-md-2">
            <label> Expiry Date * </label>
            <input type="text" class="form-control" name="room[<?php echo $room_counter; ?>][pax][Adult][<?php echo $adt_counter; ?>][expiry_date]" placeholder="Expiry Date" nolim-calendor="true" value="<?php if($adtdata) { echo $adtdata['expiry_date']; } ?>"/> 
        </div>
    </div>
    <?php } ?>

    <?php } ?>

    <?php if($pax_type=="Child") { ?>
  
    <div class="row mb-3">
        <div class="col-md-2 fw-bold">Child <?php echo $chd_counter; ?></div>
        <div class="col-md-2">
            <label> Title *</label>
            <select class="form-select form-control" name="room[<?php echo $room_counter; ?>][pax][Child][<?php echo $chd_counter; ?>][title]">
                <option value="">Title</option>
                <option value="Mr" <?php if($chddata) { if($chddata['title']=="Mr") { echo "selected"; } } ?>>Mr</option>
                <option value="Ms" <?php if($chddata) { if($chddata['title']=="Ms") { echo "selected"; } } ?>>Ms</option>
                <option value="Mrs" <?php if($chddata) { if($chddata['title']=="Mrs") { echo "selected"; } } ?> >Mrs</option>
            </select>
        </div>
        <div class="col-md-2">
            <label> First Name *</label>
            <input type="text" class="form-control" name="room[<?php echo $room_counter; ?>][pax][Child][<?php echo $chd_counter; ?>][first_name]" placeholder="First Name" value="<?php if($chddata) { echo $chddata['first_name']; } ?>" />
        </div>
        <div class="col-md-2">
            <label> Last Name *</label>
            <input type="text" class="form-control" name="room[<?php echo $room_counter; ?>][pax][Child][<?php echo $chd_counter; ?>][last_name]" placeholder="Last Name" value="<?php if($chddata) { echo $chddata['last_name']; } ?>"/>
        </div>
        <?php if($pan_required) { ?>
        <div class="col-md-2">
            <label> Pan Card </label>
            <input type="text" class="form-control" name="room[<?php echo $room_counter; ?>][pax][Child][<?php echo $chd_counter; ?>][pan_number]" placeholder="Pan Card" value="<?php if($chddata) { echo $chddata['pan_number']; } ?>"/>
        </div>
        <?php } ?>
        <div class="col-md-2">
            <label> Age *</label>
            <input type="text" class="form-control" name="room[<?php echo $room_counter; ?>][pax][Child][<?php echo $chd_counter; ?>][age]" placeholder="Age" value="<?php if($chddata) { echo $chddata['age']; } ?>"/>
        </div>
    </div>

    <?php if($passport_required) { ?>
    <div class="row mb-3">
        <div class="col-md-2 fw-bold"></div>
        <div class="col-md-2">
            <label> Nationality *</label>
            <select class="form-select form-control select_search" name="room[<?php echo $room_counter; ?>][pax][Child][<?php echo $chd_counter; ?>][nationality]">
            <option value="">Select </option>
            <?php $country_codes = get_countary_code();
                  if ($country_codes) {
                      foreach ($country_codes as $country_code) { ?>
               <option value="<?php echo $country_code['countrycode']; ?>" <?php if($chddata) { if($chddata['nationality']==$country_code['countrycode']) { echo "selected"; } } ?>>
                  <?php echo $country_code['countryname']; ?>
               </option>
               <?php }
                  } ?>
            </select>
        </div>
        <div class="col-md-2">
            <label> Passport Number *</label>
            <input type="text" class="form-control" name="room[<?php echo $room_counter; ?>][pax][Child][<?php echo $chd_counter; ?>][passport_number]" placeholder="Passport Number" value="<?php if($chddata) { echo $chddata['passport_number']; } ?>"/>
        </div>
        <div class="col-md-2">
            <label> Issue Date *</label>
            <input type="text" class="form-control" name="room[<?php echo $room_counter; ?>][pax][Child][<?php echo $chd_counter; ?>][issue_date]" placeholder="Issue Date" dob-calendor="true"  value="<?php if($chddata) { echo $chddata['issue_date']; } ?>" />
        </div>
        <div class="col-md-2">
            <label> Expiry Date * </label>
            <input type="text" class="form-control" name="room[<?php echo $room_counter; ?>][pax][Child][<?php echo $chd_counter; ?>][expiry_date]" placeholder="Expiry Date" nolim-calendor="true" value="<?php if($chddata) { echo $chddata['expiry_date']; } ?>" />
        </div>
    </div>
    <?php } ?>

    <?php } ?>

    <hr/>