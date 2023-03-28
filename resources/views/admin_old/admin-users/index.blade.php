@extends('admin.layouts.layout')
@section('title', 'Admins ')
@section('content')
<?php use App\Http\Controllers\CommonController; ?>

<div class="container-fluid px-4">
    <h3 class="mt-4">Admins</h3>
    <div class="card mb-4">
        <div class="card-header">
            <a href="{{route('admin.admin-users.create')}}" class="btn btn-success btn-icon-split" style="float: right;">
                <span class="icon text-white-50">
                    <i class="fas fa-plus"></i>
                </span>
                <span class="text">Add New Admin</span>
            </a>
        </div>
        <div class="card-body">
            <table id="datatablesSimple">
                <thead>
                    <tr>
                        <th>Sr#</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tfoot>
                    <tr>
                        <th>Sr#</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Actions</th>
                    </tr>
                </tfoot>
                <tbody>
                    @foreach ($users as $user)
                        <tr>
                        <td>{{$user->id}}</td>
                        <td>{{$user->name}}</td>
                        <td>{{$user->email}}</td>
                        <td>
                            @if($user->role == null && $user->role_id == 0)
                            Super Admin
                            @elseif($user->role != null)
                            {{$user->role->name}}
                            @else
                            N/A
                            @endif
                        </td>
                        <td  align="right">
                            <form action="{{ route('admin.admin-users.destroy', $user->id) }}" method="POST">
                                    {{ csrf_field() }}
                                    <input type="hidden" name="_method" value="DELETE">
                                    <a href="{{route('admin.admin-users.edit',$user->id)}}" class="btn btn-warning btn-circle" title="Edit Current Record" style="display: none">
                                        <i class="fas fa-exclamation-triangle"></i>
                                    </a>
                                    <button class="btn btn-danger btn-circle" onclick="return confirm('Are you sure?')" title="Delete Current Record" >
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
</div>

@endsection
