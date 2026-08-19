<section id="breadcrumbs" class="breadcrumbs">
   <div class="container">
      <div class="d-flex justify-content-between align-items-center">
         <h2>Blog</h2>
         <ol>
            <li>
               <a href="<?php echo site_url(); ?>">Home</a>
            </li>
            <li>Blog</li>
         </ol>
      </div>
   </div>
</section>

<section id="recent-posts" class="recent-posts">
   <div class="container">
      <div class="row gy-4">
         <?php
         if ($blog_list) {
            foreach ($blog_list as $blog) { ?>
               <div class="col-lg-4 col-md-6 col-12">
                  <div class="post-item position-relative h-100">
                     <div class="post-img position-relative overflow-hidden">
                        <img src="<?php echo root_url . 'uploads/blog/thumbnail/' . $blog['post_images'] ?>" alt="<?= $blog['post_title'] ?>" class="img-fluid">
                        <span class="post-date"><?= date('d M Y', $blog['created']) ?></span>
                     </div>
                     <div class="post-content d-flex flex-column">
                        <h3 class="post-title"><?= $blog['post_title'] ?></h3>
                        <div class="meta d-flex align-items-center">
                           <div class="d-flex align-items-center">
                              <i class="fa-solid fa-person"></i> <span class="ps-2"><?= $blog['posted_by'] ?></span>
                           </div>
                           <span class="px-3 text-black-50">/</span>
                           <div class="d-flex align-items-center">
                              <i class="fa-solid fa-calendar-days"></i> <span class="ps-2"><?= date('d M Y', $blog['created']) ?></span>
                           </div>
                        </div>
                        <hr>
                        <p>
                           <?php echo (strlen(strip_tags($blog['post_desc'])) > 160) ? substr(strip_tags($blog['post_desc']), 0, 160) . '....' : strip_tags($blog['post_desc']) ?>
                        </p>

                        <a class="readmore stretched-link" href="<?php echo site_url('blog'); ?>/<?= $blog['post_slug']; ?>"><span>Read More</span><i class="fa-solid fa-right-long"></i></a>
                     </div>
                  </div>
               </div>
            <?php }
         } else { ?>
            <div class="text-center p-5">
               <h5>No Blogs Available</h5>
            </div>
         <?php  } ?>
      </div>
   </div>
</section>