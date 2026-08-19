<?php

namespace Modules\Login\Controllers;

use App\Modules\Login\Models\LoginModel;
use App\Controllers\BaseController;
use App\Modules\Login\Models\CustomerAccountLogModel;
use App\Modules\Login\Models\LoginLogsModel;

class Login extends BaseController
{
    public $title =" Login"; 

    public $folder_name;
    public $validator;

    public function __construct()
    { 
        $this->folder_name = 'web_partner';
        helper('Modules\Login\Helpers\login');
    }

    public function check_user()
    {
        if ($this->request->getMethod() == 'POST') {
            $rules = $this->validate([
                'email_id' => [
                    'label' => 'Email',
                    'rules' => 'trim|required|valid_email',
                    'errors' => [
                        'required' => 'Please enter your email id.',
                        'valid_email' => 'Please enter a valid email id.'
                    ]
                ],

            ]);
            if (!$rules) {
                $errors = $this->validator->getErrors();
                $data_validation = array("StatusCode" => 1, "ErrorMessage" => array_filter($errors));
                return $this->response->setJSON($data_validation);
            }
            $email = trim($this->request->getPost('email_id'));
            $loginmodel = new LoginModel();

            $param = trim($this->request->getPost('param'));

            $user = $loginmodel->where('email_id', $email)->where('web_partner_id', web_partner_details['id'])->first();

            if ($user) {
               
                if ($param == "forgot-password") {
                    $genarate_OTP = mt_rand(100000, 999999);

                    $insertData = array(
                        "username" => $email,
                        "btype" => 'B2C',
                        "otp" => $genarate_OTP,
                        "service" => 'forgetPassword',
                        "created" => create_date(),
                        "otp_expiery" => create_otp_expiry(),
                    );
                    $inserId = $loginmodel->insertData("temp_otp_logs", $insertData);
                    $message_type = "forget password";

                    $email_type = "forget password wl customer";
                    $data['otp'] = $genarate_OTP;
                    $emailMessage = View('emails/otp-emails', $data);
                    $subject = "OTP";

                    send_email($email, $subject, $emailMessage, $email_type = $email_type, $attachment = null, $extraprameter = array(), $param1 = null);

                    $data['email_id'] = $email;
                    $add_view = view('Modules\Login\Views\forgot-password-modal', $data);
                    $data = array("StatusCode" => 5, "Message" => $add_view, 'class' => 'success_popup', "Reload" => "false", "temp" => $genarate_OTP);
                    return $this->response->setJSON($data);
                } else {
                    $data['email_id'] = $email;
                    $add_view = view('Modules\Login\Views\login-modal-password', $data);
                    $data = array("StatusCode" => 5, "Message" => $add_view, 'class' => 'success_popup', "Reload" => "false");
                    return $this->response->setJSON($data);
                }

            } else {
                $genarate_OTP = mt_rand(100000, 999999);

                $insertData = array(
                    "username" => $email,
                    "btype" => 'B2C',
                    "otp" => $genarate_OTP,
                    "service" => 'SignUp',
                    "created" => create_date(),
                    "otp_expiery" => create_otp_expiry(),
                );
                $inserId = $loginmodel->insertData("temp_otp_logs", $insertData);
                $message_type = "Signup";

                $email_type = "signup wl customer";
                $data['otp'] = $genarate_OTP;
                $emailMessage = View('emails/otp-emails', $data);
                $subject = "OTP - Important Account Security Information";

                send_email($email, $subject, $emailMessage, $email_type = $email_type, $attachment = null, $extraprameter = array(), $param1 = null);

                $data['email_id'] = $email;
                $add_view = view('Modules\Login\Views\signup-modal-password', $data);
                $data = array("StatusCode" => 5, "Message" => $add_view, 'class' => 'success_popup', "Reload" => "false", "temp" => $genarate_OTP);
                return $this->response->setJSON($data);
            }
        }
    }

