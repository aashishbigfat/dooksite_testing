<?php

namespace Modules\Flight\Controllers;

use App\Modules\Flight\Models\WebCheckInModel;
use App\Modules\Flight\Models\FlightAirlineModel;
use App\Controllers\BaseController;
use Modules\Flight\Config\Validation;


class WebCheckIn extends BaseController
{

    protected $title; 
    protected $web_partner_id; 
    protected $user_id;   
    protected $folder_name;
    protected $whitelabel_setting_data;

    public function initController(\CodeIgniter\HTTP\RequestInterface $request, \CodeIgniter\HTTP\ResponseInterface $response, \Psr\Log\LoggerInterface $logger)
    {
        parent::initController($request, $response, $logger);
        $this->title = "Web Check In";
        $this->folder_name = "web-check-in-images";
        $this->whitelabel_setting_data = admin_cookie_data()['whitelabel_setting_data'];

        $this->web_partner_id = admin_cookie_data()['admin_user_details']['web_partner_id'];

        if (permission_access_error("WebCheckIn", "WebCheckIn_Module")) {
        }
    }

    public function index()
    {
        //if (isset($this->whitelabel_setting_data['is_direct_website']) && $this->whitelabel_setting_data['is_direct_website'] == "active") {
        if (permission_access_error("WebCheckIn", "web_check_in_list")) {
            $WebCheckInModel = new WebCheckInModel();
            if ($this->request->getGet() && $this->request->getGet('key')) {
                $lists = $WebCheckInModel->search_data($this->request->getGet(), $this->web_partner_id);
            } else {
                $lists = $WebCheckInModel->web_check_in_list($this->web_partner_id);
            }
            $data = [
                'title' => $this->title,
                'list' => $lists,
                'view' => "Flight\Views\WebCheckIn\web-check-in-list",
                'pager' => $WebCheckInModel->pager,
                'search_bar_data' => $this->request->getGet(),
            ];
            return view('template/sidebar-layout', $data);
        }
        // } else {
        //     access_denied();
        // }

    }

    public function add_web_check_in_template()
    {
        if (permission_access("WebCheckIn", "add_web_check_in")) {
            $data = [
                'title' => $this->title,
            ];
            $add_blog_view = view('Modules\Flight\Views\WebCheckIn\add-web-check-in', $data);
            $data = array("StatusCode" => 0, "Message" => $add_blog_view, 'class' => 'success_popup', "Reload" => "false");
            return $this->response->setJSON($data);
        } else {
            $message = array("StatusCode" => 2, "Message" => "Permission Denied", "Class" => "error_popup");
            return $this->response->setJSON($message);
        }
    }

