<?php

namespace Modules\HotelExtranet\Controllers;

use App\Controllers\BaseController;
use App\Modules\HotelExtranet\Models\AddonModel;
use App\Modules\HotelExtranet\Models\AmenityModel;
use App\Modules\HotelExtranet\Models\HotelListModel;
use App\Modules\HotelExtranet\Models\PropertyTypeModel;
use App\Modules\HotelExtranet\Models\RoomModel;
use App\Modules\HotelExtranet\Models\RoomGalleryModel;
use App\Modules\HotelExtranet\Models\RoomPriceModel;
use App\Modules\HotelExtranet\Models\RoomAvailabilityModel;
use Modules\HotelExtranet\Config\Validation;
use DateTime;

class HotelExtranet extends BaseController
{


    protected $title; 
    protected $web_partner_id; 
    protected $user_id;   
    protected $folder_name;  

    public function initController(\CodeIgniter\HTTP\RequestInterface $request, \CodeIgniter\HTTP\ResponseInterface $response, \Psr\Log\LoggerInterface $logger)
    {
        parent::initController($request, $response, $logger);
        $this->title = "Hotel Extranet";
        $this->folder_name = "hotel";

        $this->web_partner_id = admin_cookie_data()['admin_user_details']['web_partner_id'];
    }


    public function index()
    {

        $CarInfoModel = new CarInfoModel();
        if ($this->request->getGet() && $this->request->getGet('key')) {
            $lists = $CarInfoModel->search_data($this->web_partner_id, $this->request->getGet());
        } else {
            $lists = $CarInfoModel->car_info_list($this->web_partner_id);
        }
        $data = [
            'title' => $this->title,
            'list' => $lists,
            'view' => "HotelExtranet\Views\Car-info-list",
            'pager' => $CarInfoModel->pager,
            'carTypes' => $this->carTypes,
            'search_bar_data' => $this->request->getGet(),
        ];
        return view('template/sidebar-layout', $data);
    }

    public function hotel_list()
    { 
        if (permission_access_error("HotelExtranet", "list_hotelExtranet")) {
            $HotelListModel = new HotelListModel();
            if ($this->request->getGet() && $this->request->getGet('key')) {
                $lists = $HotelListModel->search_data($this->request->getGet(), $this->web_partner_id);
            } else {
                $lists = $HotelListModel->hotel_list($this->web_partner_id);;
            }

            
            $data = [
                'title' => $this->title,
                'list' => $lists,
                'view' => "HotelExtranet\Views\hotel-list",
                'pager' => $HotelListModel->pager,
                'search_bar_data' => $this->request->getGet(),
            ];

            return view('template/sidebar-layout', $data);
        }
    }

    public function add_hotel()
    {
        if (permission_access_error("HotelExtranet", "add_hotelExtranet")) {
            $HotelListModel = new HotelListModel();
            $city_lists = $HotelListModel->city_list_select($terms = 0);

            $PropertyTypeModel = new PropertyTypeModel();
            $property_lists = $PropertyTypeModel->property_type_select($this->web_partner_id);

            $AmenityModel = new AmenityModel();
            $amenity = $AmenityModel->amenity_hotel_select($this->web_partner_id);

            $data = [
                'title' => $this->title,
                'city_lists' => $city_lists,
                'property_lists' => $property_lists,
                'amenity' => $amenity,
                'view' => "HotelExtranet\Views\add-hotel",
            ];
            return view('template/sidebar-layout', $data);
        }
    }

    public function get_city()
    {
        $terms = $this->request->getGet('term');
        $HotelListModel = new HotelListModel();
        $city_lists = $HotelListModel->city_list_select($terms);

        $availableCity = [];
        if (!empty($city_lists)) {
            foreach ($city_lists as $data) {
                $availableCity[] = ['id' => $data['city_id'], 'destination' => $data['destination'] . '-' . $data['country'], 'country' => $data['country']];
            }
        }
        echo json_encode($availableCity);
    }

    public function add_hotel_save()
    {
        if (permission_access_error("HotelExtranet", "add_hotelExtranet")) {
            $validate = new Validation();
            $data = $this->request->getPost();
           
            if ($data['latitude']) {
                $validate->hotel_list_validation['longitude']['rules'] = "trim|regex_match[/^(\+|-)?(?:180(?:(?:\.0{1,6})?)|(?:[0-9]|[1-9][0-9]|1[0-7][0-9])(?:(?:\.[0-9]{1,6})?))$/]|max_length[15]";
            } elseif ($data['longitude']) {
                $validate->hotel_list_validation['latitude']['rules'] = "trim|regex_match[/^(\+|-)?(?:90(?:(?:\.0{1,6})?)|(?:[0-9]|[1-8][0-9])(?:(?:\.[0-9]{1,6})?))$/]|max_length[15]";
            }
            $rules = $this->validate($validate->hotel_list_validation);

            if (!$rules) {
                $errors = $this->validator->getErrors();
                $data_validation = array("StatusCode" => 1, "ErrorMessage" => array_filter($errors));

                return $this->response->setJSON($data_validation);
            } else {
                $HotelListModel = new HotelListModel();
                if (isset($data['pan_required']) && $data['pan_required'] == 1) {
                    $data['pan_required'] = '1';
                } else {
                    $data['pan_required'] = '0';
                }

                if (isset($data['passport_required']) && $data['passport_required'] == 1) {
                    $data['passport_required'] = '1';
                } else {
                    $data['passport_required'] = '0';
                }
                //unset($data['hotel_city']);
                #use getFile() for single image or getFiles() for multiple image
                $field_name = 'hotel_images';
                $file = $this->request->getFile($field_name);
                $resizeDim = array('width' => 600, 'height' => 400);
                $image_upload = image_upload($file, $field_name, $this->folder_name, $resizeDim);
                if ($image_upload['status_code'] == 0) {

                    $data['created'] = create_date();
                    $data['web_partner_id'] = $this->web_partner_id;
                    $data['review_provider'] = 'Tripadvisor';
                    $data[$field_name] = $image_upload['file_name'];
                    $data['hotel_amenities'] = implode(',', $data['hotel_amenities']);
                    $added_data = $HotelListModel->insert($data);

                    if ($added_data) {
                        $message = array("StatusCode" => 0, "Message" => "hotel successfully added", "Class" => "success_popup", "Reload" => "true");
                    } else {
                        $message = array("StatusCode" => 2, "Message" => "hotel not  added", "Class" => "error_popup", "Reload" => "true");
                    }
                } else {
                    $message = array("StatusCode" => 2, "Message" => $image_upload['message'], "Class" => "error_popup", "Reload" => "true");
                }

                $this->session->setFlashdata('Message', $message);
                return $this->response->setJSON($message);
            }
        }
    }