    public function user_signup()
    {
        if ($this->request->getMethod() == 'POST') {
            $rules = $this->validate([
                'email_id' => [
                    'label' => 'Email',
                    'rules' => 'trim|required|valid_email',
                    'errors' => [
                        'required' => 'Please enter your email id.',
                        'valid_email' => 'Please enter a valid email id.'
                    ]
                ],

                'user_password' => [
                    'label' => 'Password',
                    'rules' => 'trim|required|min_length[8]|regex_match[/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/]',
                    'errors' => [
                        'required' => 'Please enter your password.',
                        'min_length' => 'Password must be at least 8 digits',
                        'regex_match' => 'The password must contain at least one uppercase letter, one lowercase letter, one number, one special character. must be at least 8 digits '
                    ]
                ],

                'otp' => [
                    'label' => 'otp',
                    'rules' => 'required|numeric',
                    'errors' => [
                        'required' => 'Please enter otp.',

                    ]
                ]

            ]);
            if (!$rules) {
                $errors = $this->validator->getErrors();
                $data_validation = array("StatusCode" => 1, "ErrorMessage" => array_filter($errors));
                return $this->response->setJSON($data_validation);
            } else {
                $data['OTP'] = trim($this->request->getPost('otp'));
                ;
                $data['BType'] = 'B2C';
                $data['Service'] = 'SignUp';
                $LoginModel = new LoginModel();
                $response = $LoginModel->verify_otp($data);
                if ($response) {

                    $OTPexpiretime = $response['otp_expiery'];
                    $currentTime = create_date();
                    if ($OTPexpiretime > $currentTime) {
                        $LoginModel->deleteData('temp_otp_logs', $response['id']);


                        $password = md5($this->request->getPost('user_password'));
                        $customer_data['password'] = $password;
                        $customer_data['email_id'] = $this->request->getPost('email_id');
                        /*end password encryption*/

                        $customer_data['mobile_verify'] = 0;


                        $customer_data['email_verify'] = 1;
                        $customer_data['status'] = 'active';

                        $customer_data['web_partner_id'] = web_partner_details['id'];

                        $customer_data['created'] = create_date();

                        $wl_customer_id = $LoginModel->insert($customer_data);
                        $updateData['customer_id'] = web_partner_details['customer_pre_fix'] . $wl_customer_id;
                        $LoginModel->where(array("web_partner_id" => web_partner_details['id'], "id" => $wl_customer_id))->set($updateData)->update();
                        $log['customer_id'] = $wl_customer_id;
                        $log['web_partner_id'] = web_partner_details['id'];
                        $log['created'] = create_date();
                        $log['action_type'] = 'credit';
                        $data['credit'] = 0;
                        $log['balance'] = 0;
                        $log['remark'] = 'Account created with 0 balance';
                        $log['payment_mode'] = 'Temporary_Credit';

                        $CustomerAccountLogModel = new CustomerAccountLogModel();
                        $account_log_added = $CustomerAccountLogModel->insert($log);

                        $loginmodel = new LoginModel();
                        $user = $loginmodel->where('email_id', $customer_data['email_id'])->where('web_partner_id', web_partner_details['id'])->where('status', 'active')->where('password', $password)->first();


                        $this->session->set('wl_customer', $user);

                        $message = array("StatusCode" => 0, "Message" => "You have successfully logged in", "Class" => "success_popup");

                        $this->session->setFlashdata('Message', $message);
                        return $this->response->setJSON($message);
                    } else {
                        $message = array("StatusCode" => 3, "Message" => " OTP Expired", "Class" => "error_popup");
                        return $this->response->setJSON($message);
                    }

                } else {
                    $message = array("StatusCode" => 3, "Message" => "Invalid OTP", "Class" => "error_popup");

                    return $this->response->setJSON($message);
                }
            }
        }
    }


    public function user_login()
    {
        if ($this->request->getMethod() == 'POST') {
            $rules = $this->validate([
                'email_id' => [
                    'label' => 'Email',
                    'rules' => 'trim|required|valid_email',
                    'errors' => [
                        'required' => 'Please enter your email id.',
                        'valid_email' => 'Please enter a valid email id.'
                    ]
                ],

                'user_password' => [
                    'label' => 'Password',
                    'rules' => 'required|min_length[8]',
                    'errors' => [
                        'required' => 'Please enter your password.',
                        'min_length' => 'Password must be at least 8 digits'
                    ]
                ]

            ]);
            if (!$rules) {
                $errors = $this->validator->getErrors();
                $data_validation = array("StatusCode" => 1, "ErrorMessage" => array_filter($errors));
                return $this->response->setJSON($data_validation);
            } else {
                $email = trim($this->request->getPost('email_id'));
                $password = trim($this->request->getPost('user_password'));
                $loginmodel = new LoginModel();
                $user = $loginmodel->where('email_id', $email)->where('web_partner_id', web_partner_details['id'])->where('status', 'active')->where('password', md5($password))->first();

                if ($user) {

                    $this->session->set('wl_customer', $user);

                    $message = array("StatusCode" => 0, "Message" => "You have successfully logged in", "Class" => "success_popup");

                    $LoginLogsModel = new LoginLogsModel();
                    $customer = $this->request->getUserAgent();
                    $loginLogs = [
                        "web_partner_id" => web_partner_details['id'],
                        "user_id" => $user['id'],
                        "user_name" => $user['first_name'],
                        "login_browser" => $customer->getBrowser() . ' ' . $customer->getVersion(),
                        'platform' => $customer->getPlatform(),
                        'role' => 'Customer',
                        "login_time" => create_date(),
                        "login_ip_address" => $this->request->getIpAddress()
                    ];

                    $LoginLogsModel->insert($loginLogs);

                    $this->session->setFlashdata('Message', $message);
                    return $this->response->setJSON($message);

                } else {
                    $message = array("StatusCode" => 3, "Message" => "Invalid Login Credentials", "Class" => "error_popup");

                    return $this->response->setJSON($message);
                }
            }
        }
    }


