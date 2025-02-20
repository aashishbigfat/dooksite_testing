<div class="accordion " id="accordionExample">
    <div class="accordion-item bg_accordion rounded">
        <h2 class="accordion-header" id="headingOne">
            <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne"
                aria-expanded="true" aria-controls="collapseOne">
                Filter by Price
            </button>
        </h2>
        <div id="collapseOne" class="accordion-collapse collapse show" aria-labelledby="headingOne"
            data-bs-parent="#accordionExample">
            <div class="accordion-body">
                <input type="text" class="js-range-slider" name="my_range" value="" data-skin="round" data-type="double"
                    data-min="0" data-max="1000" data-grid="false" />
                <div class="row">
                    <div class="col-md-6">
                        <div class="from_flight px-2 bg-light">
                            <label for="exampleInputPassword1" class="form-label m-0">Min Price </label>
                            <input type="text" maxlength="4" value="0" class="from-control w-100 bg-transparent from" />
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="from_flight px-2 bg-light">
                            <label for="exampleInputPassword1" class="form-label m-0">Max Price </label>
                            <input type="text" maxlength="4" value="1000"
                                class="from-control w-100 bg-transparent to" />
                        </div>
                    </div>
                    <div class="col-md-6 mt-3">
                        <p><b>Clear</b></p>
                    </div>
                    <div class="col-md-6 mt-3 d-flex justify-content-end">
                        <button class="btn btn-danger" style="font-size: 11px;height: 30px;width: 70px;">Apply</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="accordion-item mt-4 rounded bg_accordion">
        <h2 class="accordion-header" id="headingTwo">
            <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo"
                aria-expanded="false" aria-controls="collapseTwo">
                Flights
            </button>
        </h2>
        <div id="collapseTwo" class="accordion-collapse collapse show" aria-labelledby="headingTwo"
            data-bs-parent="#accordionExample">
            <div class="accordion-body">
                <div class="form-check">
                    <input type="checkbox" class="form-check-input" id="exampleCheck1">
                    <label class="form-check-label" for="exampleCheck1">With Flight</label>
                </div>
                <div class="form-check">
                    <input type="checkbox" class="form-check-input" id="exampleCheck1">
                    <label class="form-check-label" for="exampleCheck1">With Out Flight</label>
                </div>
                <div class="form-check">
                    <input type="checkbox" class="form-check-input" id="exampleCheck1">
                    <label class="form-check-label" for="exampleCheck1">Both</label>
                </div>
            </div>
        </div>
    </div>
    <div class="accordion-item mt-4  bg_accordion rounded">
        <h2 class="accordion-header" id="headingThree">
            <button class="accordion-button " type="button" data-bs-toggle="collapse" data-bs-target="#collapseThree"
                aria-expanded="false" aria-controls="collapseThree">
                Destination
            </button>
        </h2>
        <div id="collapseThree" class="accordion-collapse show" aria-labelledby="headingThree"
            data-bs-parent="#accordionExample">
            <div class="accordion-body">
                <p>Country</p>
                <div class="form-check">
                    <input type="checkbox" class="form-check-input" id="exampleCheck1">
                    <label class="form-check-label" for="exampleCheck1">Sri Lanka</label>
                </div>
                <div class="form-check">
                    <input type="checkbox" class="form-check-input" id="exampleCheck1">
                    <label class="form-check-label" for="exampleCheck1">Japan</label>
                </div>
                <div class="form-check">
                    <input type="checkbox" class="form-check-input" id="exampleCheck1">
                    <label class="form-check-label" for="exampleCheck1">Nepal</label>
                </div>
            </div>
        </div>
    </div>
    <div class="accordion-item mt-4  bg_accordion rounded">
        <h2 class="accordion-header" id="headingThree1">
            <button class="accordion-button " type="button" data-bs-toggle="collapse" data-bs-target="#collapseThree1"
                aria-expanded="false" aria-controls="collapseThree1">
                Duration
            </button>
        </h2>
        <div id="collapseThree1" class="accordion-collapse show" aria-labelledby="headingThree1"
            data-bs-parent="#accordionExample">
            <div class="accordion-body">
                <div class="form-check">
                    <input type="checkbox" class="form-check-input" id="exampleCheck1">
                    <label class="form-check-label" for="exampleCheck1">0 - 2 hours</label>
                </div>
                <div class="form-check">
                    <input type="checkbox" class="form-check-input" id="exampleCheck1">
                    <label class="form-check-label" for="exampleCheck1">2 -4 hours</label>
                </div>
                <div class="form-check">
                    <input type="checkbox" class="form-check-input" id="exampleCheck1">
                    <label class="form-check-label" for="exampleCheck1">4 -8 hours</label>
                </div>
                <div class="form-check">
                    <input type="checkbox" class="form-check-input" id="exampleCheck1">
                    <label class="form-check-label" for="exampleCheck1">Fullday(+8 hours)</label>
                </div>
            </div>
        </div>
    </div>
</div>