<div class="content ">
    <div class="page-content">
        <div class="table_title">
            <div class="sale_bar">
                <div class="row">
                    <div class="col-md-4">
                        <h5 class="m-0"> Add <?php echo $title ?></h5>
                    </div>
                </div>
            </div>
            <div class="page-content-area">
                <div class="card-body">
                    <form name="web-partner" tts-form='true' action="<?php echo site_url('activities/add-activities-main-save'); ?>" method="POST" id="web-partner" enctype="multipart/form-data">
                        <?php foreach ($allsupplier as $key => $item) : ?>
                            <div class="row"> 
                                <div class="col-md-12 ">
                                    <h6 class="view_head"> <?php echo $key; ?> <input type="checkbox" name="" id=""></h6>
                                </div>  
                                <?php unset($item['supplier_name'],$item['status'],$item['credentials'],$item['account_id']); ?>

                                <?php foreach ($item as $keys => $items) : ?>
                                    <div class="col">
                                        <div class="mb-3">
                                            <label for="exampleInputEmail1" class="form-label"><?php echo ucfirst($keys); ?></label>
                                            <input type="checkbox" name="" id="">
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                                

                                <div class="col-md-12 ">
                                    <h6 class="view_head"> credentials</h6>
                                </div>
                                <div class="col">
                                    <div class="mb-3">
                                        <label for="exampleInputEmail1" class="form-label">Username</label>
                                        <input type="password" class="form-control" id="exampleInputPassword1">
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="mb-3">
                                        <label for="exampleInputEmail1" class="form-label">Password</label>
                                        <input type="password" class="form-control" id="exampleInputPassword1">
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="mb-3">
                                        <label for="exampleInputEmail1" class="form-label">Mode</label>
                                        <select class="form-select" aria-label="Default select example">
                                            <option disabled selected>API Mode Status</option>
                                            <option value="Test">Test</option>
                                            <option value="Live">Live</option>
                                            
                                        </select>
                                    </div>
                                </div>
                            </div> 
                        <?php endforeach; ?>


                        <div class="row">
                            <div class="col-md-12 text-md-right">
                                <button class="btn btn-primary" type="submit">Save
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>