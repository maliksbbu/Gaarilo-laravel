

@if (count($errors) > 0)
<div class="position-fixed bottom-0 end-0 p-3" style="z-index: 11">
    <div id="liveToast" class="toast show" role="alert" aria-live="assertive" aria-atomic="true" style="background-color: red; color: white;">
        <div class="toast-header">
            <strong class="me-auto">Error</strong>
            <button type="button" class="btn-close" data-bs-dismiss="toast"
                onclick='$(".toast").addClass("hide");$(".toast").removeClass("show");' aria-label="Close"></button>
        </div>
        <div class="toast-body">
           <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
        </div>
    </div>
</div>
@endif

@if(Session::has('notify_error'))
<div class="position-fixed bottom-0 end-0 p-3" style="z-index: 11">
    <div id="liveToast" class="toast show" role="alert" aria-live="assertive" aria-atomic="true" style="background-color: red; color: white;">
        <div class="toast-header">
            <strong class="me-auto">Error</strong>
            <button type="button" class="btn-close" data-bs-dismiss="toast"
                onclick='$(".toast").addClass("hide");$(".toast").removeClass("show");' aria-label="Close"></button>
        </div>
        <div class="toast-body">
            <li>{{ Session::get('notify_error') }}</li>
        </div>
    </div>
</div>
@endif

@if(Session::has('notify_success'))
<div class="position-fixed bottom-0 end-0 p-3" style="z-index: 11">
    <div id="liveToast" class="toast show" role="alert" aria-live="assertive" aria-atomic="true" style="background-color: green; color: white;">
        <div class="toast-header">
            <strong class="me-auto">Success</strong>
            <button type="button" class="btn-close" data-bs-dismiss="toast"
                onclick='$(".toast").addClass("hide");$(".toast").removeClass("show");' aria-label="Close"></button>
        </div>
        <div class="toast-body">
            <li> {{ Session::get('notify_success') }}</li>
        </div>
    </div>
</div>
@endif
