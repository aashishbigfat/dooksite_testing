<?php

namespace App\Modules\Dashboard\Models;

use CodeIgniter\Model;

class DashboardModel extends Model
{


    public function country_list()
    {
        return $this->db->table('countries')->select('id,name')->get()->getResultArray();
    }

    public function state_list($country_id)
    {
        return $this->db->table('states')->select('id,name')->where('country_id',$country_id)->get()->getResultArray();
    }

    public function city_list($state_id)
    {
        return $this->db->table('cities')->select('id,name')->where('state_id',$state_id)->get()->getResultArray();
    }


    public function country_list_row($country_id)
    {
        return $this->db->table('countries')->select('id,name')->where('id',$country_id)->get()->getRowArray();
    }

    public function state_list_row($state_id)
    {
        return $this->db->table('states')->select('id,name')->where('id',$state_id)->get()->getRowArray();
    }

    public function city_list_row($city_id)
    {
        return $this->db->table('cities')->select('id,name')->where('id',$city_id)->get()->getRowArray();
    }

}