    public function edit_hotel_view()
    {
        if (permission_access_error("HotelExtranet", "edit_hotelExtranet")) {
            $uri = $this->request->getUri();   
            $id =  dev_decode($uri->getSegment(3));
            $PropertyTypeModel = new PropertyTypeModel();
            $property_lists = $PropertyTypeModel->property_type_select($this->web_partner_id);

            $AmenityModel = new AmenityModel();
            $amenity = $AmenityModel->amenity_hotel_select($this->web_partner_id);

            $HotelListModel = new HotelListModel();
            $details = $HotelListModel->hotel_details($id, $this->web_partner_id);

            $details['hotel_amenities'] = explode(',', $details['hotel_amenities']);
            $data = [
                'title' => $this->title,
                'id' => $id,
                'details' => $details,
                'amenity' => $amenity,
                'property_lists' => $property_lists,
                'view' => "HotelExtranet\Views\Edit-hotel",
            ];
            return view('template/sidebar-layout', $data);
        }
    }

    public function edit_hotel()
    {
        if (permission_access_error("HotelExtranet", "edit_hotelExtranet")) {


            $uri = $this->request->getUri();   
            $id =  dev_decode($uri->getSegment(3));
            $field_name = 'hotel_images';
            $data = $this->request->getPost();
            $validate = new Validation();

            if ($data['latitude']) {
                $validate->hotel_list_validation['longitude']['rules'] = "trim|regex_match[/^(\+|-)?(?:180(?:(?:\.0{1,6})?)|(?:[0-9]|[1-9][0-9]|1[0-7][0-9])(?:(?:\.[0-9]{1,6})?))$/]|max_length[15]";
            } elseif ($data['longitude']) {
                $validate->hotel_list_validation['latitude']['rules'] = "trim|regex_match[/^(\+|-)?(?:90(?:(?:\.0{1,6})?)|(?:[0-9]|[1-8][0-9])(?:(?:\.[0-9]{1,6})?))$/]|max_length[15]";
            }

            $images_file = $this->request->getFile($field_name);
            if ($images_file->getName() == '') {
                unset($validate->hotel_list_validation[$field_name]);
            }

            $rules = $this->validate($validate->hotel_list_validation);
            if (!$rules) {
                $errors = $this->validator->getErrors();
                $data_validation = array("StatusCode" => 1, "ErrorMessage" => array_filter($errors));
                return $this->response->setJSON($data_validation);
            } else {
                $HotelListModel = new HotelListModel();

                //unset($data['hotel_city']);

                if (isset($data['pan_required']) && $data['pan_required'] == 1) {
                    $data['pan_required'] = '1';
                } else {
                    $data['pan_required'] = '0';
                }

                if (isset($data['passport_required']) && $data['passport_required'] == 1) {
                    $data['passport_required'] = '1';
                } else {
                    $data['passport_required'] = '0';
                }

                $previous_data = $HotelListModel->hotel_details($id, $this->web_partner_id);
                $file = $this->request->getFile($field_name);
                if ($file->getName() != '') {
                    $resizeDim = array('width' => 600, 'height' => 400);
                    $image_upload = image_upload($file, $field_name, $this->folder_name, $resizeDim);
                    if ($image_upload['status_code'] == 0) {

                        if ($previous_data[$field_name]) {
                            if (file_exists("../uploads/$this->folder_name/" . $previous_data[$field_name])) {
                                unlink("../uploads/$this->folder_name/" . $previous_data[$field_name]);
                                unlink("../uploads/$this->folder_name/thumbnail/" . $previous_data[$field_name]);
                            }
                        }
                        $data['hotel_amenities'] = implode(',', $data['hotel_amenities']);
                        $data['modified'] = create_date();
                        $data[$field_name] = $image_upload['file_name'];
                        $added_data = $HotelListModel->where(["id" => $id, 'web_partner_id' => $this->web_partner_id])->set($data)->update();

                        if ($added_data) {
                            $message = array("StatusCode" => 0, "Message" => "Hotel successfully updated", "Class" => "success_popup", "Reload" => "true");
                        } else {
                            $message = array("StatusCode" => 2, "Message" => "Hotel not  updated", "Class" => "error_popup", "Reload" => "true");
                        }
                    } else {
                        $message = array("StatusCode" => 2, "Message" => $image_upload['message'], "Class" => "error_popup", "Reload" => "true");
                    }
                } else {

                    $data['modified'] = create_date();
                    $data['hotel_amenities'] = implode(',', $data['hotel_amenities']);
                    $data[$field_name] = $previous_data[$field_name];
                    $added_data = $HotelListModel->where(["id" => $id, 'web_partner_id' => $this->web_partner_id])->set($data)->update();

                    if ($added_data) {
                        $message = array("StatusCode" => 0, "Message" => "Hotel successfully updated", "Class" => "success_popup", "Reload" => "true");
                    } else {
                        $message = array("StatusCode" => 2, "Message" => "Hotel not  updated", "Class" => "error_popup", "Reload" => "true");
                    }
                }

                $this->session->setFlashdata('Message', $message);
                return $this->response->setJSON($message);
            }
        }
    }


    public function remove_hotel()
    {
        if (permission_access_error("HotelExtranet", "delete_hotelExtranet")) {
            $HotelListModel = new HotelListModel();
            $ids = $this->request->getPost('checklist');

            $field_name = 'hotel_images';

            foreach ($ids as $id) {
                $details = $HotelListModel->delete_image($id, $this->web_partner_id);
                if ($details !== null && isset($details[$field_name])) {
                    if (file_exists("../uploads/$this->folder_name/" . $details[$field_name])) {
                        unlink("../uploads/$this->folder_name/" . $details[$field_name]);
                        unlink("../uploads/$this->folder_name/thumbnail/" . $details[$field_name]);
                    }
                }
                $delete = $HotelListModel->remove_hotel($ids, $this->web_partner_id);
            }
            if ($delete) {
                $message = array("StatusCode" => 0, "Message" => "Hotel  successfully  deleted", "Class" => "success_popup", "Reload" => "true");
            } else {
                $message = array("StatusCode" => 2, "Message" => "Hotel  not deleted", "Class" => "error_popup", "Reload" => "true");
            }
            $this->session->setFlashdata('Message', $message);
            return $this->response->setJSON($message);
        }
    }


    public function hotel_status_change()
    {
        if (permission_access_error("HotelExtranet", "status_hotelExtranet")) {
            $validate = new Validation();
            $rules = $this->validate($validate->status);
            if (!$rules) {
                $errors = $this->validator->getErrors();
                $data_validation = array("StatusCode" => 1, "ErrorMessage" => array_filter($errors));
                return $this->response->setJSON($data_validation);
            } else {
                $HotelListModel = new HotelListModel();

                $ids = $this->request->getPost('checkedvalue');

                $data['status'] = $this->request->getPost('status');

                $update = $HotelListModel->status_change($ids, $data, $this->web_partner_id);

                if ($update) {
                    $message = array("StatusCode" => 0, "Message" => "Hotel status  successfully changed", "Class" => "success_popup", "Reload" => "true");
                } else {
                    $message = array("StatusCode" => 2, "Message" => "Hotel status not changed successfully", "Class" => "error_popup", "Reload" => "true");
                }
                $this->session->setFlashdata('Message', $message);
                return $this->response->setJSON($message);
            }
        }
    }


