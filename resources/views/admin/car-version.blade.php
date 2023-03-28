@extends('admin.layout.main')
@section('title', 'Vehicle Version ')
<?php

use App\Http\Controllers\CommonController; ?>
@section('content')
<style>
    .show-icon {
        z-index: 999999 ! important;
    }

</style>

<div class="modal fade show popup-small" id="add-honda-modal" tabindex="-1" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body">
                <form action="{{route('admin.vehicle-version.store')}}" method="POST">
                    {{csrf_field()}}
                    <h2 class="modal-heading text-center my-3 mb-5">Add Version</h2>

                    <div class="d-flex align-items-center justify-content-center mb-3">
                        <label class="white-space-nowrap me-2 w-35">Version Name</label>
                        <input type="text" class="form-control" placeholder="Name" id="name" name="name">
                    </div>
                    <div class="d-flex align-items-center justify-content-center mb-5">
                        <label class="white-space-nowrap me-2 w-35">Model</label>
                        <div class="position-relative w-100">
                            <select class="form-control" id="model_id" name="model_id">
                                @foreach ($models as $model)
                                <option value="{{$model->id}}">{{$model->name}} {{$model->name}}</option>
                                @endforeach
                            </select>
                            <img src="{{URL::asset('admin-panel/images/dchevron.png')}}" class="dchevron">
                        </div>
                    </div>
                    <div class="d-flex align-items-center justify-content-center mb-3">
                        <label class="white-space-nowrap me-2 w-35">Year</label>
                        <div class="position-relative w-100">
                            <select class="form-control" id="from_year" name="from_year">
                                <option value="0" disabled selected>From</option>
                                @foreach ((new CommonController())->GetYearsList() as $year)
                                <option value="{{$year}}">{{$year}}</option>
                                @endforeach
                            </select>
                            <img src="{{URL::asset('admin-panel/images/dchevron.png')}}" class="dchevron">
                        </div>

                        <div class="position-relative w-100">
                            <select class="form-control" id="to_year" name="to_year">
                                <option value="0" disabled selected>To</option>
                                @foreach ((new CommonController())->GetYearsList() as $year)
                                <option value="{{$year}}">{{$year}}</option>
                                @endforeach
                            </select>
                            <img src="{{URL::asset('admin-panel/images/dchevron.png')}}" class="dchevron">
                        </div>

                    </div>
                    <label class="custom-checkbox mb-5">
                        Mark Popular:
                        <input type="checkbox" checked="checked" name="mark_popular">
                        <span class="checkmark"></span>
                    </label>
                    <div class="text-center">
                        <button data-bs-dismiss="modal" type="submit" class="btn btn-primary">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="inner-content">

    <div class="d-flex align-items-center">
        <h4 class="page-heading my-4">Vehicle Version</h4>
    </div>

    @foreach ($models as $model)
    <div class="vmbox mb-3">
        <div class="row w-100 align-items-center d-flex">
            <div class="col-xl-6">
                <div class="d-flex align-items-center pt-1">
                    {{-- <img src="{{$model->logo}}" class="mx-4"> --}}
                    <h4 class="mx-4">{{$model->name}} {{$model->name}}</h4>
                </div>
            </div>
            <div class="col-xl-6">
                <div class="d-flex align-items-center justify-content-end">
                    <div class="mx-4 text-center">
                        <p>Total Versions Registered</p>
                        <h5 class="m-0">{{$model->RegisteredVersionsCount()}}</h5>
                    </div>
                    <button class="btn btn-primary mx-4" onclick="OpenAddModal({{$model->id}})">+ Add
                        Version</button>
                    <a data-bs-toggle="collapse" href="#collapseModel_{{$model->id}}"><img src="{{URL::asset('admin-panel/images/downcaret.png')}}" class="mx-4"></a>
                </div>
            </div>
        </div>
    </div>
    <div class="collapse" id="collapseModel_{{$model->id}}">
        <div class="collap-body shadow-sm show-icon">
            <table class="table table-hover">
                <tr>
                    <th>Version Name</th>
                    <th>Make</th>
                    <th>Type</th>
                    <th>Years</th>
                    <th class="text-end">Actions</th>
                </tr>
                @foreach ($model->version as $version)
                <tr>
                    <td>{{$version->name}}</td>
                    <td>{{$version->model->brand->name}}</td>
                    <td>{{$version->model->type->name}}</td>
                    <td>{{$version->from_year}} - {{$version->to_year}}</td>
                    <td>

                        <div class="d-flex align-ites-center justify-content-end">
                            <div class="mb-0 mb-lg-3 d-inline-block me-4 me-lg-0">
                                <a href="javascript:OpenEditModal({{$version->id}})">
                                    <img src="{{URL::asset('admin-panel/images/edit.png')}}">
                                </a>
                            </div>
                            <form action="{{ route('admin.vehicle-version.destroy', $version->id) }}" method="POST" id="delete_form_{{$version->id}}">
                                {{ csrf_field() }}
                                <input type="hidden" name="_method" value="DELETE">
                                <a onclick="return confirm('Are you sure?')" href="javascript:Delete({{$version->id}});">
                                    <img src="{{URL::asset('admin-panel/images/del.png')}}" class="ms-3">
                                </a>
                            </form>
                        </div>
                        <div class="modal fade show popup-small" id="edit-vtype-popup_{{$version->id}}" tabindex="-1" role="dialog">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-body">
                                        <form action="{{route('admin.vehicle-version.update', $version->id)}}" method="POST">
                                            {{csrf_field()}}
                                            <input type="hidden" name="_method" value="PATCH">
                                            <h2 class="modal-heading text-center my-3">Edit Vehicle Version</h2>
                                            {{-- <div class="d-flex justify-content-md-center justify-content-start mb-5">
                                                <div class="uploaded-img me-3">
                                                    <img src="{{URL::asset('admin-panel/images/img-icon.png')}}">
                                    </div>
                                </div> --}}
                                <div class="d-flex align-items-center justify-content-center mb-5">
                                    <label class="white-space-nowrap me-2">Version Name</label>
                                    <input type="text" class="form-control" id="name" name="name" placeholder="Car Make" value="{{$version->name}}">
                                    <input hidden type="text" class="form-control" id="version_id" name="version_id" value="{{$version->id}}">
                                </div>
                                <div class="d-flex align-items-center justify-content-center mb-5">
                                    <label class="white-space-nowrap me-2 w-35">Type</label>
                                    <div class="position-relative w-100">
                                        <select class="form-control" id="model_id" name="model_id">
                                            @foreach ($models as $model)
                                            <option value="{{$model->id}}" @if($model->id == $version->model_id) {{"selected"}} @endif>{{$model->name}}</option>
                                            @endforeach
                                        </select>
                                        <img src="{{URL::asset('admin-panel/images/dchevron.png')}}" class="dchevron">
                                    </div>
                                </div>
                                <div class="d-flex align-items-center justify-content-center mb-3">
                                    <label class="white-space-nowrap me-2 w-35">Year</label>
                                    <div class="position-relative w-100">
                                        <select class="form-control" id="from_year" name="from_year">
                                            <option value="0">From</option>
                                            @foreach ((new CommonController())->GetYearsList() as $year)
                                            <option value="{{$year}}" @if($year == $version->from_year) {{"selected"}} @endif>{{$year}}</option>
                                            @endforeach
                                        </select>
                                        <img src="{{URL::asset('admin-panel/images/dchevron.png')}}" class="dchevron">
                                    </div>

                                    <div class="position-relative w-100">
                                        <select class="form-control" id="to_year" name="to_year">
                                            <option value="0">To</option>
                                            @foreach ((new CommonController())->GetYearsList() as $year)
                                            <option value="{{$year}}" @if($year == $version->to_year) {{"selected"}} @endif>{{$year}}</option>
                                            @endforeach
                                        </select>
                                        <img src="{{URL::asset('admin-panel/images/dchevron.png')}}" class="dchevron">
                                    </div>

                                </div>
                                <div class="text-center">
                                    <button data-bs-dismiss="modal" type="submit" class="btn btn-primary">Save</button>
                                </div>
                                </form>
                            </div>
                        </div>
                    </td>
                </tr>
                @endforeach
            </table>
        </div>
    </div>
    @endforeach


<div class="row">
    {{$models->links()}}
</div>



</div>

@endsection

@section('scripts')

<script>
    function Delete(id) {
        $("#delete_form_" + id).submit();
    }

    function OpenEditModal(id) {
        $("#edit-vtype-popup_" + id).modal("show");
    }

    function OpenAddModal(id) {
        $("#model_id").val(id);
        $("#add-honda-modal").modal("show");
    }
</script>


@endsection
