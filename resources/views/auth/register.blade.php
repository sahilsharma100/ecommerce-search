{{-- Extends layout --}}
@extends('layouts.fullwidth')

{{-- Toast --}}
@include('partials.toaster')

{{-- Content --}}
@section('content')
    <div class="col-md-6">
        <div class="authincation-content">
            <div class="row no-gutters">
                <div class="col-xl-12">
                    <div class="auth-form">
                        <h4 class="text-center mb-4">Sign up your account</h4>
                        <form action="{{ route($type . '.register') }}" method="post">
                            @csrf
                            <div class="form-group">
                                <label class="mb-1"><strong>Full Name</strong></label>
                                <input type="text" class="form-control" name="name" placeholder="username">
                            </div>
                            <div class="form-group">
                                <label class="mb-1"><strong>Email</strong></label>
                                <input type="email" class="form-control" name="email" placeholder="hello@example.com">
                            </div>
                            <div class="form-group">
                                <label class="mb-1"><strong>Password</strong></label>
                                <input type="password" class="form-control" name="password" value="Password">
                            </div>
                            <div class="text-center mt-4">
                                <button type="submit" class="btn btn-primary btn-block">Sign me up</button>
                            </div>
                        </form>
                        <div class="new-account mt-3">
                            <p>Already have an account? <a class="text-primary" href="{{ route($type . '.login') }}">Sign
                                    in</a>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
