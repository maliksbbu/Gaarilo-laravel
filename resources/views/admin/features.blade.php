@extends('admin.layout.main')
@section('title', 'Features ')
@section('content')

<div class="modal fade show popup-small" id="add-vtype-popup" tabindex="-1" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body">
                <form action="{{route('admin.feature.store')}}" method="POST" enctype="multipart/form-data">
                    {{csrf_field()}}
                    <h2 class="modal-heading text-center my-3">Add Feature</h2>
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
                        <label class="white-space-nowrap me-2">Feature Name</label>
                        <input type="text" class="form-control" id="name" name="name" placeholder="Feature Name">
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
        <h4 class="page-heading my-4">Vehicle Features</h4>
        <button class="btn btn-primary ms-auto" data-bs-toggle="modal" data-bs-target="#add-vtype-popup">Add New
            Feature</button>
    </div>



    <div class="row mb-5">
        @foreach ($features as $feature)
        <div class="col-sm-4 mb-4">
            <div class="vtbox shadow-sm">
                <div class="align-items-center d-flex row w-100">
                    <div class="col-lg-10 mb-2 mb-lg-0">
                        <div class="d-flex">
                            <span>{{$feature->name}}</span>
                        </div>
                    </div>
                    <div class="col-lg-2 text-end">
                        <div class=" my-2 d-inline-block me-4 me-lg-0">
                            <a href="javascript:OpenEditModal({{$feature->id}})">
                            <img src="{{URL::asset('admin-panel/images/edit.png')}}">
                            </a>
                        </div>
                        <div class="d-inline-block ms-3">
                            <form action="{{ route('admin.feature.destroy', $feature->id) }}" method="POST" id="delete_form_{{$feature->id}}">
                                {{ csrf_field() }}
                                <input type="hidden" name="_method" value="DELETE">

                                <a onclick="return confirm('Are you sure?')" href="javascript:Delete({{$feature->id}});">
                                    <img src="{{URL::asset('admin-panel/images/del.png')}}">
                                </a>
                            </form>
                        </div>
                        <div class="modal fade show popup-small" id="edit-vtype-popup_{{$feature->id}}" tabindex="-1" role="dialog">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-body">
                                        <form action="{{route('admin.feature.update', $feature->id)}}" method="POST">
                                            {{csrf_field()}}
                                            <input type="hidden" name="_method" value="PATCH">
                                            <h2 class="modal-heading text-center my-3">Edit Vehicle Feature</h2>
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
                                                <label class="white-space-nowrap me-2">Feature Name</label>
                                                <input type="text" class="form-control" id="name" name="name" placeholder="Feature Name"
                                                    value="{{$feature->name}}">
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
