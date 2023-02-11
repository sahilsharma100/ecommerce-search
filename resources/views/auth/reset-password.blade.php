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
                        <h4 class="text-center mb-4">Reset Password</h4>
                        <form method="POST" action="{{ $path }}">
                            @csrf
                            <input type="hidden" name="token" value="{{ $token }}">
                            <div class="form-group">
                                <label><strong>Email</strong></label>
                                <input type="email" name="email" class="form-control" value="" placeholder="hello@example.com"
                                    required>
                            </div>
                            <div class="form-group">
                                <label><strong>New Password</strong></label>
                                <input type="password" name="password" class="form-control" value="" placeholder="New password"
                                    required>
                            </div>
                            <div class="form-group">
                                <label><strong>Confirm Password</strong></label>
                                <input type="password" name="password_confirmation" class="form-control" value="" placeholder="Confirm Password"
                                    required>
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
