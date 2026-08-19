<?php

namespace Modules\Feedback\Controllers;

use App\Modules\Feedback\Models\FeedbackModel;
use App\Controllers\BaseController;
use Modules\Feedback\Config\Validation;


class Feedback extends BaseController
{

    protected $title; 
    protected $web_partner_id; 
    protected $user_id;  
    protected $folder_name;  

    public function initController(\CodeIgniter\HTTP\RequestInterface $request, \CodeIgniter\HTTP\ResponseInterface $response, \Psr\Log\LoggerInterface $logger)
    {
        parent::initController($request, $response, $logger);
        $this->title = "Feedback";
        $this->folder_name = "feedback"; 
        $this->web_partner_id = admin_cookie_data()['admin_user_details']['web_partner_id'];

        if(permission_access_error("Feedback","Feedback_Module")) {

        }
        
    }

    public function index()
    { 
        $FeedbackModel = new FeedbackModel();
        if($this->request->getGet() && $this->request->getGet('key'))
        {
            $lists=$FeedbackModel->search_data($this->request->getGet(),$this->web_partner_id);
        }  else {
            $lists=$FeedbackModel->feedback_list( $this->web_partner_id);
        }
        $data = [
            'title' => $this->title,
            'list' => $lists,
            'view' => "Feedback\Views\Feedback-list",
            'pager' => $FeedbackModel->pager,
            'search_bar_data'=>$this->request->getGet(),
        ];


        return view('template/sidebar-layout', $data);
    }

    public function add_feedback_view()
    {
        if (permission_access_error("Feedback", "add_feedback")) {
            $data = [
                'title' => $this->title,
            ];
            $add_blog_view = view('Modules\Feedback\Views\add-feedback', $data);
            $data = array("StatusCode" => 0, "Message" => $add_blog_view, 'class' => 'success_popup', "Reload" => "false");
            return $this->response->setJSON($data);
        }
    }


    public function add_feedback()
    {
        if (permission_access("Feedback", "add_feedback")) {
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
        } else {
            $message = array("StatusCode" => 2, "Message" => "Permission Denied", "Class" => "error_popup");
            return $this->response->setJSON($message);
        }
    }

    public function edit_feedback_view()
    {
        if (permission_access_error("Feedback", "edit_feedback")) {
           

            $uri = $this->request->getUri();
            $id = dev_decode($uri->getSegment(3));

            $FeedbackModel = new FeedbackModel();
            $data = [
                'title' => $this->title,
                'id' => $id,
                'details' => $FeedbackModel->feedback_details($id,$this->web_partner_id),
            ];
            $blog_details = view('Modules\Feedback\Views\edit-feedback', $data);
            $data = array("StatusCode" => 0, "Message" => $blog_details, 'class' => 'success_popup');
            return $this->response->setJSON($data);
        }
    }


    public function edit_feedback()
    {
        if (permission_access("Feedback", "edit_feedback")) {
            $uri = $this->request->getUri();
            $id = dev_decode($uri->getSegment(3));
            $validate = new Validation();
            $field_name = 'image';
            $images_file = $this->request->getFile($field_name);
            if ($images_file->getName() == '') {
                unset($validate->feedback_validation[$field_name]);
            }
            
            $rules = $this->validate($validate->feedback_validation);
            if (!$rules) {
                $errors = $this->validator->getErrors();
                $data_validation = array("StatusCode" => 1, "ErrorMessage" => array_filter($errors));
                return $this->response->setJSON($data_validation);
            } else {
                $FeedbackModel = new FeedbackModel();
                $data = $this->request->getPost(); 
                $previous_data = $FeedbackModel->feedback_details($id,$this->web_partner_id); 
                $file = $this->request->getFile($field_name);
                if ($file->getName() != '') {
                    $resizeDim = array('width' => 360, 'height' => 200);
                    $image_upload = image_upload($file, $field_name, $this->folder_name, $resizeDim);
                    if ($image_upload['status_code'] == 0) { 
                        if ($previous_data[$field_name]) {
                            if (file_exists("../uploads/$this->folder_name/" . $previous_data[$field_name])) {
                                unlink("../uploads/$this->folder_name/" . $previous_data[$field_name]);
                                unlink("../uploads/$this->folder_name/thumbnail/" . $previous_data[$field_name]);
                            }
                        } 
                        $data['modified'] = create_date();
                        $data[$field_name] = $image_upload['file_name'];
                        $added_data = $FeedbackModel->where(["id"=>$id,"web_partner_id"=>$this->web_partner_id])->set($data)->update();

                        if ($added_data) {
                            $message = array("StatusCode" => 0, "Message" => "Feedback Successfully Updated", "Class" => "success_popup", "Reload" => "true");
                        } else {
                            $message = array("StatusCode" => 2, "Message" => "Feedback not  Updated", "Class" => "error_popup", "Reload" => "true");
                        }
                    } else {
                        $message = array("StatusCode" => 2, "Message" => $image_upload['message'], "Class" => "error_popup", "Reload" => "true");
                    }
                } else {

                    $data['modified'] = create_date(); 
                    $data[$field_name] = $previous_data[$field_name];
                    $added_data = $FeedbackModel->where(["id"=>$id,"web_partner_id"=>$this->web_partner_id])->set($data)->update();
                    if ($added_data) {
                        $message = array("StatusCode" => 0, "Message" => "Feedback Successfully Updated", "Class" => "success_popup", "Reload" => "true");
                    } else {
                        $message = array("StatusCode" => 2, "Message" => "Feedback not  Updated", "Class" => "error_popup", "Reload" => "true");
                    }
                }

                $this->session->setFlashdata('Message', $message);
                return $this->response->setJSON($message);
            }
        } else {
            $message = array("StatusCode" => 2, "Message" => "Permission Denied", "Class" => "error_popup");
            return $this->response->setJSON($message);
        }
    }

