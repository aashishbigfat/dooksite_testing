<?php

namespace App\Models;

use CodeIgniter\Model;

class CommonModel extends Model
{
    protected $table = '';
    protected $primarykey = 'id';
    protected $protectFields = false;
    public function api_webpartner_setting($web_partner_id)
    {
        return $this->db->table("api_webpartner_setting")->select('*')->where('web_partner_id', $web_partner_id)->orderBy("id", "DESC")->get()->getRowArray();
    }
    public function gettingCountryCodeWithCountryName()
    {
        return $this->db->table("countries")->select('name as CountryName,iso2 as CountryCode')->orderBy("name", "ASC")->get()->getResultArray();
    }
    public function wl_customer_balance($web_partner_id, $wl_customer_id)
    {
        return $this->db->table("customer_account_log")->select('balance')->where('web_partner_id', $web_partner_id)->where('customer_id', $wl_customer_id)->orderBy('id', 'DESC')->limit(1)->get()->getRowArray();
    }
    public function get_payment_detail($order_id)
    {
        $result = $this->db->table("super_admin_payment_transaction")->select('id,web_partner_id,user_id,wl_customer_id')->where('order_id', $order_id)->orderBy('id', 'DESC')->limit(1)->get()->getRowArray();
        if (empty($result)) {
            $result = $this->db->table("web_partner_payment_transaction")->select('id,web_partner_id,user_id,wl_customer_id')->where('order_id', $order_id)->orderBy('id', 'DESC')->limit(1)->get()->getRowArray();
        }
        return $result;
    }
    public function get_user_detail($user_id, $web_partner_id)
    {
        return $this->db->table("customer")->select('*')->where(array('id' => $user_id, 'web_partner_id' => $web_partner_id))->limit(1)->get()->getRowArray();
    }
    function insertData($tableName, $insertData)
    {
        $this->db->table($tableName)->insert($insertData);
        return $this->db->insertID();
    }
    function getInvoiceSuffixData($whereCondition, $otherParameter)
    {
        $field = '';
        if ($otherParameter['checkTaxableInvoce'] == 1) {
            $field = 'taxable_couter as couter,taxable_prefix as prefix,financial_year';
        } else {
            $field = 'nontaxable_couter as couter,nontaxable_prefix as prefix,financial_year';
        }
        $builder = $this->db->table('invoice_suffix_list')->select($field);
        $builder->where($whereCondition);
        $data = $builder->get()->getRowArray();
        if ($data) {
            $data['IsTaxableInvoice'] = $otherParameter['checkTaxableInvoce'];
            return $data;
        } else {
            $insertData['web_partner_id'] = $otherParameter['web_partner_id'];
            $insertData['financial_year'] = $otherParameter['financialYear'];
            $insertData['service'] = $otherParameter['service'];
            $insertData['taxable_prefix'] = $otherParameter['INVPrifix']['TaxablePrfix'];
            $insertData['nontaxable_prefix'] = $otherParameter['INVPrifix']['NONTaxablePrfix'];
            $insertData['taxable_couter'] = 1;
            $insertData['nontaxable_couter'] = 1;
            $insertData['invoice_type'] = $otherParameter['invoice_type'];
            $this->db->table('invoice_suffix_list')->insert($insertData);
            if ($otherParameter['checkTaxableInvoce'] == 1) {
                $data['prefix'] = $otherParameter['INVPrifix']['TaxablePrfix'];
                $data['couter'] = 1;
                $data['financial_year'] = $otherParameter['financialYear'];
            } else {
                $data['prefix'] = $otherParameter['INVPrifix']['NONTaxablePrfix'];
                $data['couter'] = 1;
                $data['financial_year'] = $otherParameter['financialYear'];
            }
            $data['IsTaxableInvoice'] = $otherParameter['checkTaxableInvoce'];
        }
        return $data;
    }
    public function slider_list($web_partner_id)
    {
        return $this->db->table('slider')->select('*')->where(['image_category' => 'Home-Slider', "web_partner_id" => $web_partner_id, "status" => "active"])->orderBy('id', 'DESC')->get()->getResultArray();
    }

