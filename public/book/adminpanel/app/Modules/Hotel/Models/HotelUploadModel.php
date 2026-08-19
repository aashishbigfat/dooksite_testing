<?php 
namespace App\Modules\Hotel\Models;
use CodeIgniter\Model;

class HotelUploadModel extends Model
{

    protected $table = '';
    protected $primarykey = '';
    protected $protectFields = false;

    public function get_offline_supplier($web_partner_id)
    {
        return  $this->db->table('offline_provider')->select('id,supplier_name')->where(['web_partner_id'=>$web_partner_id,'hotel_service'=>'active','status'=>'active'])->get()->getResultArray();
    }
    public function get_room_amenities($web_partner_id)
    {
        return  $this->db->table('hotel_extranet_amenity')->select('amenity_title,amenity_icon')->where(['web_partner_id'=>$web_partner_id,'status'=>'active'])->where('amenity_type !=', 'hotel')->get()->getResultArray();
    }

    public function insertData($tableName,$data)
    {
         $this->db->table($tableName)->insert($data);
         return $this->db->insertID();
    }

    function getData($tableName, $whereCondition,$selectedColumnValue  = null)
    {
        $builder=$this->db->table($tableName);
        if($selectedColumnValue != null)
        {
            $builder->select($selectedColumnValue);
        }
        if($whereCondition)
        {
            $builder->where($whereCondition);
        }
        return $builder->get()->getRowArray();
    }

    function updateData($tableName,$whereClause,$data)
    {
        $this->db->table($tableName)
            ->where($whereClause)
            ->update($data);
        return $this->db->insertID();
    }
}
?>