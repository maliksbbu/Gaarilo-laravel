@extends('front.layout.auth-layout')
@section('content')
<style>
    a.disabled {
  pointer-events: none;
  cursor: default;
}
</style>

<div class="col-lg-7 ps-0 order-1 order-lg-2" id="login-enter-phone" >
    <div class="container px-2 px-lg-0  ms-lg-0 me-lg-0">
        <div class="login-right">
            <h3>Login To Buy/Sell Your Vehicle</h3>
            <p>Don’t have an account? <a href="{{route('signup')}}">Sign up</a></p>


            <div class="row">
                <div class="col-xl-7 mb-5">
                    <div class="logtel-field">
                        <div class="choose-country">
                            <input id="country_selector" type="text">
                        </div>
                        <div class="telenumber">
                            <input class="form-control" type="text" placeholder="Phone Number" id="phone_number" name=phone_number>
                        </div>
                    </div>
                </div>
                <div class="col-xl-7">
                    <button class="btn btn-primary w-100" onclick="Login()">LOGIN</button>
                </div>
            </div>
        </div>

    </div>
</div>


<div class="col-lg-7 ps-0 order-1 order-lg-2"  id="otp-enter" style="display: none">
    <div class="container px-2 px-lg-0  ms-lg-0 me-lg-0">
        <div class="login-right">
            <h3 class="otp-heading">Login To Buy/Sell Your Vehicle</h3>

            <h5 class="mb-5">An OTP code has been sent to <a class="text-primary" id="user_phone"></a></h5>


            <div class="row">
                <div class="col-xxl-7 col-xl-10 mb-5">
                    <div class="otp-box">
                        <input type="text" class="form-control" placeholder="*" id="otp1" onkeyup="OnKeyPressOTP(event,'1')" onkeydown="OnKeyDown('1')">
                        <input type="text" class="form-control" placeholder="*" id="otp2" onkeyup="OnKeyPressOTP(event,'2')" onkeydown="OnKeyDown('2')">
                        <input type="text" class="form-control" placeholder="*" id="otp3" onkeyup="OnKeyPressOTP(event,'3')" onkeydown="OnKeyDown('3')">
                        <input type="text" class="form-control" placeholder="*" id="otp4" onkeyup="OnKeyPressOTP(event,'4')" onkeydown="OnKeyDown('4')">
                        <input type="text" class="form-control" placeholder="*" id="otp5" onkeyup="OnKeyPressOTP(event,'5')" onkeydown="OnKeyDown('5')">
                        <input type="text" class="form-control" placeholder="*" id="otp6" onkeyup="OnKeyPressOTP(event,'6')" onkeydown="OnKeyDown('6')">
                    </div>
                </div>
                <div class="col-xxl-7 col-xl-10">
                    <button class="btn btn-primary w-100 mb-5" onclick="VerifyOTP()">LOGIN</button>
                    <p id="p_hide" style="display:none" class="resendotp">Resend OTP in <span id="countdown"></span></p>
                    <a id = "resend_otp">Resend OTP Code</a>
                </div>
            </div>
        </div>

    </div>
</div>



@endsection

@section('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.inputmask/3.3.4/jquery.inputmask.bundle.min.js"></script>
<script>
    $(document).ready(function () {
        ChangeCountryCode($("#country_selector").val());

    });

    function OnKeyDown (index)
    {
        $("#otp"+index).val('');
    }

    function OnKeyPressOTP (evt, index)
    {
        $("#otp"+index).val('');
        var keyPress = evt.keyCode;
        if((keyPress >= 48 && keyPress <= 57) || (keyPress >= 96 && keyPress <= 105))
        {

            $("#otp"+index).val(evt.key);
            index++;
            $("#otp"+index).focus();
        }
        else
        {
            if(keyPress == 8)
            {
                $("#otp"+index).val('');
            }
            $("#otp"+index).val('');
        }
    }

    function Login()
    {

        var phone = $('#phone_number').val();
        if(phone.length != 13 || phone.includes('_'))
        {
            ShowToaster("Phone Number Incomplete", "Kindly add valid phone number.");
            return;
        }
        $("#login-enter-phone").hide();
        PreLoader();

        $("#user_phone").html(phone);
        $("#user_phone1").html(phone);
        $.ajax({
            url: '{{route('webapi.sendotp')}}',
            type: 'POST',
            data: {
            phone: phone,
            _token: '{{csrf_token()}}'
            },
            success: function(data) {
                if (data.result == 1)
                {
                    PreLoader("hide");
                    $("#otp-enter").show();
                }
                else if(data.result == 2)
                {
                    window.location.href = "{{url('/')}}";
                }
                else
                {
                    PreLoader("hide");
                    $("#login-enter-phone").show();
                    ShowToaster("Error", data.message);
                }
            },
            error: function(error) {
                PreLoader("hide");
                $("#login-enter-phone").show();
                console.log(error);
            }
        });

    }

    function ChangeCountryCode(country)
    {
        var countryCode = GetCountryCode(country);
        $('input[name=phone_number]').val(countryCode);
        $('#phone_number').inputmask('+999999999999');
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

        PreLoader();
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
                PreLoader("hide");
                $("#otp-enter").show();
            }
            },
            error: function(error) {
                PreLoader("hide");
                $("#otp-enter").show();
                console.log(error);
            }
        });


    }

    $(function() {
        let counter;
        let startTime = 60;
        let timerFormat = (s) => {
        return s;
    };
            timer = () => {
            startTime--;
            document.getElementById("countdown").innerHTML = timerFormat(startTime);
            if (startTime === 0)
            {
                clearInterval(counter);
                startTime = 60;
            }

        };
        $("#resend_otp").click(function() {
            Login();
            $("#resend_otp").addClass("disabled");
            document.getElementById('p_hide').style.display = "block";
            counter = setInterval(timer, 1000);
            document.getElementById("otp1").value = "";
            document.getElementById("otp2").value = "";
            document.getElementById("otp3").value = "";
            document.getElementById("otp4").value = "";
            document.getElementById("otp5").value = "";
            document.getElementById("otp6").value = "";
            setTimeout(function() {
                $("#resend_otp").removeClass("disabled");
                document.getElementById('p_hide').style.display = "none";
            }, 60000);
        });
    });

</script>
@endsection
