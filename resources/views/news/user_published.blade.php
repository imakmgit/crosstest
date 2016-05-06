@extends('layouts.master')

@section('title', 'Published News')

@section('content')
<div class="margin-top: 50px;">
    <div class="col-md-12">
			@if(count($published_news))
				@foreach($published_news as $news)
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
								Published on {{ $news['created_at'] }} 	
								<a class="error" href="#" data-url="/news/delete/{{ $news['id'] }}">Delete this news</a>

							</div>
						</div>
						<div class="col-md-12">
							<br/>
						</div>
					</div>
				@endforeach
			@else
				You have not published any news yet.
			@endif
    </div>
</div>
				<div class="clear"></div>
				<div class="text-center">
					{!! $published_news->render() !!}
				</div>
@endsection
