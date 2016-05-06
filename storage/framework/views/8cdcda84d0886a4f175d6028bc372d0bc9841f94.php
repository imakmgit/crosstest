<script type="text/javascript" src="<?php echo e(URL::asset('/js/jquery-1.11.3.min.js')); ?>"> </script>
<script type="text/javascript" src="<?php echo e(URL::asset('/js/bootstrap.min.js')); ?>"> </script>
<script type="text/javascript" src="<?php echo e(URL::asset('/js/jquery-ui.min.js')); ?>"> </script>
<script type="text/javascript" src="<?php echo e(URL::asset('/js/jquery.growl.js')); ?>"> </script>
<script type="text/javascript" src="<?php echo e(URL::asset('/js/slidebars.js')); ?>"> </script>
<script type="text/javascript" src="<?php echo e(URL::asset('/js/script.js')); ?>"> </script>
<?php if(file_exists(public_path() . '/js/' . $controller . '.js')): ?>
	<script type="text/javascript" src="<?php echo e(URL::asset('/js/' . $controller . '.js')); ?>"> </script>
<?php endif; ?>
