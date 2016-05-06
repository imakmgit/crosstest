<html>
    <head>
        <title><?php echo $__env->yieldContent('title'); ?></title>
        <?php echo $__env->make('include.css', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
    </head>
    <body class="master-layout">
		<div class="container top-margin">
			<?php echo $__env->yieldContent('content'); ?>
		</div>
    </body>
</html>
