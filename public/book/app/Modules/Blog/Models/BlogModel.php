<?php

namespace App\Modules\Blog\Models;

use CodeIgniter\Model;

class BlogModel extends Model
{
    protected $table = 'blog_post';
  
    public function blogdetails($slug)
    {
       return $this->db->table('blog_post')->select('*')->where("status",'active')->where("post_slug",$slug)->get()->getRowArray();
    }

    public function Recent_blog_list($slug)
    {  
        return $this->db->table('blog_post')->where('status','active')->where('post_slug !=',$slug)->orderBy('id', 'DESC')->limit(16)->get()->getResultArray();
         
    } 

    public function blog_list($web_partner_id)
    {
        return $this->select('id,post_title,post_slug,post_desc,posted_by,post_images,created')->where(['web_partner_id'=>$web_partner_id])
            ->where('status', 'active')->orderBy('id', 'DESC')->paginate(16); 
    }

}
