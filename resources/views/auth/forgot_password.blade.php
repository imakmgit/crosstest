@extends('layouts.guest')

@section('title', 'Forgot Password')

@section('content')
<div class="pen-title">
    <h1>NewsStand</h1>
</div>

<div class="module form-module">
    <div class="toggle hide">
        <i class="fa fa-times fa-pencil"></i>
        <div class="tooltip">Click Me</div>
    </div>
    <div class="form">
        <h2 class="text-center">Provide your email below to reset your password.</h2>
        <form id="forgot_password_form" action="/auth/forgot_password">
			{!! csrf_field() !!}
            <input type="text" placeholder="Email" name="email"/>
            <button data-text="Reset Password">Reset Password</button><br/>
        </form>
    </div>
    <div class="cta">
		<a href="/auth/login">Back to login page</a>
	</div>
</div>

@endsection