    public function add_web_check_in()
    {
        if (permission_access("WebCheckIn", "add_web_check_in")) {

            $validate = new Validation();
            $rules = $this->validate($validate->webcheckin_validation);
            if (!$rules) {
                $errors = $this->validator->getErrors();
                $data_validation = array("StatusCode" => 1, "ErrorMessage" => array_filter($errors));
                return $this->response->setJSON($data_validation);
            } else {
                $WebCheckInModel = new WebCheckInModel();
                $data = $this->request->getPost();
                $temp_airline = $data['airline_name'];
                $temp_airline = explode('-', $temp_airline);
                $data['airline_code'] = $temp_airline[0];
                $data['airline_name'] = $temp_airline[1];
                $value['url'] = $data['url'];
                $existingValue = $WebCheckInModel->CheckUniqueurl($value, $this->web_partner_id);
                if ($existingValue) {
                    $data_validation = array("StatusCode" => 1, "ErrorMessage" => ["url" => " This URL  already exists"]);
                    return $this->response->setJSON($data_validation);
                }
                $airline_name['airline_name'] = $data['airline_name'];
                $existingairlinename = $WebCheckInModel->CheckUniqueairlinename($airline_name, $this->web_partner_id);
                if ($existingairlinename) {
                    $data_validation = array("StatusCode" => 1, "ErrorMessage" => ["airline_name" => " This Airline Name  already exists"]);
                    return $this->response->setJSON($data_validation);
                }
                $field_name = 'image';
                $file = $this->request->getFile($field_name);
                $resizeDim = array('width' => 360, 'height' => 200);
                $image_upload = image_upload($file, $field_name, $this->folder_name, $resizeDim);
                if ($image_upload['status_code'] == 0) {
                    $data['created'] = create_date();
                    $data['web_partner_id'] = $this->web_partner_id;
                    $data['image'] = $image_upload['file_name'];

                    $added_data = $WebCheckInModel->insert($data);
                    if ($added_data) {
                        $message = array("StatusCode" => 0, "Message" => "Web Check-in Successfully added", "Class" => "success_popup", "Reload" => "true");
                    } else {
                        $message = array("StatusCode" => 2, "Message" => "Web Check-in not  added", "Class" => "error_popup", "Reload" => "true");
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
    public function edit_web_check_in_view()
    {
        if (permission_access("WebCheckIn", "edit_web_check_in")) {

            $uri = $this->request->getUri();
            $id = dev_decode($uri->getSegment(3));

            $WebCheckInModel = new WebCheckInModel();
            $data = [
                'title' => $this->title,
                'id' => $id,
                'details' => $WebCheckInModel->web_check_in_details($id, $this->web_partner_id),
            ];

            $blog_details = view('Modules\Flight\Views\WebCheckIn\edit-web-check-in', $data);
            $data = array("StatusCode" => 0, "Message" => $blog_details, 'class' => 'success_popup');
            return $this->response->setJSON($data);
        } else {
            $message = array("StatusCode" => 2, "Message" => "Permission Denied", "Class" => "error_popup");
            return $this->response->setJSON($message);
        }
    }

    public function edit_web_check_in()
    {
        if (permission_access("WebCheckIn", "edit_web_check_in")) {
            $uri = $this->request->getUri();
            $id =  dev_decode($uri->getSegment(3));

            $field_name = 'image';
            $validate = new Validation();
            $post_images = $this->request->getFile($field_name);
            if ($post_images->getName() == '') {
                unset($validate->webcheckin_validation[$field_name]);
            }

            $rules = $this->validate($validate->webcheckin_validation);
            if (!$rules) {
                $errors = $this->validator->getErrors();
                $data_validation = array("StatusCode" => 1, "ErrorMessage" => array_filter($errors));
                return $this->response->setJSON($data_validation);
            } else {
                $WebCheckInModel = new WebCheckInModel();
                $data = $this->request->getPost();
                $temp_airline = $this->request->getPost('airline_name');
                $temp_airline = explode('-', $temp_airline);
                if (isset($temp_airline[0])) {
                    $data['airline_code'] = $temp_airline[0];
                }
                if (isset($temp_airline[1])) {
                    $data['airline_name'] = $temp_airline[1];
                }
                $previous_data = $WebCheckInModel->web_check_in_details($id, $this->web_partner_id);

                if ($previous_data['url'] != $data['url']) {
                    $value['url'] = $data['url'];
                    $existingValue = $WebCheckInModel->CheckUniqueurl($value, $this->web_partner_id);
                    if ($existingValue) {
                        $data_validation = array("StatusCode" => 1, "ErrorMessage" => ["url" => "This URL already exists"]);
                        $data_validation = array("StatusCode" => 1, "ErrorMessage" => ["airline_name" => " This Airline Name  already exists"]);
                        return $this->response->setJSON($data_validation);
                    }
                }
                if ($previous_data['airline_name'] != $data['airline_name']) {
                    $airline_name['airline_name'] = $data['airline_name'];
                    $existingairlinename = $WebCheckInModel->CheckUniqueairlinename($airline_name, $this->web_partner_id);
                    if ($existingairlinename) {
                        $data_validation = array("StatusCode" => 1, "ErrorMessage" => ["airline_name" => " This Airline Name  already exists"]);
                        return $this->response->setJSON($data_validation);
                    }
                }

                $file = $this->request->getFile($field_name);
                if ($file->getName() != '') {
                    $resizeDim = array('width' => 200, 'height' => 200);
                    $image_upload = image_upload($file, $field_name, $this->folder_name, $resizeDim);
                    if ($image_upload['status_code'] == 0) {

                        if ($previous_data[$field_name]) {
                            if (file_exists(FCPATH . "../uploads/$this->folder_name/" . $previous_data[$field_name])) {
                                unlink(FCPATH . "../uploads/$this->folder_name/" . $previous_data[$field_name]);
                                unlink(FCPATH . "../uploads/$this->folder_name/thumbnail/" . $previous_data[$field_name]);
                            }
                        }

                        $data['modified'] = create_date();
                        $data[$field_name] = $image_upload['file_name'];
                        $added_data = $WebCheckInModel->where("id", $id)->set($data)->update();

                        if ($added_data) {
                            $message = array("StatusCode" => 0, "Message" => "Web Check-in Successfully Edit", "Class" => "success_popup", "Reload" => "true");
                        } else {
                            $message = array("StatusCode" => 2, "Message" => "Web Check-in not  Edit", "Class" => "error_popup", "Reload" => "true");
                        }
                    } else {
                        $message = array("StatusCode" => 2, "Message" => $image_upload['message'], "Class" => "error_popup", "Reload" => "true");
                    }
                } else {

                    $data['modified'] = create_date();
                    $data['image'] = $previous_data[$field_name];
                    $added_data = $WebCheckInModel->where("id", $id)->set($data)->update();

                    if ($added_data) {
                        $message = array("StatusCode" => 0, "Message" => "Web Check-in Successfully Edit", "Class" => "success_popup", "Reload" => "true");
                    } else {
                        $message = array("StatusCode" => 2, "Message" => "Web Check-in not  Edit", "Class" => "error_popup", "Reload" => "true");
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

    public function remove_web_check_in()
    {
        if (permission_access("WebCheckIn", "delete_web_check_in")) {
            $WebCheckInModel = new WebCheckInModel();
            $ids = $this->request->getPost('checklist');
            $field_name = 'image';

            foreach ($ids as $id) {
                $blog_details = $WebCheckInModel->delete_image($id, $this->web_partner_id);
                if ($blog_details[$field_name]) {
                    if (file_exists(FCPATH . "../uploads/$this->folder_name/" . $blog_details[$field_name])) {
                        unlink(FCPATH . "../uploads/$this->folder_name/" . $blog_details[$field_name]);
                        unlink(FCPATH . "../uploads/$this->folder_name/thumbnail/" . $blog_details[$field_name]);
                    }
                }
                $delete = $WebCheckInModel->remove_web_check_in($id, $this->web_partner_id);
            }

            if ($delete) {
                $message = array("StatusCode" => 0, "Message" => "Web Check-in Successfully Deleted", "Class" => "success_popup");
            } else {
                $message = array("StatusCode" => 2, "Message" => "Web Check-in not Deleted", "Class" => "error_popup", "Reload" => "true");
            }
            $this->session->setFlashdata('Message', $message);
            return $this->response->setJSON($message);
        } else {
            $message = array("StatusCode" => 2, "Message" => "Permission Denied", "Class" => "error_popup");
            return $this->response->setJSON($message);
        }
    }
}
