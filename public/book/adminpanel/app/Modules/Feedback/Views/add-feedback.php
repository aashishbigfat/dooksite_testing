<div class="modal-header">
        <h5 class="modal-title">Add <?php echo ' '.$title;?></h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>


    <form action="<?php echo site_url('feedback/add-feedback'); ?>" method="post" onsubmit="return validateForm()" tts-form="true" name="add_feedback" enctype="multipart/form-data">

        <div class="modal-body">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group form-mb-20">
                        <label> Name *  </label>
                        <input class="form-control" type="text" name="name" placeholder="Name">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group form-mb-20">
                        <label> Email * </label>
                        <input class="form-control" type="email" name="email" placeholder="Email">
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group form-mb-20">
                        <label> Mobile No * </label>
                        <input class="form-control" type="text" name="phone" placeholder="Mobile No">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group form-mb-20">
                        <label> User Image *</label>
                        <input class="form-control" type="file" name="image" >
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group form-mb-20">
                        <label> Feedback Date * </label>
                        <input class="form-control" type="text" nolim-calendor="true" name="feedback_date" placeholder="Feedback Date">
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group form-mb-20">
                        <label>Feedback Status *</label>
                        <select class="form-select" name="status" placeholder="Feedback Status">
                            <option value="active"> Active</option>
                            <option value="inactive" > Inactive </option>
                        </select>
                    </div>
                </div>

                <div class="col-md-12">
                    <div class="form-group form-mb-20">
                        <label> Feedback  Description *</label>
                        <textarea class="form-control tts-editornote" type="textarea" name="description" rows="2" placeholder="Feedback Description"></textarea>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <button class="btn btn-primary" type="submit">Save</button>
        </div>
    </form>
