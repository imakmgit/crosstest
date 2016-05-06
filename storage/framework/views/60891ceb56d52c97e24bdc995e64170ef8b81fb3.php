<html>
    <head>
        <title><?php echo $__env->yieldContent('title'); ?></title>
        <?php echo $__env->make('include.css', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
        <script type="text/javascript">
			
			<?php if(isset($property_id) && !empty($property_id)): ?>
				
				<?php foreach($property_id as $key => $value): ?>
					var <?php echo e($key); ?> = '<?php echo e($value); ?>';
				<?php endforeach; ?>

			<?php endif; ?>

        </script>
    </head>
    <body>
		<?php echo $__env->make('include.header', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
		<div class="container top-margin">
			<?php echo $__env->yieldContent('content'); ?>
		</div>
		<?php echo $__env->make('include.footer', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
		
    </body>
</html>