    public function property_type_list()
    {
        if (permission_access_error("HotelExtranet", "list_property")) {
            $PropertyTypeModel = new PropertyTypeModel();

            if ($this->request->getGet() && $this->request->getGet('key')) {
                $lists = $PropertyTypeModel->search_data($this->request->getGet(), $this->web_partner_id);
            } else {
                $lists = $PropertyTypeModel->property_type_list($this->web_partner_id);
            } 
            $data = [
                'title' => $this->title,
                'list' => $lists,
                'view' => "HotelExtranet\Views\property-type-list",
                'pager' => $PropertyTypeModel->pager,
                'search_bar_data' => $this->request->getGet(),
            ];
            return view('template/sidebar-layout', $data);
        }
    }

    public function add_property_type_view()
    {
        if (permission_access_error("HotelExtranet", "add_property")) {
            $data = [
                'title' => $this->title,
            ];
            $details = view('Modules\HotelExtranet\Views\add-property-type', $data);
            $data = array("StatusCode" => 0, "Message" => $details, 'class' => 'success_popup', "Reload" => "false");
            return $this->response->setJSON($data);
        }
    }

    public function add_property_type()
    {
        if (permission_access_error("HotelExtranet", "add_property")) {
            $validate = new Validation();

            $rules = $this->validate($validate->hotel_property_type);

            if (!$rules) {
                $errors = $this->validator->getErrors();
                $data_validation = array("StatusCode" => 1, "ErrorMessage" => array_filter($errors));
                return $this->response->setJSON($data_validation);
            } else {
                $PropertyTypeModel = new PropertyTypeModel();
                $data = $this->request->getPost();
                $data['created'] = create_date();
                $data['web_partner_id'] = $this->web_partner_id;
                $added_data = $PropertyTypeModel->insert($data);
                if ($added_data) {
                    $message = array("StatusCode" => 0, "Message" => "property type successfully added", "Class" => "success_popup", "Reload" => "true");
                } else {
                    $message = array("StatusCode" => 2, "Message" => "property type not  added", "Class" => "error_popup", "Reload" => "true");
                }

                $this->session->setFlashdata('Message', $message);
                return $this->response->setJSON($message);
            }
        }
    }


    public function edit_property_type_view()
    {
        if (permission_access_error("HotelExtranet", "edit_property")) {
            $uri = $this->request->getUri();   
            $id =  dev_decode($uri->getSegment(3));
            $PropertyTypeModel = new PropertyTypeModel();
            $details = $PropertyTypeModel->property_type_details($id, $this->web_partner_id);

            $data = [
                'title' => $this->title,
                'id' => $id,
                'details' => $details,
            ];
            $details = view('Modules\HotelExtranet\Views\edit-property-type', $data);
            $data = array("StatusCode" => 0, "Message" => $details, 'class' => 'success_popup', "Reload" => "false");
            return $this->response->setJSON($data);
        }
    }

    public function edit_property_type()
    {
        if (permission_access_error("HotelExtranet", "edit_property")) {
            $uri = $this->request->getUri();   
            $id =  dev_decode($uri->getSegment(3));
            $validate = new Validation();
            $rules = $this->validate($validate->hotel_property_type);
            if (!$rules) {
                $errors = $this->validator->getErrors();
                $data_validation = array("StatusCode" => 1, "ErrorMessage" => array_filter($errors));
                return $this->response->setJSON($data_validation);
            } else {
                $PropertyTypeModel = new PropertyTypeModel();
                $data = $this->request->getPost();
                $data['modified'] = create_date();
                $added_data = $PropertyTypeModel->where("id", $id)->where(['web_partner_id' => $this->web_partner_id])->set($data)->update();
                if ($added_data) {
                    $message = array("StatusCode" => 0, "Message" => "property type successfully updated", "Class" => "success_popup", "Reload" => "true");
                } else {
                    $message = array("StatusCode" => 2, "Message" => "property type not  updated", "Class" => "error_popup", "Reload" => "true");
                }
                $this->session->setFlashdata('Message', $message);
                return $this->response->setJSON($message);
            }
        }
    }

    public function property_type_status_change()
    {
        if (permission_access_error("HotelExtranet", "status_property")) {
            $validate = new Validation();
            $rules = $this->validate($validate->status);
            if (!$rules) {
                $errors = $this->validator->getErrors();
                $data_validation = array("StatusCode" => 1, "ErrorMessage" => array_filter($errors));
                return $this->response->setJSON($data_validation);
            } else {
                $PropertyTypeModel = new PropertyTypeModel();
                $ids = $this->request->getPost('checkedvalue');
                $data['modified'] = create_date();
                $data['status'] = $this->request->getPost('status');
                $update = $PropertyTypeModel->status_change($ids, $data, $this->web_partner_id);
                if ($update) {
                    $message = array("StatusCode" => 0, "Message" => "property type status successfully changed", "Class" => "success_popup", "Reload" => "true");
                } else {
                    $message = array("StatusCode" => 2, "Message" => "property type status not changed", "Class" => "error_popup", "Reload" => "true");
                }
                $this->session->setFlashdata('Message', $message);
                return $this->response->setJSON($message);
            }
        }
    }

    public function remove_property_type()
    {
        if (permission_access_error("HotelExtranet", "delete_property")) {
            $PropertyTypeModel = new PropertyTypeModel();
            $ids = $this->request->getPost('checklist');
            $delete = $PropertyTypeModel->remove_property_type($ids, $this->web_partner_id);
            if ($delete) {
                $message = array("StatusCode" => 0, "Message" => "property type successfully  deleted", "Class" => "success_popup", "Reload" => "true");
            } else {
                $message = array("StatusCode" => 2, "Message" => "property type not deleted", "Class" => "error_popup", "Reload" => "true");
            }
            $this->session->setFlashdata('Message', $message);
            return $this->response->setJSON($message);
        }
    }


    public function amenity_list()
    {
        if (permission_access_error("HotelExtranet", "list_amenity")) {
            $AmenityModel = new AmenityModel();
            if ($this->request->getGet() && $this->request->getGet('key')) {
                $lists = $AmenityModel->search_data($this->request->getGet(), $this->web_partner_id);
            } else {
                $lists = $AmenityModel->amenity_list($this->web_partner_id);
            }
            $data = [
                'title' => $this->title,
                'list' => $lists,
                'view' => "HotelExtranet\Views\amenity-list",
                'pager' => $AmenityModel->pager,
                'search_bar_data' => $this->request->getGet(),
            ];
            return view('template/sidebar-layout', $data);
        }
    }

    public function add_amenity_view()
    {
        if (permission_access_error("HotelExtranet", "add_amenity")) {
            $data = [
                'title' => $this->title,
            ];
            $details = view('Modules\HotelExtranet\Views\add-amenity', $data);
            $data = array("StatusCode" => 0, "Message" => $details, 'class' => 'success_popup', "Reload" => "false");
            return $this->response->setJSON($data);
        }
    }

