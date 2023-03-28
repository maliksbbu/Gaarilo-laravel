<div class="header-panel d-flex justify-content-between align-items-center">
    <h2 class="header-heading">@yield('title')</h2>

    <div class="dropdown avatar-dropdown ms-auto">
        <a class="dropdown-toggle" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
            <img src="{{URL::asset('admin-panel/images/downcaret.png')}}" class="me-2">
            <img src="{{URL::asset('admin-panel/images/avatar.png')}}">
        </a>
        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownMenuButton1">
            <li><a class="dropdown-item" href="#">Profile</a></li>
            <li><a class="dropdown-item" href="{{ route('adminLogout') }}">Signout</a></li>
        </ul>
    </div>
</div>
