@extends('admin.layout.main')
@section('title', 'City ')
@section('content')
<style>
    .show-icon {
        z-index: 0 ! important;
    }
</style>

<div class="modal fade show popup-small" id="add-honda-modal" tabindex="-1" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body">
                <form action="{{route('admin.city.store')}}" method="POST">
                    {{csrf_field()}}
                    <h2 class="modal-heading text-center my-3 mb-5">Add City</h2>

                    <input type="hidden" id="province_id" name="province_id" value="">
                    <div class="d-flex align-items-center justify-content-center mb-3">
                        <label class="white-space-nowrap me-2 w-35">City Name</label>
                        <input type="text" class="form-control" placeholder="Name" id="name" name="name">
                    </div>
                    {{-- <div class="d-flex align-items-center justify-content-center mb-5">
                    <label class="white-space-nowrap me-2 w-35">Model</label>
                    <div class="position-relative w-100">
                        <select class="form-control" id="model_id" name="model_id">
                            @foreach ($models as $model)
                                <option value="{{$model->id}}">{{$model->brand->name}} {{$model->name}}</option>
                    @endforeach
                    </select>
                    <img src="{{URL::asset('admin-panel/images/dchevron.png')}}" class="dchevron">
            </div>
        </div> --}}
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
        <h4 class="page-heading my-4">Cities</h4>
    </div>

    @foreach ($provinces as $province)
    <div class="vmbox mb-3">
        <div class="row w-100 align-items-center d-flex">
            <div class="col-xl-6">
                <div class="d-flex align-items-center pt-1">
                    {{-- <img src="{{$model->logo}}" class="mx-4"> --}}
                    <h4 class="mx-4">{{$province->name}}</h4>
                </div>
            </div>
            <div class="col-xl-6">
                <div class="d-flex align-items-center justify-content-end">
                    <div class="mx-4 text-center">
                        <p>Total Cities Registered</p>
                        <h5 class="m-0">{{$province->RegisteredVCitiesCount()}}</h5>
                    </div>
                    <button class="btn btn-primary mx-4" onclick="OpenAddModal({{$province->id}})">+ Add
                        City</button>
                    <a data-bs-toggle="collapse" href="#collapseModel_{{$province->id}}"><img src="{{URL::asset('admin-panel/images/downcaret.png')}}" class="mx-4"></a>
                </div>
            </div>
        </div>
    </div>
    <div class="collapse" id="collapseModel_{{$province->id}}">
        <div class="collap-body shadow-sm show-icon">
            <table class="table table-hover">
                <tr>
                    <th>City Name</th>
                    <th class="text-end">Actions</th>
                </tr>
                @foreach ($province->city as $city)
                <tr>
                    <td>{{$city->name}}</td>
                    <td>
                     
                        <div class="d-flex align-ites-center justify-content-end">
                        <div class="mb-0 mb-lg-3 d-inline-block me-4 me-lg-0">
                                <a href="javascript:OpenEditModal({{$city->id}})">
                                    <img src="{{URL::asset('admin-panel/images/edit.png')}}">
                                </a>
                            </div>
                            <div class="d-inline-block">
                                <form action="{{ route('admin.city.destroy', $city->id) }}" method="POST" id="delete_form_{{$city->id}}">
                                    {{ csrf_field() }}
                                    <input type="hidden" name="_method" value="DELETE">

                                    <a onclick="return confirm('Are you sure?')" href="javascript:Delete({{$city->id}});">
                                        <img src="{{URL::asset('admin-panel/images/del.png')}}" class="ms-3">
                                    </a>
                                </form>
                            </div>

                            <div class="modal fade show popup-small" id="edit-vtype-popup_{{$city->id}}" tabindex="-1" role="dialog">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-body">
                                            <form action="{{route('admin.city.update', $city->id)}}" method="POST">
                                                {{csrf_field()}}
                                                <input type="hidden" name="_method" value="PATCH">
                                                <h2 class="modal-heading text-center my-3">Edit City</h2>
                                                {{-- <div class="d-flex justify-content-md-center justify-content-start mb-5">
                                                <div class="uploaded-img me-3">
                                                    <img src="{{URL::asset('admin-panel/images/img-icon.png')}}">
                                        </div>
                                    </div> --}}
                                    <div class="d-flex align-items-center justify-content-center mb-5">
                                        <label class="white-space-nowrap me-2">City</label>
                                        <input type="text" class="form-control" id="name" name="name" placeholder="Car Make" value="{{$city->name}}">
                                    </div>
                                    <div class="d-flex align-items-center justify-content-center mb-5">
                                        <label class="white-space-nowrap me-2 w-35">Type</label>
                                        <div class="position-relative w-100">
                                            <select class="form-control" id="province_id" name="province_id">
                                                @foreach ($provinces as $province)
                                                <option value="{{$province->id}}">{{$province->name}}</option>
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
        $("#province_id").val(id);
        $("#add-honda-modal").modal("show");
    }
</script>


@endsection