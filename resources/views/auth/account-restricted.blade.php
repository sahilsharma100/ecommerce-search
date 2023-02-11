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
                    <div class="auth-form text-center">
                        <img src="{{ asset('images/restricted.png') }}" alt="">
                        <h2 class="mb-4">Account Restricted</h2>
                        <a href="{{ route($type.'.logout') }}" class="btn btn-primary btn-block">Log Out</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
