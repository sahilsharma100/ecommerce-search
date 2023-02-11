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
                        <h4 class="text-center mb-4">Sign in your account</h4>
                        <form action="{{ route($type . '.login') }}" method="POST">
                            @csrf
                            <div class="form-group">
                                <label class="mb-1"><strong>Email</strong></label>
                                <input type="email" class="form-control" name="email" value="{{ old('email', $type.'@'.$type.'.com') }}"
                                    placeholder="hello@example.com">
                            </div>
                            <div class="form-group">
                                <label class="mb-1"><strong>Password</strong></label>
                                <input type="password" name="password" class="form-control" value="Qwerty@123!">
                            </div>
                            <div class="form-row d-flex justify-content-between mt-4 mb-2">
                                <div class="form-group">
                                    <div class="custom-control custom-checkbox ml-1">
                                        <input type="checkbox" name="active" class="custom-control-input"
                                            id="basic_checkbox_1">
                                        <label class="custom-control-label" for="basic_checkbox_1">Remember my
                                            preference</label>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <a href="{{ route($type . '.forgot-password') }}">Forgot Password?</a>
                                </div>
                            </div>
                            <div class="text-center">
                                <button type="submit" class="btn btn-primary btn-block">Sign Me In</button>
                            </div>
                        </form>
                        @if ($type == 'client')
                            <div class="new-account mt-3">
                                <p>Don't have an account? <a class="text-primary" href="{{ route($type . '.register') }}">Sign
                                        up</a></p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