    public function add_amenity()
    {
        if (permission_access_error("HotelExtranet", "add_amenity")) {
            $validate = new Validation();
            $rules = $this->validate($validate->hotel_amenity);
            if (!$rules) {
                $errors = $this->validator->getErrors();
                $data_validation = array("StatusCode" => 1, "ErrorMessage" => array_filter($errors));
                return $this->response->setJSON($data_validation);
            } else {
                $field_name = 'amenity_icon';
                $file = $this->request->getFile($field_name);
                $resizeDim = array('width' => 128, 'height' => 128);
                $image_upload = image_upload($file, $field_name, $this->folder_name, $resizeDim);
                if ($image_upload['status_code'] == 0) {
                    $AmenityModel = new AmenityModel();
                    $data = $this->request->getPost();
                    $data['created'] = create_date();
                    $data[$field_name] = $image_upload['file_name'];
                    $data['web_partner_id'] = $this->web_partner_id;
                    $added_data = $AmenityModel->insert($data);

                    if ($added_data) {
                        $message = array("StatusCode" => 0, "Message" => "amenity successfully added", "Class" => "success_popup", "Reload" => "true");
                    } else {
                        $message = array("StatusCode" => 2, "Message" => "amenity not  added", "Class" => "error_popup", "Reload" => "true");
                    }
                } else {
                    $message = array("StatusCode" => 2, "Message" => $image_upload['message'], "Class" => "error_popup", "Reload" => "true");
                }
                $this->session->setFlashdata('Message', $message);
                return $this->response->setJSON($message);
            }
        }
    }


    public function edit_amenity_view()
    {
        if (permission_access_error("HotelExtranet", "edit_amenity")) {
            $uri = $this->request->getUri();   
            $id =  dev_decode($uri->getSegment(3));
            $AmenityModel = new AmenityModel();
            $details = $AmenityModel->amenity_details($id, $this->web_partner_id);
            $data = [
                'title' => $this->title,
                'id' => $id,
                'details' => $details,
            ];
            $details = view('Modules\HotelExtranet\Views\edit-amenity', $data);
            $data = array("StatusCode" => 0, "Message" => $details, 'class' => 'success_popup', "Reload" => "false");
            return $this->response->setJSON($data);
        }
    }

