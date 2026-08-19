<div class="content">
    <div class="page-content">
        <div class="page-content-area">
            <div class="card">
                <?php ?>

                <div class="card-header text-white">Review Detail</div>
                <div class="card-body">
                    <div class="col-md-12 text-end mb-2">
                        <a href="<?php echo site_url('hotel-upload/room-information');?>?id=<?php echo $iddetail; ?>"><i class="fa fa-arrow-left"></i> Back</a>
                    </div>

                    <div class="view_head">
                        <div class="row">
                            <div class="col-md-12"><span>Hotel Information</span></div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-3 col-xs-6 col-6">
                            <div class="cart_info-field">
                                <p class="cart_info-field--title">
                                    Bussiness Type :
                                    <span class="cart_info-field--detail">
                                        <span>
                                            &nbsp;
                                            <?php echo $hotel_detail['bussiness_type'];?>
                                        </span>
                                    </span>
                                </p>
                            </div>
                        </div>
                        <div class="col-md-3 col-xs-6 col-6">
                            <div class="cart_info-field">
                                <p class="cart_info-field--title">
                                    Agent Info :
                                    <span class="cart_info-field--detail">
                                        <span>
                                            &nbsp;
                                            <?php echo isset($hotel_detail['agent_info']) ? $hotel_detail['agent_info'] : ''; ?>
                                        </span>
                                    </span>
                                </p>
                            </div>
                        </div>
                        <div class="col-md-3 col-xs-6 col-6">
                            <div class="cart_info-field">
                                <p class="cart_info-field--title">
                                    Issue Supplier :
                                    <span class="cart_info-field--detail">
                                        <span class="tts-text-success">
                                            &nbsp;
                                            <?php echo explode("#",$hotel_detail['supplier'])[1];?>
                                        </span>
                                    </span>
                                </p>
                            </div>
                        </div>
                        <div class="col-md-3 col-xs-6 col-6">
                            <div class="cart_info-field">
                                <p class="cart_info-field--title">
                                    Hotel City :
                                    <span class="cart_info-field--detail">
                                        <span>
                                            &nbsp;
                                            <?php echo $hotel_detail['hotel_city'];?>
                                        </span>
                                    </span>
                                </p>
                            </div>
                        </div>
                        <div class="col-md-3 col-xs-6 col-6">
                            <div class="cart_info-field">
                                <p class="cart_info-field--title">
                                    Hotel Name :
                                    <span class="cart_info-field--detail">
                                        <span>
                                            &nbsp;
                                            <?php echo $hotel_detail['hotel_name'];?>
                                        </span>
                                    </span>
                                </p>
                            </div>
                        </div>
                        <div class="col-md-3 col-xs-6 col-6">
                            <div class="cart_info-field">
                                <p class="cart_info-field--title">
                                    Star Rating :
                                    <span class="cart_info-field--detail">
                                        <span>
                                            &nbsp;
                                            <?php echo $hotel_detail['hotel_star_rating'];?>
                                        </span>
                                    </span>
                                </p>
                            </div>
                        </div>
                        <div class="col-md-3 col-xs-6 col-6">
                            <div class="cart_info-field">
                                <p class="cart_info-field--title">
                                    CheckIn Date :
                                    <span class="cart_info-field--detail">
                                        <span>
                                            &nbsp;
                                            <?php echo $hotel_detail['check_in_date'];?>
                                        </span>
                                    </span>
                                </p>
                            </div>
                        </div>
                        <div class="col-md-3 col-xs-6 col-6">
                            <div class="cart_info-field">
                                <p class="cart_info-field--title">
                                    CheckOut Date :
                                    <span class="cart_info-field--detail">
                                        <span>
                                            &nbsp;
                                            <?php echo $hotel_detail['check_out_date'];?>
                                        </span>
                                    </span>
                                </p>
                            </div>
                        </div>

                        <div class="col-md-3 col-xs-6 col-6">
                            <div class="cart_info-field">
                                <p class="cart_info-field--title">
                                    Contact Number :
                                    <span class="cart_info-field--detail">
                                        <span>
                                            &nbsp;
                                            <?php echo $hotel_detail['dial_code'];?>
                                            <?php echo $hotel_detail['contact_number'];?>
                                        </span>
                                    </span>
                                </p>
                            </div>
                        </div>
                        <div class="col-md-3 col-xs-6 col-6">
                            <div class="cart_info-field">
                                <p class="cart_info-field--title">
                                    Email Id :
                                    <span class="cart_info-field--detail">
                                        <span>
                                            &nbsp;
                                            <?php echo $hotel_detail['email_id'];?>
                                        </span>
                                    </span>
                                </p>
                            </div>
                        </div>

                        <div class="col-md-6 col-xs-6 col-6">
                            <div class="cart_info-field">
                                <p class="cart_info-field--title">
                                    Hotel Address :
                                    <span class="cart_info-field--detail">
                                        <span>
                                            &nbsp;
                                            <?php echo $hotel_detail['hotel_address'];?>
                                        </span>
                                    </span>
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="view_head mt-3">
                        <div class="row">
                            <div class="col-md-12"><span>Room Information</span></div>
                        </div>
                    </div>

                    <?php if($hotel_detail) {

                        foreach($hotel_detail['room_data'] as $roomkey=>$item) { ?>

                  
                    <div class="col-lg-12 col-md-12 col-12" style="padding: 10px; margin-bottom: 10px;">
                        <h5>
                            Room  <?php echo $roomkey; ?>
                        </h5>
                        <ul class="d-flex align-items-center justify-content-between mb-3 border-top border-bottom py-2">
                            <li>
                                <h6 class="m0">Room Name</h6>
                            </li>
                            <li>
                                <h6 class="m0">
                                    Amenities
                                </h6>
                            </li>
                            <li>
                                <h6 class="m0">Guests</h6>
                            </li>
                        </ul>
                        <ul class="mt-3 d-flex align-items-center justify-content-between mb-3">
                            <li>
                                <span><?php echo $item['room_name'];?></span>
                            </li>
                            <li>
                                <span>
                                    Incl :
                                    <?php 
                                        $roomamenities="";
                                        if($item['room_amenities']) 
                                        { 
                                            foreach($item['room_amenities'] as $amenitie) 
                                            {
                                                $roomamenities.= $amenitie. ', ';
                                            }
                                        }
                                        ?>
                                    <span>
                                        <?php echo rtrim($roomamenities,', '); ?>
                                    </span>
                                    
                                </span>
                            </li>
                            <li>
                                <span> <b> Adult </b> : Mr Abhay Kumar </span>
                                <span> <b> Adult </b> : Mr sdf sdf </span>
                            </li>
                        </ul>
                       
                    
                    <h6>Passenger Information</h6>
                    <table class="table table-bordered mb-3">
                        <thead>
                            <tr>
                                <th scope="col">Type</th>
                                <th scope="col">Title</th>
                                <th scope="col">First Name</th>
                                <th scope="col">Last Name</th>
                                <th scope="col">Age</th>
                                <th scope="col">PAN Number</th>
                                <th scope="col">Nationality</th>
                                <th scope="col">Passport Number</th>
                                <th scope="col">Issue Date</th>
                                <th scope="col">Expiry Date</th>
                            </tr>
                        </thead>

                        <tbody>
                            <?php if($item['pax'])
                            {
                                foreach($item['pax'] as $paxkey=>$paxitem)
                                {
                                  
                                    foreach($paxitem as $key=>$paxinfo)
                                    {
                                        ?>
                                            <tr>
                                                <th scope="row"><?php echo $paxkey;?></th>
                                                <td><?php echo $paxinfo['title'];?></td>
                                                <td><?php echo $paxinfo['first_name'];?></td>
                                                <td><?php echo $paxinfo['last_name'];?></td>
                                                <td><?php if(isset($paxinfo['age'])) { echo $paxinfo['age']; } ?></td>
                                                <td><?php echo isset($paxinfo['pan_number']);?></td>
                                                <td><?php echo isset($paxinfo['nationality']);?></td>
                                                <td><?php echo isset($paxinfo['passport_number']);?></td>
                                                <td><?php echo isset($paxinfo['issue_date']);?></td>
                                                <td><?php echo isset($paxinfo['expiry_date']);?></td>
                                            </tr>

                                        <?php
                                    }
                                }
                            } ?>
                           
                           
                        </tbody>
                    </table>

                    <h6>Fare Information</h6>
                    <div class="row">
                        <div class="col-md-2 amendment_leftpad col-6 p0">
                            <p class="mb-0"><strong>Room Price</strong></p>
                            <p class="price-width-left">
                                ₹ <?php echo $item['room_price'];?>
                            </p>
                        </div>
                        <div class="col-md-2 amendment_leftpad col-6 p0">
                            <p class="mb-0"><strong>Tax</strong></p>
                            <p class="price-width-left">
                                ₹ <?php echo $item['tax'];?>
                            </p>
                        </div>
                        <div class="col-md-2 amendment_leftpad col-6 p0">
                            <p class="mb-0"><strong>Other Charge</strong></p>
                            <p class="price-width-left">
                                ₹ <?php echo $item['othercharge'];?>
                            </p>
                        </div>
                        <div class="col-md-2 amendment_leftpad col-6 p0">
                            <p class="mb-0"><strong>Markup</strong></p>
                            <p class="price-width-left">
                                ₹ <?php echo $item['markup'];?>
                            </p>
                        </div>
                        <div class="col-md-2 amendment_leftpad col-6 p0">
                            <p class="mb-0"><strong>Discount</strong></p>
                            <p class="price-width-left">
                                ₹ <?php echo $item['othercharge'];?>
                            </p>
                        </div>
                        
                        <div class="col-md-2 amendment_leftpad col-6 p0">
                            <p class="mb-0"><b>Total Amount</b></p>
                            <p class="price-width-left">
                                <b> ₹ 1703.73 </b>
                            </p>
                        </div>
                    </div>

                        </div>
                    <div class="row">
                    <div class="col-md-6">
                        <a class="btn btn-primary" href="<?php echo  site_url('hotel-upload?id=' . $iddetail) ?>"> Previous</a>
                    </div>
                    <div class="col-md-6 text-end">
                            <a class="btn btn-primary" href="<?php echo  site_url('hotel-upload/generate-hotel-voucher?id=' . $iddetail) ?>"> Save</a>
                    </div>
                    
                </div>
                    <?php  } } ?>

                </div>
            </div>
        </div>
    </div>
</div>
