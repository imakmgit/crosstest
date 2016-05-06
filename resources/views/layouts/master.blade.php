<html>
    <head>
        <title>@yield('title')</title>
        @include('include.css')
    </head>
    <body class="master-layout">
		@include('include.header')
		<div class="container top-margin">
			@yield('content')
		</div>
		@include('include.footer')
		
    </body>
</html>