    public function login_modal()
    {
        

        $uri = $this->request->getUri();   
        $data['email_id'] =  $uri->getSegment(3);

        if ($this->request->getGet('id')) {
            $data['detail_page'] = $this->request->getGet('id');
        } else {
            $data['detail_page'] = '';
        }

        $add_view = view('Modules\Login\Views\login-modal', $data);
        $data = array("StatusCode" => 9, "Message" => $add_view, 'class' => 'success_popup', "Reload" => "false");
        return $this->response->setJSON($data);
    }

    public function password_reset()
    {
        if ($this->request->getMethod() == 'POST') {
            $rules = $this->validate([
                'email_id' => [
                    'label' => 'Email',
                    'rules' => 'trim|required|valid_email',
                    'errors' => [
                        'required' => 'Please enter your email id.',
                        'valid_email' => 'Please enter a valid email id.'
                    ]
                ],

                'user_password' => [
                    'label' => 'Password',
                    'rules' => 'trim|required|min_length[8]|regex_match[/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/]',
                    'errors' => [
                        'required' => 'Please enter your password.',
                        'min_length' => 'Password must be at least 8 digits',
                        'regex_match' => 'The password must contain at least one uppercase letter, one lowercase letter, one number, one special character.'
                    ]
                ],

                'otp' => [
                    'label' => 'otp',
                    'rules' => 'required|numeric',
                    'errors' => [
                        'required' => 'Please enter otp.',

                    ]
                ]

            ]);
            if (!$rules) {
                $errors = $this->validator->getErrors();
                $data_validation = array("StatusCode" => 1, "ErrorMessage" => array_filter($errors));
                return $this->response->setJSON($data_validation);
            } else {
                $data['OTP'] = trim($this->request->getPost('otp'));
                ;
                $data['BType'] = 'B2C';
                $data['Service'] = 'forgetPassword';
                $LoginModel = new LoginModel();
                $response = $LoginModel->verify_otp($data);
                if ($response) {
                    $OTPexpiretime = $response['otp_expiery'];
                    $currentTime = create_date();
                    if ($OTPexpiretime > $currentTime) {
                        $LoginModel->deleteData('temp_otp_logs', $response['id']);

                        $password = md5($this->request->getPost('user_password'));
                        $customer_data['password'] = $password;
                        $email_id = $this->request->getPost('email_id');
                        /*end password encryption*/

                        $customer_data['modified'] = create_date();
                        $added_data = $LoginModel->where("email_id", $email_id)->set($customer_data)->update();

                        $message = array("StatusCode" => 0, "Message" => "password  successfully changed", "Class" => "success_popup");

                        $this->session->setFlashdata('Message', $message);
                        return $this->response->setJSON($message);
                    } else {
                        $message = array("StatusCode" => 3, "Message" => " OTP Expired", "Class" => "error_popup");
                        return $this->response->setJSON($message);
                    }

                } else {
                    $message = array("StatusCode" => 3, "Message" => "Invalid OTP", "Class" => "error_popup");

                    return $this->response->setJSON($message);
                }
            }
        }
    }



    public function access_account()
    {

        if ($this->request->getMethod() == 'get') {
            $this->session->remove('wl_customer');
            $this->session->remove('comapny_detail');
            $this->session->remove('admin_user_details');

            $uri = $this->request->getUri();   
            $access_token =  dev_decode($uri->getSegment(2));

           

            $access_token = explode('-', $access_token);


            if (isset($access_token[0]) && isset($access_token[1]) && isset($access_token[2])) {
                $token_ip = $access_token[2];
                $UserIp = $this->request->getIpAddress();
                if ($UserIp == $token_ip) {
                    $login_email = $access_token[0];
                    $customer_id = $access_token[1];
                    $loginmodel = new LoginModel();
                    $user = $loginmodel->where('email_id', $login_email)->where('id', $customer_id)->where('status', 'active')->first();
                    if ($user) {
                        $this->session->set('wl_customer', $user);
                        $message = array("StatusCode" => 0, "Message" => "You have successfully logged in", "Class" => "success_popup");

                        $LoginLogsModel = new LoginLogsModel();
                        $customer = $this->request->getUserAgent();
                        $loginLogs = [
                            "web_partner_id" => web_partner_details['id'],
                            "user_id" => $user['id'],
                            "user_name" => $user['first_name'],
                            "login_browser" => $customer->getBrowser() . ' ' . $customer->getVersion(),
                            'platform' => $customer->getPlatform(),
                            'role' => 'Customer',
                            "login_time" => create_date(),
                            "login_ip_address" => $this->request->getIpAddress()
                        ];

                        $LoginLogsModel->insert($loginLogs);
                        $this->session->setFlashdata('Message', $message);
                        return redirect()->to(site_url('/flight'));
                    } else {
                        $message = array("StatusCode" => 1, "Message" => "Invalid Login Credentials", "Class" => "error_popup");
                    }
                    $this->session->setFlashdata('Message', $message);
                    return redirect()->to(site_url('/login'));
                } else {
                    $message = array("StatusCode" => 1, "Message" => "access token invalid", "Class" => "error_popup");
                    $this->session->setFlashdata('Message', $message);
                    return redirect()->to(site_url('/login'));
                }
            } else {
                $message = array("StatusCode" => 1, "Message" => "Invalid Login Credentials", "Class" => "error_popup");
                $this->session->setFlashdata('Message', $message);
                return redirect()->to(site_url('/login'));
            }
        }
    }


