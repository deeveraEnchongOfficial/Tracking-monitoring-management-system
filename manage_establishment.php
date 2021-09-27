<?php include 'db_connect.php' ?>
<?php
if(isset($_GET['id'])){
$qry = $conn->query("SELECT * FROM establishments where id= ".$_GET['id']);
foreach($qry->fetch_array() as $k => $val){
	$$k=$val;
}
}
?>
<div class="container-fluid">
	<form action="" id="manage-establishment">
		<input type="hidden" name="id" value="<?php echo isset($id) ? $id :'' ?>">
		<div class="form-group">
			<label for="" class="control-label">Establishment</label>
			<input type="text" class="form-control" name="name"  value="<?php echo isset($name) ? $name :'' ?>" required>
		</div>
		<div class="form-group">
			<label for="" class="control-label">Address</label>
			<textarea name="address" id="address" class="form-control" cols="30" rows="2" required><?php echo isset($address) ? $address :'' ?></textarea>
		</div>
	</form>
</div>
<script>
	$('#manage-establishment').submit(function(e){
		e.preventDefault()
		start_load()
		$('#msg').html('')
		$.ajax({
			url:'ajax.php?action=save_establishment',
			data: new FormData($(this)[0]),
		    cache: false,
		    contentType: false,
		    processData: false,
		    method: 'POST',
		    type: 'POST',
			success:function(resp){
				if(resp==1){
					alert_toast("Data successfully saved",'success')
					setTimeout(function(){
						location.reload()
					},1500)

				}
				
			}
		})
	})
</script>