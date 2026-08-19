<div class="modal-header">
     <h5 class="modal-title">Edit Slider</h5>
     <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
 </div>
 
    <form action="<?php echo site_url('slider/edit-slider/' . dev_encode($id)); ?>" method="post" tts-form="true"
          name="edit_slider" enctype="multipart/form-data">

        <div class="modal-body">
            <div class="row">

                <div class="col-6">
                    <div class="form-group">
                        <label>Slider Text1</label>
                        <input class="form-control" type="text" name="slider_text1" placeholder="Slider Text1"
                               value="<?php echo $details['slider_text1']; ?>">
                    </div>
                </div>

                <div class="col-6">
                    <div class="form-group">
                        <label>Slider Text2</label>
                        <input class="form-control" type="text" name="slider_text2" placeholder="Slider Text2"
                               value="<?php echo $details['slider_text2']; ?>">
                    </div>
                </div>

                <div class="col-6">
                    <div class="form-group">
                        <label>Button Text</label>
                        <input class="form-control" type="text" name="url_button_text" placeholder="Button Text"
                               value="<?php echo $details['url_button_text']; ?>">
                    </div>
                </div>

                <div class="col-6">
                    <div class="form-group">
                        <label> Slider Image * </label>
                        <input class="form-control" type="file" name="slider_image" placeholder="Slider Image">
                    </div>
                </div>

                <div class="col-12">
                    <div class="form-group">
                        <label>URL</label>
                        <input class="form-control" type="text" name="url" placeholder="URL"
                               value="<?php echo $details['url']; ?>">
                    </div>
                </div>


                <div class="col-6">
                    <div class="form-group">
                        <label>Status *</label>
                        <select class="form-select" name="status" placeholder="Blog Status">
                            <option value="active" <?php if ($details['status'] == "active") {
                                echo "selected";
                            } ?>>Active
                            </option>
                            <option value="inactive" <?php if ($details['status'] == "inactive") {
                                echo "selected";
                            } ?>> Inactive
                            </option>
                        </select>
                    </div>
                </div>

                <div class="col-6">
                    <div class="form-group">
                        <label>Category Type *</label>
                        <select class="form-select" name="image_category" placeholder="Slider Type">
                            <option value="Home-Slider" <?php if ($details['image_category'] == "Home-Slider") {
                                echo "selected";
                            } ?>>Home-Slider
                            </option>
                            <option value="Agent-Home-Slider" <?php if ($details['image_category'] == "Agent-Home-Slider") {
                                echo "selected";
                            } ?>>Agent-Home-Slider
                            </option>
                            <option value="Agent-Login" <?php if ($details['image_category'] == "Agent-Login") {
                                echo "selected";
                            } ?>>Agent-Login
                            </option>
                        </select>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-footer">
         <button class="btn btn-primary" type="submit">Save</button>
     </div>
    </form>
 
