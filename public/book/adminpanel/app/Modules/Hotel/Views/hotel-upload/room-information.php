

<div class="content">
    <div class="page-content">
        <div class="page-content-area">
            <div class="card">
                <div class="p-3">
                    <div class="row">
                        <div class="col-md-12 text-end">
                            <a href="<?php echo site_url('hotel-upload');?>?id=<?php echo $iddetail; ?>"><i class="fa fa-arrow-left"></i> Back</a>
                        </div>
                        <div class="col-md-3">
                            <b>City :</b>
                            <?php echo $hotel_detail['hotel_city']; ?>
                        </div>
                        <div class="col-md-3">
                            <p>
                                <b>Hotel Name :</b>
                                <?php echo $hotel_detail['hotel_name']; ?>
                            </p>
                        </div>
                        <div class="col-md-3">
                            <b>Check-In :</b>
                            <?php echo $hotel_detail['check_in_date']; ?>
                        </div>
                        <div class="col-md-3">
                            <b>Check-Out :</b>
                            <?php echo $hotel_detail['check_out_date']; ?>
                        </div>
                    </div>
                 
                    <button class="badge badge-wt" id="add-room-button" hotel-upload-add-room="true" tts-hotel-upload-method-name="hotel-upload/add-room" room-counter="0"><i class="fa fa-add"></i> Add Room</button>

                   <button class="badge badge-wt" type="button" hotel-upload-romm-remove="true"><i class="fa fa-trash"></i> Remove</button>
                </div>

                <form name="room-upload" tts-form="true" action="<?php echo site_url('hotel-upload/room-info-save/') ?><?php echo $_GET['id']; ?>" method="POST" id="room-upload">
                        <div tts-call-put-room-html></div>
                        <div class="row p-3">
                            <div class="col-md-12 text-end">
                                <button class="btn btn-primary" type="submit">Save & Continue</button>
                            </div>
                        </div>
                </form>
            </div>
        </div>
    </div>
</div>

<input type="hidden" id="passport_required" value="<?php if(isset($hotel_detail['passport_required'])) { echo $hotel_detail['passport_required']; } ?>">
<input type="hidden" id="pan_required" value="<?php if(isset($hotel_detail['pan_required'])) { echo $hotel_detail['pan_required']; }?>">
<input type="hidden" id="id" value="<?php if(isset($_GET['id'])) { echo $_GET['id']; }?>">
<?php if(isset($hotel_detail['room_data'])) { echo count($hotel_detail['room_data']); } ?>
<script>
$(document ).ready(function() {
    setTimeout(() => {

    
        <?php if(isset($hotel_detail)) { ?>

            for (let i = 0; i <= 2; i++) {
                console.log(i);
               
                $("[hotel-upload-add-room]").click();
               
            }
           
        <?php } else { ?>
            $("[hotel-upload-add-room]").click();
        <?php  } ?>
    }, 50);
});
</script>