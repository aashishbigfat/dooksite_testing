<?php

namespace Modules\Blog\Controllers; 

use App\Controllers\BaseController; 
use App\Modules\Blog\Models\BlogModel;


class Blog extends BaseController
{

    protected $title;
    protected $metakeywords;
    protected $metadescription;
    protected $wl_customer_id;
    protected $web_partner_details;
    protected $web_partner_id;
    protected $folder_name;
    protected $wl_customer_info; 
    public $validation;
    public $request;

    public function initController(\CodeIgniter\HTTP\RequestInterface $request, \CodeIgniter\HTTP\ResponseInterface $response, \Psr\Log\LoggerInterface $logger)
    {
        parent::initController($request, $response, $logger);
        $this->title = "Blog";
        $this->metakeywords="";
        $this->metadescription="";
       
        $this->web_partner_details = web_partner_details;
        $this->web_partner_id = web_partner_details['id'];
        $this->wl_customer_id = '';
        $this->wl_customer_info = array();
        if (isset(session()->get('wl_customer')['id'])){
            $this->wl_customer_id = session()->get('wl_customer')['id'];
            $this->wl_customer_info = session()->get('wl_customer');
        }       
    }

    public function index()
    { 

        $BlogModel = new BlogModel();
        $blog_list = $BlogModel->blog_list($this->web_partner_id);
        $MetaInfoData = static_meta_information('Blog','Index');
        $data = [    
            'blog_list' => $blog_list,
            'pager' => $BlogModel->pager,
            'title' => isset($MetaInfoData['title']) ? $MetaInfoData['title'] : '',
            'metakeywords' => isset($MetaInfoData['keyword']) ? $MetaInfoData['keyword'] : '',
            'metadescription' => isset($MetaInfoData['description']) ? $MetaInfoData['description'] : '',
            'metarobots' => isset($MetaInfoData['robots']) ? $MetaInfoData['robots'] : '',
            'view' => "Blog\Views\index",
        ];
        return view('template/default-layout', $data);  
    }

    public function blogdetail()
    {    
        $uri = service('uri');
        $slug = $uri->getSegment(2);
        $BlogModel = new BlogModel();
        $Recent_blog_list =  $BlogModel->Recent_blog_list($slug);
        $blogdetails=$BlogModel->blogdetails($slug);        
        if($blogdetails)
        {
            $this->title=$blogdetails['meta_title'];
            $this->metakeywords=$blogdetails['meta_keyword'];
            $this->metadescription=$blogdetails['meta_description'];
        }
        
        $data = [ 
            'title' => $this->title, 
            'metakeywords' => $this->metakeywords, 
            'metadescription' => $this->metadescription, 
            'blogdetails' =>$blogdetails,
            'Recent_blog_list' => $Recent_blog_list,
            'view' => "Blog\Views\detail",
        ];
        return view('template/default-layout', $data);
    }
}
