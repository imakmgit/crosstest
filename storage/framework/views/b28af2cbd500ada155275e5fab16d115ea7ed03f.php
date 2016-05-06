<?php $__env->startSection('title', 'NewsStand Dashboard'); ?>

<?php $__env->startSection('content'); ?>
<div class="margin-top: 50px;">
    <div class="col-md-8">
        <div class="panel panel-primary">
            <div class="panel-heading">Publish a news</div>
			<div class="panel-body">
				<form role="form" action="/news/create" method="post" enctype="multipart/form-data">
					<?php echo csrf_field(); ?>

					<label class="error">All fields are required. Plesae fill up all fields to  publish your news.</label>

					<div class="form-group">
						<label for="news_title" class="news_label">News Title:</label>
						<input type="text" class="form-control" id="news_title" name="news_title" value="<?php echo e($form_data ? $form_data['news_title'] : ''); ?>">
						<label class="news-info">Provide maximum 20 words for news title.</label>
						<label class="error"><?php echo e($message && array_key_exists('news_title', $message) ? $message['news_title'] : ''); ?></label>
					</div>
					<div class="form-group">
						<label for="news_image" class="news_label">News Image:</label>
						<input type="file" class="form-control" id="image" name="news_image">
						<label class="news-info">Upload image with jpg, jpeg, png, gif extension of size less than 1MB.</label>
						<label class="error"><?php echo e($message && array_key_exists('news_image', $message) ? $message['news_image'] : ''); ?></label>
					</div>
					<div class="form-group">
						<label for="news_content" class="news_label">News Content:</label>
						<textarea class="form-control" placeholder="" id="news_content"  name="news_content"> <?php echo e($form_data ? $form_data['news_content'] : ''); ?></textarea>
						<label class="news-info">Use atleast 300 words to post news.</label>
						<label class="error"><?php echo e($message && array_key_exists('news_content', $message) ? $message['news_content'] : ''); ?></label>
					</div>
					<button type="submit" class="btn btn-primary">Submit</button>
				</form>
			</div>
    </div>
</div>
<div class="col-md-4">
    <div class="panel panel-primary">
        <div class="panel-heading">
			Your recently published news&nbsp;&nbsp;&nbsp;
			<a class="show-all" href="/dashboard/news">Show all</a>
		</div>
        <div class="panel-body">
			<?php if(count($latest_news)): ?>
				<?php foreach($latest_news as $news): ?>
					<div class="news-teaser" data-url="<?php echo e($news['url']); ?>">
						<div class="news-teaser-title">
							<?php echo e(substr($news['title'], 0, 40)); ?>...
						</div>
						<div class="news-teaser-content">
							<?php echo e(substr($news['content'], 0, 120)); ?>...
						</div>
						<div class="news-teaser-date">
							<?php echo e($news['created_at']); ?>

						</div>
					</div>
				<?php endforeach; ?>
			<?php else: ?>
				You have not published any news yet.
			<?php endif; ?>

        </div>
    </div>
</div>
</div>

<?php if($news_message || ($news_error && array_key_exists('growl_notification', $news_error))): ?>
<script type="text/javascript">
var growl_notification = {
		error: <?php echo e($news_message ? 'false' : 'true'); ?>,
		message: "<?php echo e($news_message ? $news_message['message'] : $news_error['message']); ?>"
	}
</script>
<?php endif; ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.master', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>