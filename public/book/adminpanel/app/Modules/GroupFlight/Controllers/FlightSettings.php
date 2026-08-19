<?php

namespace Modules\Flight\Controllers;

use App\Modules\Flight\Models\FlightAirportModel;
use App\Modules\Flight\Models\FlightAirlineModel;
use App\Controllers\BaseController;
use Modules\Flight\Config\Validation;


class FlightSettings extends BaseController
{

    protected $title; 
    protected $web_partner_id; 
    protected $user_id;   
    protected $folder_name;
    protected $whitelabel_setting_data;


    public function initController(\CodeIgniter\HTTP\RequestInterface $request, \CodeIgniter\HTTP\ResponseInterface $response, \Psr\Log\LoggerInterface $logger)
    {
        parent::initController($request, $response, $logger);
        $this->title = "Flight Settings";

        $this->folder_name = "airline-images";

        $this->whitelabel_setting_data = admin_cookie_data()['whitelabel_setting_data'];


    }

    public function index()
    {
        if (isset($this->whitelabel_setting_data['is_direct_website']) && $this->whitelabel_setting_data['is_direct_website'] == "active") {
            if (permission_access_error("Setting", "airport_list")) {
                $FlightAirportModel = new FlightAirportModel();
                if ($this->request->getGet() && $this->request->getGet('key')) {
                    $lists = $FlightAirportModel->search_data($this->request->getGet());
                } else {
                    $lists = $FlightAirportModel->airport_list();
                }
                $data = [
                    'title' => $this->title,
                    'airport_list' => $lists,
                    'view' => "Flight\Views\FlightSettings\Flight-Airport-list",
                    'pager' => $FlightAirportModel->pager,
                    'search_bar_data' => $this->request->getGet(),
                ];
                return view('template/sidebar-layout', $data);
            }
        } else {
            access_denied();
        }

    }


    public function add_airport_template()
    {

        if (permission_access("Setting", "add_airport")) {
            $data = [
                'title' => $this->title,
            ];
            $add_blog_view = view('Modules\Flight\Views\FlightSettings\add-airport', $data);
            $data = array("StatusCode" => 0, "Message" => $add_blog_view, 'class' => 'success_popup', "Reload" => "false");
            return $this->response->setJSON($data);
        } else {
            $message = array("StatusCode" => 2, "Message" => "Permission Denied", "Class" => "error_popup");
            return $this->response->setJSON($message);
        }
    }

    public function add_airport()
    {
        if (permission_access("Setting", "add_airport")) {
            $validate = new Validation();
            $rules = $this->validate($validate->airport_validation);
            if (!$rules) {
                $errors = $this->validator->getErrors();
                $data_validation = array("StatusCode" => 1, "ErrorMessage" => array_filter($errors));
                return $this->response->setJSON($data_validation);
            } else {
                $FlightAirportModel = new FlightAirportModel();
                $data = $this->request->getPost();
                $added_data = $FlightAirportModel->insert($data);

                if ($added_data) {
                    $message = array("StatusCode" => 0, "Message" => "Airport Successfully added", "Class" => "success_popup");
                } else {
                    $message = array("StatusCode" => 2, "Message" => "Airport not  added", "Class" => "error_popup");
                }

                $this->session->setFlashdata('Message', $message);
                return $this->response->setJSON($message);
            }
        } else {
            $message = array("StatusCode" => 2, "Message" => "Permission Denied", "Class" => "error_popup");
            return $this->response->setJSON($message);
        }
    }

    public function edit_airport_view()
    {
        if (permission_access("Setting", "edit_airport")) {
          
            $uri = $this->request->getUri();   
            $id = dev_decode($uri->getSegment(3)); 
            $FlightAirportModel = new FlightAirportModel();
            $data = [
                'title' => $this->title,
                'id' => $id,
                'details' => $FlightAirportModel->airport_details($id),
            ];
            $blog_details = view('Modules\Flight\Views\FlightSettings\edit-airport', $data);
            $data = array("StatusCode" => 0, "Message" => $blog_details, 'class' => 'success_popup');
            return $this->response->setJSON($data);
        } else {
            $message = array("StatusCode" => 2, "Message" => "Permission Denied", "Class" => "error_popup");
            return $this->response->setJSON($message);
        }
    }