    public function signout()
    {
        $this->session->remove('wl_customer');
        return redirect()->to(site_url('/'));
    }


    public function auth_init(){
        $jsonStr = file_get_contents('php://input'); 
        $jsonObj = json_decode($jsonStr); 
 
            if(!empty($jsonObj->request_type) && $jsonObj->request_type == 'user_auth'){ 
            $credential = !empty($jsonObj->credential)?$jsonObj->credential:''; 
            list($header, $payload, $signature) = explode (".", $credential); 
            $responsePayload = json_decode(base64_decode($payload)); 
            if(!empty($responsePayload)){ 
                /*  (
                    [iss] => https://accounts.google.com
                    [azp] => 30072294180-dojtebg89hp7mqn5f6kf30ca7lmalgk4.apps.googleusercontent.com
                    [aud] => 30072294180-dojtebg89hp7mqn5f6kf30ca7lmalgk4.apps.googleusercontent.com
                    [sub] => 114542448200264138789
                    [email] => vishalmasthead@gmail.com
                    [email_verified] => 1
                    [nbf] => 1716796184
                    [name] => vishal singh
                    [picture] => https://lh3.googleusercontent.com/a/ACg8ocJAAyN_JZ4QQ-VxXo8W0NnMyDkOWfM2vxrH5ea1PVGOydCLQw=s96-c
                    [given_name] => vishal
                    [family_name] => singh
                    [iat] => 1716796484 * 
                    [exp] => 1716800084 *
                    [jti] => 3fb2d1cc5ee740b581a288a9ee7f43aacfe415ec * 
                ) */
                // old code 
                $oauth_provider = 'google'; 
                $oauth_uid  = !empty($responsePayload->sub)?$responsePayload->sub:''; 
                $first_name = !empty($responsePayload->given_name)?$responsePayload->given_name:''; 
                $last_name  = !empty($responsePayload->family_name)?$responsePayload->family_name:''; 
                $email      = !empty($responsePayload->email)?$responsePayload->email:''; 
                $picture    = !empty($responsePayload->picture)?$responsePayload->picture:''; 
             
                $loginmodel = new LoginModel();
                $check_user_exists = $loginmodel->googleusercheck($email,$oauth_uid, $oauth_provider,web_partner_details['id']);
                if(!empty($check_user_exists)){
                    if($check_user_exists['google_token'] == ""){
                        $this->session->set('wl_customer', $check_user_exists);
                        $message = array("StatusCode" => 0, "Message" => "You have successfully logged in", "Class" => "success_popup");
                        $LoginLogsModel = new LoginLogsModel();
                        $customer = $this->request->getUserAgent();
                        $loginLogs = [
                            "web_partner_id" => web_partner_details['id'],
                            "user_id" => $check_user_exists['id'],
                            "user_name" => $check_user_exists['first_name'],
                            "login_browser" => $customer->getBrowser() . ' ' . $customer->getVersion(),
                            'platform' => $customer->getPlatform(),
                            'role' => 'Customer',
                            "login_time" => create_date(),
                            "login_ip_address" => $this->request->getIpAddress()
                        ];
                        $LoginLogsModel->insert($loginLogs);
                        $this->session->setFlashdata('Message', $message);
                        return $this->response->setJSON($message);
                    }else if($check_user_exists['google_token'] != ""){
                        $this->session->set('wl_customer', $check_user_exists);
                        $message = array("StatusCode" => 0, "Message" => "You have successfully logged in", "Class" => "success_popup");
                        $LoginLogsModel = new LoginLogsModel();
                        $customer = $this->request->getUserAgent();
                        $loginLogs = [
                            "web_partner_id" => web_partner_details['id'],
                            "user_id" => $check_user_exists['id'],
                            "user_name" => $check_user_exists['first_name'],
                            "login_browser" => $customer->getBrowser() . ' ' . $customer->getVersion(),
                            'platform' => $customer->getPlatform(),
                            'role' => 'Customer',
                            "login_time" => create_date(),
                            "login_ip_address" => $this->request->getIpAddress()
                        ];
                        $LoginLogsModel->insert($loginLogs);
                        $this->session->setFlashdata('Message', $message);
                        return $this->response->setJSON($message);
                    }
                }else{
                    //if now user found with this email id then signup and saving user data in user table and account logs 
                        $customer_data['email_id'] = $email;
                        $customer_data['mobile_verify'] = 0;
                        $customer_data['first_name'] = $first_name;
                        $customer_data['last_name'] =  $last_name;
                        $directory = realpath(FCPATH . '../whitelabel/uploads/customer/thumbnail/') . '/';
                        $directory_outer = realpath(FCPATH . '../whitelabel/uploads/customer/') . '/';
                        $filename = $email . "_" . $first_name . "_" . $last_name . ".jpg"; 
                        $ch = curl_init();
                        curl_setopt($ch, CURLOPT_URL, $picture);
                        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                        $imageContent = curl_exec($ch);
                        if ($imageContent === false) {
                            echo "Error fetching image: " . curl_error($ch);
                        } else {
                            $savePath = $directory . $filename;
                            $result = file_put_contents($savePath, $imageContent);
                            $savePath = $directory_outer . $filename;
                            $result_outer = file_put_contents($savePath, $imageContent);

                            if($result == false || $result_outer == false){
                                $message = array("StatusCode" => 1, "Message" => "Internal error while loggin", "Class" => "error_popup");
                                $this->session->setFlashdata('Message', $message);
                                return $this->response->setJSON($message);
                            }
                        }
                        curl_close($ch);
                        $customer_data['profile_pic'] =  $filename;
                        $customer_data['email_verify'] = 1;
                        $customer_data['status'] = 'active';
                        $customer_data['web_partner_id'] = web_partner_details['id'];
                        $customer_data['google_token'] = $oauth_uid;
                        $customer_data['created'] = create_date();
                        $wl_customer_id = $loginmodel->insert($customer_data);
                        $updateData['customer_id'] = web_partner_details['customer_pre_fix'] . $wl_customer_id;
                        $loginmodel->where(array("web_partner_id" => web_partner_details['id'], "id" => $wl_customer_id))->set($updateData)->update();
                        $log['customer_id'] = $wl_customer_id;
                        $log['web_partner_id'] = web_partner_details['id'];
                        $log['created'] = create_date();
                        $log['action_type'] = 'credit';
                        $data['credit'] = 0;
                        $log['balance'] = 0;
                        $log['remark'] = 'Account created with 0 balance';
                        $log['payment_mode'] = 'Temporary_Credit';
                        $CustomerAccountLogModel = new CustomerAccountLogModel();
                        $account_log_added = $CustomerAccountLogModel->insert($log);
                        $loginmodel = new LoginModel();
                        $user = $loginmodel->where('email_id', $customer_data['email_id'])->where('web_partner_id', web_partner_details['id'])->where('status', 'active')->where('google_token', $oauth_uid)->first();
                        $this->session->set('wl_customer', $user);
                        $message = array("StatusCode" => 0, "Message" => "You have successfully logged in", "Class" => "success_popup");
                        $this->session->setFlashdata('Message', $message);
                        return $this->response->setJSON($message);
                }
                // old code 


                    //new code starts here 
                   

                    //new code ends here 
                }
            }
            
    }


