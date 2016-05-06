<p>
Hello <?php echo e($user[0]['name']); ?>,
</p>
<p>
Someone has requested to reset password of your account from <?php echo e($ip); ?> ip address. Please click on the link below to reset password.<br/>
<?php echo e($url); ?>

</p>
<p>
If you have not requested password reset, ignore this email.
</p>
<p>
Regards,<br/>
NewsStand Support Team
</p>
