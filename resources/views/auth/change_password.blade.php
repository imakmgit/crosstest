@extends('layouts.guest')

@section('title', $is_password_set ? 'Set Password' : 'Update Password')

@section('content')
<div class="pen-title">
    <h1>NewsStand</h1>
</div>

<div class="module form-module">
    <div class="form" style="display: block;">
        <h2 class="text-center">Hello {{ $user_info[0]['name'] }}! Please {{ $is_password_set ? 'set' : 'reset' }} password of your account below</h2>
        <form id="change_password_form" action="/auth/update_password">
			{!! csrf_field() !!}
			@if($is_password_reset && $logged_in_user)
            <input type="password" placeholder="Current Password" name="current_password"/>
            @endif
            <input type="password" placeholder="{{ $is_password_reset && $logged_in_user ? 'New ' : '' }}Password" name="password"/>
            <input type="password" placeholder="Retype {{ $is_password_reset && $logged_in_user ? 'New ' : '' }}password" name="confirm_password"/>
            <button data-text="{{ $is_password_set ? 'Set ' : 'Update'}} Password">
				{{ $is_password_set ? 'Set ' : 'Update'}} Password
			</button>
        </form>
    </div>
</div>

@endsection
