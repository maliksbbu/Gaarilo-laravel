@extends('admin.layout.main')
@section('title', 'Provinces ')
@section('content')

<div class="modal fade show popup-small" id="add-vtype-popup" tabindex="-1" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body">
                <form action="{{route('admin.province.store')}}" method="POST" enctype="multipart/form-data">
                    {{csrf_field()}}
                    <h2 class="modal-heading text-center my-3">Add Province</h2>
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

                    {{-- <div class="d-flex justify-content-center mb-5">
                        <div class="choosedbox shadow-sm">
                            <img src="{{URL::asset('admin-panel/images/pcar.png')}}">
                            <i class="fa fa-times choosed-cross"></i>
                        </div>
                    </div> --}}


                    <div class="d-flex align-items-center justify-content-center mb-5">
                        <label class="white-space-nowrap me-2">Province Name</label>
                        <input type="text" class="form-control" id="name" name="name" placeholder="Province Name">
                    </div>
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
        <h4 class="page-heading my-4">Provinces</h4>
        <button class="btn btn-primary ms-auto" data-bs-toggle="modal" data-bs-target="#add-vtype-popup">Add New
            Province</button>
    </div>



    <div class="row mb-5">
        @foreach ($provinces as $province)
        <div class="col-sm-4 mb-4">
            <div class="vtbox shadow-sm">
                <div class="align-items-center d-flex row w-100">
                    <div class="col-lg-10 mb-2 mb-lg-0">
                        <div class="d-flex">
                            <span>{{$province->name}}</span>
                        </div>
                    </div>
                    <div class="col-lg-2 text-end">
                        <div class=" my-2 d-inline-block me-4 me-lg-0">
                            <a href="javascript:OpenEditModal({{$province->id}})">
                            <img src="{{URL::asset('admin-panel/images/edit.png')}}">
                            </a>
                        </div>
                        <div class="d-inline-block ms-3">
                            <form action="{{ route('admin.province.destroy', $province->id) }}" method="POST" id="delete_form_{{$province->id}}">
                                {{ csrf_field() }}
                                <input type="hidden" name="_method" value="DELETE">

                                <a onclick="return confirm('Are you sure?')" href="javascript:Delete({{$province->id}});">
                                    <img src="{{URL::asset('admin-panel/images/del.png')}}">
                                </a>
                            </form>
                        </div>
                        <div class="modal fade show popup-small" id="edit-vtype-popup_{{$province->id}}" tabindex="-1" role="dialog">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-body">
                                        <form action="{{route('admin.province.update', $province->id)}}" method="POST">
                                            {{csrf_field()}}
                                            <input type="hidden" name="_method" value="PATCH">
                                            <h2 class="modal-heading text-center my-3">Edit Province</h2>
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

                                            {{-- <div class="d-flex justify-content-center mb-5">
                                                <div class="choosedbox shadow-sm">
                                                    <img src="{{$brand->logo}}" width="100%">
                                                    <i class="fa fa-times choosed-cross"></i>
                                                </div>
                                            </div> --}}


                                            <div class="d-flex align-items-center justify-content-center mb-5">
                                                <label class="white-space-nowrap me-2">Province Name</label>
                                                <input type="text" class="form-control" id="name" name="name" placeholder="Province Name"
                                                    value="{{$province->name}}">
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
                </div>
            </div>
        </div>
        @endforeach


    </div>
</div>

@endsection

@section('scripts')

<script>
function Delete(id)
{
    $("#delete_form_"+id).submit();
}
function OpenEditModal (id)
{
$("#edit-vtype-popup_"+id).modal("show");
}
</script>

@endsection
