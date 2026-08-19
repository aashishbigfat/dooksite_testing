<style>
   .btn-primary {
   color: #fff;
   background-color: #337ab7;
   border-color: #2e6da4;
   }
   .btn-group-xs>.btn, .btn-xs {
   padding: 1px 5px;
   font-size: 12px;
   line-height: 1.5;
   border-radius: 3px;
   }
</style>
<div class="content">
   <div class="page-content">
      <div class="page-content-area">
         <div class="card-header">
            <div class="table_title">
               <div class="topbar">
                  <span>Room <?php echo 'Availability';  ?> </span>
               </div>
            </div>
         </div>
         <div class="card-body">
            <div class="row">
               <div class="col-md-12 ">
                  <form name="web-partner" tts-form='true'
                     action="<?php echo site_url('hotel-extranet/room-availability-update/') . dev_encode($room_id); ?>"
                     method="POST" id="web-partner">
                     <div style="overflow: auto;">
                        <table style="width: 100%; font-family: Calibri; font-size: 15px; border: 1px solid #C7C7C7;  border-collapse: collapse;  float: left;    margin-top: 15px;   border-spacing: 0px;  color: #fff;">
                           <tbody>
                              <tr>
                                 <td style="border-bottom: 1px solid #C7C7C7;font-size: 16px; color: #333; padding: 2px 0px; padding: 1px 10px; border-right: 1px solid #C7C7C7; text-align: left;border-left: 1px solid #C7C7C7;">
                                    <b>Year:</b>
                                 </td>
                                 <td style="border-bottom: 1px solid #C7C7C7;font-size: 16px; color: #333; padding: 2px 10px; padding: 1px 10px; border-right: 1px solid #C7C7C7; text-align: left;">
                                    <select class="form-control" name="year" id="year">
                                       <option value="<?php echo $year; ?>" <?php if ($year == $_GET['year']) {
                                          echo "selected";
                                          
                                          } ?>><?php echo $year; ?></option>
                                       <option value="<?php echo $next_year; ?>" <?php if ($next_year == $_GET['year']) {
                                          echo "selected";
                                          
                                          } ?>><?php echo $next_year; ?></option>
                                    </select>
                                 </td>
                                 <td style="border-bottom: 1px solid #C7C7C7;font-size: 16px; color: #333; padding: 2px 0px; padding: 1px 10px; border-right: 1px solid #C7C7C7; text-align: left;border-left: 1px solid #C7C7C7;">
                                    <b>Room Title:</b>
                                 </td>
                                 <td style="border-bottom: 1px solid #C7C7C7;font-size: 16px; color: #333; padding: 2px 10px; padding: 1px 10px; border-right: 1px solid #C7C7C7; text-align: left;">
                                    <b><?php echo $room_title;?></b>
                                 </td>
                              </tr>
                           </tbody>
                        </table>
                        <?php
                           $year = $_GET['year'];
                           
                           // change this to another year
                           
                           $row = 0; // to set the number of rows and columns in yearly calendar
                           
                           echo "<table class='main' align='center'>"; // Outer table
                           
                           //  Creating calendars for each month by looping 12 times //
                           
                           for ($m = 1; $m <= 12; $m++) {
                           
                               $month = date($m);  // Month
                           
                               $dateObject = DateTime::createFromFormat('!m', $m);
                           
                               $monthName = $dateObject->format('m'); // Month name to display at top
                           
                               $monthfull = $dateObject->format('F'); // Month name to display at top
                           
                               $d = 2; // To Finds today's date
                           
                               $no_of_days = date('t', mktime(0, 0, 0, $month, 1, $year)); //This is to calculate number of days in a month
                           
                           
                           
                               $j = date('w', mktime(0, 0, 0, $month, 1, $year)); // This will calculate the week day of the first day of the month
                           
                           
                           
                               $j = $j - 1;
                           
                               if ($j < 0) {
                           
                                   $j = 6;
                           
                               }  // if it is Sunday //
                           
                               //// end of if starting day of the week is Monday ////
                           
                               $adj = str_repeat("<td style='width: 68px;'><span></span></td>", $j);  // Blank starting cells of the calendar
                           
                               $blank_at_end = 42 - $j - $no_of_days; // Days left after the last day of the month
                           
                               $adj2 = str_repeat("<td style='width: 68px;' ><span></span></td>", $blank_at_end); // Blank ending cells of the calendar
                           
                               /// Starting of top line showing year and month to select ///////////////
                           
                               if (($row % 1) == 0) {
                           
                                   echo "</tr><tr>";
                           
                               }
                           
                               echo "<td><table class='main' style='margin: 13px;' ></tr>";
                           
                               //// End of the top line showing name of the days of the week//////////
                           
                               //////// Starting of the days//////////
                           
                           
                           
                               $z = $m - 1;
                           
                               $month = array();
                           
                           
                           
                               for ($i = 1; $i <= $no_of_days; $i++) {
                           
                           
                           
                                   $inputval = $room_availabilities[$z]["d$i"];
                           
                           
                           
                           
                           
                                   $inputid = $room_availabilities[$z]["id"];
                           
                           
                           
                           
                           
                                   echo "<input type='hidden' value='$inputid' name='data[$monthfull][id]'>";
                           
                           
                           
                           
                           
                                   $date = "$year-$monthName-$i";
                           
                           
                           
                                   $day = date('D', strtotime($date));
                           
                           
                           
                                   if ($i == 1) {
                           
                                       echo ' <td align="center"><br/><b style="margin-right: 40px;">' . $monthfull . " " . $year . ' </b></td>
                           
                           
                           
                           <td><br/><span class="btn btn-primary btn-xs pointer monthswise" atr-months="' . $monthfull . '"> >> </span></td><td></td>';
                           
                                       $month[] = '';
                           
                                       $data = array(
                           
                                           $monthfull => $month
                           
                                       );
                           
                           
                           
                                   }
                           
                                   $month[] = '';
                           
                                   $data = array(
                           
                                       $monthfull => $month
                           
                                   );
                           
                           
                           
                                   echo $adj . "<td style='padding-left: 7px;'><label class='l_day'>$i ($day)</label>
                           
                           <input  type='number' name='data[$monthfull][d$i] ' value='$inputval' min='0' style='width:75px;' class='monthswisevalue_$monthfull' id='mv_$monthfull$i' /></td>"; // This will display the date inside the calendar cell
                           
                                   $adj = '';
                           
                                   $j++;
                           
                           
                           
                               }
                           
                           
                           
                               echo $adj2;   // Blank the balance cell of calendar at the end
                           
                               echo "</tr></table></td>";
                           
                               $row = $row + 1;
                           
                           }
                           
                           echo "</table>";
                           
                           
                           
                           ?>
                     </div>
                     <?php //if (permission_access("CarExtranet", "car_availability_update")) { ?>
                     <div class="row">
                        <div class="col-md-12 text-md-right">
                           <button class="btn btn-primary" type="submit">Update></button>
                        </div>
                     </div>
                     <?php //}?>
                  </form>
               </div>
            </div>
         </div>
      </div>
   </div>
</div>
<script>
   $(function () {
   
       $('#year').change(function () {
   
           var year = $(this).val();
   
           window.location.href = "<?php echo site_url('/hotel-extranet/get-room-availability?key') . $key . "&year=" ?>" + year;
   
       });
   
   });
   
   
   
   $(".monthswise").on('click', function () {
   
       var months = $(this).attr('atr-months');
   
       var putval = ($("#mv_" + months + '1').val());
   
       if (putval) {
   
           $('.monthswisevalue_' + months).val(putval);
   
       } else {
   
           alert("Please Enter Value in First Box, and Click");
   
   
   
       }
   
   });
   
</script>