@extends('front.layout.main')
@section('content')

<div class="row gray-section no-margin" style="margin-top: 12%">
    <div class="container">
        <div class="content-block" style="margin-left:10%; margin-right: 10%;">
            <h2>{{ $title }}</h2>
            <div class="title-divider"></div>
            <p>{!! $content !!}</p>
        </div>
    </div>
</div>
@endsection