    public function pages_list($web_partner_id)
    {
        $menuArray = array();
        $getmenuList = $this->db->table("pages_menu_list")->select('menu_name,menu_type,page_content')->where('web_partner_id', $web_partner_id)->get()->getResultArray();
        if ($getmenuList) {
            $page_contents = array_column($getmenuList, "page_content");
            $pageValue = array();
            foreach ($page_contents as $page_content) {
                $pagedata = array();
                if (!empty($page_content)) {
                    $pagedata = explode(",", $page_content);
                }
                $pageValue = array_merge($pageValue, $pagedata);
            }
            $pageValue = array_unique($pageValue);
            $Pagedata = $this->db->table("pages")->select('id,title,slug_url,custom_url')->whereIn('id', $pageValue)->where('web_partner_id', $web_partner_id)->where(['status' => 'active'])->get()->getResultArray();

            $dataId = array_column($Pagedata, "id");

            // Prepare menu array
            foreach ($getmenuList as $menuList) {
                $title = array_column($Pagedata, 'title', "id");
                $slug_url = array_column($Pagedata, 'slug_url', "id");
                $custom_url = array_column($Pagedata, 'custom_url', "id");
                $pagedata = array();

                if (!empty($menuList['page_content'])) {
                    $pageContentIds = explode(",", $menuList['page_content']);
                    foreach ($pageContentIds as $pageContentId) {
                        if (isset($title[$pageContentId]) && isset($slug_url[$pageContentId]) && isset($custom_url[$pageContentId])) {
                            array_push($pagedata, array(
                                "id" => $pageContentId,
                                "title" => $title[$pageContentId],
                                "slug_url" => $slug_url[$pageContentId],
                                "custom_url" => $custom_url[$pageContentId]
                            ));
                        }
                    }
                }
                $menuArray[$menuList['menu_type']]['menu_name'] = $menuList['menu_name'];
                $menuArray[$menuList['menu_type']]['page_content'] = $pagedata;
            }
        }
        return $menuArray;
    }




    public function webpartner_whitelabel_details_bydomain_b2c($webpartner_url)
    {
        // pr($webpartner_url);
        // DIE;
        return $this->db->table("whitelabel_webpartner_setting")->select('*')->whereIn('domain_name', $webpartner_url)->where('b2c_business', "active")->orderBy("id", "DESC")->get()->getRowArray();
    }
    public function webpartner_whitelabel_details_bydomain_b2b($webpartner_url)
    {
        return $this->db->table("whitelabel_webpartner_setting")->select('*')->whereIn('domain_name', $webpartner_url)->where('b2b_business', "active")->orderBy("id", "DESC")->get()->getRowArray();
    }
    public function webpartner_details($web_partner_id)
    {
        return $this->db->table("web_partner")->select('*')->where('status', "active")->where('id', $web_partner_id)->orderBy("id", "DESC")->get()->getRowArray();
    }
    public function web_partner_balance($web_partner_id)
    {
        return $this->db->table("web_partner_account_log")->select('balance')->where('web_partner_id', $web_partner_id)->orderBy('id', 'DESC')->limit(1)->get()->getRowArray();
    }
    public function holidayshowMenu($web_partner_id)
    {
        return $this->db->table('holiday_destination')->select('id,destination_country,destination_name,destination_slug')->where(['status' => 'active', 'web_partner_id' => $web_partner_id])->orderBy('id', 'DESC')->get()->getResultArray();
    }
    public function holidayshowMenuThemes($web_partner_id)
    {
        return $this->db->table('holiday_themes')->select('id,theme_name,theme_slug')->where(['show_on_home' => 1, 'status' => 'active', 'web_partner_id' => $web_partner_id])->orderBy('id', 'DESC')->get()->getResultArray();
    }
    public function offers_list($web_partner_id)
    {
        $responseArray = array();
        $datas = $this->db->table('web_partner_offers')->select('id,title,description,service,url,image')->where('status', 'active')->where(["web_partner_offers.web_partner_id" => $web_partner_id])->orderBy('id', 'DESC')->get()->getResultArray();
        if ($datas) {
            foreach ($datas as $value) {
                if (isset($responseArray[$value['service']])) {
                    array_push($responseArray[$value['service']], $value);
                } else {
                    $responseArray[$value['service']][0] = $value;
                }
            }
        } 
        return $responseArray;
    }
    function blog_list($web_partner_id)
    {
        return $this->db->table('blog_post')->select('id,post_title,post_slug,post_desc,posted_by,post_images,created')->where(['web_partner_id' => $web_partner_id])
            ->where('status', 'active')->limit(4)->orderBy('id', 'DESC')->get()->getResultArray();
    }
    public function get_feedback_model_list($web_partner_id)
    {
        $data = $this->db->table('customer_feedback')->select('*')->where('status', 'active')
            ->where('web_partner_id', $web_partner_id)->orderBy('id', 'DESC')->limit(4)->get()->getResultArray();
        return $data;
    }
    public function getWebPartnerDetails($web_partner_id)
    {
        $query = $this->db->table('web_partner')
            ->select('web_partner.*, api_webpartner_setting.*')
            ->join('api_webpartner_setting', 'api_webpartner_setting.web_partner_id = web_partner.id', 'left')
            ->where('web_partner.status', 'active')
            ->where('web_partner.id', $web_partner_id)
            ->orderBy('web_partner.id', 'DESC')->get()->getRowArray();
        return $query;
    }



    function getWebisteCurrency($web_partner_id)
    {
        return   $this->db->table('currency')->select('currency,convertion_rate,currency_name,country,currency_symbol,default_currency,decimal_point')->where(['status' => 'active', 'web_partner_id' => $web_partner_id])->orderBy("default_currency", "Desc")->get()->getResultArray();
    }
}
