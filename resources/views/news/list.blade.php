@extends('layouts.master')

@section('title', 'Welcome to NewsStand')

@section('content')
<div class="margin-top: 50px;">
    <div class="col-md-12">
			@if(count($latest_news))
				@foreach($latest_news as $news)
					<div class="news">
						<div class="col-md-1 news-image">
							<img src="{{ $news['image_path'] }}"/>
						</div>
						<div  class="col-md-11 home-news-content">
							<div  class="col-md-12 home-news-title">
								<a href="{{ $news['url'] }}">{{ $news['title'] }}</a>
							</div>
							<div class="news-content">
							{{ substr($news['content'], 0, 500) }} ... 
							</div>
							<div class="news-info">
								Published on {{ $news['created_at'] }} by {{ $news['news_creator']['name'] }}
							</div>
						</div>
						<div class="col-md-12">
							<br/>
						</div>
					</div>
				@endforeach
			@else
				No news yet!! Why don't you login and publish a great news. Click <a href="/auth/login">here</a> to login.
			@endif
    </div>
</div>
@endsection
