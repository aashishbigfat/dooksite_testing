<section id="breadcrumbs" class="breadcrumbs">
   <div class="container">
      <div class="d-flex justify-content-between align-items-center">
         <h2>Blog</h2>
         <ol>
            <li>
                 <a href="<?php echo site_url(); ?>">Home</a>
            </li>
            <li>
                 <a href="<?php echo site_url('blog'); ?>">Blog</a>
            </li>
            <?php if(isset($blogdetails) && $blogdetails) { ?>
            <li>
               <?php echo $blogdetails['post_title']; ?>
            </li>
            <?php } ?>
         </ol>
      </div>
   </div>
</section>

<section class="blog-single">
   <div class="container">
      <div class="row">
         <div class="col-lg-8 entries">
            <article class="entry entry-single">
               <div class="entry-img"> <img src="<?php echo root_url . 'uploads/blog/' . $blogdetails['post_images'] ?>" alt="<?=$blogdetails['post_title']?>" class="img-fluid"></div>
               <h2 class="entry-title"><?php echo $blogdetails['post_title']; ?></h2>
               <div class="entry-meta">
                  <ul>
                     <li class="d-flex align-items-center">
                      <span class="post-author-img"> <i class="fa fa-user"></i> <?=$blogdetails['posted_by']?></span>
                        
                     </li>
                     <li class="d-flex align-items-center">
                        <span><i class="fa fa-calendar"></i> <?=date('d M Y',$blogdetails['created'])?></span>
                     </li>
                  </ul>
               </div>
               <div class="entry-content">
                  <p><?php echo $blogdetails['post_desc']; ?></p>
               </div>
            </article>
         </div>

         <div class="col-lg-4">
            <div class="sidebar">
               <h3 class="sidebar-title">Recent Posts</h3>
               <div class="sidebar-item recent-posts">
                  <?php if(count($Recent_blog_list) > 0 ) : ?>
                     <?php foreach($Recent_blog_list as $item) : ?>
                        <div class="post-item clearfix mb-4">
                           <img src="<?php echo root_url . 'uploads/blog/' . $item['post_images'] ?>" alt="<?=$item['post_title']?>">
                           <h4><a href="<?php echo site_url('blog'); ?>/<?= $item['post_slug']; ?>"><?php echo $item['post_title']; ?></a></h4>
                           <span class="ms-3"><i class="fa fa-calendar"></i> <?=date('d M Y',$item['created'])?></span>
                        </div>
                     <?php endforeach; ?>
                  <?php else: ?>
                     <div class="text-center">No Data Found</div>
                  <?php endif; ?>
               </div>
            </div>
         </div>
      </div>
   </div>
</section>