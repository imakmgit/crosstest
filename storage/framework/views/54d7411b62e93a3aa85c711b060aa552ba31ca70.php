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
		<?php if(!empty($user)): ?>

		  <li>
			<a href="/dashboard">Publish a News</a>
		  </li>
		  <li>
			<a href="/dashboard/news">My Published News</a>
		  </li>
		 <?php endif; ?>
		<li class="dropdown">
			<?php if(!empty($user)): ?>
			<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
				<span class="truncate"><?php echo e($user[0]['name']); ?></span><span class="caret"></span>
			</a>
			<?php else: ?>
			<a href="/auth/login">Login</a>
			<?php endif; ?>
			<ul class="dropdown-menu">
				<li><a href="/auth/logout">Logout</a></li>
			</ul>
		</li>
	  </ul>
	</div><!--/.nav-collapse -->
  </div>
</nav>
