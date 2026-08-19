<?php

namespace Modules\Login\Controllers;

use CodeIgniter\I18n\Time;
use App\Modules\Login\Models\LoginModel;
use App\Controllers\BaseController;
use App\Modules\Login\Models\WebPartnerModel;
use App\Modules\Login\Models\LoginLogsModel;


class Login extends BaseController
{
    public $title;
    public $template_id_forgot_password_otp;
    public $template_id_mobile_verification_otp;
    public $template_id_user_credientils;
    public $template_id_user_id;
    public $template_id_password_reset;
    public function __construct()
    {
        $this->title = "Admin Login";
        helper('Modules\Login\Helpers\login');
        $this->template_id_forgot_password_otp = "";
        $this->template_id_mobile_verification_otp = "";
        $this->template_id_user_credientils = "";
        $this->template_id_user_id = "";
        $this->template_id_forgot_password_otp = "";
        $this->template_id_password_reset = "";
    }



    public function index()
    {
        // Get the fingerprint
        if ($this->session->get('admin_user')) {
            return redirect()->to(site_url('/dashboard'));
        }


        if ($this->request->getMethod() == 'POST') {
            $rules = $this->validate([
                'user_email' => [
                    'label' => 'Email',
                    'rules' => 'required|valid_email|trim',
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
                $response = [
                    'success' => false,
                    'message' => $this->validation->getErrors(),
                    'status_code' => 109
                ];
                return $this->response->setJSON($response);
            }
            $email = $this->request->getVar('user_email');
            $password = $this->request->getVar('user_password');

            $deviceId = trim($this->request->getPost('deviceId'));

            $loginmodel = new LoginModel();
            $user = $loginmodel->where('login_email', $email)->where('web_partner_id', whitelabel['web_partner_id'])->where('password', md5($password))->first();
           
            if ($user) {

                $existDevice = explode(",", $user['trusted_device']);
                if (in_array($deviceId, $existDevice)) {
                    $trusted_user = 1;
                } else {
                    $trusted_user = 0;
                }


                $user_status = $user['status'];
                if ($user_status == "active") {
                    //new code to send otp on email address and moblie number starts here 
                    if (super_admin_website_setting['admin_login_otp'] == true) {
                        if ($trusted_user === 1) {
                            $this->session->set('admin_user', $user);
                            $message = array("StatusCode" => 0, "Message" => "You have successfully logged in", "Class" => "success_popup");
                            $this->session->setFlashdata('Message', $message);
                            $LoginLogsModel = new LoginLogsModel();
                            $agent = $this->request->getUserAgent();
                            $loginLogs = [
                                "web_partner_id" => $user['web_partner_id'],
                                "user_id" => $user['id'],     
                                "user_name" => $user['first_name'] . ' ' . $user['last_name'],
                                "mobile_no" => $user['mobile_no'],
                                "email_id" => $user['login_email'],
                                "login_browser" => $agent->getBrowser() . ' ' . $agent->getVersion(),
                                'platform' => $agent->getPlatform(),
                                'role' => 'WebPartner',
                                'login_source' => 'Website',
                                'btype' => 'WEB',
                                "login_time" => create_date(),
                                "login_ip_address" => $this->request->getIpAddress()
                            ]; 
                            $LoginLogsModel->insert($loginLogs);

                            $response = [
                                'success' => true,
                                'status_code' => 101,
                                'message' => site_url('/dashboard'),
                            ];
                            return $this->response->setJSON($response);
                        } else {
                            $current_time = create_date();
                            $otp_expiry = $current_time + 120;
                            $agent_mobile_number = "91" . $user['mobile_no'];
                            $otp = mt_rand(1000, 9999);
                            $data['otp'] = $otp;

                            $dataTemp = array(
                                "web_partner_id" => super_admin_website_setting['id'],
                                "username" => $email,
                                "otp" => $otp,
                                "btype" => "Admin",
                                "service" => "Login",
                                "created" => $current_time,
                                "otp_expiery" => $otp_expiry,
                            );
                            $temp_otp_table_data =  $loginmodel->InsertData($dataTemp);
                            $this->session->set('login_verification_otp', $otp);
                            $this->session->set('user_detail', $user);
                            $agent_mobile = $user['mobile_no'];
                            $agent_email = $user['login_email'];
                            $Emailmessage = "Dear Travel Partner,
                        Your OTP to Login : {$otp} For security reasons, DO NOT SHARE the OTP with anyone. Regards Expertztrip  Pvt Ltd";
                            $data['otp'] = $otp;
                            $Emailmessage = view('Views/emails/otp-emails.php', $data);
                            send_email($email, $subject = "Login verification OTP", $Emailmessage, $email_type = 'Login verifcation', $attachment = null);

                            $email_hidden = substr($email, 0, 5) . str_repeat('*', strlen($email) - 5);
                            $mobile_hidden = substr($user['mobile_no'], 0, 3) . str_repeat('*', strlen($user['mobile_no']) - 8) . substr($user['mobile_no'], -5);

                            $response = [
                                'success' => true,
                                'message' => 'OTP Sent successfully!',
                                // 'temp_otp' => $otp,
                                'otp_message' => 'A One-Time-Password has been sent to <span style="color: red;">' . $email_hidden . '</span> and <span style="color: red;">' . $mobile_hidden . '</span>',
                                'email' => $email,
                                'pwd' => $password,
                                'InsertedId' => $temp_otp_table_data,
                                'status_code' => 111
                            ];
                            return $this->response->setJSON($response);
                        }
                    } else if (super_admin_website_setting['admin_login_otp'] != true) {
                        $this->session->set('admin_user', $user);
                        $message = array("StatusCode" => 0, "Message" => "You have successfully logged in", "Class" => "success_popup");
                        $this->session->setFlashdata('Message', $message);
                        $LoginLogsModel = new LoginLogsModel();
                        $agent = $this->request->getUserAgent();
                        $loginLogs = [
                            "web_partner_id" => $user['web_partner_id'],
                            "user_id" => $user['id'],     
                            "user_name" => $user['first_name'] . ' ' . $user['last_name'],
                            "mobile_no" => $user['mobile_no'],
                            "email_id" => $user['login_email'],
                            "login_browser" => $agent->getBrowser() . ' ' . $agent->getVersion(),
                            'platform' => $agent->getPlatform(),
                            'role' => 'WebPartner',
                            'login_source' => 'Website',
                            'btype' => 'WEB',
                            "login_time" => create_date(),
                            "login_ip_address" => $this->request->getIpAddress()
                        ];
                        $LoginLogsModel->insert($loginLogs);

                        $response = [
                            'success' => true,
                            'status_code' => 101,
                            'message' => site_url('/dashboard'),
                        ];
                        return $this->response->setJSON($response);
                    }
                } else {
                    $response = [
                        'success' => false,
                        'message' => 'Your account has been not activated yet.Please contact your system administrator',
                        'status_code' => 1
                    ];
                    return $this->response->setJSON($response);
                }
            } else {
                $response = [
                    'success' => false,
                    'message' => 'Invalid Login Credentials',
                    'status_code' => 1
                ];
                return $this->response->setJSON($response);
            }
            $this->session->setFlashdata('Message', $message);
            return redirect()->to(site_url('/login'));
        }


        $this->session->remove('admin_comapny_detail');
        $this->session->remove('admin_user_details');
        $this->session->remove('whitelabel_setting_data');
        $data = [
            'title' => $this->title,
            'validation' => $this->validation
        ];
        return view('Modules\Login\Views\login', $data);
    }


      

    public function access_account()
    {
        if ($this->request->getMethod() == 'get') {
            $this->session->remove('admin_user');
            $this->session->remove('comapny_detail');
            $this->session->remove('admin_user_details');

            $uri = $this->request->getUri();   
         
            $access_token = dev_decode_direct_access($uri->getSegment(2));
            $access_token = explode('-', $access_token);
            if (isset($access_token[0]) && isset($access_token[1]) && isset($access_token[2])) {
                $token_ip = $access_token[2];
                $UserIp = $this->request->getIpAddress();
                if ($UserIp == $token_ip) {
                    $login_email = $access_token[0];
                    $web_partner_id = $access_token[1];
                    $loginmodel = new LoginModel();
                    $user = $loginmodel->where('login_email', $login_email)->where('web_partner_id', $web_partner_id)->where('primary_user', 1)->first();
                    if ($user) {
                        $user['accessBySuperAdmin'] = 1;
                        $this->session->set('admin_user', $user);
                        $message = array("StatusCode" => 0, "Message" => "You have successfully logged in", "Class" => "success_popup");
                        $this->session->setFlashdata('Message', $message);
                        return redirect()->to(site_url('/dashboard'));
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

    public function forgot_password()
    {
        $rules = $this->validate([
            'registered_email' => [
                'label' => 'registered email',
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
        $email = trim($this->request->getPost('registered_email'));
        $loginmodel = new LoginModel();
        $user = $loginmodel->where('login_email', $email)->first();
        if ($user) {
            $otp = mt_rand(1000, 9999);
            $this->session->set('forgot_password_email', $email);
            $this->session->set('forgot_password_otp', $otp);
            $data['otp'] = $otp;
            $message = view('Views/emails/otp-emails.php', $data);
            $Otp_message = "OTP to reset your login password is {$otp}. Please DO NOT SHARE the OTP with anyone. If not requested by you, please contact immediately on";
            $to_mob = $user['mobile_no'];
            $tempid = $this->template_id_forgot_password_otp;
            $sms_type = 'forgot password';
            send_sms($to_mob, $Otp_message, $tempid, $sms_type);
            send_email($email, $subject = "Forgot Password", $message, $email_type = 'Forgot Password', $attachment = null);
            $message = array("StatusCode" => 0, "Message" => "OTP Sent", /* 'temp_otp' => $otp, */ 'class' => 'success_popup', 'Reload' => 'false');
            return $this->response->setJSON($message);
        } else {
            $errors['registered_email'] = "Email does not exists";
            $data_validation = array("StatusCode" => 1, "ErrorMessage" => array_filter($errors), 'Reload' => 'false');
            return $this->response->setJSON($data_validation);
        }
    }

    public function validate_otp_password()
    {
        $rules = $this->validate([
            'OTP' => [
                'label' => 'OTP',
                'rules' => 'trim|required',
                'errors' => [
                    'required' => 'Please enter OTP.',

                ]
            ],
            'new_password' => [
                'label' => 'new password',
                'rules' => 'required|min_length[8]',
                'errors' => [
                    'required' => 'Please enter new password.',
                    'min_length' => 'Password must be at least 8 digits'
                ]
            ]
        ]);
        if (!$rules) {
            $errors = $this->validator->getErrors();
            $data_validation = array("StatusCode" => 1, "ErrorMessage" => array_filter($errors));
            return $this->response->setJSON($data_validation);
        } else {
            $StatusCode = 1;
            $data = $this->request->getPost();
            $forgot_password_otp = session()->get('forgot_password_otp');
            $email = session()->get('forgot_password_email');
            if ($forgot_password_otp == $data['OTP']) {
                $StatusCode = 0;
                $loginmodel = new LoginModel();
                $update_password = [
                    "password" => md5($data['new_password'])
                ];
                $loginmodel->where("login_email", $email)->set($update_password)->update();
                $this->session->remove('forgot_password_email');
                $this->session->remove('forgot_password_otp');
            }
            if ($StatusCode == 0) {
                $loginmodel = new LoginModel();
                $user = $loginmodel->where('login_email', $email)->first();
                $WebPartnerModel = new WebPartnerModel();
                $WebPartnerModel = $WebPartnerModel->web_partner_list_details($user['web_partner_id']);
                $company_name = $WebPartnerModel['company_name'];
                $message = "Dear {$company_name}, your password is successfully reset. For more details contact ";
                $to_mob = $user['mobile_no'];
                $tempid = $this->template_id_password_reset;
                $sms_type = 'password reset';
                send_sms($to_mob, $message, $tempid, $sms_type);
                $message = array("StatusCode" => 0, "Message" => "Password changes successfully", 'Reload' => 'true', "Class" => "success_popup");
                $this->session->setFlashdata('Message', $message);
            } else {
                $errors['OTP'] = "Invalid OTP";
                $message = array("StatusCode" => 1, "ErrorMessage" => $errors, 'Reload' => 'false');
            }
            return $this->response->setJSON($message);
        }
    }

    public function signout()
    {
        $this->session->remove('admin_user');
        $this->session->remove('comapny_detail');  
        $this->session->remove('admin_comapny_detail');
        $this->session->remove('admin_user_details');
        $this->session->remove('whitelabel_setting_data');
        $this->session->remove('currencyinfo');
        $this->session->remove('super_admin_website_setting'); 
        $this->session->destroy(); 
        $this->session->close(); 
        return redirect()->to(site_url('/login'));
       
    }


    public function verify_otp_password()
    {
        $rules = $this->validate([
            'OTP' => [
                'label' => 'OTP',
                'rules' => 'trim|required',
                'errors' => [
                    'required' => 'Please enter OTP.',

                ]
            ],
        ]);
        if (!$rules) {
            $errors = $this->validator->getErrors();
            $data_validation = array("StatusCode" => 1, "ErrorMessage" => array_filter($errors));
            return $this->response->setJSON($data_validation);
        } else {
            $StatusCode = 1;
            $data = $this->request->getPost();



            $loginmodel = new LoginModel();

            $datafromDatabase = $loginmodel->getData($data['insertId']);


            $parts = 4;
            $uniqueId = Login::getUniqueId($parts);


            // device trustable checkbox is click or not 
            $devicecheck = trim($this->request->getPost('trustable'));

            $login_verification_otp = session()->get('login_verification_otp');

            $currentDate = create_date();

            if ($currentDate <= $datafromDatabase['otp_expiery']) {
                if ($datafromDatabase['otp'] ==  $data['OTP']) {
                    $StatusCode = 0;
                    $this->session->remove('login_verification_otp');
                    $datafromDatabase = $loginmodel->DeleteData($data['insertId']);
                }
            } else {
                $response = [
                    'success' => false,
                    'message' => "Your OTP is expired",
                    'status_code' => 1
                ];
                return $this->response->setJSON($response);
            }


            if ($StatusCode == 0) {
                $user =  session()->get('user_detail');
                if ($user) {
                    $this->session->remove('user_detail');

                    if ($devicecheck === "1") {
                        $trusted_device_arrray = explode(",", $user['trusted_device']);
                        $trusted_device_arrray = array_filter($trusted_device_arrray);
                        array_push($trusted_device_arrray, $uniqueId);
                        if (count($trusted_device_arrray) > 3) {
                            array_shift($trusted_device_arrray);
                        }
                        $new_trusted_device = implode(",", $trusted_device_arrray);
                        $updateDataUser = array(
                            "trusted_device" => $new_trusted_device,
                        );
                        $loginmodel->updateUserDetail($user['id'], $updateDataUser);
                    }
                }
                $this->session->set('admin_user', $user);
                $message = array("StatusCode" => 0, "Message" => "You have successfully logged in", "Class" => "success_popup");
                $this->session->setFlashdata('Message', $message);
                $LoginLogsModel = new LoginLogsModel();
                $agent = $this->request->getUserAgent();
                $loginLogs = [   
                    "web_partner_id" => $user['web_partner_id'],
                    "user_id" => $user['id'],     
                    "user_name" => $user['first_name'] . ' ' . $user['last_name'],
                    "mobile_no" => $user['mobile_no'],
                    "email_id" => $user['login_email'],
                    "login_browser" => $agent->getBrowser() . ' ' . $agent->getVersion(),
                    'platform' => $agent->getPlatform(),
                    'role' => 'WebPartner',
                    'login_source' => 'Website',
                    'btype' => 'WEB',
                    "login_time" => create_date(),
                    "login_ip_address" => $this->request->getIpAddress()
                ];
                $LoginLogsModel->insert($loginLogs);

                // return redirect()->to(site_url('/dashboard'));
                $response = [
                    'success' => true,
                    'message' => site_url('/dashboard'),
                    'deviceId' => $uniqueId,
                    'status_code' => 0
                ];
                return $this->response->setJSON($response);
            } else {
                $response = [
                    'success' => false,
                    'message' => "Invalid OTP",
                    'status_code' => 1
                ];
                return $this->response->setJSON($response);
            }
            return $this->response->setJSON($message);
        }
    }


    function getUniqueId($parts)
    {
        $stringArr = [];
        for ($i = 0; $i < $parts; $i++) {
            $S4 = sprintf('%04x', mt_rand(0, 0xffff));
            $stringArr[] = $S4;
        }
        return implode('-', $stringArr);
    }
}
