<section id="breadcrumbs" class="breadcrumbs">
	<div class="container">
		<div class="d-flex justify-content-between align-items-center">
			<h2><?php echo isset($content['title']) ? $content['title'] : ""; ?></h2>
			<ol>
				<li>
					<a href="<?php echo site_url(); ?>">Home</a>
				</li>
				<li><?php echo isset($content['title']) ? $content['title'] : ""; ?></li>
			</ol>
		</div>
	</div>
</section>
<?php if ($content) { ?>
	<section class="page-section">
		<div class="container ">
			<div class="row m0">
				<div class="page-box">
					<div class="page-title">
						<h2 class="nh_color page_content_header">
							<span class="fz30"><?php echo isset($content['title']) ? $content['title'] : ""; ?></span>
						</h2>
					</div>
					<div class="row">
						<div class="page_content_details w-100">
							<p><?php echo isset($content['content']) ? $content['content'] : ""; ?></p>
						</div>
					</div>
				</div>
			</div>

		</div>
	</section>
<?php } else { ?>
	<section class="page-section">
		<div class="container">
			<div class="text-center">
				<h1 class="nh_color pt-3 pb-3 position-relative page_content_header">
					<div class="row">
						<div class="page_content_details w-100">
							<p class="text-center">404 Data Not Found</p>
						</div>
					</div>
				</h1>
			</div>
		</div>
	</section>
<?php } ?>