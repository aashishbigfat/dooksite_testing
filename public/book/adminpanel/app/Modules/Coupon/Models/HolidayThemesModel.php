<?php

namespace App\Modules\Holiday\Models;

use CodeIgniter\Model;

class HolidayThemesModel extends Model
{
    protected $table = 'holiday_themes';
    protected $primarykey = 'id';
    protected $protectFields = false;

    public function holiday_themes_list_select($web_partner_id){
        return  $this->select('id,theme_name,web_partner_id')->where(["web_partner_id"=>$web_partner_id])->get()->getResultArray();
    }

}