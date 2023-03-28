@extends('front.layout.auth-layout')
@section('content')

<div class="col-lg-7 ps-0 order-1 order-lg-2">
    <div class="container px-2 px-lg-0  ms-lg-0 me-lg-0">
        <div class="login-right">
            <h3 class="logtxt my-4">Admin Login</h3>

            <form class="{{ route('adminLoginPost') }}" method="post">
            {!! csrf_field() !!}

            <div class="row">
                <div class="col-xl-7 mb-3">
                    <label>Email</label>
                    <input type="email" class="form-control" id="email" name="email" placeholder="Email">
                </div>
                <div class="col-xl-7 mb-5">
                    <label>Password</label>
                    <input type="password" class="form-control" id="password" name="password" placeholder="password">
                </div>
                <div class="col-xl-7">
                    <button class="btn btn-primary w-100">Login</button>
                </div>
            </div>

            </form>
        </div>

    </div>
</div>

@endsection
