@extends('frontend.layouts.master')
@push('title') Registration @endpush

@section('content')
  
  <div class="container">
    <div class="row mt-4 mb-4 justify-content-center">
       <div class="col-md-12 mt-4">
          <p class="color_gray"><a href="/" class="text-danger">Home</a> / Registration</p>
        </div>
       <div class="col-md-6 mt-3" style="position: relative;">
         <div class="common_form p-3" style="border-radius: 20px;">
                  <div class="row justify-content-center">
                     <div class="col-md-2 col-3">
                            <img src="{{asset('assets/images/Group8098876.png')}}" class="w-100 h-100">
                        </div>
                        <div class="col-md-4 col-9 d-flex align-items-center">
                            <h5 class="modal-title" id="exampleModalLabel">New Registration</h5>                         
                        </div>                         
                   </div>
                    <form action="" class="aa-login-form mt-4" id="frmRegistration">
                        <div class="input-group mb-4">
                            <span class="input-group-text">
                                <img src="{{asset('assets/images/user(1).png')}}" alt="">
                            </span>
                            <input type="name" class="form-control" placeholder="Enter Name" required name="name">
                        </div>
                        <div class="input-group mb-4">
                            <span class="input-group-text">
                                <img src="{{asset('assets/images/mail.png')}}" alt="">
                            </span>
                            <input type="email" class="form-control" placeholder="Enter Email ID" required name="email">
                        </div>
                        <div class="input-group mb-4">
                            <span class="input-group-text">
                                <img src="{{asset('assets/images/lock.png')}}" alt="">
                            </span>
                            <input type="password" class="form-control" placeholder="Password" required name="password">
                            <span class="input-group-text">
                                    <i class="fa fa-eye-slash" id="togglePassword" style="cursor: pointer;"></i>
                                </span>
                        </div>
                        <div class="input-group mb-4">
                            <span class="input-group-text">
                                <img src="{{asset('assets/images/tablet.png')}}" alt="">
                            </span>
                            <input type="mobile" class="form-control" placeholder="Enter Mobile No." required name="mobile">
                        </div>
                        <button type="submit" class="btn btn-danger w-100" id="btnRegistration">Register</button>
                           @csrf     
                    </form>
                     <div id="thank_you_msg" class="field_error"></div>
                 <!--    <div class="text-center mt-4">
                        <p>If you have an account? <a href="#" class="text-danger"><b>Login now!</b></a></p>
                    </div> -->
                </div>
          </div>
      </div>
  </div>


@endsection