    public function edit_airports()
    {
        if (permission_access("Setting", "edit_airport")) {
            $uri = $this->request->getUri();   
            $id = dev_decode($uri->getSegment(3)); 

            $validate = new Validation();
            $validate->airport_validation['code']['rules'] = "required|is_unique[flight_airports.code,id,$id]";
            $rules = $this->validate($validate->airport_validation);
            if (!$rules) {
                $errors = $this->validator->getErrors();
                $data_validation = array("StatusCode" => 1, "ErrorMessage" => array_filter($errors));
                return $this->response->setJSON($data_validation);
            } else {
                $FlightAirportModel = new FlightAirportModel();
                $data = $this->request->getPost();
                $added_data = $FlightAirportModel->where("id", $id)->set($data)->update();
                if ($added_data) {
                    $message = array("StatusCode" => 0, "Message" => "Airport Successfully Updated", "Class" => "success_popup");
                } else {
                    $message = array("StatusCode" => 2, "Message" => "Airport not  Updated", "Class" => "error_popup");
                }

                $this->session->setFlashdata('Message', $message);
                return $this->response->setJSON($message);
            }
        } else {
            $message = array("StatusCode" => 2, "Message" => "Permission Denied", "Class" => "error_popup");
            return $this->response->setJSON($message);
        }
    }

    public function remove_airport()
    {
        if (permission_access("Setting", "delete_airport")) {
            $FlightAirportModel = new FlightAirportModel();
            $ids = $this->request->getPost('checklist');
            $delete = $FlightAirportModel->remove_airport($ids);

            if ($delete) {
                $message = array("StatusCode" => 0, "Message" => "Airport Successfully Deleted", "Class" => "success_popup");
            } else {
                $message = array("StatusCode" => 2, "Message" => "Airport not Deleted", "Class" => "error_popup", "Reload" => "true");
            }
            $this->session->setFlashdata('Message', $message);
            return $this->response->setJSON($message);
        } else {
            $message = array("StatusCode" => 2, "Message" => "Permission Denied", "Class" => "error_popup");
            return $this->response->setJSON($message);
        }
    }


    public function airlines_list()
    {
        if (isset($this->whitelabel_setting_data['is_direct_website']) && $this->whitelabel_setting_data['is_direct_website'] == "active") {
            if (permission_access_error("Setting", "airlines_list")) {
                $FlightAirlineModel = new FlightAirlineModel();
                if ($this->request->getGet() && $this->request->getGet('key')) {
                    $lists = $FlightAirlineModel->search_data($this->request->getGet());
                } else {
                    $lists = $FlightAirlineModel->airline_list();
                }

               /*  pr( $lists);
                die; */
                $data = [
                    'title' => $this->title,
                    'aiport_list' => $lists,
                    'view' => "Flight\Views\FlightSettings\Flight-Airline-list",
                    'pager' => $FlightAirlineModel->pager,
                    'search_bar_data' => $this->request->getGet(),
                ];
                return view('template/sidebar-layout', $data);
            }
        } else {
            access_denied();
        }
    }

    public function add_airline_template()
    {
        if (permission_access("Setting", "add_airlines")) {
            $data = [
                'title' => $this->title,
            ];
            $add_blog_view = view('Modules\Flight\Views\FlightSettings\add-airline', $data);
            $data = array("StatusCode" => 0, "Message" => $add_blog_view, 'class' => 'success_popup', "Reload" => "false");
            return $this->response->setJSON($data);
        } else {
            $message = array("StatusCode" => 2, "Message" => "Permission Denied", "Class" => "error_popup");
            return $this->response->setJSON($message);
        }
    }

