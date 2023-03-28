@extends('admin.layouts.layout')
@section('title', 'Roles ')
@section('content')
<?php use App\Http\Controllers\CommonController; ?>

<div class="container-fluid px-4">
    <h3 class="mt-4">Roles</h3>
    <div class="card mb-4">
        <div class="card-header">
            <a href="{{route('admin.roles.create')}}" class="btn btn-success btn-icon-split" style="float: right;">
                <span class="icon text-white-50">
                    <i class="fas fa-plus"></i>
                </span>
                <span class="text">Add New Role</span>
            </a>
        </div>
        <div class="card-body">
            <table id="datatablesSimple">
                <thead>
                    <tr>
                        <th>Sr#</th>
                        <th>Name</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tfoot>
                    <tr>
                        <th>Sr#</th>
                        <th>Name</th>
                        <th>Actions</th>
                    </tr>
                </tfoot>
                <tbody>
                    @foreach ($roles as $user)
                        <tr>
                        <td>{{$user->id}}</td>
                        <td>{{$user->name}}</td>
                        <td align="right">
                            <form action="{{ route('admin.roles.destroy', $user->id) }}" method="POST">
                                    {{ csrf_field() }}
                                    <input type="hidden" name="_method" value="DELETE">
                                    <a href="{{route('admin.roles.edit',$user->id)}}" class="btn btn-warning btn-circle" title="Edit Current Record">
                                        <i class="fas fa-exclamation-triangle"></i>
                                    </a>
                                    <button class="btn btn-danger btn-circle" onclick="return confirm('Are you sure?')" title="Delete Current Record" style="display: none">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    <p style="font-size: 1em"><b>Note:</b> Super admin is created by default</p>
</div>

@endsection
