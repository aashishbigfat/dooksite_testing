
<?php 
$roomdata=array();
$finalroom_amenities=array();
if($hotel_detail) 
{
    if(isset($hotel_detail['room_data'][$room_counter]))
    {
        $roomdata=$hotel_detail['room_data'][$room_counter];

        if($roomdata)
        {
            foreach($roomdata['room_amenities'] as $amenities) {

                $array=array('value'=>$amenities,'option'=>"selected");
                array_push($finalroom_amenities,$array);
            }
        }
    }

    if($room_amenities)
    {
        foreach($room_amenities as $amenities) {

            $array=array('value'=>$amenities['amenity_title'],'option'=>"");
            array_push($finalroom_amenities,$array);
        }
    }
    $temp = array_unique(array_column($finalroom_amenities, 'value'));
    $amenitiesunique_arr = array_intersect_key($finalroom_amenities, $temp);
} 
?>


<div room="<?php echo $room_counter; ?>">
<div class="card-header text-white">
    <div class="row">
        <div class="col-md-4">Room <?php echo $room_counter; ?></div>
        <div class="col-md-8 text-end">
        <button class="badge badge-wt" type="button" hotel-upload-add-adt-pax="true" tts-hotel-upload-method-name="hotel-upload/add-passanger" room-counter="<?php echo $room_counter; ?>" pax-type="Adult" pax-adt-counter="0"><i class="fa fa-add"></i> Add Adult</button>
        <button class="badge badge-wt" type="button" hotel-upload-add-chd-pax="true" tts-hotel-upload-method-name="hotel-upload/add-passanger" room-counter="<?php echo $room_counter; ?>" pax-type="Child" pax-chd-counter="0"><i class="fa fa-add"></i> Add Child</button>
             
        </div>
    </div>
</div>
<div class="card-body">
    <div class="view_head">
        <div class="row">
            <div class="col-md-4">
                Room Information
            </div>
            <div class="col-md-8 text-end">
               
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-3">
            <div class="form-group form-mb-20">
                <label> Room Name *</label>
                <input type="text" class="form-control" name="room[<?php echo $room_counter; ?>][room_name]" placeholder="Room Name"  value="<?php if($roomdata) { echo $roomdata['room_name']; } ?>"/>
            </div>
        </div>
        <div class="col-md-9">
            <div class="form-group form-mb-20">
                <label> Amenities *</label>
                <select class="form-control tokenizer" multiple="true" name="room[<?php echo $room_counter; ?>][room_amenities][]" value="">
                    <?php if($amenitiesunique_arr) { 
                    foreach($amenitiesunique_arr as $amenities) { ?>
                     <option value="<?php echo $amenities['value']; ?>" <?php echo $amenities['option']; ?>><?php echo $amenities['value']; ?></option>
                    <?php } } ?>
                </select>
                <span>Note : you can select multiple Amenities and add run time amenities</span>
            </div>
        </div>
        <div class="col-md-12">
            <div class="form-group form-mb-20">
                <label> Room Cancellation *</label>
                <textarea name="room[<?php echo $room_counter; ?>][room_cancellation_policy]" class="tts-editornote" rows="3"><?php if($roomdata) { echo $roomdata['room_cancellation_policy']; } ?></textarea>
            </div>
        </div>
    </div>

    <div class="view_head">
        <div class="row">
            <div class="col-md-12">Passenger Information</div>
        </div>
    </div>
    <div tts-call-put-passanger-html="<?php echo $room_counter; ?>"></div>


    <div class="view_head">
        <div class="row">
            <div class="col-md-12">Fare Information</div>
        </div>
    </div>


    <div class="row">
        <div class="col-md-3">
            <div class="form-group form-mb-20">
                <label> Room Price *</label>
                <input type="text" class="form-control" name="room[<?php echo $room_counter; ?>][room_price]" placeholder="Room Price" value="<?php if($roomdata) { echo $roomdata['room_price']; } ?>" />
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-group form-mb-20">
                <label> Tax *</label>
                <input type="text" class="form-control" name="room[<?php echo $room_counter; ?>][tax]" placeholder="Tax" value="<?php if($roomdata) { echo $roomdata['tax']; } ?>" />
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-group form-mb-20">
                <label> Other Charge *</label>
                <input type="text" class="form-control" name="room[<?php echo $room_counter; ?>][othercharge]" placeholder="Other Charge" value="<?php if($roomdata) { echo $roomdata['othercharge']; } ?>" />
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-group form-mb-20">
                <label>Display Markup *</label>
                <select class="form-select" name="room[<?php echo $room_counter; ?>][markup_type]">
                    <option value="">Please select Markup</option>
                    <option value="in_tax" <?php if($roomdata) { if($roomdata['markup_type']=="in_tax") { echo "selected";} } ?>>In Tax</option>
                    <option value="in_service_charge" <?php if($roomdata) { if($roomdata['markup_type']=="in_service_charge") { echo "selected";} } ?>>In Service Charge</option>
                </select>
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-group form-mb-20">
                <label>Markup </label>
                <input class="form-control" type="number" name="room[<?php echo $room_counter; ?>][markup]"  placeholder="Markup" value="<?php if($roomdata) { echo $roomdata['markup']; } else { echo 0; } ?>">
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-group form-mb-20">
                <label>Discount </label>
                <input class="form-control" type="number" name="room[<?php echo $room_counter; ?>][discount]" placeholder="Discount" value="<?php if($roomdata) { echo $roomdata['discount']; } else { echo 0; } ?>">
            </div>
        </div>
    </div>



   
</div>
 </div>

 <?php if($room_counter==1) { ?>
 <script>
$(document ).ready(function() {
    setTimeout(() => {
        $("[hotel-upload-add-adt-pax]").click();
        $('.note-editable').height(120);
    }, 50);
});
</script>
<?php } ?>

<script>
$(document ).ready(function() {
    setTimeout(() => {
        $('.note-editable').height(120);
    }, 50);
});
</script>