@extends('admin.layouts.layout')
@section('title', 'Add Admin ')
@section('content')
<?php use App\Http\Controllers\CommonController; ?>

<div class="container-fluid px-4">
    <h3 class="mt-4">Add Admin</h3>
    <div class="card mb-4">
        <div class="card-header">
            <a href="{{route('admin.admin-users.index')}}" class="btn btn-secondary btn-icon-split" style="float: right;">
                <span class="icon text-white-50">
                    <i class="fas fa-arrow-left"></i>
                </span>
                <span class="text">View Admins</span>
            </a>
        </div>
        <div class="card-body">
            <form class="form-horizontal" action="{{route('admin.admin-users.store')}}" method="POST" enctype="multipart/form-data" role="form">
                {{csrf_field()}}
                <div class="row">
                    <div class="col-sm-2"><label class=" form-label" for="name"> Name: </label></div>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" id="name" name="name" placeholder="Username">
                    </div>
                </div>
                <br>
                <div class="row">
                    <div class="col-sm-2"><label class=" form-label" for="email"> Email: </label></div>
                    <div class="col-sm-10">
                        <input type="email" class="form-control" id="email" name="email" placeholder="Email Address">
                    </div>
                </div>
                <br>
                <div class="row">
                    <div class="col-sm-2"><label class=" form-label" for="password"> Password: </label></div>
                    <div class="col-sm-10">
                        <input type="password" class="form-control" id="password" name="password" placeholder="Enter Password">
                    </div>
                </div>
                <br>
                <div class="row">
                    <div class="col-sm-2"><label class=" form-label" for="confirm_password"> Confirm Password: </label></div>
                    <div class="col-sm-10">
                        <input type="password" class="form-control" id="confirm_password" name="confirm_password" placeholder="Confirm Password">
                    </div>

                </div>
                <br>
                <div class="row">
                    <div class="col-sm-2"><label class=" form-label" for="role_id"> Role: </label></div>
                    <div class="col-sm-10">
                        <select class="form-control" id="role_id" name="role_id">
                            <option value="0">Super Admin</option>
                            @foreach ($roles as $role)
                                <option value="{{$role->id}}">{{$role->name}}</option>
                            @endforeach
                        </select>
                    </div>

                </div>
                <br>



                <button class="btn btn-primary btn-icon-split" style="float: right;" type="submit">
                    <span class="icon text-white-50">
                    </span>
                    <span class="text">Save</span>
                </button>
            </form>
        </div>
    </div>
</div>

@endsection
