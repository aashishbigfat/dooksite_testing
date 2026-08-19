<?php



namespace App\Modules\Flight\Models;



use CodeIgniter\Model;



class CouponModel extends Model
{

    protected $table = 'coupon_flight';

    protected $primarykey = 'id';

    protected $protectFields = false;



    function getCouponList($input,$web_partner_id)
    {

        if(isset($input['IsDomestic']) && !$input['IsDomestic'])
        {
          $input['IsDomestic'] =0;
        }

        $journeytype = array("1" => "oneway", "2" => "round-trip", "3" => "multicity");

        $cabinClass = array("1" => "Economy", "2" => "PremiumEconomy", "3" => "Business", "4" => "First");

        $firstAirSegment = reset($input['AirSegments']);

        $lastAirSegment = end($input['AirSegments']);

        $departureDate = strtotime(explode("T", $firstAirSegment['PreferredTime'])[0]);

        $builder = $this->db->table('coupon_flight');

        $builder->select('id,code,coupon_desc');
        $builder->where('web_partner_id', $web_partner_id);
        $builder->where('find_in_set("' . $input['IsDomestic'] . '", is_domestic) <> 0');

        $builder->where('find_in_set("' .$cabinClass[$input['CabinClass']]. '", cabin_class) <> 0');

        $builder->where('find_in_set("' . $journeytype[$input['JourneyType']] . '", journey_type) <> 0');

        $builder->where('status', 'active');

        $builder->where('use_limit>', 0);

        $builder->where('coupon_visible', '1');

        $builder->groupStart();

        $builder->where('travel_date_from<=', $departureDate);

        $builder->where('travel_date_to>=', $departureDate);

        $builder->groupEnd();

        $builder->groupStart();

        $builder->where('valid_from<=', strtotime(date('Y-m-d')));

        $builder->where('valid_to>=', strtotime(date('Y-m-d')));

        $builder->groupEnd();

        $builder->groupStart();

        $builder->where('find_in_set("' . $firstAirSegment['Origin'] . '", from_airport_code) <> 0');

        $builder->orWhere('from_airport_code', "ANY");

        $builder->groupEnd();

        $builder->groupStart();

        $builder->where('find_in_set("' . $firstAirSegment['Destination'] . '", to_airport_code) <> 0');

        $builder->orWhere('to_airport_code', "ANY");

        $builder->groupEnd();

        return  $result = $builder->get()->getResultArray();

    }

    function getDataByCode($input,$web_partner_id)
    {

        if(isset($input['IsDomestic']) && !$input['IsDomestic'])
        {
          $input['IsDomestic'] =0;
        }

        $journeytype = array("1" => "oneway", "2" => "round-trip", "3" => "multicity");

        $cabinClass = array("1" => "Economy", "2" => "PremiumEconomy", "3" => "Business", "4" => "First");

        $firstAirSegment = reset($input['AirSegments']);

        $lastAirSegment = end($input['AirSegments']);

        $departureDate = strtotime(explode("T", $firstAirSegment['PreferredTime'])[0]);

        $builder = $this->db->table('coupon_flight');

        $builder->select('id,coupon_type,value,max_limit,code as coupon_code');

        $builder->where('find_in_set("' . $input['IsDomestic'] . '", is_domestic) <> 0');

        $builder->where('find_in_set("' .$cabinClass[$input['CabinClass']]. '", cabin_class) <> 0');

        $builder->where('find_in_set("' . $journeytype[$input['JourneyType']] . '", journey_type) <> 0');

        $builder->where('status', 'active');

        $builder->where('use_limit>', 0);

        $builder->where('code', $input['code']);

        $builder->groupStart();

        $builder->where('travel_date_from<=', $departureDate);

        $builder->where('travel_date_to>=', $departureDate);

        $builder->groupEnd();

        $builder->groupStart();

        $builder->where('valid_from<=', strtotime(date('Y-m-d')));

        $builder->where('valid_to>=', strtotime(date('Y-m-d')));

        $builder->groupEnd();

        $builder->groupStart();

        $builder->where('find_in_set("' . $firstAirSegment['Origin'] . '", from_airport_code) <> 0');

        $builder->orWhere('from_airport_code', "ANY");

        $builder->groupEnd();

        $builder->groupStart();

        $builder->where('find_in_set("' . $firstAirSegment['Destination'] . '", to_airport_code) <> 0');

        $builder->orWhere('to_airport_code', "ANY");

        $builder->groupEnd();

        return $result = $builder->get()->getRowArray();

    }

    function insertData($tableName, $insertData)
    {
        $this->db->table($tableName)->insert($insertData);
        return $this->db->insertID();
    }

    function getData($tableName, $whereCondtion,$select)
    {
        return  $this->db->table($tableName)->select($select)->where($whereCondtion)->get()->getRowArray();
    }


    function updateData($tableName, $whereCondition, $updateData)
    {
        $this->db->table($tableName)->where($whereCondition)->update($updateData);
    }

    function getCouponByToken($token,$web_partner_id){

        $builder = $this->db->table("coupon_log");
        $builder->select('id,coupon_code,couponInfo');
        $builder->where(['token'=>$token,'use_for'=>'flight','web_partner_id'=>$web_partner_id]);
        $query = $builder->get()->getRowArray();
        return $query;
    }


    public function remove_promo_log($search_token_log,$web_partner_id)
    {   
        $builder = $this->db->table("coupon_log");
         return  $builder->select('*')->where('token', $search_token_log)->where('web_partner_id',$web_partner_id)->delete();
    }



}





