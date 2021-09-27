<?php 
include('db_connect.php');
session_start();
if(isset($_GET['id'])){
$user = $conn->query("SELECT * FROM users where id =".$_GET['id']);
foreach($user->fetch_array() as $k =>$v){
	$meta[$k] = $v;
}
}
?>
<div class="container-fluid">
	<div id="msg"></div>
	
	<form action="" id="manage-user">	
		<input type="hidden" name="id" value="<?php echo isset($meta['id']) ? $meta['id']: '' ?>">
		<div class="form-group">
			<label for="name">Name</label>
			<input type="text" name="name" id="name" class="form-control" value="<?php echo isset($meta['name']) ? $meta['name']: '' ?>" required>
		</div>
		<div class="form-group">
			<label for="username">Username</label>
			<input type="text" name="username" id="username" class="form-control" value="<?php echo isset($meta['username']) ? $meta['username']: '' ?>" required  autocomplete="off">
		</div>
		<div class="form-group">
			<label for="password">Password</label>
			<input type="password" name="password" id="password" class="form-control" value="" autocomplete="off">
			<?php if(isset($meta['id'])): ?>
			<small><i>Leave this blank if you dont want to change the password.</i></small>
		<?php endif; ?>
		</div>
		<div class="form-group">
			<label for="type">User Type</label>
			<select name="type" id="type" class="custom-select">
				<option value="2" <?php echo isset($meta['type']) && $meta['type'] == 2 ? 'selected': '' ?>>Staff</option>
				<option value="1" <?php echo isset($meta['type']) && $meta['type'] == 1 ? 'selected': '' ?>>Admin</option>
			</select>
		</div>
		<div id="est-field">
		<?php if($_SESSION['login_type'] == 1): ?>
			<div class="form-group">
				<label for="" class="control-label">Establishment</label>
				<select name="establishment_id" id="" class="custom-select select2">
					<option value=""></option>
			<?php 
				$establishment = $conn->query("SELECT * FROM establishments order by name asc");
				while($row= $establishment->fetch_assoc()):
			?>
					<option value="<?php echo $row['id'] ?>" <?php echo isset($meta['establishment_id']) && $row['id'] == $meta['establishment_id'] ? 'selected' : '' ?>><?php echo $row['name'] ?></option>
			<?php endwhile; ?>
				</select>
			</div>
			<?php else: ?>
			<input type="hidden" name="establishment_id" value="<?php echo isset($meta['establishment_id']) ? $meta['establishment_id'] : $_SESSION['login_establishment_id'] ?>">
			<?php endif; ?>
		</div>
		

	</form>
</div>
<script>
	$('.select2').select2({
		placeholder:"Please select here",
		width:"100%"
	})
	
	$('#manage-user').submit(function(e){
		e.preventDefault();
		start_load()
		$.ajax({
			url:'ajax.php?action=save_user',
			method:'POST',
			data:$(this).serialize(),
			success:function(resp){
				if(resp ==1){
					alert_toast("Data successfully saved",'success')
					setTimeout(function(){
						location.reload()
					},1500)
				}else{
					$('#msg').html('<div class="alert alert-danger">Username already exist</div>')
					end_load()
				}
			}
		})
	})
	$('#type').change(function(){
		if($(this).val() == 1){
			$('#est-field').hide()
		}else{
			$('#est-field').show()
		}
	})	
</script>