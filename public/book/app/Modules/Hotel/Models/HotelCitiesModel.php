<?php 

namespace App\Modules\Hotel\Models;

use CodeIgniter\Model;

class HotelCitiesModel extends Model
{
    protected $table = 'hotel_city_list';
    protected $primarykey = 'id';
    protected $protectFields = false;


    public function cities_list($data)
    {
        $response = [];
        if ($data) {
            $get_city = $this->select('id,destination,country,country_code,city_id')->Like('destination', $data,'after')->orderBy('priority','DESC')->limit(20)->get()->getResultArray();
            if ($get_city) {
                foreach ($get_city as $key => $get_city_val) {
                    $response[] = array('city_dom'=>trim($get_city_val['destination']."_".$get_city_val['city_id']."_".$get_city_val['country_code']), 'value' => trim($get_city_val['destination'].",".$get_city_val['country']));
                }
            } else {
                $response = [];
            }
        }else{
            $get_city = $this->select('id,destination,country,country_code,city_id')->where('priority !=', null)->orderBy('priority','DESC')->limit(20)->get()->getResultArray();
            if ($get_city) {
                foreach ($get_city as $key => $get_city_val) {
                    $response[] = array('city_dom'=>trim($get_city_val['destination']."_".$get_city_val['city_id']."_".$get_city_val['country_code']), 'value' => trim($get_city_val['destination'].",".$get_city_val['country']));
                }
            }else {
                $response = [];
            }
        }
        return json_encode($response);
    }
}
?>