<html>
    <head>
        <title>@yield('title')</title>
        @include('include.css')
    </head>
    <body class="master-layout">
		<div class="container top-margin">
			@yield('content')
		</div>
    </body>
</html>
