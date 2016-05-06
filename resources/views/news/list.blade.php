@extends('layouts.master')

@section('title', 'NewsStand Dashboard')

@section('content')
<div class="margin-top: 50px;">
    <div class="col-md-12">
			@if(count($latest_news))
				@foreach($latest_news as $news)
					<div class="news">
						<div  class="col-md-12">{{ $news['title'] }}</div>
						<div class="col-md-1 news-image">
							<img src="{{ $news['image_path'] }}"/>
						</div>
						<div  class="col-md-11">
							{{ substr($news['content'], 0, 500) }} ...
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
@endsection
