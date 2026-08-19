<?php

namespace Modules\Career\Controllers;

use App\Modules\Career\Models\CareerModel;
use App\Modules\Career\Models\JobRequestModel;
use App\Controllers\BaseController;
use Modules\Career\Config\Validation;



class Career extends BaseController
{


    protected $title; 
    protected $web_partner_id; 
    protected $user_id; 
    protected $folder_name; 
    protected $whitelabel_user;  

    public function initController(\CodeIgniter\HTTP\RequestInterface $request, \CodeIgniter\HTTP\ResponseInterface $response, \Psr\Log\LoggerInterface $logger)
    {
        parent::initController($request, $response, $logger);
        $this->title = "Career"; 
        $this->web_partner_id = admin_cookie_data()['admin_user_details']['web_partner_id'];
        $this->user_id = admin_cookie_data()['admin_user_details']['id']; 
        $this->folder_name = 'Career';
        $this->whitelabel_user = admin_cookie_data()['admin_comapny_detail']['whitelabel_user'];

        if(permission_access_error("Career","Career_Module")) {

        }
    }

    public function index()
    {

        $CareerModel = new CareerModel();
        if($this->request->getGet() && $this->request->getGet('key'))
        {
            $lists=$CareerModel->search_data($this->request->getGet(),$this->web_partner_id);
        }  else {
            $lists=$CareerModel->career_list($this->web_partner_id);
        }
        $data = [
            'title' => $this->title,
            'list' => $lists,
            'view' => "Career\Views\career-list",
            // 'pager' => $CareerModel->pager,
            'search_bar_data'=>$this->request->getGet(),
        ];

        // pr($data['list']); die;

        return view('template/sidebar-layout', $data);
    }

    
  

    public function add_career_view()
    {
        if (permission_access_error("Career", "add_career")) {
            $CareerModel = new CareerModel();
            $career_categories_lists = $CareerModel->career_categories_list($this->web_partner_id);

            $data = [
                'title' => $this->title,
                'categories_lists' => $career_categories_lists,
            ];
           
            $add_blog_view = view('Modules\Career\Views\add-career', $data);
            $data = array("StatusCode" => 0, "Message" => $add_blog_view, 'class' => 'success_popup', "Reload" => "false");
            return $this->response->setJSON($data);
        }
    }
   


    public function add_career()
    {
        if (permission_access_error("Career", "add_career")) {
            $validate = new Validation();
            $rules = $this->validate($validate->career_validation);
            if (!$rules) {
                $errors = $this->validator->getErrors();
                $data_validation = array("StatusCode" => 1, "ErrorMessage" => array_filter($errors));
                return $this->response->setJSON($data_validation);
            } else {
                $CareerModel = new CareerModel();
                $data = $this->request->getPost();

                $data['created'] = create_date();
                $data['web_partner_id'] = $this->web_partner_id;

                $added_data = $CareerModel->insert($data);

                if ($added_data) {
                    $message = array("StatusCode" => 0, "Message" => "Career Successfully added", "Class" => "success_popup");
                } else {
                    $message = array("StatusCode" => 2, "Message" => "Career not  added", "Class" => "error_popup");
                }

                $this->session->setFlashdata('Message', $message);
                return $this->response->setJSON($message);
            }
        }
    }

    public function edit_career_view()
    {
        if (permission_access_error("Career", "edit_career")) {
            
            $uri = $this->request->getUri();
            $id = dev_decode($uri->getSegment(3));

            $CareerModel = new CareerModel();
            $career_categories_lists = $CareerModel->career_categories_list($this->web_partner_id);
            $data = [
                'title' => $this->title,
                'id' => $id,
                'categories_lists' => $career_categories_lists,
                'details' => $CareerModel->career_details($id,$this->web_partner_id)
            ];
            $blog_details = view('Modules\Career\Views\edit-career', $data);
            $data = array("StatusCode" => 0, "Message" => $blog_details, 'class' => 'success_popup');
            return $this->response->setJSON($data);
        }
    }


    public function edit_career()
    {

        if (permission_access_error("Career", "edit_career")) {
            

            $uri = $this->request->getUri();
            $id = dev_decode($uri->getSegment(3));

            $validate = new Validation();
            $rules = $this->validate($validate->career_validation);
            if (!$rules) {
                $errors = $this->validator->getErrors();
                $data_validation = array("StatusCode" => 1, "ErrorMessage" => array_filter($errors));
                return $this->response->setJSON($data_validation);
            } else {
                $CareerModel = new CareerModel();
                $data = $this->request->getPost();
                $data['modified'] = create_date();
              
                $added_data = $CareerModel->where(['id'=>$id,'web_partner_id' =>$this->web_partner_id])->set($data)->update();

                if ($added_data) {
                    $message = array("StatusCode" => 0, "Message" => "Career Successfully Updated", "Class" => "success_popup");
                } else {
                    $message = array("StatusCode" => 2, "Message" => "Career not  Updated", "Class" => "error_popup");
                }


                $this->session->setFlashdata('Message', $message);
                return $this->response->setJSON($message);
            }
        }
    }

