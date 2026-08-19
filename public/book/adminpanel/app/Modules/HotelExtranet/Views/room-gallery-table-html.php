<?php $trash_uri = "hotel-extranet/remove-room-gallery"; ?>
<form action="<?php echo site_url($trash_uri); ?>" method="POST" tts-form="true" id="formlist">
   <div class="table-responsive">
      <table class="table table-bordered table-hover">
      <thead class="table-active">
         <tr>
            <th><label><input type="checkbox" name="check_all"
               id="selectall"/></label>
            </th>
            <th>Image</th>
            <th>Title</th>
            <th>Created</th>
         </tr>
      </thead>
      <tbody>
         <?php
            if (!empty($list) && is_array($list)) {
            
                foreach ($list as $data) {
            
            
            
                    ?>
         <tr>
            <td>
               <label><input type="checkbox" name="checklist[]"
                  class="checkbox"
                  value="<?php echo $data['id']; ?>"/></label>
            </td>
            <td>
               <img src="<?php echo root_url . "uploads/hotel/thumbnail/" . $data['room_gallery']; ?>"
                  alt="<?php echo $data['room_gallery']; ?>"
                  class="tts-blog-image">
            </td>
            <td>
               <?php echo ucfirst($data['image_title']); ?>
            </td>
            <td>
               <?php echo date_created_format($data['created']); ?>
            </td>
         </tr>
         <?php }
            } else {
            
                echo "<tr> <td colspan='11' class='text_center'><b>No Gallery Found</b></td></tr>";
            
            } ?>
      </tbody>
   </table>
   </div>
</form>