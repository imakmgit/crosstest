<script type="text/javascript" src="{{ URL::asset('/js/jquery-1.11.3.min.js') }}"> </script>
<script type="text/javascript" src="{{ URL::asset('/js/bootstrap.min.js') }}"> </script>
<script type="text/javascript" src="{{ URL::asset('/js/jquery-ui.min.js') }}"> </script>
<script type="text/javascript" src="{{ URL::asset('/js/jquery.growl.js') }}"> </script>
<script type="text/javascript" src="{{ URL::asset('/js/slidebars.js') }}"> </script>
<script type="text/javascript" src="{{ URL::asset('/js/script.js') }}"> </script>
@if(file_exists(public_path() . '/js/' . $controller . '.js'))
	<script type="text/javascript" src="{{ URL::asset('/js/' . $controller . '.js') }}"> </script>
@endif
