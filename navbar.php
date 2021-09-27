
<style>
	.collapse a{
		text-indent:10px;
	}
</style>
<nav id="sidebar" class='mx-lt-5 bg-dark' >
		
		<div class="sidebar-list">

				<a href="index.php?page=home" class="nav-item nav-home"><span class='icon-field'><i class="fa fa-home"></i></span> Home</a>
				<a href="index.php?page=records" class="nav-item nav-records"><span class='icon-field'><i class="fa fa-th-list"></i></span> Records</a>
				<?php if($_SESSION['login_type'] == 1): ?>
				<a href="index.php?page=establishment" class="nav-item nav-establishment"><span class='icon-field'><i class="fa fa-building"></i></span> Establishment List</a>
				<a href="index.php?page=persons" class="nav-item nav-persons"><span class='icon-field'><i class="fa fa-user-friends"></i></span> Person List</a>
				<a href="index.php?page=users" class="nav-item nav-users"><span class='icon-field'><i class="fa fa-users"></i></span> Users</a>
				
			<?php endif; ?>
		</div>

</nav>
<script>
	$('.nav_collapse').click(function(){
		console.log($(this).attr('href'))
		$($(this).attr('href')).collapse()
	})
	$('.nav-<?php echo isset($_GET['page']) ? $_GET['page'] : '' ?>').addClass('active')
</script>
