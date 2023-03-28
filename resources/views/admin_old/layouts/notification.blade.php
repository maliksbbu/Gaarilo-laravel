@if (count($errors) > 0)
<div class="alert alert-danger" style="margin-top: 0%;">
    <ul>
        @foreach ($errors->all() as $error)
        <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif

@if(Session::has('notify_error'))
<div class="alert alert-danger" style="margin-top: 0%;">
    <li>{{ Session::get('notify_error') }}</li>
</div>
@endif

@if(Session::has('notify_success'))
<div class="alert alert-success" style="margin-top: 0%;">
    <li> {{ Session::get('notify_success') }}</li>
</div>
@endif
