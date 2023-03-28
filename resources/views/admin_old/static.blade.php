@extends('admin.layouts.layout')
@section('title', $header.' ')

@section('content')
<?php use App\Http\Controllers\CommonController; ?>

<div class="container-fluid px-4">
    <h3 class="mt-4">{{$header}}</h3>
    <div class="card mb-4">
        <div class="card-body">
            <form class="form-horizontal" action="{{route('admin.updateStaticSetting', $type)}}" method="POST" enctype="multipart/form-data" role="form">
                {{csrf_field()}}

                <div class="row">

                    <textarea id="input" name="input">{{$setting}}</textarea>

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

@section('script')
<script src="//cdn.ckeditor.com/4.6.2/standard/ckeditor.js"></script>
<script type="text/javascript">
    CKEDITOR.replace('input');
    CKEDITOR.config.width = '100%';
</script>
@endsection
