<?php

namespace Modules\Coupon\Controllers;

use App\Modules\Coupon\Models\UmrahCouponModel;
use App\Modules\Umrah\Models\UmrahDestinationModel;
use App\Modules\Umrah\Models\UmrahThemesModel;
use App\Controllers\BaseController;
use Modules\Coupon\Config\Validation;
use Throwable;

class SuperAdminUmrahCoupon extends BaseController
{

    protected $title;
    protected $web_partner_id;
    protected $web_partner_details;
    protected $user_id;
    protected $admin_comapny_detail;

    public function initController(\CodeIgniter\HTTP\RequestInterface $request, \CodeIgniter\HTTP\ResponseInterface $response, \Psr\Log\LoggerInterface $logger)
    {
        parent::initController($request, $response, $logger);
        $this->title = "Umrah Coupon";

        $this->web_partner_id = admin_cookie_data()['admin_user_details']['web_partner_id'];
        $this->web_partner_details = admin_cookie_data()['admin_user_details'];
        $this->admin_comapny_detail = admin_cookie_data()['admin_comapny_detail'];
        $this->user_id = admin_cookie_data()['admin_user_details']['id'];

        if (permission_access_error("Coupon", "Coupon_Module")) {
        }
    }




    public function umrah_coupon()
    {
        try {
            if (permission_access_error("Coupon", "coupon_umrah_list")) {
                $UmrahCouponModel = new UmrahCouponModel();

                $search_data = $this->request->getGet();
                $lists = $UmrahCouponModel->umrahcoupon_list($this->web_partner_id, $search_data);

                $data = [
                    'title' => $this->title,
                    'list' => $lists,
                    'view' => "Coupon\Views\UmrahCoupon\umrah-coupon-list",
                    'pager' => $UmrahCouponModel->pager,
                    'search_bar_data' => $search_data,
                ];
                return view('template/sidebar-layout', $data);
            }
        } catch (Throwable $e) {
            log_error_details($e);

            $data = [
                'view' => "Hajj\Views/exception_view",
                'error_message' => 'An error occurred. Please try again later.'
            ];
            return view('template/sidebar-layout', $data);
        }
    }




    public function add_umrah_coupon_view()
    {
        try {
            if (permission_access_error("Coupon", "coupon_umrah_add")) {
                $UmrahCouponModel = new UmrahCouponModel();
                $UmrahThemesModel = new UmrahThemesModel();
                $UmrahDestinationModel = new UmrahDestinationModel();
                $theme = $UmrahThemesModel->umrah_themes_list_select($this->web_partner_id);
                $destination = $UmrahDestinationModel->umrah_destination_list_select($this->web_partner_id);
                $package_list = $UmrahCouponModel->getDataArray('umrah_package_list', ['web_partner_id' => $this->web_partner_id], 0, 1, 'id,package_name');
                $data = [
                    'title' => $this->title,
                    'umrah_theme' => $theme,
                    'umrah_destination' => $destination,
                    'umrah_package' => $package_list,
                ];
                $add_coupon_view = view('Modules\Coupon\Views\UmrahCoupon\add-umrah-coupon', $data);
                $data = ["StatusCode" => 0, "Message" => $add_coupon_view, 'class' => 'success_popup', "Reload" => "false"];
                return $this->response->setJSON($data);
            }
        } catch (Exception $e) {
            log_message('error', 'Error in add_umrah_coupon_view function: ' . $e->getMessage() . ' - ' . $e->getTraceAsString());

            $data = [
                'view' => "Umrah\Views/exception_view",
                'error_message' => 'An error occurred. Please try again later.'
            ];
            return view('template/sidebar-layout', $data);
        }
    }




    public function add_coupon_umrah()
    {

        $StatusCode = 2;
        $Message = "An error occurred while adding discount.";
        $Class = "error_popup";
        $doReload = "true";

        try {
            if (permission_access_error("Coupon", "coupon_umrah_add")) {
                $data = $this->request->getPost();
                $validate = new Validation();

                if (isset($data['package_select']) && $data['package_select'] == 'ANY') {
                    unset($validate->umrah_coupon_validation['umrah_package.*']);
                }
                $rules = $this->validate($validate->umrah_coupon_validation);
                if (!$rules) {
                    $errors = $this->validator->getErrors();

                    if (isset($errors['umrah_package.*'])) {
                        $errors['umrah_package[]'] = $errors['umrah_package.*'];
                        unset($errors['umrah_package.*']);
                    }
                    return $this->response->setJSON([
                        "StatusCode" => 1,
                        "ErrorMessage" => array_filter($errors)
                    ]);
                }

                $UmrahCouponModel = new UmrahCouponModel();

                $data['created'] = create_date();
                if ($data['valid_from']) {
                    $data['valid_from'] = strtotime($data['valid_from']);
                }
                if ($data['valid_to']) {
                    $data['valid_to'] = strtotime($data['valid_to']);
                }

                if ($data['travel_date_from']) {
                    $data['travel_date_from'] = strtotime($data['travel_date_from']);
                }
                if ($data['travel_date_to']) {
                    $data['travel_date_to'] = strtotime($data['travel_date_to']);
                }
                $data['web_partner_id'] = $this->web_partner_id;

                if (isset($data['package_select']) && $data['package_select'] == 'ANY') {
                    $data['umrah_package'] = 'ANY';
                    $data['umrah_package_id'] = 'ANY';
                    unset($data['package_select']);
                } else {
                    $umrah_package_id = [];
                    $umrah_package = [];
                    foreach ($data['umrah_package'] as $package) {
                        $temp_package = explode('_', $package);
                        array_push($umrah_package_id, $temp_package[0]);
                        array_push($umrah_package, $temp_package[1]);
                    }
                    $data['umrah_package_id'] = implode(',', $umrah_package_id);
                    $data['umrah_package'] = implode(',', $umrah_package);
                }
                $data['destination_name'] = 'ANY';
                $data['destination_id'] = 'ANY';
                $data['theme_name'] = 'ANY';
                $data['theme_id'] = 'ANY';

                $added_data = $UmrahCouponModel->insert($data);
                if ($added_data) {
                    $StatusCode = 0;
                    $Message = "Coupon added successfully";
                    $Class = "success_popup";
                    $doReload = "true";
                } else {
                    $Message = "Coupon could not be added";
                }
            } else {
                $data = array("StatusCode" => 2, "Message" => "Permission Denied", "Class" => "error_popup", "Reload" => "true");
                return view('template/sidebar-layout', $data);
            }
        } catch (Throwable $e) {
            log_error_details($e);
        }
        $response = ["StatusCode" => $StatusCode, "Message" => $Message, "Class" => $Class, "Reload" => $doReload];
        $this->session->setFlashdata('Message', $response);
        return $this->response->setJSON($response);
    }



