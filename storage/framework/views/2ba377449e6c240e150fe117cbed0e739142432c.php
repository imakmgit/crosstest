<html>
    <head>
        <title><?php echo $__env->yieldContent('title'); ?></title>
        <?php echo $__env->make('include.css', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
    </head>
    <body class="master-layout">
		<?php echo $__env->make('include.header', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
		<div class="container top-margin">
			<?php echo $__env->yieldContent('content'); ?>
		</div>
		<?php echo $__env->make('include.footer', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
		
    </body>
</html>
