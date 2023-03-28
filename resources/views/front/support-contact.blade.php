@extends('front.layout.main')
@section('content')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.3.0/css/all.min.css" integrity="sha512-SzlrxWUlpfuzQ+pcUCosxcglQRNAq/DZjVsC0lE40xsADsfeQoEypE+enwcOiGjk/bSuGGKHEyjSoQ1zVisanQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
<form id="post_ad_form" action="{{route('save-support-contact')}}" method="POST" enctype="multipart/form-data">
    {{csrf_field()}}
    <section class="section-space pb-5">
        <div class="container-xl">
            <div id="contact-info" style="margin-top: 50px;">
                <div class="row align-items-center mb-5">
                    <div class="col-xl-3 col-lg-4 col-md-5 text-md-end text-start mb-3 mb-md-0">
                        <label> Name<span class="required">* </span> </label>
                    </div>
                    <div class="col-xl-6 col-lg-7 col-md-7">
                        <input class="form-control" type="text" id="name" name="name">
                    </div>
                </div>
                <div class="row align-items-center mb-5">
                    <div class="col-xl-3 col-lg-4 col-md-5 text-md-end text-start mb-3 mb-md-0">
                        <label>Phone<span class="required">* </span></label>
                    </div>
                    <div class="col-xl-6 col-lg-7 col-md-7">
                        <input class="form-control" type="text" placeholder="+9203000000000" id="phone" name="phone">
                    </div>
                </div>
                <div class="row align-items-center mb-5">
                    <div class="col-xl-3 col-lg-4 col-md-5 text-md-end text-start mb-3 mb-md-0">
                        <label>Email<span class="required">* </span></label>
                    </div>
                    <div class="col-xl-6 col-lg-7 col-md-7">
                        <input class="form-control" type="text" placeholder="asdf@gmail.com" id="email" name="email">
                    </div>
                </div>
                <div class="row align-items-center mb-5">
                    <div class="col-xl-3 col-lg-4 col-md-5 text-md-end text-start mb-3 mb-md-0">
                        <label>Message<span class="required">*</span></label>
                    </div>
                    <div class="col-md-6">
                        <textarea class="form-control" rows="20" id="description" name="description"></textarea>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <div class="text-end section-space">
        <div class="container-xl">
            <button class="btn btn-primary" onclick="Submit(event)">Submit & Continue</button>
        </div>
    </div>
</form>
<section class="section-space">
       
        <div class="container-xl">
        <h3 style="font-weight: bold;">Contact Us For Support</h3>
            <div id="contact-info">
                <div class="row align-items-center mb-5">
                    <div class="col-xl-3 col-lg-4 col-md-5">
                        <label>Sale Support 1</label>
                    </div>
                    <div class="col-xl-9 col-lg-4 col-md-5">
                    <a href="https://wa.me/3335073444?"><label style="cursor: pointer;">+923335073444</label><i class="fa-brands fa-whatsapp fa-2x" style="margin-left: 15px; color:green"></i></a>
                    </div>
                </div>
                <div class="row align-items-center mb-5">
                    <div class="col-xl-3 col-lg-4 col-md-5">
                        <label>Sale Support 2</label>
                    </div>
                    <div class="col-xl-9 col-lg-4 col-md-5">
                    <a href="https://wa.me/3373339330?"><label style="cursor: pointer;">+923373339330</label><i class="fa-brands fa-whatsapp fa-2x" style="margin-left: 15px; color:green"></i></a>
                    </div>
                </div>
                <div class="row align-items-center mb-5">
                    <div class="col-xl-3 col-lg-4 col-md-5">
                        <label>Sale Support 3</label>
                    </div>
                    <div class="col-xl-9 col-lg-4 col-md-5">
                    <a href="https://wa.me/3373339332?"><label style="cursor: pointer;">+923373339332</label><i class="fa-brands fa-whatsapp fa-2x" style="margin-left: 15px;color:green"></i></a>
                    </div>
                </div>
            </div>   
        </div>
</section>
@endsection
@section('scripts')
<script type='text/javascript' src="https://rawgit.com/RobinHerbots/jquery.inputmask/3.x/dist/jquery.inputmask.bundle.js"></script>
<script>
    var maxImages = 6;

    function Submit(e) {
        e.preventDefault();
        if ($("#name").val() == "") {
            ShowToaster("Error", "Enter Name");
            return;
        }
        if ($("#phone").val() == "") {
            ShowToaster("Error", "Enter Phone Number");
            return;
        }
        if ($("#email").val() == "") {
            ShowToaster("Error", "Enter Email");
            return;
        }
        if ($("#description").val() == "") {
            ShowToaster("Error", "Enter Description");
            return;
        }
        $("#post_ad_form").submit();
    }

    $('document').ready(function() {

        $('#phone').inputmask('+999999999999');
    });
</script>
@endsection
