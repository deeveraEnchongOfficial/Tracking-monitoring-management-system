<?php include('db_connect.php');?>

<div class="container-fluid">
<style>
	input[type=checkbox]
{
  /* Double-sized Checkboxes */
  -ms-transform: scale(1.5); /* IE */
  -moz-transform: scale(1.5); /* FF */
  -webkit-transform: scale(1.5); /* Safari and Chrome */
  -o-transform: scale(1.5); /* Opera */
  transform: scale(1.5);
  padding: 10px;
}
</style>
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
						<b>List of Individuals</b>
						<span class="">

							<button class="btn btn-primary btn-block btn-sm col-sm-2 float-right" type="button" id="new_person">
					<i class="fa fa-plus"></i> Add Person</button>
					<button class="btn btn-success btn-block btn-sm col-sm-2 float-right mr-2 mt-0" type="button" id="print_selected">
					<i class="fa fa-print"></i> Print Selected</button>
				</span>
					</div>
					<div class="card-body">
						<table class="table table-condensed table-hover">
							<thead>
								<tr>
									<th class="text-center">
										 <div class="form-check">
										  <input class="form-check-input position-static" type="checkbox" id="check_all"  aria-label="...">
										</div>
									</th>
									<th class="text-center">#</th>
									<th class="">Tracking ID</th>
									<th class="">Name</th>
									<th class="">Address</th>
									<th class="text-center">Action</th>
								</tr>
							</thead>
							<tbody>
								<?php 
								$i = 1;
								$types = $conn->query("SELECT *,concat(lastname,', ',firstname,' ',middlename) as name, concat(address,', ',street,', ',baranggay,', ',city,', ',state,', ',zip_code) as caddress FROM persons order by name asc");
								while($row=$types->fetch_assoc()):
								?>
								<tr>
									<th class="text-center">
										<div class="form-check">
										 	<input class="form-check-input position-static input-lg" type="checkbox" name="checked[]" value="<?php echo $row['id'] ?>">
									 	</div>
									</th>
									<td class="text-center"><?php echo $i++ ?></td>
									<td class="">
										 <p> <b><?php echo $row['tracking_id'] ?></b></p>
									</td>
									<td class="">
										 <p> <b><?php echo ucwords($row['name']) ?></b></p>
									</td>
									<td class="">
										 <p> <b><?php echo $row['caddress'] ?></b></p>
									</td>
									<td class="text-center">
										<button class="btn btn-sm btn-outline-primary view_person" type="button" data-id="<?php echo $row['id'] ?>" >View</button>
										<button class="btn btn-sm btn-outline-primary edit_person" type="button" data-id="<?php echo $row['id'] ?>" >Edit</button>
										<button class="btn btn-sm btn-outline-danger delete_person" type="button" data-id="<?php echo $row['id'] ?>">Delete</button>
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
	$('#new_person').click(function(){
		uni_modal("New Person","manage_person.php","mid-large")
	})
	
	$('.edit_person').click(function(){
		uni_modal("Edit Person","manage_person.php?id="+$(this).attr('data-id'),"mid-large")
		
	})
	$('.view_person').click(function(){
		uni_modal("Person Details","view_person.php?id="+$(this).attr('data-id'),"large")
		
	})
	$('.delete_person').click(function(){
		_conf("Are you sure to delete this Person?","delete_person",[$(this).attr('data-id')])
	})
	$('#check_all').click(function(){
		if($(this).prop('checked') == true)
			$('[name="checked[]"]').prop('checked',true)
		else
			$('[name="checked[]"]').prop('checked',false)
	})
	$('[name="checked[]"]').click(function(){
		var count = $('[name="checked[]"]').length
		var checked = $('[name="checked[]"]:checked').length
		if(count == checked)
			$('#check_all').prop('checked',true)
		else
			$('#check_all').prop('checked',false)
	})
	$('#print_selected').click(function(){
		var checked = $('[name="checked[]"]:checked').length
		if(checked <= 0){
			alert_toast("Check atleast one individual details row first.","danger")
			return false;
		}
		var ids = [];
		$('[name="checked[]"]:checked').each(function(){
			ids.push($(this).val())
		})
		start_load()
		$.ajax({
			url:"print_persons.php",
			method:"POST",
			data:{ids : ids},
			success:function(resp){
				if(resp){
					var nw = window.open("","_blank","height=600,width=900")
					nw.document.write(resp)
					nw.document.close()
					nw.print()
					setTimeout(function(){
						nw.close()
						end_load()
					},700)
				}
			}
		})
	})

	function delete_person($id){
		start_load()
		$.ajax({
			url:'ajax.php?action=delete_person',
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