<?php

namespace Modules\Pages\Controllers;

use App\Modules\Pages\Models\PagesModel;
use App\Controllers\BaseController;
use Modules\Pages\Config\Validation;
use App\Libraries\CaptchaCodes;


class Pages extends BaseController
{

    protected $title;
    protected $web_partner_details;
    protected $web_partner_id;

    public function initController(\CodeIgniter\HTTP\RequestInterface $request, \CodeIgniter\HTTP\ResponseInterface $response, \Psr\Log\LoggerInterface $logger)
    {
        parent::initController($request, $response, $logger);
        $this->title = "Pages";
        $this->web_partner_details = web_partner_details;
        $this->web_partner_id = web_partner_details['id'];
    }


    public function pages($slug)
    {
        $uri = service('uri');
        $content = array();
        if (count($uri->getSegments()) == 1) {
            if ($slug) {
                $pagesModel = new PagesModel();
                $content = $pagesModel->get_page_details($slug, $this->web_partner_id);
                $data = [
                    'title' => isset($content['meta_title']) ? $content['meta_title'] : '',
                    'metakeywords' => isset($content['meta_keyword']) ? $content['meta_keyword'] : '',
                    'metadescription' => isset($content['meta_description']) ? $content['meta_description'] : '',
                    'content' => $content,
                    'view' => "Pages\Views\pages-details",
                ];

                return view('template/default-layout', $data);
            }
        } else {
            echo view("errors/html/error_404");
        }
    }


    public function generateCaptcha()
    {
        $captcha = new CaptchaCodes();
        $captcha->phpcaptcha('#000000', '#FFFFFF', 200, 50, rand(15, 25), 100, '#3fbbac');
    }

    public function contact_us()
    {
        $MetaInfoData = static_meta_information('Contactus', 'Index');
        $data = [
            'title' => isset($MetaInfoData['title']) ? $MetaInfoData['title'] : '',
            'metakeywords' => isset($MetaInfoData['keyword']) ? $MetaInfoData['keyword'] : '',
            'metadescription' => isset($MetaInfoData['description']) ? $MetaInfoData['description'] : '',
            'metarobots' => isset($MetaInfoData['robots']) ? $MetaInfoData['robots'] : '',
            'view' => 'Pages\Views\contact_us'
        ];
        return view('template/default-layout', $data);
    }

    public function web_check_in()
    {

        $PagesModel = new PagesModel();
        $webcheckinData = $PagesModel->webcheckin_list($this->web_partner_id);

        $data = [
            'title' => 'WebCheckIn',
            'web_check_in' => $webcheckinData,
            'view' => 'Pages\Views\web_check_in'
        ];
        return view('template/default-layout', $data);
    }

    public function Offers_list()
    {
        $PagesModel = new PagesModel();
        $offerData = $PagesModel->offers_list($this->web_partner_id);
        /*  pr($offerData);exit; */
        $data = [
            'title' => 'Offers',
            'offers_list' => $offerData,
            'view' => 'Pages\Views\offers_list'
        ];
        return view('template/default-layout', $data);
    }

