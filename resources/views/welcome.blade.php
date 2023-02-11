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
                        <h4 class="text-center mb-4">Welcome To Ecommerce Search</h4>
                        <div class="text-center mt-2">
                            <a href="{{ route('client.login') }}" class="btn btn-primary btn-block">Client Login</a>
                        </div>

                        <div class="text-center mt-2">
                            <a href="{{ route('admin.login') }}" class="btn btn-dark btn-block">Admin Login</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
