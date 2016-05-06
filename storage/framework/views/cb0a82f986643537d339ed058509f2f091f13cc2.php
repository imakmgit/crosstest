<?php $__env->startSection('content'); ?>
<div style="padding: 20px;">
	<div style="font-size: 50px;font-family: sans-serif;border-bottom: 1px solid #d2d2d2;padding: 5px;">
		<?php echo e($news[0]['title']); ?> (Published on <?php echo e($news['0']['created_at']); ?> by <?php echo e($user_info['name']); ?>)</div>
	<div class="col-md-8 col-md-offset-2 news-image">
		<img src="http://www.newsstand.com/<?php echo e($news[0]['image_path']); ?>" />
	</div>
	<div class="col-md-12 news-content" >
		<p><?php echo str_replace("\n", '</p><p>', $news[0]['content']); ?></p>
	</div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.pdf', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>