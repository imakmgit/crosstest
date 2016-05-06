<?php $__env->startSection('title', 'NewsStand Dashboard'); ?>

<?php $__env->startSection('content'); ?>
<div class="margin-top: 50px;">
    <div class="col-md-12">
			<?php if(count($latest_news)): ?>
				<?php foreach($latest_news as $news): ?>
					<div class="news">
						<div  class="col-md-12"><?php echo e($news['title']); ?></div>
						<div class="col-md-1 news-image">
							<img src="<?php echo e($news['image_path']); ?>"/>
						</div>
						<div  class="col-md-11">
							<?php echo e(substr($news['content'], 0, 500)); ?> ...
						</div>
						<div class="col-md-12">
							<br/>
						</div>
					</div>
				<?php endforeach; ?>
			<?php else: ?>
				You have not published any news yet.
			<?php endif; ?>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.master', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>