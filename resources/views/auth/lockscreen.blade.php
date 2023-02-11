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
                        <h4 class="text-center mb-4">Verify Account</h4>
                        <form action="{{ route('client.verify-email') }}" method="post">
                            @csrf
                            <div class="form-group">
                                <label><strong>Enter Code</strong></label>
                                <input type="code" name="code" class="form-control" value="" maxlength="6" minlength="6" required>
                                <div class="form-text">
                                    Please check your amail account. We have sent a code on your email.
                                </div>
                            </div>
                            <div class="text-center">
                                <button type="submit" class="btn btn-primary btn-block">Verify</button>
                            </div>

                            <div class="text-center mt-2">
                                <a href="{{ route('client.resend-verification-email') }}" class="btn btn-dark btn-block">Resend Code</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
