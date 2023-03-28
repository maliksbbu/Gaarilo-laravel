<?php
$current_url = $_SERVER['REQUEST_URI'];
?>
<nav class="sidebar">
    <div class="logo d-flex justify-content-center p-3">
        <a href="{{route('admin.dashboard')}}"><img src="{{URL::asset('front/images/blue-logo.png')}}" alt="" width="100px"></a>
        <div class="sidebar_close_icon d-lg-none">
            <i class="ti-close"></i>
        </div>
    </div>
    <ul id="sidebar_menu" class="p-0">
        <li class="main-title">
            Manage cars
        </li>
        <li>
            <a href="{{route('admin.vehicle-type.index')}}" class="{{ ($current_url == '/admin/vehicle-type') ? 'active' : '' }}">Vehicle type</a>
            <ul class="p-0">
                <li><a href="{{route('admin.vehicle-make.index')}}" class="{{ ( str_contains($current_url, '/admin/vehicle-make')) ? 'active' : '' }}">Car Make</a></li>
                <li><a href="{{route('admin.vehicle-model.index')}}" class="{{ (str_contains($current_url, '/admin/vehicle-model')) ? 'active' : '' }}">Car Model</a></li>
                <li><a href="{{route('admin.vehicle-version.index')}}" class="{{ (str_contains($current_url, '/admin/vehicle-version')) ? 'active' : '' }}">Car Version</a></li>
            </ul>
        </li>
        <li class="main-title">
            Manage More
        </li>
        <ul class="p-0">
            <li><a  href="{{route('admin.color.index')}}" class="{{ (str_contains($current_url, '/admin/color')) ? 'active' : '' }}">Car Colors</a></li>
            <li><a  href="{{route('admin.feature.index')}}" class="{{ (str_contains($current_url, '/admin/feature')) ? 'active' : '' }}">Car Features</a></li>
            <li><a  href="{{route('admin.province.index')}}" class="{{ (str_contains($current_url, '/admin/province')) ? 'active' : '' }}">Provinces</a></li>
            <li><a  href="{{route('admin.city.index')}}" class="{{ (str_contains($current_url, '/admin/city')) ? 'active' : '' }}">Cities</a></li>

            <li><a href="{{route('admin.video.create')}}">Videos & Reviews</a></li>
            <li><a href="{{route('admin.news.index')}}">News</a></li>
            <li><a href="">Send Notifications</a></li>
            <li><a href="">Send Emails</a></li>
            <li><a href="{{route('admin.target-tracker')}}">Target Tracker</a></li>
        </ul>
        <li class="main-title">
            Others
        </li>
        <ul class="p-0" onclick="myFunction(event)">
            <li><a href="{{route('admin.admin-users.index')}}">Admins</a></li>
            <li><a href="{{route('admin.viewSettings')}}">Settings</a></li>

        </ul>
    </ul>
</nav>
<script>
    window.onload = function() {
        var test = window.location.href;
    };


    function myFunction(e) {
        var elems = document.querySelector(".active");
        if (elems !== null) {
            elems.classList.remove("active");
        }
        e.target.className = "active";
    }
</script>
