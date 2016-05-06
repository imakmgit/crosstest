<?php $__env->startSection('title', $is_password_set ? 'Set Password' : 'Update Password'); ?>

<?php $__env->startSection('content'); ?>
<div class="pen-title">
    <h1>NewsStand</h1>
</div>

<div class="module form-module">
    <div class="form" style="display: block;">
        <h2 class="text-center">Hello <?php echo e($user_info[0]['name']); ?>! Please <?php echo e($is_password_set ? 'set' : 'reset'); ?> password of your account below</h2>
        <form id="change_password_form" action="/auth/update_password">
			<?php echo csrf_field(); ?>

			<?php if($is_password_reset && $logged_in_user): ?>
            <input type="password" placeholder="Current Password" name="current_password"/>
            <?php endif; ?>
            <input type="password" placeholder="<?php echo e($is_password_reset && $logged_in_user ? 'New ' : ''); ?>Password" name="password"/>
            <input type="password" placeholder="Retype <?php echo e($is_password_reset && $logged_in_user ? 'New ' : ''); ?>password" name="confirm_password"/>
            <button data-text="<?php echo e($is_password_set ? 'Set ' : 'Update'); ?> Password">
				<?php echo e($is_password_set ? 'Set ' : 'Update'); ?> Password
			</button>
        </form>
    </div>
</div>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.guest', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>