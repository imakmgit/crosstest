<?php $__env->startSection('title', $news[0]['title']); ?>

<?php $__env->startSection('content'); ?>
<div>
	<div class="col-md-12 news-title">
		<div>
			<?php echo e($news[0]['title']); ?>

		</div>
		<div class="info pull-left">Published on <?php echo e($news['0']['created_at']); ?> by <?php echo e($user_info['name']); ?></div>
		<div class="info pull-right">		
			<a href="/pdf<?php echo e($news[0]['url']); ?>">Save as PDF</a>
		</div>
	</div>
	<div class="col-md-8 col-md-offset-2 news-image">
		<img src="<?php echo e($news[0]['image_path']); ?>" alt="<?php echo e($news[0]['title']); ?>" title="<?php echo e($news[0]['title']); ?>" />
	</div>
	<div class="col-md-12 news-content" >
		<p><?php echo str_replace("\n", '</p><p>', $news[0]['content']); ?></p>
	</div>
	<div class="pull-right">
		<a href="/pdf<?php echo e($news[0]['url']); ?>">Save as PDF</a>
	</div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.master', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>