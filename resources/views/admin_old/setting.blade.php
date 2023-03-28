@extends('admin.layouts.layout')
@section('title', 'Settings ')
@section('content')
<div class="container-fluid">

    <!-- Page Heading -->
    {{-- <h1 class="h3 mb-2 text-gray-800">Settings</h1> --}}
    <p> </p>

    <!-- DataTales Example -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold "> Admin Settings </h6>
        </div>
        <div class="card-body">
            <form class="form-horizontal" action="{{route('admin.updateSettings')}}" method="POST" enctype="multipart/form-data" role="form">
                {{csrf_field()}}

                @foreach($settings as $setting)
                @if($setting->tag_type == 'SELECT')
                <div class="row">
                    <div class="col-sm-2"><label class="form-label" for="{{$setting->tag_name}}"> {{$setting->name}}: </label></div>
                    <div class="col-sm-10">
                        <select class="form-control" id="{{$setting->tag_name}}" name="{{$setting->tag_name}}" value="{{$setting->value}}">
                            <?php
                            $listOptions = explode(',', $setting->select_values);
                            ?>
                            @foreach($listOptions as $option)
                            <option value="{{$option}}" <?php if ($option == $setting->value) {
                                                            echo "selected";
                                                        } else {
                                                            echo "";
                                                        }  ?>>{{$option}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <br>
                @else
                <div class="row">
                    <div class="col-sm-2"><label class="form-label" for="{{$setting->tag_name}}"> {{$setting->name}}: </label></div>
                    <div class="col-sm-10">
                        <input type="{{$setting->type}}" class="form-control" id="{{$setting->tag_name}}" name="{{$setting->tag_name}}" value="{{$setting->value}}" placeholder="Enter {{$setting->name}}">
                    </div>
                </div>
                <br>
                @endif
                @endforeach
                @if($settings->count() == 0)
                <div class="row" style="display: block; text-align: center;">
                    <h1>Nothing to show</h1>
                </div>
                @endif
                @if($settings->count() > 0)
                <button class="btn btn-primary" style="float: right;" type="submit">
                    <span class="text">Save</span>
                </button>
                @endif
            </form>


        </div>

        @endsection