    public function google_oauth(){


      
        // $google_oauth_client_id = '1076689055174-9t3jns5vf60vgtt2eb57do83j73cbkf8.apps.googleusercontent.com';
        // $google_oauth_client_secret = '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';
        // $google_oauth_redirect_uri = 'https://www.travetrips.com/login/google-oauth';
        // $google_oauth_version = 'v3';

        $google_oauth_client_id = whitelabel['google_login_auth_key'];
        $google_oauth_client_secret = whitelabel['google_oauth_client_secret'];
        $google_oauth_redirect_uri =  site_url('login/google-oauth');
        $google_oauth_version = whitelabel['google_oauth_version'];
 

        if (isset($_GET['code']) && !empty($_GET['code'])) {
            $params = [
                'code' => $_GET['code'],
                'client_id' => $google_oauth_client_id,
                'client_secret' => $google_oauth_client_secret,
                'redirect_uri' => $google_oauth_redirect_uri,
                'grant_type' => 'authorization_code'
            ];
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, 'https://accounts.google.com/o/oauth2/token');
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($params));
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $response = curl_exec($ch);
            curl_close(handle: $ch);
            $response = json_decode($response, true);
            if (isset($response['access_token']) && !empty($response['access_token'])) {
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, 'https://www.googleapis.com/oauth2/' . $google_oauth_version . '/userinfo');
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_HTTPHEADER, ['Authorization: Bearer ' . $response['access_token']]);
                $response = curl_exec($ch);
                curl_close($ch);
                $profile = json_decode($response, true);
                if (isset($profile['email'])) {
                    $email = $profile['email'];
                    $oauth_provider = 'google'; 
                    $oauth_uid  = isset($profile['sub']) ? $profile['sub'] : ''; 
                    $first_name =  isset($profile['given_name']) ? $profile['given_name'] : ''; 
                    $last_name  =  isset($profile['family_name']) ? $profile['family_name'] : ''; 
                    $picture    =  isset($profile['picture']) ? $profile['picture'] : ''; 
                    $loginmodel = new LoginModel();
                    $check_user_exists = $loginmodel->googleusercheck($email,$oauth_uid, $oauth_provider,web_partner_details['id']);
                    if(!empty($check_user_exists)){
                        if($check_user_exists['google_token'] == ""){
                            $this->session->set('wl_customer', $check_user_exists);
                            $message = array("StatusCode" => 0, "Message" => "You have successfully logged in", "Class" => "success_popup");
                            $LoginLogsModel = new LoginLogsModel();
                            $customer = $this->request->getUserAgent();
                            $loginLogs = [
                                "web_partner_id" => web_partner_details['id'],
                                "user_id" => $check_user_exists['id'],
                                "user_name" => $check_user_exists['first_name'],
                                "login_browser" => $customer->getBrowser() . ' ' . $customer->getVersion(),
                                'platform' => $customer->getPlatform(),
                                'role' => 'Customer',
                                "login_time" => create_date(),
                                "login_ip_address" => $this->request->getIpAddress()
                            ];
                            $LoginLogsModel->insert($loginLogs);

                            $message = array("StatusCode" => 3, "Message" => "You have successfully logged in", "Class" => "success_popup");
                            $this->session->setFlashdata('Message', $message);

                            return redirect()->to(site_url());


                        }else if($check_user_exists['google_token'] != ""){
                            $this->session->set('wl_customer', $check_user_exists);
                            $message = array("StatusCode" => 0, "Message" => "You have successfully logged in", "Class" => "success_popup");
                            $LoginLogsModel = new LoginLogsModel();
                            $customer = $this->request->getUserAgent();
                            $loginLogs = [
                                "web_partner_id" => web_partner_details['id'],
                                "user_id" => $check_user_exists['id'],
                                "user_name" => $check_user_exists['first_name'],
                                "login_browser" => $customer->getBrowser() . ' ' . $customer->getVersion(),
                                'platform' => $customer->getPlatform(),
                                'role' => 'Customer',
                                "login_time" => create_date(),
                                "login_ip_address" => $this->request->getIpAddress()
                            ];
                            $LoginLogsModel->insert($loginLogs);
                           

                           $message = array("StatusCode" => 3, "Message" => "You have successfully logged in", "Class" => "success_popup");
                            $this->session->setFlashdata('Message', $message);
                            return redirect()->to(site_url());

                        }
                    }else{
                        //if now user found with this email id then signup and saving user data in user table and account logs 
                            $customer_data['email_id'] = $email;
                            $customer_data['mobile_verify'] = 0;
                            $customer_data['first_name'] = $first_name;
                            $customer_data['last_name'] =  $last_name;
                            $directory = realpath(FCPATH . '../whitelabel/uploads/customer/thumbnail/') . '/';
                            $directory_outer = realpath(FCPATH . '../whitelabel/uploads/customer/') . '/';
                            $filename = $email . "_" . $first_name . "_" . $last_name . ".jpg"; 
                            $ch = curl_init();
                            curl_setopt($ch, CURLOPT_URL, $picture);
                            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                            $imageContent = curl_exec($ch);
                            if ($imageContent === false) {
                                echo "Error fetching image: " . curl_error($ch);
                            } else {
                                $savePath = $directory . $filename;
                                $result = file_put_contents($savePath, $imageContent);
                                $savePath = $directory_outer . $filename;
                                $result_outer = file_put_contents($savePath, $imageContent);
    
                                if($result == false || $result_outer == false){
                                    $message = array("StatusCode" => 1, "Message" => "Internal error while loggin", "Class" => "error_popup");
                                    $this->session->setFlashdata('Message', $message);
                                    return $this->response->setJSON($message);
                                }
                            }
                            curl_close($ch);
                            $customer_data['profile_pic'] =  $filename;
                            $customer_data['email_verify'] = 1;
                            $customer_data['status'] = 'active';
                            $customer_data['web_partner_id'] = web_partner_details['id'];
                            $customer_data['google_token'] = $oauth_uid;
                            $customer_data['created'] = create_date();
                            $wl_customer_id = $loginmodel->insert($customer_data);
                            $updateData['customer_id'] = web_partner_details['customer_pre_fix'] . $wl_customer_id;
                            $loginmodel->where(array("web_partner_id" => web_partner_details['id'], "id" => $wl_customer_id))->set($updateData)->update();
                            $log['customer_id'] = $wl_customer_id;
                            $log['web_partner_id'] = web_partner_details['id'];
                            $log['created'] = create_date();
                            $log['action_type'] = 'credit';
                            $data['credit'] = 0;
                            $log['balance'] = 0;
                            $log['remark'] = 'Account created with 0 balance';
                            $log['payment_mode'] = 'Temporary_Credit';
                            $CustomerAccountLogModel = new CustomerAccountLogModel();
                            $account_log_added = $CustomerAccountLogModel->insert($log);
                            $loginmodel = new LoginModel();
                            $user = $loginmodel->where('email_id', $customer_data['email_id'])->where('web_partner_id', web_partner_details['id'])->where('status', 'active')->where('google_token', $oauth_uid)->first();
                            $this->session->set('wl_customer', $user);
                            $message = array("StatusCode" => 0, "Message" => "You have successfully logged in", "Class" => "success_popup");
                            $this->session->setFlashdata('Message', $message);
                            return $this->response->setJSON($message);
                    }
                    //new code to save data in database ends here 
                } else {

                    $message = array("StatusCode" => 3, "Message" => "Could not retrieve profile information! Please try again later!", "Class" => "error_popup");
                    return $this->response->setJSON($message);
                }
            } else {
                $message = array("StatusCode" => 3, "Message" => "Invalid access token! Please try again later!", "Class" => "error_popup");
                return $this->response->setJSON($message);
            }
        } else {
            $params = [
                'response_type' => 'code',
                'client_id' => $google_oauth_client_id,
                'redirect_uri' => $google_oauth_redirect_uri,
                'scope' => 'https://www.googleapis.com/auth/userinfo.email https://www.googleapis.com/auth/userinfo.profile',
                'access_type' => 'offline',
                'prompt' => 'consent'
            ];
            header('Location: https://accounts.google.com/o/oauth2/auth?' . http_build_query($params));
            exit;
        }
    }


    public function facebook_oauth(){
        // $facebook_oauth_app_id = '493405097020017';
        // $facebook_oauth_app_secret = '3367bb479edb8aa24227e3b11ce90e89';
        // $facebook_oauth_redirect_uri = 'https://www.travetrips.com/login/facebook-oauth';  
        // $facebook_oauth_version = 'v21.0';

        $facebook_oauth_app_id = whitelabel['facebook_login_auth_key'];
        $facebook_oauth_app_secret = whitelabel['facebook_oauth_app_secret'];
        $facebook_oauth_redirect_uri = site_url('login/facebook-oauth');
        $facebook_oauth_version = whitelabel['facebook_oauth_version'];

       
        if (isset($_GET['code']) && !empty($_GET['code'])) {
            
        } else {
            $params = [
                'client_id' => $facebook_oauth_app_id,
                'redirect_uri' => $facebook_oauth_redirect_uri,
                'response_type' => 'code',
                'scope' => 'email'
            ];
            header('Location: https://www.facebook.com/dialog/oauth?' . http_build_query($params));
            exit;
        }

        $params = [
            'client_id' => $facebook_oauth_app_id,
            'client_secret' => $facebook_oauth_app_secret,
            'redirect_uri' => $facebook_oauth_redirect_uri,
            'code' => $_GET['code']
        ];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://graph.facebook.com/oauth/access_token');
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($params));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);
        curl_close($ch);
        $response = json_decode($response, true);

        if (isset($response['access_token']) && !empty($response['access_token'])) {
            // Execute cURL request to retrieve the user info associated with the Facebook account
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, 'https://graph.facebook.com/' . $facebook_oauth_version . '/me?fields=name,email,picture');
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, ['Authorization: Bearer ' . $response['access_token']]);
            $response = curl_exec($ch);
            curl_close($ch);
            $profile = json_decode($response, true);
        
            //profile data exists
            if (isset($profile['email'])) {

                //new code is here 
                $oauth_provider = 'facebook';
                $oauth_uid = isset($profile['id']) ? $profile['id'] : '';
                $facebookUsername = isset($profile['name']) ? $profile['name'] : '';
                $email = isset($profile['email']) ? $profile['email'] : '';
                $picture = isset($profile['picture']['data']['url']) ? $profile['picture']['data']['url'] : '';

                $array_where = ["facebook_token" => $oauth_uid, "oauth_provider" => $oauth_provider, "web_partner_id" => web_partner_details['id']];

                $loginmodel = new LoginModel();
                $check_user_exists = $loginmodel->googleusercheck($email, $oauth_uid, $oauth_provider, web_partner_details['id']);
                $username = explode(" ", $facebookUsername);
                $first_name = $username[0];
                $last_name = $username[1];

                if (!empty($check_user_exists)) {
                    if ($check_user_exists['facebook_token'] == "") {
                        $this->session->set('wl_customer', $check_user_exists);
                        $LoginLogsModel = new LoginLogsModel();
                        $customer = $this->request->getUserAgent();
                        $loginLogs = [
                            "web_partner_id" => web_partner_details['id'],
                            "user_id" => $check_user_exists['id'],
                            "user_name" => $check_user_exists['first_name'],
                            "login_browser" => $customer->getBrowser() . ' ' . $customer->getVersion(),
                            'platform' => $customer->getPlatform(),
                            'role' => 'Customer',
                            "login_time" => create_date(),
                            "login_ip_address" => $this->request->getIpAddress()
                        ];
                        $LoginLogsModel->insert($loginLogs);
                      

                        $message = array("StatusCode" => 3, "Message" => "You have successfully logged in", "Class" => "success_popup");
                        $this->session->setFlashdata('Message', $message);
                        return redirect()->to(site_url());


                    } else if ($check_user_exists['facebook_token'] != "") {
                        $this->session->set('wl_customer', $check_user_exists);
                      
                        $LoginLogsModel = new LoginLogsModel();
                        $customer = $this->request->getUserAgent();
                        $loginLogs = [
                            "web_partner_id" => web_partner_details['id'],
                            "user_id" => $check_user_exists['id'],
                            "user_name" => $check_user_exists['first_name'],
                            "login_browser" => $customer->getBrowser() . ' ' . $customer->getVersion(),
                            'platform' => $customer->getPlatform(),
                            'role' => 'Customer',
                            "login_time" => create_date(),
                            "login_ip_address" => $this->request->getIpAddress()
                        ];
                        $LoginLogsModel->insert($loginLogs);
                     
                        $message = array("StatusCode" => 3, "Message" => "You have successfully logged in", "Class" => "success_popup");
                        $this->session->setFlashdata('Message', $message);
                        return redirect()->to(site_url());
                    }
                } else {
                    $customer_data['email_id'] = $email;
                    $customer_data['mobile_verify'] = 0;
                    $customer_data['first_name'] = isset($first_name) ? $first_name : "";
                    $customer_data['last_name'] = isset($last_name) ? $last_name : "";
                    $customer_data['oauth_provider'] = 'facebook';
                    $directory = realpath(FCPATH . '../whitelabel/uploads/customer/thumbnail/') . '/';
                    $directory_outer = realpath(FCPATH . '../whitelabel/uploads/customer/') . '/';
                    $filename = $email . "_" . $first_name . "_" . $last_name . ".jpg";
                    $ch = curl_init();
                    curl_setopt($ch, CURLOPT_URL, $picture);
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                    $imageContent = curl_exec($ch);
                    if ($imageContent === false) {
                        echo "Error fetching image: " . curl_error($ch);
                    } else {
                        $savePath = $directory . $filename;
                    
                        $result = file_put_contents($savePath, $imageContent);
                        $savePath = $directory_outer . $filename;
                        $result_outer = file_put_contents($savePath, $imageContent);
        
                        if ($result == false || $result_outer == false) {
                            $message = array("StatusCode" => 1, "Message" => "Internal error while loggin", "Class" => "error_popup");
                            $this->session->setFlashdata('Message', $message);
                            return $this->response->setJSON($message);
                        }
                    }
                    curl_close($ch);
                    $customer_data['profile_pic'] = $filename;
                    $customer_data['email_verify'] = 1;
                    $customer_data['status'] = 'active';
                    $customer_data['web_partner_id'] = web_partner_details['id'];
                    $customer_data['facebook_token'] = $oauth_uid;
                    $customer_data['created'] = create_date();
                    $wl_customer_id = $loginmodel->insert($customer_data);
                    $updateData['customer_id'] = web_partner_details['customer_pre_fix'] . $wl_customer_id;
                    $loginmodel->where(array("web_partner_id" => web_partner_details['id'], "id" => $wl_customer_id))->set($updateData)->update();
                    $log['customer_id'] = $wl_customer_id;
                    $log['web_partner_id'] = web_partner_details['id'];
                    $log['created'] = create_date();
                    $log['action_type'] = 'credit';
                    $data['credit'] = 0;
                    $log['balance'] = 0;
                    $log['remark'] = 'Account created with 0 balance';
                    $log['payment_mode'] = 'Temporary_Credit';
                    $CustomerAccountLogModel = new CustomerAccountLogModel();
                    $account_log_added = $CustomerAccountLogModel->insert($log);
                    $loginmodel = new LoginModel();
                    $user = $loginmodel->where('email_id', $customer_data['email_id'])->where('web_partner_id', web_partner_details['id'])->where('status', 'active')->where('facebook_token', $oauth_uid)->first();
                    $this->session->set('wl_customer', $user);
                    $message = array("StatusCode" => 3, "Message" => "You have successfully logged in", "Class" => "success_popup");
                    $this->session->setFlashdata('Message', $message);
                    return redirect()->to(site_url());
                }
            } else {
                     $message = array("StatusCode" => 3, "Message" => "Could not retrieve profile information! Please try again later!", "Class" => "error_popup");
                    return $this->response->setJSON($message);
            }
        } else {
            $message = array("StatusCode" => 3, "Message" => "Invalid access token! Please try again later!", "Class" => "error_popup");
            return $this->response->setJSON($message);
        }

    }
}