    public function career_details(){
        
        $uri = $this->request->getUri();
        $id = dev_decode($uri->getSegment(3));
        

        $CareerModel = new CareerModel();
        $data = [
            'title' => $this->title,
            'id' => $id,
            'details' => $CareerModel->career_details($id,$this->web_partner_id),
        ];
        $blog_details = view('Modules\Career\Views\career-details', $data);
        $data = array("StatusCode" => 0, "Message" => $blog_details, 'class' => 'success_popup');
        return $this->response->setJSON($data);
    }

    public function remove_career()
    {
        if (permission_access_error("Career", "delete_career")) {
            $CareerModel = new CareerModel();
            $ids = $this->request->getPost('checklist');
            $delete = $CareerModel->remove_career($ids,$this->web_partner_id);

            if ($delete) {
                $message = array("StatusCode" => 0, "Message" => "Career Successfully Deleted", "Class" => "success_popup");
            } else {
                $message = array("StatusCode" => 2, "Message" => "Career  not Deleted", "Class" => "error_popup", "Reload" => "true");
            }
            $this->session->setFlashdata('Message', $message);
            return $this->response->setJSON($message);
        }
    }
   

    public function job_application_list(): string
    {
        if (permission_access_error("Career", "career_applications")) {
            $JobRequestModel = new JobRequestModel();

            if($this->request->getGet() && $this->request->getGet('key'))
        {
            $lists = $JobRequestModel->search_data($this->request->getGet(),$this->web_partner_id);
        }  else {
            $lists = $JobRequestModel->job_application_list($this->web_partner_id);
        }
            $data = [
                'title' => $this->title,
                'list' => $lists,
                'search_bar_data'=>$this->request->getGet(),
                'view' => "Career\Views\job-application-list",
             
            ];

           

            return view('template/sidebar-layout', $data);
        }
    }

    public function remove_job_application()
    {
        if (permission_access_error("Career", "delete_applications")) {
            $JobRequestModel = new JobRequestModel();
            $ids = $this->request->getPost('checklist');
            $field_name = 'resume_file';
            foreach ($ids as $id) {
                $blog_details = $JobRequestModel->delete_image($id,$this->web_partner_id);

                if (isset($blog_details[$field_name]) && $blog_details[$field_name] !== "") {
                    $file_path = FCPATH . "../uploads/$this->folder_name/" . $blog_details[$field_name];
                
                    if (file_exists($file_path)) {
                        unlink($file_path);
                    }
                }
            $delete = $JobRequestModel->remove_job_application($ids,$this->web_partner_id);
            }
            if ($delete) {
                $message = array("StatusCode" => 0, "Message" => "Job Application Successfully Deleted", "Class" => "success_popup");
            } else {
                $message = array("StatusCode" => 2, "Message" => "Job Application  not Deleted", "Class" => "error_popup", "Reload" => "true");
            }
            $this->session->setFlashdata('Message', $message);
            return $this->response->setJSON($message);
        }
    }

    public function career_status_change()
    {

        if (permission_access_error("Career", "career_status")) {
            $validate = new Validation();
            $rules = $this->validate($validate->career_status);
            if (!$rules) {
                $errors = $this->validator->getErrors();
                $data_validation = array("StatusCode" => 1, "ErrorMessage" => array_filter($errors));
                return $this->response->setJSON($data_validation);
            } else {
                $CareerModel = new CareerModel();
                $ids = $this->request->getPost('checkedvalue');

                $data['status'] = $this->request->getPost('status');

                $update = $CareerModel->career_status_change($ids, $data,$this->web_partner_id);

                if ($update) {
                    $message = array("StatusCode" => 0, "Message" => "Career status  successfully changed", "Class" => "success_popup", "Reload" => "true");
                } else {
                    $message = array("StatusCode" => 2, "Message" => "Career status not changed successfully", "Class" => "error_popup", "Reload" => "true");
                }
                $this->session->setFlashdata('Message', $message);
                return $this->response->setJSON($message);
            }
        }
    }


    public function career_categories_list()
    {
        if (permission_access_error("Career", "career_categories_list")) {
        $CareerModel = new CareerModel();
        if($this->request->getGet() && $this->request->getGet('key'))
        {
            $lists=$CareerModel->career_search_data($this->request->getGet(),$this->web_partner_id);
        }  else {
            $lists=$CareerModel->career_categories_list($this->web_partner_id);
        }
   
        $data = [
            'title' => $this->title,
            'list' => $lists,
            'view' => "Career\Views\career-categories-list",
            'pager' => $CareerModel->pager,
            'search_bar_data'=>$this->request->getGet(),
        ];

        return view('template/sidebar-layout', $data);
    }
}