    public function edit_amenity()
    {
        if (permission_access_error("HotelExtranet", "edit_amenity")) {
            $uri = $this->request->getUri();   
            $id =  dev_decode($uri->getSegment(3));
            $field_name = 'amenity_icon';
            $validate = new Validation();
            $images_file = $this->request->getFile($field_name);
            if ($images_file->getName() == '') {
                unset($validate->hotel_amenity[$field_name]);
            }
            $rules = $this->validate($validate->hotel_amenity);
            if (!$rules) {
                $errors = $this->validator->getErrors();
                $data_validation = array("StatusCode" => 1, "ErrorMessage" => array_filter($errors));
                return $this->response->setJSON($data_validation);
            } else {
                $AmenityModel = new AmenityModel();
                $data = $this->request->getPost();
                $previous_data = $AmenityModel->amenity_details($id, $this->web_partner_id);
                $file = $this->request->getFile($field_name);
                if ($file->getName() != '') {
                    $resizeDim = array('width' => 128, 'height' => 128);
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
                        $added_data = $AmenityModel->where("id", $id)->where(['web_partner_id' => $this->web_partner_id])->set($data)->update();

                        if ($added_data) {
                            $message = array("StatusCode" => 0, "Message" => "Hotel successfully updated", "Class" => "success_popup", "Reload" => "true");
                        } else {
                            $message = array("StatusCode" => 2, "Message" => "Hotel not  updated", "Class" => "error_popup", "Reload" => "true");
                        }
                    } else {
                        $message = array("StatusCode" => 2, "Message" => $image_upload['message'], "Class" => "error_popup", "Reload" => "true");
                    }
                } else {
                    $data['modified'] = create_date();
                    $data[$field_name] = $previous_data[$field_name];
                    $added_data = $AmenityModel->where("id", $id)->where(['web_partner_id' => $this->web_partner_id])->set($data)->update();

                    if ($added_data) {
                        $message = array("StatusCode" => 0, "Message" => "amenity successfully updated", "Class" => "success_popup", "Reload" => "true");
                    } else {
                        $message = array("StatusCode" => 2, "Message" => "amenity not  updated", "Class" => "error_popup", "Reload" => "true");
                    }
                }
                $this->session->setFlashdata('Message', $message);
                return $this->response->setJSON($message);
            }
        }
    }


    public function amenity_status_change()
    {
        if (permission_access_error("HotelExtranet", "status_amenity")) {
            $validate = new Validation();
            $rules = $this->validate($validate->status);
            if (!$rules) {
                $errors = $this->validator->getErrors();
                $data_validation = array("StatusCode" => 1, "ErrorMessage" => array_filter($errors));
                return $this->response->setJSON($data_validation);
            } else {
                $AmenityModel = new AmenityModel();
                $ids = $this->request->getPost('checkedvalue');
                $data['modified'] = create_date();
                $data['status'] = $this->request->getPost('status');

                $update = $AmenityModel->status_change($ids, $data, $this->web_partner_id);

                if ($update) {
                    $message = array("StatusCode" => 0, "Message" => "amenity status successfully changed", "Class" => "success_popup", "Reload" => "true");
                    redirect('hotel-extranet/amenity-list', 'refresh');
                } else {
                    $message = array("StatusCode" => 2, "Message" => "amenity status not changed", "Class" => "error_popup", "Reload" => "true");
                }
                $this->session->setFlashdata('Message', $message);
                return $this->response->setJSON($message);
            }
        }
    }

    public function remove_amenity()
    {
        if (permission_access_error("HotelExtranet", "delete_amenity")) {
            $AmenityModel = new AmenityModel();
            $ids = $this->request->getPost('checklist');

            $field_name = 'amenity_icon';
            foreach ($ids as $id) {
                $details = $AmenityModel->delete_image($id, $this->web_partner_id);
                if ($details !== null && isset($details[$field_name])) {
                    if (file_exists("./uploads/$this->folder_name/" . $details[$field_name])) {
                        unlink("./uploads/$this->folder_name/" . $details[$field_name]);
                        unlink("./uploads/$this->folder_name/thumbnail/" . $details[$field_name]);
                    }
                }
                $delete = $AmenityModel->remove_amenity($ids, $this->web_partner_id);
            }
            if ($delete) {
                $message = array("StatusCode" => 0, "Message" => "amenity successfully  deleted", "Class" => "success_popup", "Reload" => "true");
            } else {
                $message = array("StatusCode" => 2, "Message" => "amenity not deleted", "Class" => "error_popup", "Reload" => "true");
            }
            $this->session->setFlashdata('Message', $message);
            return $this->response->setJSON($message);
        }
    }


    public function addon_list()
    {
        if (permission_access_error("HotelExtranet", "list_addon")) {
           
            $uri = $this->request->getUri();   
            $hotel_id =  dev_decode($uri->getSegment(3));
            $AddonModel = new AddonModel();
            $lists = $AddonModel->addon_list($hotel_id, $this->web_partner_id);

            $HotelListModel = new HotelListModel();
            $hotel_name = $HotelListModel->get_hotel_name($hotel_id, $this->web_partner_id);

            $data = [
                'title' => $this->title,
                'list' => $lists,
                'view' => "HotelExtranet\Views\addon-list",
                'pager' => $AddonModel->pager,
                'hotel_id' => $hotel_id,
                'hotel' => $hotel_name
            ];
            return view('template/sidebar-layout', $data);
        }
    }

    public function add_addon_view()
    {
        $uri = $this->request->getUri();   
            $hotel_id =  dev_decode($uri->getSegment(3));
        if (permission_access_error("HotelExtranet", "add_addon")) {
            $data = [
                'title' => $this->title,
                'hotel_id' => $hotel_id
            ];
            $details = view('Modules\HotelExtranet\Views\add-addon', $data);
            $data = array("StatusCode" => 0, "Message" => $details, 'class' => 'success_popup', "Reload" => "false");
            return $this->response->setJSON($data);
        }
    }

    public function add_addon()
    {
        if (permission_access_error("HotelExtranet", "add_addon")) {
            $validate = new Validation();

            $rules = $this->validate($validate->hotel_addon);

            if (!$rules) {
                $errors = $this->validator->getErrors();
                $data_validation = array("StatusCode" => 1, "ErrorMessage" => array_filter($errors));
                return $this->response->setJSON($data_validation);
            } else {
                $uri = $this->request->getUri();   
                $hotel_id =  dev_decode($uri->getSegment(3));
                $AddonModel = new AddonModel();
                $data = $this->request->getPost();
                $data['hotel_extranet_id'] = $hotel_id;
                $data['created'] = create_date();
                $data['web_partner_id'] = $this->web_partner_id;
                $added_data = $AddonModel->insert($data);

                if ($added_data) {
                    $message = array("StatusCode" => 0, "Message" => "hotel addon successfully added", "Class" => "success_popup", "Reload" => "true");
                } else {
                    $message = array("StatusCode" => 2, "Message" => "hotel addon not  added", "Class" => "error_popup", "Reload" => "true");
                }

                $this->session->setFlashdata('Message', $message);
                return $this->response->setJSON($message);
            }
        }
    }


    public function edit_addon_view()
    {
        if (permission_access_error("HotelExtranet", "edit_addon")) {
            $uri = $this->request->getUri();   
            $id =  dev_decode($uri->getSegment(3));
            $AddonModel = new AddonModel();
            $details = $AddonModel->addon_details($id, $this->web_partner_id);

            $data = [
                'title' => $this->title,
                'id' => $id,
                'details' => $details,
            ];
            $details = view('Modules\HotelExtranet\Views\edit-addon', $data);
            $data = array("StatusCode" => 0, "Message" => $details, 'class' => 'success_popup', "Reload" => "false");
            return $this->response->setJSON($data);
        }
    }

    public function edit_addon()
    {
        if (permission_access_error("HotelExtranet", "edit_addon")) {
            $uri = $this->request->getUri();   
            $id =  dev_decode($uri->getSegment(3));

            $validate = new Validation();

            $rules = $this->validate($validate->hotel_addon);
            if (!$rules) {
                $errors = $this->validator->getErrors();
                $data_validation = array("StatusCode" => 1, "ErrorMessage" => array_filter($errors));
                return $this->response->setJSON($data_validation);
            } else {
                $AddonModel = new AddonModel();
                $data = $this->request->getPost();

                $data['modified'] = create_date();
                $added_data = $AddonModel->where(["id" => $id, 'web_partner_id' => $this->web_partner_id])->set($data)->update();
                if ($added_data) {
                    $message = array("StatusCode" => 0, "Message" => "hotel addon successfully updated", "Class" => "success_popup", "Reload" => "true");
                } else {
                    $message = array("StatusCode" => 2, "Message" => "hotel addon not  updated", "Class" => "error_popup", "Reload" => "true");
                }
                $this->session->setFlashdata('Message', $message);
                return $this->response->setJSON($message);
            }
        }
    }

    public function addon_status_change()
    {

        if (permission_access_error("HotelExtranet", "status_addon")) {
            $validate = new Validation();
            $rules = $this->validate($validate->status);
            if (!$rules) {
                $errors = $this->validator->getErrors();
                $data_validation = array("StatusCode" => 1, "ErrorMessage" => array_filter($errors));
                return $this->response->setJSON($data_validation);
            } else {
                $AddonModel = new AddonModel();
                $ids = $this->request->getPost('checkedvalue');
                $data['modified'] = create_date();
                $data['status'] = $this->request->getPost('status');

                $update = $AddonModel->status_change($ids, $data, $this->web_partner_id);

                if ($update) {
                    $message = array("StatusCode" => 0, "Message" => "hotel addon status successfully changed", "Class" => "success_popup", "Reload" => "true");
                } else {
                    $message = array("StatusCode" => 2, "Message" => "hotel addon status not changed", "Class" => "error_popup", "Reload" => "true");
                }
                $this->session->setFlashdata('Message', $message);
                return $this->response->setJSON($message);
            }
        }
    }

    public function remove_addon()
    {
        if (permission_access_error("HotelExtranet", "delete_addon")) {
            $AddonModel = new AddonModel();
            $ids = $this->request->getPost('checklist');
            $delete = $AddonModel->remove_addon($ids, $this->web_partner_id);

            if ($delete) {
                $message = array("StatusCode" => 0, "Message" => "hotel addon successfully  deleted", "Class" => "success_popup", "Reload" => "true");
            } else {
                $message = array("StatusCode" => 2, "Message" => "hotel addon not deleted", "Class" => "error_popup", "Reload" => "true");
            }
            $this->session->setFlashdata('Message', $message);
            return $this->response->setJSON($message);
        }
    }

    public function room_list()
    {
        if (permission_access_error("HotelExtranet", "list_room")) {
         
            $uri = $this->request->getUri();   
            $hotelData =  json_decode(dev_decode($uri->getSegment(3)),true); 
            $RoomModel = new RoomModel();
            $lists = $RoomModel->room_list($hotelData['hotel_id'], $this->web_partner_id);

            $HotelListModel = new HotelListModel();
            $hotel_name = $HotelListModel->get_hotel_name($hotelData['hotel_id'], $this->web_partner_id);

            $data = [
                'title' => $this->title,
                'list' => $lists,
                'view' => "HotelExtranet\Views\Room-list",
                'pager' => $RoomModel->pager,
                'hotel_id' => isset($hotelData['hotel_id']) ? $hotelData['hotel_id'] : '',
                'supplier_id' => isset($hotelData['supplier_id']) ? $hotelData['supplier_id'] : '',
                'hotel' => $hotel_name
            ];
            return view('template/sidebar-layout', $data);
        }
    }

    public function add_room_view()
    {
        $uri = $this->request->getUri();   
        $hotelData =  json_decode(dev_decode($uri->getSegment(3)),true);  
        if (permission_access_error("HotelExtranet", "add_room")) {

            $AmenityModel = new AmenityModel();
            $amenity = $AmenityModel->amenity_room_select($this->web_partner_id);
            $data = [
                'title' => $this->title,
                'hotel_id' => isset($hotelData['hotel_id']) ? $hotelData['hotel_id'] : '',
                'supplier_id' => isset($hotelData['supplier_id']) ? $hotelData['supplier_id'] : '',
                'amenity' => $amenity
            ];
            $details = view('Modules\HotelExtranet\Views\add-room', $data);
            $data = array("StatusCode" => 0, "Message" => $details, 'class' => 'success_popup', "Reload" => "false");
            return $this->response->setJSON($data);
        }
    }




    public function add_room()
    {
        if (permission_access_error("HotelExtranet", "add_room")) {
            $validate = new Validation();
            $rules = $this->validate($validate->hotel_room);
            if (!$rules) {
                $errors = $this->validator->getErrors();
                $data_validation = array("StatusCode" => 1, "ErrorMessage" => array_filter($errors));
                return $this->response->setJSON($data_validation);
            } else {
                $uri = $this->request->getUri();   
                $hotelData =  json_decode(dev_decode($uri->getSegment(3)),true);  
              
                $RoomModel = new RoomModel();
                $RoomAvailabilityModel = new RoomAvailabilityModel();
                $data = $this->request->getPost();
                $data['room_amenities'] = implode(',', $data['room_amenities']);

                $data['hotel_extranet_id'] = $hotelData['hotel_id'];
                $data['supplier_id'] = $hotelData['supplier_id'];
                $data['created'] = create_date();
                $data['web_partner_id'] = $this->web_partner_id;
                $room_id = $RoomModel->insert($data);
                $year = date('Y');
                for ($m = 1; $m <= 12; $m++) {
                    $availability = [];
                    $month = date($m);
                    $dateObject = DateTime::createFromFormat('!m', $m);
                    $month_full = $dateObject->format('F');
                    $no_of_days = date('t', mktime(0, 0, 0, $month, 1, $year));
                    if ($m >= date('m')) {
                        for ($i = 1; $i <= $no_of_days; $i++) {
                            $availability['hotel_extranet_room_id'] = $room_id;
                            $availability['supplier_id'] = $hotelData['supplier_id'];
                            $availability['year'] = $year;
                            $availability['month'] = $month_full;
                            $availability['d' . $i] = $data['room_quantity'];
                            $availability['created'] = create_date();
                            $availability['web_partner_id'] = $this->web_partner_id;
                        }
                        $availability_id = $RoomAvailabilityModel->insert($availability);
                    }
                }

                if ($room_id) {
                    $message = array("StatusCode" => 0, "Message" => "hotel room successfully added", "Class" => "success_popup", "Reload" => "true");
                } else {
                    $message = array("StatusCode" => 2, "Message" => "hotel room not  added", "Class" => "error_popup", "Reload" => "true");
                }

                $this->session->setFlashdata('Message', $message);
                return $this->response->setJSON($message);
            }
        }
    }

    public function edit_room_view()
    {
        if (permission_access_error("HotelExtranet", "edit_room")) {
            $uri = $this->request->getUri();   
            $id =  dev_decode($uri->getSegment(3));
            $RoomModel = new RoomModel();
            $details = $RoomModel->room_details($id, $this->web_partner_id);
            $AmenityModel = new AmenityModel();
            $amenity = $AmenityModel->amenity_room_select($this->web_partner_id);

            $details['room_amenities'] = explode(',', $details['room_amenities']);

            $data = [
                'title' => $this->title,
                'id' => $id,
                'details' => $details,
                'amenity' => $amenity
            ];
            $details = view('Modules\HotelExtranet\Views\edit-room', $data);
            $data = array("StatusCode" => 0, "Message" => $details, 'class' => 'success_popup', "Reload" => "false");
            return $this->response->setJSON($data);
        }
    }

    public function edit_room()
    {
        if (permission_access_error("HotelExtranet", "edit_room")) {
            $uri = $this->request->getUri();   
            $id =  dev_decode($uri->getSegment(3));
            $validate = new Validation();
            $rules = $this->validate($validate->hotel_room);
            if (!$rules) {
                $errors = $this->validator->getErrors();
                $data_validation = array("StatusCode" => 1, "ErrorMessage" => array_filter($errors));
                return $this->response->setJSON($data_validation);
            } else {
                $RoomModel = new RoomModel();
                $data = $this->request->getPost();
                $data['room_amenities'] = implode(',', $data['room_amenities']);
                $data['modified'] = create_date();
                $added_data = $RoomModel->where(["id" => $id, 'web_partner_id' => $this->web_partner_id])->set($data)->update();
                if ($added_data) {
                    $message = array("StatusCode" => 0, "Message" => "hotel room successfully updated", "Class" => "success_popup", "Reload" => "true");
                } else {
                    $message = array("StatusCode" => 2, "Message" => "hotel room not  updated", "Class" => "error_popup", "Reload" => "true");
                }
                $this->session->setFlashdata('Message', $message);
                return $this->response->setJSON($message);
            }
        }
    }

    public function room_status_change()
    {
        if (permission_access_error("HotelExtranet", "status_room")) {
            $validate = new Validation();
            $rules = $this->validate($validate->status);
            if (!$rules) {
                $errors = $this->validator->getErrors();
                $data_validation = array("StatusCode" => 1, "ErrorMessage" => array_filter($errors));
                return $this->response->setJSON($data_validation);
            } else {
                $RoomModel = new RoomModel();
                $ids = $this->request->getPost('checkedvalue');
                $data['modified'] = create_date();
                $data['status'] = $this->request->getPost('status');

                $update = $RoomModel->status_change($ids, $data, $this->web_partner_id);

                if ($update) {
                    $message = array("StatusCode" => 0, "Message" => "room status successfully changed", "Class" => "success_popup", "Reload" => "true");
                } else {
                    $message = array("StatusCode" => 2, "Message" => "room addon status not changed", "Class" => "error_popup", "Reload" => "true");
                }
                $this->session->setFlashdata('Message', $message);
                return $this->response->setJSON($message);
            }
        }
    }

    public function remove_room()
    {
        if (permission_access_error("HotelExtranet", "delete_room")) {
            $RoomModel = new RoomModel();
            $ids = $this->request->getPost('checklist');
            $delete = $RoomModel->remove_room($ids, $this->web_partner_id);

            if ($delete) {
                $message = array("StatusCode" => 0, "Message" => "room successfully  deleted", "Class" => "success_popup", "Reload" => "true");
            } else {
                $message = array("StatusCode" => 2, "Message" => "room not deleted", "Class" => "error_popup", "Reload" => "true");
            }
            $this->session->setFlashdata('Message', $message);
            return $this->response->setJSON($message);
        }
    }

    public function room_gallery()
    {
        if (permission_access_error("HotelExtranet", "list_room_gallery")) {
        
            $uri = $this->request->getUri();   
            $room_id =  dev_decode($uri->getSegment(3));
            $RoomGalleryModel = new RoomGalleryModel();
            $lists = $RoomGalleryModel->room_gallery($room_id, $this->web_partner_id);

            $data = [
                'title' => $this->title,
                'room_id' => $room_id,
                'list' => $lists,
                'pager' => $RoomGalleryModel->pager,
                'search_bar_data' => $this->request->getGet(),
            ];
            $view = view('Modules\HotelExtranet\Views\room-gallery', $data);
            $data = array("StatusCode" => 0, "Message" => $view, 'class' => 'success_popup', "Reload" => "false");
            return $this->response->setJSON($data);
        }
    }

    public function add_room_gallery()
    {
        if (permission_access_error("HotelExtranet", "add_room_gallery")) {
            $uri = $this->request->getUri();   
            $room_id =  dev_decode($uri->getSegment(3));
            $validate = new Validation();
            $rules = $this->validate($validate->room_gallery_validation);
            if (!$rules) {
                $errors = $this->validator->getErrors();
                $data_validation = array("StatusCode" => 1, "ErrorMessage" => array_filter($errors));
                return $this->response->setJSON($data_validation);
            } else {
                $RoomGalleryModel = new RoomGalleryModel();
                $data = $this->request->getPost();
                #use getFile() for single image or getFiles() for multiple image
                $field_name = 'room_gallery';
                $file = $this->request->getFile($field_name);
                $resizeDim = array('width' => 600, 'height' => 400);
                $image_upload = image_upload($file, $field_name, $this->folder_name, $resizeDim);
                if ($image_upload['status_code'] == 0) {
                    $data['hotel_extranet_room_id'] = $room_id;
                    $data['created'] = create_date();
                    $data['web_partner_id'] = $this->web_partner_id;
                    $data[$field_name] = $image_upload['file_name'];
                    $added_data = $RoomGalleryModel->insert($data);

                    $lists = $RoomGalleryModel->room_gallery($room_id, $this->web_partner_id);

                    $data = [
                        'title' => $this->title,
                        'room_id' => $room_id,
                        'list' => $lists,
                        'pager' => $RoomGalleryModel->pager,
                    ];
                    $data_table = view('Modules\HotelExtranet\Views\room-gallery-table-html', $data);

                    if ($added_data) {
                        $message = array("StatusCode" => 7, "Html_data" => $data_table, "Message" => "image successfully uploaded", "FormBlank" => "true", "Class" => "success_popup", "Reload" => "false");
                    } else {
                        $message = array("StatusCode" => 2, "Message" => "image not uploaded", "Class" => "error_popup", "Reload" => "false");
                    }
                } else {
                    $message = array("StatusCode" => 2, "Message" => $image_upload['message'], "Class" => "error_popup", "Reload" => "false");
                }
                return $this->response->setJSON($message);
            }
        }
    }

    public function remove_room_gallery()
    {
        if (permission_access_error("HotelExtranet", "delete_room_gallery")) {
            $RoomGalleryModel = new RoomGalleryModel();
            $ids = $this->request->getPost('checklist');
            $field_name = 'room_gallery';
            foreach ($ids as $id) {
                $details = $RoomGalleryModel->delete_image($id, $this->web_partner_id);
                if ($details[$field_name]) {
                    if (file_exists("./uploads/$this->folder_name/" . $details[$field_name])) {
                        unlink("./uploads/$this->folder_name/" . $details[$field_name]);
                        unlink("./uploads/$this->folder_name/thumbnail/" . $details[$field_name]);
                    }
                }
                $delete = $RoomGalleryModel->remove_room_gallery($id, $this->web_partner_id);
            }

            if ($delete) {
                $message = array("StatusCode" => 0, "Message" => "gallery image successfully  deleted", "Class" => "success_popup", "Reload" => "true");
            } else {
                $message = array("StatusCode" => 2, "Message" => "gallery image not deleted", "Class" => "error_popup", "Reload" => "true");
            }

            return $this->response->setJSON($message);
        }
    }



    public function room_price_list()
    {
        if (permission_access_error("HotelExtranet", "list_room_price")) {
            $uri = $this->request->getUri();   
            $room_id =  dev_decode($uri->getSegment(3));


            $RoomPriceModel = new RoomPriceModel();

            $lists = $RoomPriceModel->room_price_list($room_id, $this->web_partner_id);

            $data = [
                'title' => $this->title,
                'list' => $lists,
                'view' => "HotelExtranet\Views\Room-price-list",
                'pager' => $RoomPriceModel->pager,
                'room_id' => $room_id
            ];
            return view('template/sidebar-layout', $data);
        }
    }

    public function add_room_price_view()
    {
        $uri = $this->request->getUri();   
        $room_id =  dev_decode($uri->getSegment(3));
        if (permission_access_error("HotelExtranet", "add_room_price")) {

            $data = [
                'title' => $this->title,
                'room_id' => $room_id,
            ];
            $details = view('Modules\HotelExtranet\Views\add-room-price', $data);
            $data = array("StatusCode" => 0, "Message" => $details, 'class' => 'success_popup', "Reload" => "false");
            return $this->response->setJSON($data);
        }
    }

    public function add_room_price()
    {
        if (permission_access_error("HotelExtranet", "add_room_price")) {
            $validate = new Validation();

            $rules = $this->validate($validate->room_price);

            if (!$rules) {
                $errors = $this->validator->getErrors();
                $data_validation = array("StatusCode" => 1, "ErrorMessage" => array_filter($errors));
                return $this->response->setJSON($data_validation);
            } else {
                $uri = $this->request->getUri();   
                $room_id =  dev_decode($uri->getSegment(3));
                $RoomPriceModel = new RoomPriceModel();
                $data = $this->request->getPost();
                if ($data['start_date']) {
                    $data['start_date'] = strtotime($data['start_date']);
                }
                if ($data['end_date']) {
                    $data['end_date'] = strtotime($data['end_date']);
                }
                $data['hotel_extranet_room_id'] = $room_id;
                $data['created'] = create_date();
                $data['web_partner_id'] = $this->web_partner_id;
                $added_data = $RoomPriceModel->insert($data);

                if ($added_data) {
                    $message = array("StatusCode" => 0, "Message" => "room price successfully added", "Class" => "success_popup", "Reload" => "true");
                } else {
                    $message = array("StatusCode" => 2, "Message" => "room price not  added", "Class" => "error_popup", "Reload" => "true");
                }

                $this->session->setFlashdata('Message', $message);
                return $this->response->setJSON($message);
            }
        }
    }


    public function edit_room_price_view()
    {
        if (permission_access_error("HotelExtranet", "edit_room_price")) {
            $uri = $this->request->getUri();   
            $id =  dev_decode($uri->getSegment(3));
            $RoomPriceModel = new RoomPriceModel();
            $details = $RoomPriceModel->room_price_details($id, $this->web_partner_id);

            if (isset($details['start_date']) && $details['start_date'] != '') {
                $details['start_date'] = timestamp_to_date($details['start_date']);
            }
            if (isset($details['end_date']) && $details['end_date'] != '') {
                $details['end_date'] = timestamp_to_date($details['end_date']);
            }
            $data = [
                'title' => $this->title,
                'id' => $id,
                'details' => $details,
            ];
            $details = view('Modules\HotelExtranet\Views\edit-room-price', $data);
            $data = array("StatusCode" => 0, "Message" => $details, 'class' => 'success_popup', "Reload" => "false");
            return $this->response->setJSON($data);
        }
    }

    public function edit_room_price()
    {
        if (permission_access_error("HotelExtranet", "edit_room_price")) {
            $uri = $this->request->getUri();   
            $id =  dev_decode($uri->getSegment(3));
            $validate = new Validation();

            $rules = $this->validate($validate->room_price);
            if (!$rules) {
                $errors = $this->validator->getErrors();
                $data_validation = array("StatusCode" => 1, "ErrorMessage" => array_filter($errors));
                return $this->response->setJSON($data_validation);
            } else {
                $RoomPriceModel = new RoomPriceModel();
                $data = $this->request->getPost();
                if ($data['start_date']) {
                    $data['start_date'] = strtotime($data['start_date']);
                }
                if ($data['end_date']) {
                    $data['end_date'] = strtotime($data['end_date']);
                }
                $data['modified'] = create_date();
                $added_data = $RoomPriceModel->where(["id" => $id, 'web_partner_id' => $this->web_partner_id])->set($data)->update();
                if ($added_data) {
                    $message = array("StatusCode" => 0, "Message" => "room price successfully updated", "Class" => "success_popup", "Reload" => "true");
                } else {
                    $message = array("StatusCode" => 2, "Message" => "room price not updated", "Class" => "error_popup", "Reload" => "true");
                }
                $this->session->setFlashdata('Message', $message);
                return $this->response->setJSON($message);
            }
        }
    }


    public function remove_room_price()
    {
        if (permission_access_error("HotelExtranet", "delete_room_price")) {
            $RoomPriceModel = new RoomPriceModel();
            $ids = $this->request->getPost('checklist');
            $delete = $RoomPriceModel->remove_room_price($ids, $this->web_partner_id);

            if ($delete) {
                $message = array("StatusCode" => 0, "Message" => "room price successfully  deleted", "Class" => "success_popup", "Reload" => "true");
            } else {
                $message = array("StatusCode" => 2, "Message" => "room price not deleted", "Class" => "error_popup", "Reload" => "true");
            }
            $this->session->setFlashdata('Message', $message);
            return $this->response->setJSON($message);
        }
    }


    public function get_room_availability()
    { 
        $data = $this->request->getGet(); 
        $room_id = dev_decode($data['key']);
        $year = $data['year']; 
        $RoomAvailabilityModel = new RoomAvailabilityModel();

        $RoomModel = new RoomModel();
        $room_info = $RoomModel->get_room_title($room_id, $this->web_partner_id);

        $room_title = $room_info['room_title'];
        $room_availabilities = $RoomAvailabilityModel->get_room_availabilities($room_id, $year, $this->web_partner_id); 
       /*  pr($room_availabilities);exit; */
        $next_year = date('Y', strtotime('+1 years')); 

        // Function to generate availability data
        $generateAvailability = function ($year) use ($RoomAvailabilityModel, $room_id) {
            for ($m = 1; $m <= 12; $m++) {
                $availability = [];
                $month = str_pad($m, 2, '0', STR_PAD_LEFT);
                $dateObject = DateTime::createFromFormat('!m', $m);
                $month_full = $dateObject->format('F');
                $no_of_days = date('t', mktime(0, 0, 0, $month, 1, $year));

                for ($i = 1; $i <= $no_of_days; $i++) {
                    $availability['hotel_extranet_room_id'] = $room_id;
                    $availability['year'] = $year;
                    $availability['month'] = $month_full;
                    $availability['d' . $i] = 0;
                    $availability['web_partner_id'] = $this->web_partner_id;
                }
                $RoomAvailabilityModel->insert($availability);
            }
        };

        // Generate data for the current year if not available
        if (empty($room_availabilities)) { 
            $generateAvailability($year);
            $room_availabilities = $RoomAvailabilityModel->get_room_availabilities($room_id, $year, $this->web_partner_id);
        }

        // Generate data for the next year
        $next_year_availabilities = $RoomAvailabilityModel->get_room_availabilities($room_id, $next_year, $this->web_partner_id);
        if (empty($next_year_availabilities)) {
            $generateAvailability($next_year);
        }
 

        $data = [
            'title' => $this->title,
            'room_availabilities' => $room_availabilities,
            'next_year' => $next_year,
            'year' => date('Y'),
            'room_id' => $room_id,
            'key' => $data['key'],
            'room_title' => $room_title,
           /*  'view' => "HotelExtranet\Views\Room-availability", */
        ];
        if($year >= $next_year){
            $data['view'] = "HotelExtranet\Views\Room-availability-next-year";
        }else{
            $data['view'] = "HotelExtranet\Views\Room-availability";
        }
        
      


        $data['tss'] = "sasas";

        

        return view('template/sidebar-layout', $data);
    }



    /* public function get_room_availability()
    { 
        $data = $this->request->getGet(); 
        $room_id = dev_decode($data['key']);
        $year = $data['year']; 
        $RoomAvailabilityModel = new RoomAvailabilityModel();

        $RoomModel = new RoomModel();
        $room_info = $RoomModel->get_room_title($room_id, $this->web_partner_id);

        $room_title = $room_info['room_title'];
        $room_availabilities = $RoomAvailabilityModel->get_room_availabilities($room_id, $year, $this->web_partner_id); 

        $next_year = date('Y', strtotime('+1 years')); 
        if (empty($room_availabilities)) { 
            for ($m = 1; $m <= 12; $m++) {
                $availability = [];
                $month = date($m);
                $dateObject = DateTime::createFromFormat('!m', $m);
                $month_full = $dateObject->format('M');
                $no_of_days = date('t', mktime(0, 0, 0, $month, 1, $year));
                if ($m >= date('m')) {
                    for ($i = 1; $i <= $no_of_days; $i++) {
                        $availability['hotel_extranet_room_id'] = $room_id;
                        $availability['year'] = $year;
                        $availability['month'] = $month_full;
                        $availability['d' . $i] = 0;
                        $availability['web_partner_id'] = $this->web_partner_id;
                    }
                    $availability_id = $RoomAvailabilityModel->insert($availability);
                }
                $room_availabilities = $RoomAvailabilityModel->get_room_availabilities($room_id, $year, $this->web_partner_id);
            }
        }

        $data = [
            'title' => $this->title,
            'room_availabilities' => $room_availabilities,
            'next_year' => $next_year,
            'year' => date('Y'),
            'room_id' => $room_id,
            'key' => $data['key'],
            'room_title' => $room_title,
            'view' => "HotelExtranet\Views\Room-availability",
        ];
        return view('template/sidebar-layout', $data);
    } */

    public function room_availability_update()
    {
        $uri = $this->request->getUri();   
        $room_id =  dev_decode($uri->getSegment(3));
        $data = $this->request->getPost();
        $year = $data['year'];

        $RoomAvailabilityModel = new RoomAvailabilityModel();

        $car_availabilities = $RoomAvailabilityModel->get_room_availabilities($room_id, $year, $this->web_partner_id);

        if ($car_availabilities) {
            foreach ($data['data'] as $key => $availability) {
                $availability['hotel_extranet_room_id'] = $room_id;
                $availability['year'] = $year;
                $availability['month'] = $key;
                $availability['modified'] = create_date();
                $id = $availability['id'];
                unset($availability['id']);
                $availability_id = $RoomAvailabilityModel->where(["id" => $id, "web_partner_id" => $this->web_partner_id])->set($availability)->update();
            }
        } else {
            foreach ($data['data'] as $key => $availability) {
                $availability['hotel_extranet_room_id'] = $room_id;
                $availability['year'] = $year;
                $availability['month'] = $key;
                $availability['web_partner_id'] = $this->web_partner_id;
                unset($availability['id']);
                $availability_id = $RoomAvailabilityModel->insert($availability);
            }
        }

        if ($availability_id) {
            $message = array("StatusCode" => 0, "Message" => "Room Availability Updated", "Class" => "success_popup");
        } else {
            $message = array("StatusCode" => 2, "Message" => "Room Availability not  Updated", "Class" => "error_popup");
        }

        $this->session->setFlashdata('Message', $message);
        return $this->response->setJSON($message);
    }
}
