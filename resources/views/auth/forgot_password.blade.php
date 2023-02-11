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
                        <h4 class="text-center mb-4">Forgot Password</h4>
                        <form method="POST" action="{{ route($type.'.forgot-password') }}">
                            @csrf
                            <div class="form-group">
                                <label><strong>Email</strong></label>
                                <input type="email" name="email" class="form-control" value="" placeholder="hello@example.com" required>
                            </div>
                            <div class="text-center">
                                <button type="submit" class="btn btn-primary btn-block">SUBMIT</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