    public function umrah_coupon_status_change()
    {

        $StatusCode = 2;
        $Message = "An error occurred while changing status.";
        $Class = "error_popup";
        $doReload = "true";
        try {
            if (permission_access_error("Coupon", "coupon_umrah_change_status")) {
                $validate = new Validation();
                $rules = $this->validate($validate->status);

                if (!$rules) {
                    $errors = $this->validator->getErrors();
                    return $this->response->setJSON([
                        "StatusCode" => 1,
                        "ErrorMessage" => array_filter($errors)
                    ]);
                }

                $UmrahCouponModel = new UmrahCouponModel();
                $ids = $this->request->getPost('checkedvalue');

                $data['status'] = $this->request->getPost('status');

                $UmrahCouponModel->status_change($ids, $data, $this->web_partner_id);

                $StatusCode = 0;
                $Message = "Umrah Coupon status changed successfully.";
                $Class = "success_popup";
            } else {
                $data = array("StatusCode" => 2, "Message" => "Permission Denied", "Class" => "error_popup", "Reload" => "true");
                return view('template/sidebar-layout', $data);
            }
        } catch (Throwable $e) {
            log_error_details($e);
        }

        $message = ["StatusCode" => $StatusCode, "Message" => $Message, "Class" => $Class, "Reload" => $doReload];
        $this->session->setFlashdata('Message', $message);
        return $this->response->setJSON($message);
    }




    public function remove_umrah_coupon()
    {
        $StatusCode = 2;
        $Message = "An error occurred while deleting the coupon.";
        $Class = "error_popup";
        $doReload = "true";
        try {
            if (permission_access_error("Coupon", "coupon_umrah_delete")) {
                $UmrahCouponModel = new UmrahCouponModel();
                $ids = $this->request->getPost('checklist');
                $delete = $UmrahCouponModel->remove_coupon($ids, $this->web_partner_id);

                if ($delete) {
                    $StatusCode = 0;
                    $Message = "Coupon deleted successfully";
                    $Class = "success_popup";
                } else {
                    $Message = "Coupon could not be deleted!";
                }
            } else {
                $data = array("StatusCode" => 2, "Message" => "Permission Denied", "Class" => "error_popup", "Reload" => "true");
                return view('template/sidebar-layout', $data);
            }
        } catch (Throwable $e) {
            log_error_details($e);
        }
        $message = ["StatusCode" => $StatusCode, "Message" => $Message, "Class" => $Class, "Reload" => $doReload];
        $this->session->setFlashdata('Message', $message);
        return $this->response->setJSON($message);
    }




    public function coupon_umrah_details()
    {
        try {
            if (permission_access("Coupon", "coupon_umrah_detail_list")) {
                $uri = $this->request->getUri();
                $id = dev_decode($uri->getSegment(3));
                $UmrahCouponModel = new UmrahCouponModel();
                $details = $UmrahCouponModel->umrah_coupon_details($id, $this->web_partner_id);
                $data = [
                    'title' => $this->title,
                    'details' => $details,
                ];
                $blog_details = view('Modules\Coupon\Views\UmrahCoupon\coupon-umrah-detail', $data);
                $data = ["StatusCode" => 0, "Message" => $blog_details, 'class' => 'success_popup'];
                return $this->response->setJSON($data);
            } else {
                $data = array("StatusCode" => 2, "Message" => "Permission Denied", "Class" => "error_popup", "Reload" => "true");
                return view('template/sidebar-layout', $data);
            }
        } catch (Throwable $e) {
            log_error_details($e);
            $message = ["StatusCode" => 2, "Message" => "", "Class" => "error_popup", "Reload" => "true"];
            $this->session->setFlashdata('Message', $message);
            return $this->response->setJSON($message);
        }
    }
}
