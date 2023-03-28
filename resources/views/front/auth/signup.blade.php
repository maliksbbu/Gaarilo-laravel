@extends('front.layout.auth-layout')
@section('content')



<div class="col-lg-7 ps-0 order-1 order-lg-2">
    <div class="container px-2 px-lg-0  ms-lg-0 me-lg-0">
        <div class="login-right">
            <h3>Sign Up To Buy/Sell Your Vehicle</h3>
            <p>Already have an account? <a href="{{route('login')}}">Sign in</a></p>

            <form id="signup_form" action="{{route('signup.post')}}" method="POST" enctype="multipart/form-data">
                {{csrf_field()}}
                <input type="hidden" id="is_business" name="is_business" value="YES" />
                <div class="row">
                    <div class="col-xl-7">
                        <div class="row">
                            <div class="col-sm-6 mb-4">
                                <input type="text" class="form-control" placeholder="First Name" id="first_name"
                                    name="first_name" value="{{old('first_name')}}">
                            </div>
                            <div class="col-sm-6 mb-4">
                                <input type="text" class="form-control" placeholder="Last Name" id="last_name"
                                    name="last_name" value="{{old('last_name')}}">
                            </div>
                            <div class="col-sm-12 mb-4">
                                <input type="email" class="form-control" placeholder="Email" id="email" name="email"
                                    value="{{old('email')}}">
                            </div>
                            <div class="col-sm-12 mb-4">
                                <input type="text" class="form-control" placeholder="Phone Number" name="phone"
                                    id="phone" value="+92{{old('phone') ? str_replace('+92','', old('phone')): ''}}">
                            </div>
                        </div>
                        <div class="img-box">
                            <div class="me-2">
                                <img id="preview-normal_image" src="{{URL::asset('front/images/logavatar.png')}}">
                            </div>
                            <div class="d-flex align-items-end flex-column">
                                <span class="mb-4">
                                    Profile Picture
                                </span>
                                <button class="btn btn-primary btn-sm" id="profile_image_button">Select</button>
                                <input type="file" name="image" id="image" accept="image/*">
                            </div>
                        </div>


                        <label class="custom-checkbox my-5 white-space-nowrap">
                            To post an Ad you must enter &nbsp<span class="text-primary">Showroom Details</span>
                            <input type="checkbox" id="business_detail_checkbox" checked>
                            <span class="checkmark"></span>
                        </label>
                        <div id="business_details" class="mb-1">
                            <div class="row">
                                <div class="col-sm-12 mb-4">
                                    <input type="text" class="form-control" placeholder="Showroom Name"
                                        id="business_name" name="business_name" value="{{old('business_name')}}"  maxlength="30">
                                </div>
                                <div class="col-sm-12 mb-4">
                                    <input type="email" class="form-control" placeholder="Showroom Email"
                                        id="business_email" name="business_email" value="{{old('business_email')}}">
                                </div>
                                <div class="col-sm-12 mb-4">
                                    <input type="text" class="form-control" placeholder="Showroom Phone Number"
                                        id="business_phone_number" name="business_phone_number"
                                        value="+92{{old('business_phone_number') ? str_replace('+92','', old('business_phone_number')): ''}}">
                                </div>
                                <div class="col-sm-12 mb-4">
                                        <select class="form-control" id="city" name="city">
                                            <option value="" selected disabled>Select City</option>
                                            @foreach ($cities as $city)
                                                <option value="{{$city->id}}">{{strtoupper($city->name)}}</option>
                                            @endforeach
                                        </select>
                                </div>
                                <div class="col-sm-12 mb-4">
                                    <div class="position-relative" data-bs-toggle="modal"
                                        data-bs-target="#signup-address-popup">
                                        <input type="text" class="form-control" placeholder="Address" id="main_address"
                                            name="main_address" value="{{old('main_address')}}">
                                        <img src="{{URL::asset('front/images/locicon.png')}}" class="locicon">
                                        <input type="hidden" value="33.6844" name="latitude" id="latitude" />
                                        <input type="hidden" value="73.0479" name="longitude" id="longitude" />
                                        <input type="hidden" value="" name="address" id="address" />
                                    </div>
                                </div>
                            </div>
                            <div class="img-box">
                                <div class="me-2">
                                    <img id="preview-business_image" src="{{URL::asset('front/images/logavatar.png')}}">
                                </div>
                                <div class="d-flex align-items-end flex-column">
                                    <span class="mb-4">
                                        Profile Picture
                                    </span>
                                    <button class="btn btn-primary btn-sm" id="business_image_button">Select</button>
                                    <input type="file" name="business_image" id="business_image" accept="image/*">
                                </div>
                            </div>
                        </div>
                        <label class="custom-checkbox my-5 white-space-nowrap">
                            I agree with the &nbsp<span class="text-primary"><a href="{{url('terms')}}">Terms of service & </a><a href="{{url('privacy')}}">privacy policy</a></span>
                            <input type="checkbox" id="agree_terms_conditions">
                            <span class="checkmark"></span>
                        </label>
                        <button class="btn btn-primary w-100" onclick="SignUp(event)">SIGNUP</button>
                    </div>
                </div>
            </form>

        </div>
    </div>

</div>

<!-- Address Modal -->
@endsection

