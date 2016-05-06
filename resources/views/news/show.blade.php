@extends('layouts.master')

@section('title', $news[0]['title'])

@section('content')
<div>
	<div class="col-md-12 news-title">
		<div>
			{{ $news[0]['title'] }}
		</div>
		<div class="info pull-left">Published on {{ $news['0']['created_at'] }} by {{ $user_info['name'] }}</div>
		<div class="info pull-right">		
			<a href="/pdf{{ $news[0]['url'] }}">Save as PDF</a>
		</div>
	</div>
	<div class="col-md-8 col-md-offset-2 news-image">
		<img src="{{ $news[0]['image_path'] }}" alt="{{ $news[0]['title'] }}" title="{{ $news[0]['title'] }}" />
	</div>
	<div class="col-md-12 news-content" >
		<p>{!! str_replace("\n", '</p><p>', $news[0]['content']) !!}</p>
	</div>
</div>
@endsection
