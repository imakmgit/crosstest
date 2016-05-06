<?php $__env->startSection('title', 'Welcome to NewsStand'); ?>

<?php $__env->startSection('content'); ?>
<div class="margin-top: 50px;">
    <div class="col-md-12">
			<?php if(count($latest_news)): ?>
				<?php foreach($latest_news as $news): ?>
					<div class="news">
						<div class="col-md-1 news-image">
							<img src="<?php echo e($news['image_path']); ?>"/>
						</div>
						<div  class="col-md-11 home-news-content">
							<div  class="col-md-12 home-news-title">
								<a href="<?php echo e($news['url']); ?>"><?php echo e($news['title']); ?></a>
							</div>
							<div class="news-content">
							<?php echo e(substr($news['content'], 0, 500)); ?> ... 
							</div>
							<div class="news-info">
								Published on <?php echo e($news['created_at']); ?> by <?php echo e($news['news_creator']['name']); ?>

							</div>
						</div>
						<div class="col-md-12">
							<br/>
						</div>
					</div>
				<?php endforeach; ?>
			<?php else: ?>
				No news yet!! Why don't you login and publish a great news. Click <a href="/auth/login">here</a> to login.
			<?php endif; ?>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.master', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>