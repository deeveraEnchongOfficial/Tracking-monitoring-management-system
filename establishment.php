<?php include('db_connect.php');?>

<div class="container-fluid">
	
	<div class="col-lg-12">
		<div class="row mb-4 mt-4">
			<div class="col-md-12">
				
			</div>
		</div>
		<div class="row">
			<!-- FORM Panel -->

			<!-- Table Panel -->
			<div class="col-md-12">
				<div class="card">
					<div class="card-header">
						<b>Estblishment List</b>
						<span class="float:right"><button class="btn btn-primary btn-block btn-sm col-sm-2 float-right" type="button" id="new_establishment">
					<i class="fa fa-plus"></i> Add Establishment
				</button></span>
					</div>
					<div class="card-body">
						<table class="table table-condensed table-bordered table-hover">
							<thead>
								<tr>
									<th class="text-center">#</th>
									<th class="">Establishment</th>
									<th class="">Address</th>
									<th class="text-center">Action</th>
								</tr>
							</thead>
							<tbody>
								<?php 
								$i = 1;
								$types = $conn->query("SELECT * FROM establishments order by id asc");
								while($row=$types->fetch_assoc()):
								?>
								<tr>
									<td class="text-center"><?php echo $i++ ?></td>
									
									<td class="">
										 <p> <b><?php echo ucwords($row['name']) ?></b></p>
									</td>
									<td class="">
										 <p> <b><?php echo $row['address'] ?></b></p>
									</td>
									<td class="text-center">
										<button class="btn btn-sm btn-outline-primary edit_establishment" type="button" data-id="<?php echo $row['id'] ?>" >Edit</button>
										<button class="btn btn-sm btn-outline-danger delete_establishment" type="button" data-id="<?php echo $row['id'] ?>">Delete</button>
									</td>
								</tr>
								<?php endwhile; ?>
							</tbody>
						</table>
					</div>
				</div>
			</div>
			<!-- Table Panel -->
		</div>
	</div>	

</div>
<style>
	
	td{
		vertical-align: middle !important;
	}
	td p{
		margin: unset
	}
	img{
		max-width:100px;
		max-height: :150px;
	}
</style>
<script>
	$(document).ready(function(){
		$('table').dataTable()
	})
	$('#new_establishment').click(function(){
		uni_modal("New Establishment","manage_establishment.php")
	})
	
	$('.edit_establishment').click(function(){
		uni_modal("Edit Establishment","manage_establishment.php?id="+$(this).attr('data-id'))
		
	})
	$('.delete_establishment').click(function(){
		_conf("Are you sure to delete this Establishment?","delete_establishment",[$(this).attr('data-id')])
	})
	
	function delete_establishment($id){
		start_load()
		$.ajax({
			url:'ajax.php?action=delete_establishment',
			method:'POST',
			data:{id:$id},
			success:function(resp){
				if(resp==1){
					alert_toast("Data successfully deleted",'success')
					setTimeout(function(){
						location.reload()
					},1500)

				}
			}
		})
	}
</script>