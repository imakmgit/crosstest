<nav class="navbar navbar-default ng-scope">
  <div class="container">
	<div class="navbar-header">
	  <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
		<span class="sr-only">Toggle navigation</span>
		<span class="icon-bar"></span>
		<span class="icon-bar"></span>
		<span class="icon-bar"></span>
	  </button>
	  <a class="navbar-brand" href="/">NewsStand</a>
	</div>
	<div id="navbar" class="navbar-collapse collapse">
	  <ul class="nav navbar-nav navbar-right">
		<li class="dropdown">
			@if(!empty($user))
			<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
				<span class="truncate">{{ $user[0]['name'] }}</span><span class="caret"></span>
			</a>
			@else
			<a href="/auth/login">Login</a>
			@endif
			<ul class="dropdown-menu">
				<li><a href="/news/list">Published News</a></li>
				<li><a href="/auth/logout">Logout</a></li>
			</ul>
		</li>
	  </ul>
	</div><!--/.nav-collapse -->
  </div>
</nav>