@section('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.inputmask/3.3.4/jquery.inputmask.bundle.min.js"></script>
<script>
    $(document).ready(function () {
        $('#phone').inputmask('+999999999999');
        $('#business_phone_number').inputmask('+999999999999');
        $("#profile_image_button").click(function(e){
            e.preventDefault();
            $("#image").click();
        });
        $("#business_image_button").click(function(e){
            e.preventDefault();
            $("#business_image").click();
        });
        // $("#otp1").focus(function(e) {
        // $("#otp1").val('');
        // });
        // $("#otp1").on('keyup', function(e) {
        // $("#otp2").focus();
        // $("#otp2").val('');
        // });
        // $("#otp2").on('keyup', function(e) {
        // $("#otp3").focus();
        // $("#otp3").val('');
        // });
        // $("#otp3").on('keyup', function(e) {
        // $("#otp4").focus();
        // $("#otp4").val('');
        // });
        // $("#otp4").on('keyup', function(e) {
        // $("#otp5").focus();
        // $("#otp5").val('');
        // });
        // $("#otp5").on('keyup', function(e) {
        // $("#otp6").focus();
        // $("#otp6").val('');
        // });
        $("#business_detail_checkbox").change(function () {
            if($("#business_detail_checkbox").is(':checked'))
            {
                $("#business_details").show();
                $("input[name=is_business]").val("YES");
            }
            else
            {
                $("#business_details").hide();
                $("input[name=is_business]").val("NO");
            }
        });
    });
    function SignUp(e)
    {
        e.preventDefault();
        var phone = $('#phone').val();
        if(phone.length != 13 || phone.includes('_'))
        {
            ShowToaster("Phone Number Incomplete", "Kindly add valid phone number.");
            return;
        }
        if(!$("#agree_terms_conditions").is(':checked'))
        {
            ShowToaster("Agree With Terms & Conditions First", "");
            return;
        }
        if($("#first_name").val() == "")
        {
            ShowToaster("First Name", "Add First Name");
            return;
        }
        if($("#last_name").val() == "")
        {
            ShowToaster("Last Name", "Add Last Name");
            return;
        }
        if($("#image").val() == "")
        {
            ShowToaster("Profile Image", "Add Profile Image");
            return;
        }
        if($("#email").val() == "")
        {
            ShowToaster("Email", "Add Email");
            return;
        }
        if(!$("#business_detail_checkbox").is(':checked'))
        {
            $("#signup_form").submit();
        }
        else
        {
            var business_phone = $('#business_phone_number').val();
            if(business_phone.length != 13 || business_phone.includes('_'))
            {
            ShowToaster("Business Phone Number Incomplete", "Kindly add valid business phone number.");
            return;
            }
            if($("#city").val() == "")
            {
            ShowToaster("City", "Kindly Select City");
            return;
            }
            if($("#main_address").val() == "")
            {
                ShowToaster("Enter Address", "Kindly add valid business address.");
                return;
            }
            if($("#business_name").val() == "")
            {
                ShowToaster("Business Name", "Add Business Name");
                return;
            }
            if($("#business_email").val() == "")
            {
                ShowToaster("Business Email", "Add Business Email");
                return;
            }
            if($("#business_image").val() == "")
            {
                ShowToaster("Business Image", "Add Business Image");
                return;
            }
            $("#signup_form").submit();
        }
    }
    function IsOTP (evt,index)
    {
        if($("#otp"+index).val().length < 1)
        {
            evt = (evt) ? evt : window.event;
            var charCode = (evt.which) ? evt.which : evt.keyCode;
            if (charCode > 31 && (charCode < 48 || charCode> 57)) {
                return false;
                }
                return true;
        }
        return false;
    }
    function VerifyOTP ()
    {
        if($("#otp1").val() == "" || $("#otp2").val() == "" || $("#otp3").val() == "" || $("#otp4").val() == "" || $("#otp5").val() == "" || $("#otp6").val() == "")
        {
            ShowToaster("OTP Incomplete", "Kindly fill all 6 digits of OTP.");
            return;
        }
        $("#otp-loader").show();
        $("#otp-enter").hide();
        var numberOtp = $("#otp1").val()+$("#otp2").val()+$("#otp3").val()+$("#otp4").val()+$("#otp5").val()+$("#otp6").val();
        var phone = $('#phone_number').val();
        $.ajax({
        url: '{{route('webapi.verifyotp')}}',
        type: 'POST',
        data: {
        phone: phone,
        otp: numberOtp,
        _token: '{{csrf_token()}}'
        },
        success: function(data) {
            if (data.result == 1)
            {
                window.location.href = "{{url('/')}}";
            }
            else
            {
                ShowToaster("Error", data.message);
                $("#otp-loader").hide();
                $("#otp-enter").show();
            }
            },
            error: function(error) {
                $("#otp-loader").hide();
                $("#otp-enter").show();
                console.log(error);
            }
        });
    }
 $(document).ready(function (e) {
    $('#image').change(function(){
     let reader = new FileReader();
     reader.onload = (e) => {
       $('#preview-normal_image').attr('src', e.target.result);
       $('#preview-normal_image').attr('width', '100%');
     }
     reader.readAsDataURL(this.files[0]);
    });
 });
 $(document).ready(function (e) {
  $('#business_image').change(function(){
   let reader = new FileReader();
   reader.onload = (e) => {
     $('#preview-business_image').attr('src', e.target.result);
     $('#preview-business_image').attr('width', "100%");
   }
   reader.readAsDataURL(this.files[0]);
  });
});
</script>
@endsection
