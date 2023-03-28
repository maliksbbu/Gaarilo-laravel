@extends('admin.layout.main')
@section('title', 'Vehicle Model ')

@section("body")
<div class="modal fade show popup-small" id="add-honda-modal" tabindex="-1" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body">
                <form action="{{route('admin.vehicle-model.store')}}" method="POST">
                    {{csrf_field()}}
                    <h2 class="modal-heading text-center my-3 mb-5">Add Model</h2>

                    <input type="hidden" id="brand_id" name="brand_id" value="">
                    <div class="d-flex align-items-center justify-content-center mb-3">
                        <label class="white-space-nowrap me-2 w-35">Modal Name</label>
                        <input type="text" class="form-control" placeholder="Name" id="name" name="name">
                    </div>
                    <div class="d-flex align-items-center justify-content-center mb-5">
                        <label class="white-space-nowrap me-2 w-35">Type</label>
                        <div class="position-relative w-100">
                            <select class="form-control" id="type_id" name="type_id">
                                @foreach ($types as $type)
                                <option value="{{$type->id}}">{{$type->name}}</option>
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
@endsection

@section('content')
<style>
    .show-icon {
        z-index: 0 ! important;
    }
</style>

<div class="inner-content">

    <div class="d-flex align-items-center">
        <h4 class="page-heading my-4">Vehicle Models</h4>
    </div>

    @foreach ($brands as $brand)
    <div class="vmbox mb-3">
        <div class="row w-100 align-items-center d-flex">
            <div class="col-xl-6">
                <div class="d-flex align-items-center pt-1">
                    <img src="{{$brand->logo}}" class="mx-4 vmimg">
                    <h4 class="m-0">{{$brand->name}}</h4>
                </div>
            </div>
            <div class="col-xl-6">
                <div class="d-flex align-items-center justify-content-end">
                    <div class="mx-4 text-center">
                        <p>Total Model Registered</p>
                        <h5 class="m-0">{{$brand->RegisteredModelsCount()}}</h5>
                    </div>
                    <button class="btn btn-primary mx-4" onclick="OpenAddModal({{$brand->id}})">+ Add
                        Model</button>
                    <a data-bs-toggle="collapse" href="#collapseModel_{{$brand->id}}"><img src="{{URL::asset('admin-panel/images/downcaret.png')}}" class="mx-4"></a>
                </div>
            </div>
        </div>
    </div>
    <div class="collapse" id="collapseModel_{{$brand->id}}">
        <div class="collap-body shadow-sm show-icon">
            <table class="table table-hover">
                <tr>
                    <th>Model Name</th>
                    <th>Vehicle Type</th>
                    <th class="text-end">Actions</th>
                </tr>
                @foreach ($brand->model as $model)
                <tr>
                    <td>{{$model->name}}</td>
                    <td>
                        @if($model->type != null)
                        {{$model->type->name}}
                        @else
                        N/A
                        @endif
                    </td>
                    <td>

                        <div class="d-flex align-ites-center justify-content-end">
                            <div class="mb-0 mb-lg-3 d-inline-block me-4 me-lg-0">
                                <a href="javascript:OpenEditModal({{$model->id}})">
                                    <img src="{{URL::asset('admin-panel/images/edit.png')}}">
                                </a>
                            </div>
                            <div class="d-inline-block">
                                <form action="{{ route('admin.vehicle-model.destroy', $model->id) }}" method="POST" id="delete_form_{{$model->id}}">
                                    {{ csrf_field() }}
                                    <input type="hidden" name="_method" value="DELETE">

                                    <a onclick="return confirm('Are you sure?')" href="javascript:Delete({{$model->id}});">
                                        <img src="{{URL::asset('admin-panel/images/del.png')}}" class="ms-3">
                                    </a>
                                </form>
                            </div>
                            <div class="modal fade show popup-small" id="edit-vtype-popup_{{$model->id}}" tabindex="-1" role="dialog">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-body">
                                            <form action="{{route('admin.vehicle-model.update', $model->id)}}" method="POST">
                                                {{csrf_field()}}
                                                <input type="hidden" name="_method" value="PATCH">
                                                <h2 class="modal-heading text-center my-3">Edit Vehicle Model</h2>
                                                {{-- <div class="d-flex justify-content-md-center justify-content-start mb-5">
                                                <div class="uploaded-img me-3">
                                                    <img src="{{URL::asset('admin-panel/images/img-icon.png')}}">
                                        </div>
                                        <div class="uploaded-action">
                                            <label class="browsbtn btn btn-primary mb-2"> + Add Icon
                                                <input type="file" id="image" name="image" size="60">
                                            </label>
                                        </div>
                                    </div> --}}

                                    <div class="d-flex justify-content-center mb-5">
                                        <div class="choosedbox shadow-sm">
                                            <img src="{{$brand->logo}}" width="100%">
                                            <i class="fa fa-times choosed-cross"></i>
                                        </div>
                                    </div>


                                    <div class="d-flex align-items-center justify-content-center mb-5">
                                        <label class="white-space-nowrap me-2">Car Make</label>
                                        <input type="text" class="form-control" id="name" name="name" placeholder="Car Make" value="{{$model->name}}">
                                        <input hidden type="text" class="form-control" id="brand_id" name="brand_id" placeholder="Car Make" value="{{$brand->id}}">
                                    </div>
                                    <div class="d-flex align-items-center justify-content-center mb-5">
                                        <label class="white-space-nowrap me-2 w-35">Type</label>
                                        <div class="position-relative w-100">
                                            <select class="form-control" id="type_id" name="type_id">
                                                @foreach ($types as $type)
                                                <option value="{{$type->id}}">{{$type->name}}</option>
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
                        </div>
        </div>
    </div>
    </td>
    </tr>
    @endforeach
    </table>
</div>
</div>
@endforeach




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
        $("#brand_id").val(id);
        $("#add-honda-modal").modal("show");
    }
</script>


@endsection
