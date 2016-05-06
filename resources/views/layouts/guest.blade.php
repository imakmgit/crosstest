<html>
    <head>
        <title>@yield('title')</title>
        @include('include.css')
        <script type="text/javascript">
			
			@if(isset($property_id) && !empty($property_id))
				
				@foreach($property_id as $key => $value)
					var {{ $key }} = '{{ $value }}';
				@endforeach

			@endif

        </script>
    </head>
    <body>
		@include('include.header')
		<div class="container top-margin">
			@yield('content')
		</div>
		@include('include.footer')
		
    </body>
</html>
