@extends('layouts.master')

@section('title', 'NewsStand Dashboard')

@section('content')
<div class="margin-top: 50px;">
    <div class="col-md-8">
        <div class="panel panel-primary">
            <div class="panel-heading">Publish a news</div>
			<div class="panel-body">
				<form role="form" action="/news/create" method="post" enctype="multipart/form-data">
					{!! csrf_field() !!}
					<div class="form-group">
						<label for="news_title" class="news_label">News Title:</label>
						<input type="text" class="form-control" id="news_title" name="news_title" value="{{ $form_data ? $form_data['news_title'] : '' }}">
						<label class="error">{{ $message && array_key_exists('news_title', $message) ? $message['news_title'] : ''}}</label>
					</div>
					<div class="form-group">
						<label for="news_image" class="news_label">News Image:</label>
						<input type="file" class="form-control" id="image" name="news_image">
						<label class="error">{{ $message && array_key_exists('news_image', $message) ? $message['news_image'] : ''}}</label>
					</div>
					<div class="form-group">
						<label for="news_content" class="news_label">News Content:</label>
						<textarea class="form-control" placeholder="" id="news_content"  name="news_content"> {{ $form_data ? $form_data['news_content'] : '' }}</textarea>
						<label class="error">{{ $message && array_key_exists('news_content', $message) ? $message['news_content'] : ''}}</label>
					</div>
					<button type="submit" class="btn btn-primary">Submit</button>
				</form>
			</div>
    </div>
</div>
<div class="col-md-4">
    <div class="panel panel-primary">
        <div class="panel-heading">Your recently published news</div>
        <div class="panel-body">
			@if(count($latest_news))
				@foreach($latest_news as $news)
					<div class="news-teaser" data-url="{{ $news['url'] }}">
						<div class="news-teaser-title">
							{{ $news['title'] }}
						</div>
						<div class="news-teaser-content">
							{{ substr($news['content'],0, 50) }}...
						</div>
						<div class="news-teaser-date">
							{{ $news['created_at'] }}
						</div>
					</div>
				@endforeach
			@else
				You have not published any news yet.
			@endif

        </div>
    </div>
</div>
</div>
@if($news_message || ($news_error && array_key_exists('growl_notification', $news_error)))
<script type="text/javascript">
var growl_notification = {
		error: {{ $news_message ? 'false' : 'true' }},
		message: "{{ $news_message ? $news_message['message'] : $news_error['message'] }}"
	}
</script>
@endif
@endsection
