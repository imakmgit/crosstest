@extends('layouts.pdf')

@section('content')
<div style="padding: 20px;">
	<div style="font-size: 50px;font-family: sans-serif;border-bottom: 1px solid #d2d2d2;padding: 5px;">
		{{ $news[0]['title'] }}
	</div>
	<div class="col-md-8 col-md-offset-2 news-image">
		<img src="http://www.newsstand.com/{{ $news[0]['image_path'] }}" />
	</div>
	<div class="col-md-12 news-content" >
		<p>{!! str_replace("\n", '</p><p>', $news[0]['content']) !!}</p>
	</div>
</div>
@endsection