    public function feedback_details(){
        $uri = $this->request->getUri();
        $id = dev_decode($uri->getSegment(3));
        $FeedbackModel = new FeedbackModel();
        $data = [
            'title' => $this->title,
            'id' => $id,
            'details' => $FeedbackModel->feedback_details($id,$this->web_partner_id),
        ];
        $blog_details = view('Modules\Feedback\Views\feedback-details', $data);
        $data = array("StatusCode" => 0, "Message" => $blog_details, 'class' => 'success_popup');
        return $this->response->setJSON($data);
    }

    public function remove_feedback()
    {
        if (permission_access("Feedback", "delete_feedback")) {
            $FeedbackModel = new FeedbackModel();
            $ids = $this->request->getPost('checklist');
 
            $field_name = 'image';

            foreach ($ids as $id) {
                $details = $FeedbackModel->delete_image($id,$this->web_partner_id);
                if ($details[$field_name]) {
                    if (file_exists(FCPATH."../uploads/$this->folder_name/" . $details[$field_name])) {
                        unlink(FCPATH."../uploads/$this->folder_name/" . $details[$field_name]);
                        unlink(FCPATH."../uploads/$this->folder_name/thumbnail/" . $details[$field_name]);
                    }
                }
                $delete = $FeedbackModel->remove_Feedback($ids,$this->web_partner_id);
                 
            } 
            if ($delete) {
                $message = array("StatusCode" => 0, "Message" => "Feedback Successfully Deleted", "Class" => "success_popup");
            } else {
                $message = array("StatusCode" => 2, "Message" => "Feedback  not Deleted", "Class" => "error_popup", "Reload" => "true");
            }
            $this->session->setFlashdata('Message', $message);
            return $this->response->setJSON($message);
        } else {
            $message = array("StatusCode" => 2, "Message" => "Permission Denied", "Class" => "error_popup");
            return $this->response->setJSON($message);
        }
    }
 

    public function feedback_status_change()
    {
        if (permission_access("Feedback", "feedback_status")) {
            $validate = new Validation();
            $rules = $this->validate($validate->feedback_status);
            if (!$rules) {
                $errors = $this->validator->getErrors();
                $data_validation = array("StatusCode" => 1, "ErrorMessage" => array_filter($errors));
                return $this->response->setJSON($data_validation);
            } else {
                $FeedbackModel = new FeedbackModel();
                $ids = $this->request->getPost('checkedvalue');

                $data['status'] = $this->request->getPost('status');

                $update = $FeedbackModel->feedback_status_change($ids, $data,$this->web_partner_id);

                if ($update) {
                    $message = array("StatusCode" => 0, "Message" => "Feedback status  successfully changed", "Class" => "success_popup", "Reload" => "true");
                } else {
                    $message = array("StatusCode" => 2, "Message" => "Feedback status not changed successfully", "Class" => "error_popup", "Reload" => "true");
                }
                $this->session->setFlashdata('Message', $message);
                return $this->response->setJSON($message);
            }
        } else {
            $message = array("StatusCode" => 2, "Message" => "Permission Denied", "Class" => "error_popup");
            return $this->response->setJSON($message);
        }
    }

}