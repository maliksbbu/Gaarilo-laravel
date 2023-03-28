<?php use App\Models\Admin;
use App\Http\Controllers\CommonController;
?>
<nav class="sb-sidenav accordion sb-sidenav-dark" id="sidenavAccordion">
    <div class="sb-sidenav-menu">
        <div class="nav">
            @if(Admin::checkAccess(auth()->guard('admin')->user()->id, 'dashboard'))

            <div class="sb-sidenav-menu-heading">Main</div>
             @if(Admin::checkAccess(auth()->guard('admin')->user()->id, 'dashboard'))
            <a class="nav-link" href="{{ route('admin.dashboard') }}">
                <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
                Dashboard
            </a>
            @endif



            @endif

            @if(Admin::checkAccess(auth()->guard('admin')->user()->id, 'roles') || Admin::checkAccess(auth()->guard('admin')->user()->id, 'admin'))
            <div class="sb-sidenav-menu-heading">Manage</div>
            @if(Admin::checkAccess(auth()->guard('admin')->user()->id, 'admin'))
            <a class="nav-link" href="{{ route('admin.admin-users.index') }}">
                <div class="sb-nav-link-icon"><i class="fas fa-user"></i></div>
                Admins
            </a>
            @endif

            @if(Admin::checkAccess(auth()->guard('admin')->user()->id, 'roles'))
            <a class="nav-link" href="{{ route('admin.roles.index') }}">
                <div class="sb-nav-link-icon"><i class="fas fa-user-tag"></i></div>
                Admin Roles
            </a>
            @endif
            @endif

        </div>
    </div>
    <div class="sb-sidenav-footer">
        <div class="small">Logged in as:</div>
        {{auth()->guard('admin')->user()->name}}
    </div>
</nav>