    public function newsletter()
    {
        $data = $this->request->getPost();
        $validation = \Config\Services::validation();
        $validation->setRules([
            'email' => 'required|valid_email',
        ]);

        if (!$validation->run($data)) {
            $errors = $validation->getErrors();
            $data_validation = [
                "StatusCode" => 1,
                "ErrorMessage" => array_filter($errors)
            ];
            return $this->response->setJSON($data_validation);
        } else {
            $email = $data['email'];

            $pagesModel = new PagesModel();
            $existingEmail = $pagesModel->getEmail($email, $this->web_partner_id);

            if ($existingEmail) {
                $data_validation = [
                    "StatusCode" => 1,
                    "ErrorMessage" => ["email" => "Email id already subscribed"]
                ];
                return $this->response->setJSON($data_validation);
            }

            $insertData = [
                'email' => $email,
                'created' => create_date(),
                'web_partner_id' => $this->web_partner_id
            ];

            $emailType = 'Newsletter';
            $tableName = 'newsletter';
            $message = "Thanks for subscribing. Now, get all travel info & deals at your fingertips!";
            $emailTemplateName = 'message';

            $pagesModel->insertData($tableName, $insertData);

            if ($email) {
                $data['message'] = $message;
                unset($insertData['created'], $insertData['web_partner_id']);
                $data['UserInfo'] = $insertData;
                $messageview = view('Views/emails/' . $emailTemplateName, $data);
                $subjact = "Thank you for subscribing";
                /*  send_email($email, $subjact, $messageview, $emailType, $attachment = null, $extraParameter = null); */
                $data_validation = [
                    "StatusCode" => 0,
                    "Message" => $message,
                    "Class" => "success_popup",
                    "Reload" => "false",
                    "FormBlank" => "true",
                ];
                return $this->response->setJSON($data_validation);
            }
        }
    }
    public function savedata()
    {

        $data = $this->request->getPost();
        $validate = new Validation();
        $rules = $this->validate($validate->query_validation); 
        if (!$rules) {
            $errors = $this->validation->getErrors();
            $data_validation = array("StatusCode" => 1, "ErrorMessage" => array_filter($errors));
            return $this->response->setJSON($data_validation);
        } else {
            $captchaCode = session()->get('captcha_code'); 
            if (trim($data['captchagenerate']) !== trim($captchaCode)) {
                $errorcode['captchagenerate'] = "Captcha verification failed!";
                $validation = array("StatusCode" => 1, "ErrorMessage" => array_filter($errorcode));
                return $this->response->setJSON($validation);
            }  
            
            $data['web_partner_id'] = $this->web_partner_id;
            $PagesModel = new PagesModel();
            $data['created_date'] = create_date();
            unset($data['captchagenerate']);
            $PagesModel->InsertData('contact_us', $data);
            $EmailType = 'ContactUs';
            $EmailId = $data['email'];
            if ($EmailId) {
                $Smsmessage = "Contact Us request successfully submitted";
                $data['message'] = $Smsmessage;
                $data['ContactUs'] = $data;
                $message = view('Views/emails/contact-us-email', $data);
                /*  send_email($EmailId, $Smsmessage, $message, $EmailType, $attachment = null, $extraprameter = null); */
            }
            $response = array("StatusCode" => 0, "ErrorMessage" => $Smsmessage);
            $message = array("StatusCode" => 0, "Message" => $Smsmessage, "Class" => "success_popup");
            session()->remove('captcha_code');
            $this->session->setFlashdata('Message', $message);
            return $this->response->setJSON($response);
        }
    }



    function all_Services_Enquiry_Form()
    {

        $add_view = view('Modules\Pages\Views\allservicesquery');
        $data = array("StatusCode" => 9, "Message" => $add_view, 'class' => 'success_popup', "Reload" => "false");
        return $this->response->setJSON($data);
    }


    function BookingReview()
    {
        $uri = service('uri');
        $ref_number = $uri->getSegment(2);

        // $ref_number = $uri->getSegment(3);
        $variable_Value = dev_decode($ref_number);

        $variableValuesArray = explode(",", $variable_Value);

        // pr( $variableValuesArray);
        // die;

        // $slug = $this->request->uri->getSegment(2);
        // $variable_Value = dev_decode($slug);


        // $variableValuesArray = explode(",",$variable_Value);



        $PagesModel = new PagesModel();
        $details = $PagesModel->get_page_details($ref_number, $this->web_partner_id);

        $details['variableValuesArray'] = "sdfsd";

        $data = [
            'title' => $variableValuesArray['1'],
            'details' => $variableValuesArray,
            'view' => 'Pages\Views\booking-review'
        ];
        return view('template/default-layout', $data);
    }


    public function reviewSaveData()
    {
        $data = $this->request->getPost();
        pr("line 124 ends here");
        pr($data);
        die;
    }




    /* *************************** Abhay ***************************  */
    public function deleteAllSessionsAndLogs()
    {
        $sessionPath = WRITEPATH . 'session/';
        $this->deleteFilesInDirectory($sessionPath);

        $logPath = WRITEPATH . 'logs/';
        $this->deleteFilesInDirectory($logPath);

        $debugbar = WRITEPATH . 'debugbar/';
        $this->deleteFilesInDirectory($debugbar);

        return "All session or log and debugbar files deleted successfully.";
    }

    private function deleteFilesInDirectory($path)
    {
        // Check if the directory exists
        if (is_dir($path)) {
            $files = glob($path . '*'); // Get all files in the directory

            foreach ($files as $file) {
                if (is_file($file)) {
                    unlink($file); // Delete the file
                }
            }
        }
    }

    /* *************************** Abhay ***************************  */

    function webFrame()
    {
        $search_Form_template = !empty(whitelabel['selected_template']) ? whitelabel['selected_template'] : '0';
        return  view('template/webframe/default-layout.php');
    }
}
