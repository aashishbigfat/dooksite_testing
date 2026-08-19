<?php

namespace Modules\Currency\Controllers;

use App\Modules\Currency\Models\CurrencyModel;
use App\Controllers\BaseController;
use Modules\Currency\Config\Validation;


class Currency extends BaseController
{

    public function initController(\CodeIgniter\HTTP\RequestInterface $request, \CodeIgniter\HTTP\ResponseInterface $response, \Psr\Log\LoggerInterface $logger)
    {
        ini_set('serialize_precision', -1);
        parent::initController($request, $response, $logger);
        $this->title = "Super Admin Currency";
        $this->folder_name = "Currency";
        $this->web_partner_id = admin_cookie_data()['admin_user_details']['web_partner_id'];

       
    }

    public function index()
    {

        $CurrencyModel = new CurrencyModel();
        if($this->request->getGet() && $this->request->getGet('key'))
        {
            $lists=$CurrencyModel->search_data($this->request->getGet(),$this->web_partner_id,);
        }  else {
            $lists=$CurrencyModel->currency_list($this->web_partner_id);
        }
        $data = [
            'title' => $this->title,
            'list' => $lists,
            'view' => "Currency\Views\currency-list",
            'pager' => $CurrencyModel->pager,
            'search_bar_data'=>$this->request->getGet(),
        ];


        return view('template/sidebar-layout', $data);
    }

    public function add_currency_view()
    {
         //if (permission_access_error("Currency", "add_currency")) {
        $CurrencyModel = new CurrencyModel();
        $country = $CurrencyModel->get_currency();
            $data = [
                'title' => $this->title,
                'country'=> $country,
            ];
            $add_blog_view = view('Modules\Currency\Views\add-currency', $data);
            $data = array("StatusCode" => 0, "Message" => $add_blog_view, 'class' => 'success_popup', "Reload" => "false");
            return $this->response->setJSON($data);
        //}
    }


    public function add_currency()
    {
           $data = $this->request->getPost(); 
            $validate = new Validation();
            $rules = $this->validate($validate->currency_validation);
            if (!$rules) {
                $errors = $this->validator->getErrors();
                $data_validation = array("StatusCode" => 1, "ErrorMessage" => array_filter($errors));
                return $this->response->setJSON($data_validation);
            } else {
                $country = $data['country'];
                $CurrencyModel = new CurrencyModel();
                $existingValue = $CurrencyModel->get_unique_country($country,$this->web_partner_id);
                if ($existingValue) {
                    $data_validation = array("StatusCode" => 1, "ErrorMessage" => ["country" => "Country  already Exites"]);
                    return $this->response->setJSON($data_validation);
                }
                $CurrencyModel = new CurrencyModel();
                $data = $this->request->getPost();
              
              
             
       
                    $data['created'] = create_date();
                    $data['web_partner_id'] =  $this->web_partner_id;
           
                    $added_data = $CurrencyModel->insert($data);

                    if ($added_data) {
                        $message = array("StatusCode" => 0, "Message" => "currency successfully added", "Class" => "success_popup", "Reload" => "true");
                    } else {
                        $message = array("StatusCode" => 2, "Message" => "currency not  added", "Class" => "error_popup", "Reload" => "true");
                    }
              


                $this->session->setFlashdata('Message', $message);
                return $this->response->setJSON($message);
            }
     
    }


    public function edit_currency_view()
    {
        //if (permission_access_error("Currency", "edit_currency")) {
           /*  $id = dev_decode($this->request->uri->getSegment(3)); */
            $uri = service('uri'); 
            $id = dev_decode($uri->getSegment(3));
            $CurrencyModel = new CurrencyModel();
            $country = $CurrencyModel->get_currency();
            $data = [
                'title' => $this->title,
                'id' => $id,
                'country' => $country,
                'details' => $CurrencyModel->currency_details($id, $this->web_partner_id),
            ];
            $blog_details = view('Modules\Currency\Views\edit-currency', $data);
            $data = array("StatusCode" => 0, "Message" => $blog_details, 'class' => 'success_popup');
            return $this->response->setJSON($data);
        //}
    }

