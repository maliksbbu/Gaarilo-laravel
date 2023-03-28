@extends('admin.layouts.layout')
@section('title', 'Edit Role ')
@section('content')
<?php
use App\Models\Admin;
?>

<div class="container-fluid px-4">
    <h3 class="mt-4">Edit Role</h3>
    <div class="card mb-4">
        <div class="card-header">
            <a href="{{route('admin.roles.index')}}" class="btn btn-secondary btn-icon-split" style="float: right;">
                <span class="icon text-white-50">
                    <i class="fas fa-arrow-left"></i>
                </span>
                <span class="text">View Roles</span>
            </a>
        </div>
        <div class="card-body">
            <form class="form-horizontal" action="{{route('admin.roles.update', $role->id)}}" method="POST" enctype="multipart/form-data" role="form">
                {{csrf_field()}}
                <input type="hidden" name="_method" value="PATCH">
                <div class="row">
                    <div class="col-sm-2"><label class=" form-label" for="name"> Name: </label></div>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" id="name" name="name" placeholder="Role Name" value="{{$role->name}}" disabled>
                    </div>
                </div>
                <br>

                <div class="row">
                    <div class="col-sm-2"><label class=" form-label" for="name"> Rights: </label></div>
                    <div class="col-sm-10">

                        <?php
                            $editArray = json_decode($role->role_json)
                        ?>

                        <table class="table">
                            <tbody>
                                @foreach ($rights as $key => $right)
                                    <tr>
                                        <td>
                                            <input type="checkbox" class="form-check-input"  id="checked_roles" name="checked_roles[]" value="{{$key}}" <?php if(in_array($key,$editArray)){echo "checked";} ?>>
                                        </td>
                                        <td>
                                            <label class=" form-label" for="checked_roles"> {{$right}} </label>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>

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
