<?php $__env->startSection('title', 'Login'); ?>

<?php $__env->startSection('content'); ?>
<div class="pen-title">
    <h1>NewsStand</h1>
</div>

<div class="module form-module">
    <div class="toggle hide">
        <i class="fa fa-times fa-pencil"></i>
        <div class="tooltip">Click Me</div>
    </div>
    <div class="form">
        <h2>Login to your account</h2>
        <form id="login_form" action="/auth/validate_login">
			<?php echo csrf_field(); ?>

            <input type="text" placeholder="Email" name="email"/>
            <input type="password" placeholder="Password" name="password"/>
            <button>Login</button><br/>
			<a href="#" class="signin-signup">Don't have an account? Click here to get one.</a>
        </form>
    </div>
    <div class="form">
        <h2>Create an account</h2>
        
        <form id="signup_form" action="/auth/signup">
			<?php echo csrf_field(); ?>

            <input type="text" name="full_name" placeholder="Full Name"/>
            <input type="text" name="email" placeholder="Email Address"/>
            <button>Register</button><br/>
			<a href="#" class="signin-signup">Have an account? Click here to login.</a>
        </form>
    </div>
    <div class="cta">
		<a href="/auth/forgot_password">Forgot your password?</a>
	</div>
</div>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.guest', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>