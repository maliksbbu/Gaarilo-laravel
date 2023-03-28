@extends('admin.layout.main')
@section('title', 'Dashboard')
@section('content')

<div class="inner-content videos-reviews-page">
    <div class="d-flex align-items-center">
        <h4 class="page-heading my-4">News</h4>
        <div class="ms-auto">
            <div class="d-flex align-items-center">
                <button class="btn btn-primary ms-2" data-bs-toggle="modal" data-bs-target="#add-news-modal">
                    <i class="fa fa-plus me-2"></i>
                    Add News
                </button>
            </div>
        </div>
    </div>

    @foreach ($news as $new)
    <div class="pending-ads-box mb-3">
        <div class="row">
            <div class="col-lg-12">
                <div class="d-flex align-content-center">
                    <div class="news-info">
                        <span class="clr-primary">{{$new->title}}</span>

                        @if($new->important == "YES")
                        <button class="btn btn-danger btn-sm default-btn ms-2">
                            Important
                        </button>
                        <span>
                           <a href="{{url('/admin/news'.'/'. $new->id)}}"> <i class="fas fa-times-circle clr-primary ms-2"></i></a>
                        </span>
                        @endif
                    </div>
                    <div class="close-alert ms-auto">
                        <div class=" my-2 d-inline-block me-4 me-lg-0">
                            <a href="javascript:OpenEditModal({{$new->id}})">
                                <img src="{{URL::asset('admin-panel/images/edit.png')}}">
                            </a>
                        </div>
                        <div class="d-inline-block ms-3">
                            <form action="{{ route('admin.news.destroy', $new->id) }}" method="POST" id="delete_form_{{$new->id}}">
                                {{ csrf_field() }}
                                <input type="hidden" name="_method" value="DELETE">

                                <a onclick="return confirm('Are you sure?')" href="javascript:Delete({{$new->id}});">
                                    <img src="{{URL::asset('admin-panel/images/del.png')}}">
                                </a>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="mt-10">
                    <p>
                        {{$new->text}}
                    </p>

                    <div class="text-end">
                        <b>Last Updated:</b>
                        <span class="clr-primary"><b>{{ date('d/m/Y', strtotime($new->updated_at)) }}</b></span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade show popup-small" id="edit-vtype-popup_{{$new->id}}" tabindex="-1" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-body">
                    <form action="{{route('admin.news.update', $new->id)}}" method="POST">
                        {{csrf_field()}}
                        <input type="hidden" name="_method" value="PATCH">
                        <h2 class="modal-heading text-center my-3">Edit News</h2>
                        <div class="d-flex align-items-center justify-content-center mb-5">
                            <label class="white-space-nowrap me-2">News Text</label>
                            <input rows="10" cols="10" class="form-control" id="text" name="text" placeholder="News Text" value="{{$new->text}}">
                            <div class="text-center">
                                <button data-bs-dismiss="modal" type="submit" class="btn btn-primary">Save</button>
                            </div>   
                        </div>
                    </form>            
                </div>
            </div>
        </div>
   </div>
@endforeach
</div>
<div class="modal fade show popup-small" id="add-news-modal" tabindex="-1" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body">
                <h2 class="modal-heading text-center my-3">
                    Add News
                </h2>

                <form method="POST" action="{{route('admin.news.store')}}" >
                    {{ csrf_field() }}
                <div class="d-flex align-items-center justify-content-center mb-5">
                    <label class="white-space-nowrap me-2">Name</label>
                    <input type="text" class="form-control" placeholder="Name" name="title"/>
                </div>
                <div class="d-flex align-items-center justify-content-center mb-5">
                    <label class="white-space-nowrap me-2">Description</label>
                    <textarea name="text" id="" cols="30" rows="10" class="form-control"
                        placeholder="Description"></textarea>
                        </div>
                        <div class="col-md-12 mb-4">
                            <span class="d-flex">
                                <label for="">Mark as Important</label>
                                <input type="checkbox" class="m-1" name="important" />
                            </span>
                        </div>
                        <div class="text-center">
                            <button data-bs-dismiss="modal" class="btn btn-primary" type="submit">
                                Save
                            </button>
                        </div>
                </div>
            </div>
        </div>
    </div>

    @endsection
    <script>
        function Delete(id) {
            $("#delete_form_" + id).submit();
        }

        function OpenEditModal(id) {
            $("#edit-vtype-popup_" + id).modal("show");
        }
    </script>