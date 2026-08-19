<div class="modal-header">
    <h5 class="modal-title">Website Template Details</h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>

<?php if (!empty($details)) : ?>

    <div class="modal-body" style="max-height: 500px; overflow-y: auto;">
        <div id="carouselDetails" class="carousel slide" data-bs-ride="carousel">
            <ol class="carousel-indicators">
                <?php foreach ($details as $key => $item) : ?>
                    <li data-bs-target="#carouselDetails" data-bs-slide-to="<?php echo $key; ?>" <?php echo $key == 0 ? 'class="active"' : ''; ?>></li>
                <?php endforeach; ?>
            </ol>

            <?php $path = str_replace('/home/nexes/public_html/', 'https://www.', str_replace('whitelabel', '', $_SERVER['DOCUMENT_ROOT'])); ?>

            <div class="carousel-inner">
                <?php foreach ($details as $key => $item) : ?>
                    <div class="carousel-item <?php echo $key === 0 ? 'active' : ''; ?>">
                        <img src="<?php echo $path . "uploads/website_template/" . $item['image']; ?>" class="d-block w-100" alt="<?php echo $item['title']; ?>">
                    </div>
                <?php endforeach; ?>
            </div>
            <button class="carousel-control-prev" type="button" data-bs-target="#carouselDetails" data-bs-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Previous</span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#carouselDetails" data-bs-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Next</span>
            </button>
        </div>
    </div>

<?php else : ?>
    <div class="modal-body">
        <div class="row">
            <div class="col-12 text-center">
                <h4>Data Not Found</h4>
            </div>
        </div>
    </div>
<?php endif; ?>