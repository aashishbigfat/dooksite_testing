<?php

namespace Modules\Feedback\Controllers;
use App\Modules\Feedback\Models\FeedbackModel;
 
use App\Controllers\BaseController;
use Modules\Feedback\Config\Validation;
 

class Feedback extends BaseController
{

    public $title;
    public $folder_name;
    public $web_partner_details;
    public $web_partner_id;
    public $wl_customer_id;
    public $booking_source;
    public $wl_customer_info;
    public $Services;
    public $customer_gst_number;
    public $validation;
    public $request;

    public function initController(\CodeIgniter\HTTP\RequestInterface $request, \CodeIgniter\HTTP\ResponseInterface $response, \Psr\Log\LoggerInterface $logger)
    {
        parent::initController($request, $response, $logger);
        $this->folder_name = "feedback";
        $this->title = "Feedback";
        $this->web_partner_details = web_partner_details;
        $this->web_partner_id = web_partner_details['id'];
        $this->wl_customer_id = '';
        if (isset(session()->get('wl_customer')['id'])) {
            $this->wl_customer_id = session()->get('wl_customer')['id'];
        }

       
        ini_set("memory_limit", "1024M");
        $this->booking_source = "Wl_b2c";
    }

    public function index()
    {
        $FeedbackModel = new FeedbackModel();
        $feedbac_list = $FeedbackModel->get_feedback_model_list($this->web_partner_id);
       
        $data = [
            'title' => $this->title,
            'feedbac_list' => $feedbac_list, 
            'view' => "Feedback\Views\booking\index",
        ];
        return view('template/default-layout', $data);
    } 


    public function read_more()
    {
        $FeedbackModel = new FeedbackModel();
        $feedbac_list = $FeedbackModel->get_feedback_model_listRead_more($this->web_partner_id);
        $data = [
            'title' => $this->title,
          
            'feedbac_list' => $feedbac_list, 
            'view' => "Feedback\Views\booking/read-more",
        ];
        return view('template/default-layout', $data);
    }


    public function add_feedback_saved()
    {
        $validate = new Validation();
        $rules = $this->validate($validate->feedback_validation);
        if (!$rules) {
            $errors = $this->validator->getErrors();
            $data_validation = array("StatusCode" => 1, "ErrorMessage" => array_filter($errors));
            return $this->response->setJSON($data_validation);
        } else {
            $FeedbackModel = new FeedbackModel();
            $data = $this->request->getPost(); 
 
            $field_name = 'image';
            $file = $this->request->getFile($field_name);
            $resizeDim = array('width' => 150, 'height' => 180);
            $image_upload = image_upload($file, $field_name, $this->folder_name, $resizeDim);
           
            if ($image_upload['status_code'] == 0) { 
                $data['created'] = create_date();
                $data['feedback_date'] = "NO Date";
                $data['status'] = "inactive";
                $data[$field_name] = $image_upload['file_name']; 
                $data['web_partner_id'] = $this->web_partner_id;
              
                $added_data = $FeedbackModel->insert($data);
                
                if ($added_data) {
                    $message = array("StatusCode" => 0, "Message" => "Feedback Successfully added", "Class" => "success_popup", "Reload" => "true");
                } else {
                    $message = array("StatusCode" => 2, "Message" => "Feedback not  added", "Class" => "error_popup", "Reload" => "true");
                }
            } else {
                $message = array("StatusCode" => 2, "Message" => $image_upload['message'], "Class" => "error_popup", "Reload" => "true");
            }

            $this->session->setFlashdata('Message', $message);
            return $this->response->setJSON($message);
        }
        
    }
     

}
