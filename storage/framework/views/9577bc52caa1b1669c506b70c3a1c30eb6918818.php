<p>
Hello <?php echo e($user->name); ?>,
</p>
<p>
Welcome to NewsStand. Your account has been created successfully. Please follow the below steps to  activate your account and start publishing news. 
</p>
<p>
	<ul>
		<li>Click <a href="<?php echo e($url); ?>">here</a> to validate your email address. if clicking does not work, copy paste the following url in your browser.<br/><?php echo e($url); ?></li>
		<li>Set password for your account.</li>
		<li>And your are done. Start publishing news.</li>
	</ul>
</p>
<p>
If you face any difficulties, please contact us at support@newsstand.com.
</p>
<p>
Regards,<br/>
NewsStand Support Team
</p>