    public function edit_currency()
    {

        // if (permission_access_error("Currency", "edit_currency")) {

        $uri = service('uri'); 
        $id = dev_decode($uri->getSegment(3));
        $data = $this->request->getPost();
        
        $validate = new Validation();
        $rules = $this->validate($validate->currency_validation);
    
        if (!$rules) {
            $errors = $this->validator->getErrors();
            $data_validation = array("StatusCode" => 1, "ErrorMessage" => array_filter($errors));
            return $this->response->setJSON($data_validation);
        } else {
            $CurrencyModel = new CurrencyModel();
            $getcountry = $CurrencyModel->currency_details($id, $this->web_partner_id);
        //     if($getcountry['country'] != $data['country']){
        //     $country = $data['country'];
        //     $CurrencyModel = new CurrencyModel();
        //     $existingValue = $CurrencyModel->get_unique_ucountry($country,$this->web_partner_id);
        //     if ($existingValue) {
        //         $data_validation = array("StatusCode" => 1, "ErrorMessage" => ["country" => "Country  already Exites"]);
        //         return $this->response->setJSON($data_validation);
        //     }
        // }
            $CurrencyModel = new CurrencyModel();
            $data = $this->request->getPost();
            $data['web_partner_id'] =  $this->web_partner_id;
            $data['modified'] = create_date();

            $added_data = $CurrencyModel->where(["id" => $id, 'web_partner_id' => $this->web_partner_id])->set($data)->update();

            if ($added_data) {
                $message = array("StatusCode" => 0, "Message" => "currency successfully Edit", "Class" => "success_popup", "Reload" => "true");
            } else {
                $message = array("StatusCode" => 2, "Message" => "currency not  Edit", "Class" => "error_popup", "Reload" => "true");
            }
           
           
            $this->session->setFlashdata('Message', $message);
            return $this->response->setJSON($message);
        }
       // }
    }


public function getgsm($value,$web_partner_id)
    {
      return $this->select('id,type,value,status,created,modified')->where(['type' => 'GSM'])->where(['value'=>$value,'web_partner_id'=>$web_partner_id])->paginate(40);
    }

    public function remove_currency()
    {
       // if (permission_access_error("Currency", "delete_currency")) {

        $CurrencyModel = new CurrencyModel();
        $id= $this->request->getPost('checklist');
            $delete = $CurrencyModel->remove_currency($id,$this->web_partner_id);
        if ($delete) {
            $message = array("StatusCode" => 0, "Message" => "currency  successfully  deleted", "Class" => "success_popup", "Reload" => "true");
        } else {
            $message = array("StatusCode" => 2, "Message" => "currency  not deleted", "Class" => "error_popup", "Reload" => "true");
        }
        $this->session->setFlashdata('Message', $message);
        return $this->response->setJSON($message);
        //}
    }



    
    public function currency_status_change()
    {

    
            $validate = new Validation();
            $rules = $this->validate($validate->currency_status);
            if (!$rules) {
                $errors = $this->validator->getErrors();
                $data_validation = array("StatusCode" => 1, "ErrorMessage" => array_filter($errors));
                return $this->response->setJSON($data_validation);
            } else {
                $CurrencyModel = new CurrencyModel();
                $ids = $this->request->getPost('checkedvalue');

                $data['status'] = $this->request->getPost('status');

                $update = $CurrencyModel->currency_status_change($this->web_partner_id, $ids, $data);

                if ($update) {
                    $message = array("StatusCode" => 0, "Message" => "currency status  successfully update", "Class" => "success_popup", "Reload" => "true");
                } else {
                    $message = array("StatusCode" => 2, "Message" => "currency status not updated successfully", "Class" => "error_popup", "Reload" => "true");
                }
                $this->session->setFlashdata('Message', $message);
                return $this->response->setJSON($message);
            }
      
        }




        public function  default_status_update()
        {
            
            $uri = service('uri'); 
            $ids = dev_decode($uri->getSegment(3));
             $CurrencyModel = new CurrencyModel();
             $data['default_currency'] = $this->request->getPost('default_currency');
             
             $default_status_value = $CurrencyModel->check_default_status($this->web_partner_id, $ids);

            if($default_status_value['defaultStatus'] == "active") {
                $data['default_currency'] = "inactive";
            }
            else {
                $data['default_currency'] = "active";
            }
            $updated = $CurrencyModel->updateData('currency', ['web_partner_id' => $this->web_partner_id], ['default_currency' => "inactive"]);
             $update = $CurrencyModel->change_default_Status($this->web_partner_id, $ids,$data);
             if ($update) {
                $message = array("StatusCode" => 0, "Message" => "currency status  successfully update", "Class" => "success_popup", "Reload" => "true");
            } else {
                $message = array("StatusCode" => 2, "Message" => "currency status not updated successfully", "Class" => "error_popup", "Reload" => "true");
            }
            //$this->session->setFlashdata('Message', $message);
            // return $this->response->setJSON($message);
        }





   


}