<div class="row">
    <div class="col-md-12">
        <div class="listing-loader-block">
            <div class="tts-showcenter">
                <div class="tts-loading-box">
                    <h5> Please Wait... </h5>
                    <p class="mb-2">We are looking for all available hotels for you</p>
                    <h6><?php echo $searchData['location']; ?></h6>
                    <div class="loader mt-3 mb-3">
                        <div role="status" class="spinner-grow text-primary">
                            <span class="sr-only">Loading...</span>
                        </div>
                        <div role="status" class="spinner-grow text-secondary">
                            <span class="sr-only">Loading...</span>
                        </div>
                        <div role="status" class="spinner-grow text-danger">
                            <span class="sr-only">Loading...</span>
                        </div>
                        <div role="status" class="spinner-grow text-dark">
                            <span class="sr-only">Loading...</span>
                        </div>
                    </div>
                    <div class="row pax_row">
                        <div class="col">
                            <p class="mb-0"><strong> Check-In : </strong> <?php echo $searchData['checkIn'] ?>,
                                <strong> Nights : </strong> <?php echo $nights = getDateDiffrence($searchData['checkIn'], $searchData['checkOut']); ?>,
                                <strong> Check-Out : </strong> <?php echo $searchData['checkOut'] ?>
                            </p>
                            <p class="mb-0"><strong> Rooms & Guests </strong> : <?php $Guest =  adult_child_count($searchData);
                                                                                echo   $Guest['total_p'] . " Guest"; ?>, <?php echo   $searchData['room'] . " Rooms"; ?></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>