    public function add_airline()
    {
        if (permission_access("Setting", "add_airlines")) {

            $field_name = 'images';

            $validate = new Validation();

            $post_images = $this->request->getFile($field_name);
            if ($post_images->getName() == '') {
                unset($validate->airline_validation[$field_name]);
            }

            $rules = $this->validate($validate->airline_validation);
            if (!$rules) {
                $errors = $this->validator->getErrors();
                $data_validation = array("StatusCode" => 1, "ErrorMessage" => array_filter($errors));
                return $this->response->setJSON($data_validation);
            } else {
                $FlightAirlineModel = new FlightAirlineModel();
                $data = $this->request->getPost();
                if (isset($data['islcc']) && $data['islcc'] === "true") {
                    $data['islcc'] = "true";
                } else {
                    $data['islcc'] = "false";
                }

                $file = $this->request->getFile($field_name);
                if ($file->getName() != '') {
                    $field_name = 'images';
                    $file = $this->request->getFile($field_name);
                    $resizeDim = array('width' => 48, 'height' => 48);
                    $image_upload = FlightSettings::image_upload_airline($file, $field_name, $this->folder_name, $resizeDim, $data);
                    if ($image_upload['status_code'] == 0) {
                        unset($data['images']);

                        $added_data = $FlightAirlineModel->insert($data);
                        if ($added_data) {
                            $message = array("StatusCode" => 0, "Message" => "Airline Successfully added", "Class" => "success_popup");
                        } else {
                            $message = array("StatusCode" => 2, "Message" => "Airline not added", "Class" => "error_popup");
                        }
                    } else {
                        $message = array("StatusCode" => 2, "Message" => $image_upload['message'], "Class" => "error_popup", "Reload" => "true");
                    }
                } else {
                    unset($data['images']);
                    $added_data = $FlightAirlineModel->insert($data);
                    if ($added_data) {
                        $message = array("StatusCode" => 0, "Message" => "Airline Successfully added", "Class" => "success_popup");
                    } else {
                        $message = array("StatusCode" => 2, "Message" => "Airline not added", "Class" => "error_popup");
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
    public function edit_airline_view()
    {
        if (permission_access("Setting", "edit_airlines")) {
            $uri = $this->request->getUri();   
            $id = dev_decode($uri->getSegment(3)); 
            $FlightAirlineModel = new FlightAirlineModel();
            $data = [
                'title' => $this->title,
                'id' => $id,
                'details' => $FlightAirlineModel->airline_details($id),
            ];

            $blog_details = view('Modules\Flight\Views\FlightSettings\edit-airline', $data);
            $data = array("StatusCode" => 0, "Message" => $blog_details, 'class' => 'success_popup');
            return $this->response->setJSON($data);
        } else {
            $message = array("StatusCode" => 2, "Message" => "Permission Denied", "Class" => "error_popup");
            return $this->response->setJSON($message);
        }
    }

    public function edit_airline()
    {
        if (permission_access("Setting", "edit_airlines")) {
            $uri = $this->request->getUri();   
            $id = dev_decode($uri->getSegment(3)); 
            $field_name = 'images';
            $validate = new Validation();
            $post_images = $this->request->getFile($field_name);
            if ($post_images->getName() == '') {
                unset($validate->airline_validation[$field_name]);
            }

            $validate->airline_validation['airline_code']['rules'] = "required|is_unique[flight_airline_code.airline_code,id,$id]";
            $rules = $this->validate($validate->airline_validation);

            if (!$rules) {
                $errors = $this->validator->getErrors();
                $data_validation = array("StatusCode" => 1, "ErrorMessage" => array_filter($errors));
                return $this->response->setJSON($data_validation);
            } else {
                $FlightAirlineModel = new FlightAirlineModel();
                $data = $this->request->getPost();

                if (isset($data['islcc']) && $data['islcc'] === "true") {
                    $data['islcc'] = "true";
                } else {
                    $data['islcc'] = "false";
                }
                $previous_data = $FlightAirlineModel->airline_details($id);
                $previous_image['images'] = $previous_data['airline_code'] . ".png";

                $file = $this->request->getFile($field_name);
                if ($file->getName() != '') {
                    $resizeDim = array('width' => 48, 'height' => 48);
                    if (file_exists(FCPATH . "../uploads/$this->folder_name/" . $previous_image[$field_name])) {
                        unlink(FCPATH . "../uploads/$this->folder_name/" . $previous_image[$field_name]);
                    }
                    $image_upload = FlightSettings::image_upload_airline($file, $field_name, $this->folder_name, $resizeDim, $data);
                    if ($image_upload['status_code'] == 0) {
                        $data[$field_name] = $image_upload['file_name'];
                        unset($data['images']);
                        $added_data = $FlightAirlineModel->where("id", $id)->set($data)->update();
                        if ($added_data) {
                            $message = array("StatusCode" => 0, "Message" => "Airline Successfully Updated", "Class" => "success_popup");
                        } else {
                            $message = array("StatusCode" => 2, "Message" => "Airline not  Updated", "Class" => "error_popup");
                        }
                    } else {
                        $message = array("StatusCode" => 2, "Message" => $image_upload['message'], "Class" => "error_popup", "Reload" => "true");
                    }
                } else {
                    $added_data = $FlightAirlineModel->where("id", $id)->set($data)->update();
                    if ($added_data) {
                        $message = array("StatusCode" => 0, "Message" => "Airline Successfully Updated", "Class" => "success_popup");
                    } else {
                        $message = array("StatusCode" => 2, "Message" => "Airline not  Updated", "Class" => "error_popup");
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

    public function remove_airline()
    {
        if (permission_access("Setting", "delete_airlines")) {
            $FlightAirlineModel = new FlightAirlineModel();
            $ids = $this->request->getPost('checklist');
            $delete = $FlightAirlineModel->remove_airline($ids);
            if ($delete) {
                $message = array("StatusCode" => 0, "Message" => "Airline Successfully Deleted", "Class" => "success_popup");
            } else {
                $message = array("StatusCode" => 2, "Message" => "Airline not Deleted", "Class" => "error_popup", "Reload" => "true");
            }
            $this->session->setFlashdata('Message', $message);
            return $this->response->setJSON($message);
        } else {
            $message = array("StatusCode" => 2, "Message" => "Permission Denied", "Class" => "error_popup");
            return $this->response->setJSON($message);
        }
    }
    public function image_upload_airline($file, $field_name, $upload_folder, $resizeDim, $airline_code): array
    {
        $validation = \Config\Services::validation();
        $request_data = service('request');
        $msg = '';
        if (is_array($file)) {
            //code used for multiple files uploading
            $validation->setRules([
                $field_name => [
                    "uploaded[$field_name].0",
                    "mime_in[$field_name,image/png,image]",
                    "max_size[$field_name,1024]",
                ]
            ]);
            if ($validation->withRequest($request_data)->run()) {
                $newName = '';
                foreach ($file as $key => $img) {
                    if ($img->isValid() && !$img->hasMoved()) {
                        $ImageName = str_replace(" ", "_", $img->getName());
                        $newNameRandom = $airline_code['airline_code'] . ".png";
                        if ($img->move(FCPATH . "../uploads/$upload_folder/", $newNameRandom)) {
                            /*---------generate thumbnail-----*/
                            $path = FCPATH . "../uploads/$upload_folder/" . $newNameRandom;

                            $image = service('image');
                            $image->withFile($path)
                                ->resize($resizeDim['width'], $resizeDim['height'], true, 'height');
                            $msg = 'file uploaded successfully';
                            $status_code = 0;
                            $newName .= $newNameRandom . ',';
                        } else {
                            $msg = $img->getErrorString() . " " . $img->getError();
                            $status_code = 1;
                        }
                    }
                }
                $newName = rtrim($newName, ",");
            } else {
                $msg = $validation->getError($field_name);
                $status_code = 1;
            }
        } else {

            $validation->setRules([
                $field_name => [
                    "uploaded[$field_name]",
                    "mime_in[$field_name,image/png,image]",
                    "max_size[$field_name,1024]",
                ]
            ]);

            if ($validation->withRequest($request_data)->run()) {
                if ($file->isValid() && !$file->hasMoved()) {
                    $ImageFileName = str_replace(" ", "_", $file->getName());
                    $newName = $airline_code['airline_code'] . ".png";
                    if ($file->move(FCPATH . "../uploads/$upload_folder/", $newName)) {
                        /*---------generate thumbnail-----*/
                        $path = FCPATH . "../uploads/$upload_folder/" . $newName;
                        $image = service('image');
                        $image->withFile($path)
                            ->resize($resizeDim['width'], $resizeDim['height'], true, 'height');
                        $msg = 'file uploaded successfully';
                        $status_code = 0;
                    } else {
                        $msg = $file->getErrorString() . " " . $file->getError();
                        $status_code = 1;
                    }
                }
            } else {
                $msg = $validation->getError($field_name);
                $status_code = 1;
            }
        }

        if ($status_code == 1) {
            $file_name = '';
        } else {
            $file_name = $newName;
        }

        $return_data = [
            'status_code' => $status_code,
            'message' => $msg,
            'file_name' => $file_name
        ];
        return $return_data;
    }

}