    public function add_career_categories_view()
    {
        if (permission_access_error("Career", "add_career_categories")) {
        $CareerModel = new CareerModel();
            $data = [
                'title' => $this->title,
            ];
        $add_blog_view = view('Modules\Career\Views\add-career-categories', $data);
            $data = array("StatusCode" => 0, "Message" => $add_blog_view, 'class' => 'success_popup', "Reload" => "false");
            return $this->response->setJSON($data);
       
       
    }
}

    public function add_career_categories()
    {
        if (permission_access_error("Career", "add_career_categories")) {
            $validate = new Validation();
            $rules = $this->validate($validate->career_categories_validation);
            if (!$rules) {
                $errors = $this->validator->getErrors();
                $data_validation = array("StatusCode" => 1, "ErrorMessage" => array_filter($errors));
                return $this->response->setJSON($data_validation);
            } else {
                $CareerModel = new CareerModel();
                $data = $this->request->getPost();

                $data['created'] = create_date();
                $data['web_partner_id'] = $this->web_partner_id;
 
                $added_data = $CareerModel->insertData('career_category',$data);

                if ($added_data) {
                    $message = array("StatusCode" => 0, "Message" => "Career Categories Successfully added", "Class" => "success_popup");
                } else {
                    $message = array("StatusCode" => 2, "Message" => "Career Categories not  added", "Class" => "error_popup");
                }

                $this->session->setFlashdata('Message', $message);
                return $this->response->setJSON($message);
            }
       
    }
}

    public function edit_career_categories_view()
    {
        if (permission_access_error("Career", "edit_categories")) {
        

        $uri = $this->request->getUri();
        $id = dev_decode($uri->getSegment(3));

        $CareerModel = new CareerModel();
        $details = $CareerModel->categories_list_details($id,$this->web_partner_id);
        $data = [
            'title' => $this->title,
            'id' => $id,
            'details' => $details,
        ];
        $blog_details = view('Modules\Career\Views\edit-categories', $data);
        $data = array("StatusCode" => 0, "Message" => $blog_details, 'class' => 'success_popup');
        return $this->response->setJSON($data);
    }
}


    public function edit_categories()
    {
        if (permission_access_error("Career", "edit_categories")) {
            $uri = $this->request->getUri();
            $id = dev_decode($uri->getSegment(3));
            $validate = new Validation();
            $rules = $this->validate($validate->career_categories_validation);
            if (!$rules) {
                $errors = $this->validator->getErrors();
                $data_validation = array("StatusCode" => 1, "ErrorMessage" => array_filter($errors));
                return $this->response->setJSON($data_validation);
            } else {
                $CareerModel = new CareerModel();
                $data = $this->request->getPost();
                $data['modified'] = create_date();
               
                $added_data = $CareerModel->updateData('career_category', ['id' => $id,'web_partner_id'=>$this->web_partner_id], $data);

                if ($added_data) {
                    $message = array("StatusCode" => 0, "Message" => "Career Categories Successfully Updated", "Class" => "success_popup");
                } else {
                    $message = array("StatusCode" => 2, "Message" => "Career Categories not  Updated", "Class" => "error_popup");
                }
                $this->session->setFlashdata('Message', $message);
                return $this->response->setJSON($message);
            }
       
    }
}

    public function remove_career_categories()
    {
        if (permission_access_error("Career", "remove_career_categories")) {
            $CareerModel = new CareerModel();
            $ids = $this->request->getPost('checklist');
            $delete = $CareerModel->remove_career_categories($ids,$this->web_partner_id);
            if ($delete) {
                $message = array("StatusCode" => 0, "Message" => "Career Successfully Deleted", "Class" => "success_popup");
            } else {
                $message = array("StatusCode" => 2, "Message" => "Career  not Deleted", "Class" => "error_popup", "Reload" => "true");
            }
            $this->session->setFlashdata('Message', $message);
            return $this->response->setJSON($message);
       
    }
}

    public function career_categories_status_change()
    {

        if (permission_access_error("Career", "career_categories_status_change")) {
            $validate = new Validation();
            $rules = $this->validate($validate->career_status);
            if (!$rules) {
                $errors = $this->validator->getErrors();
                $data_validation = array("StatusCode" => 1, "ErrorMessage" => array_filter($errors));
                return $this->response->setJSON($data_validation);
            } else {
                $CareerModel = new CareerModel();
                $ids = $this->request->getPost('checkedvalue');

                $data['status'] = $this->request->getPost('status');

                $update = $CareerModel->career_categories_status_change($ids, $data,$this->web_partner_id);

                if ($update) {
                    $message = array("StatusCode" => 0, "Message" => "Career status  successfully changed", "Class" => "success_popup", "Reload" => "true");
                } else {
                    $message = array("StatusCode" => 2, "Message" => "Career status not changed successfully", "Class" => "error_popup", "Reload" => "true");
                }
                $this->session->setFlashdata('Message', $message);
                return $this->response->setJSON($message);
            }
       
    }
}

}