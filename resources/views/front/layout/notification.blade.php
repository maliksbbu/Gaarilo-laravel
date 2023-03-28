@if (count($errors) > 0)
<div class="toaster shadow-sm" id="toaster-message-notify">
<h4 class="mb-0" id="toaster-title">{{"Error"}}</h4>
<p class="mb-0" id="toaster-content">
    @foreach ($errors->all() as $error)
    {{$error}}
    @endforeach
</p>
</div>
@endif

@if(Session::has('notify_error'))
<div class="toaster shadow-sm" id="toaster-message-notify">
    <h4 class="mb-0" id="toaster-title">{{"Error"}}</h4>
    <p class="mb-0" id="toaster-content">{{Session::get('notify_error')}}</p>
</div>
@endif

@if(Session::has('notify_success'))
<div class="toaster shadow-sm" id="toaster-message-notify">
    <h4 class="mb-0" id="toaster-title">{{"Success"}}</h4>
    <p class="mb-0" id="toaster-content">{{Session::get('notify_success')}}</p>
</div>
@endif

<script>
    $(document).ready(function(){
        $("#toaster-message-notify").fadeOut(7000);

    });
</script>
