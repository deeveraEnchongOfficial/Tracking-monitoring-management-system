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
						<b>Monitoring List</b>
						<span class="">

							<button class="btn btn-primary btn-block btn-sm col-sm-2 float-right" type="button" id="new_records">
					<i class="fa fa-plus"></i> New</button>
					<button class="btn btn-success btn-block btn-sm col-sm-2 float-right mr-2 mt-0" type="button" id="print">
					<i class="fa fa-print"></i> Print</button>
				</span>
					</div>
					<div class="card-body">
						<div class="row form-group">
							<div class="col-md-4">
								<label for="" class="control-label">From</label>
								<input type="date" class="form-control" name="from"  value="<?php echo isset($_GET['from']) ? date('Y-m-d',strtotime($_GET['from'])) :date('Y-m-d', strtotime(date('Y-m-1'))); ?>" required>
							</div>
							<div class="col-md-4">
								<label for="" class="control-label">To</label>
								<input type="date" class="form-control" name="to"  value="<?php echo isset($_GET['to']) ? date('Y-m-d',strtotime($_GET['to'])) :date('Y-m-d', strtotime(date('Y-m-1')." +1 month - 1 day")); ?>" required>
							</div>
							<div class="col-md-2">
								<label for="" class="control-label">&nbsp</label>
								<button class="btn btn-primary btn-block" id="filter" type="button">Filter</button>								
							</div>
						</div>
						<hr>
						<table class="table table-bordered table-condensed table-hover">
							<colgroup>
								<col width="2%">
								<col width="10%">
								<col width="10%">
								<col width="15%">
								<col width="20%">
								<col width="15%">
								<col width="10%">
							</colgroup>
							<thead>
								<tr>
									<th class="text-center">#</th>
									<th class="">Date</th>
									<th class="">Tracking ID</th>
									<th class="">Name</th>
									<th class="">Address</th>
									<th class="">Establishment</th>
									<th class="">Temperature</th>
									<th class="text-center">Action</th>
								</tr>
							</thead>
							<tbody>
								<?php 
								$i = 1;
								$from = isset($_GET['from']) ? date('Y-m-d',strtotime($_GET['from'])) :date('Y-m-d', strtotime(date('Y-m-1'))); 
								$to = isset($_GET['to']) ? date('Y-m-d',strtotime($_GET['to'])) :date('Y-m-d', strtotime(date('Y-m-1')." +1 month - 1 day"));
								$ewhere = '';
								if($_SESSION['login_establishment_id'] > 0)
									$ewhere = " and t.establishment_id = '".$_SESSION['login_establishment_id']."' ";
								$tracks = $conn->query("SELECT t.*,concat(p.lastname,', ',p.firstname,' ',p.middlename) as name, concat(p.address,', ',p.street,', ',p.baranggay,', ',p.city,', ',p.state,', ',p.zip_code) as caddress,e.name as ename,p.tracking_id FROM person_tracks t inner join persons p on p.id = t.person_id inner join establishments e on e.id = t.establishment_id where date(t.date_created) between '$from' and '$to' $ewhere order by t.id desc");
								while($row=$tracks->fetch_assoc()):
								?>
								<tr>
									
									<td class="text-center"><?php echo $i++ ?></td>
									<td class="">
										 <p> <b><?php echo date("M d,Y h:i A",strtotime($row['date_created'])) ?></b></p>
									</td>
									<td class="">
										 <p> <b><?php echo $row['tracking_id'] ?></b></p>
									</td>
									<td class="">
										 <p> <b><?php echo ucwords($row['name']) ?></b></p>
									</td>
									<td class="">
										 <p> <b><?php echo $row['caddress'] ?></b></p>
									</td>
									<td class="">
										 <p> <b><?php echo ucwords($row['ename']) ?></b></p>
									</td>
									<td class="text-right">
										 <p> <b><?php echo $row['temperature'] ?>&#730;</b></p>
									</td>

									<td class="text-center">
										<button class="btn btn-sm btn-outline-primary edit_records" type="button" data-id="<?php echo $row['id'] ?>" >Edit</button>
										<button class="btn btn-sm btn-outline-danger delete_records" type="button" data-id="<?php echo $row['id'] ?>">Delete</button>
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
	$('#new_records').click(function(){
		uni_modal("New Record","manage_records.php")
	})
	
	$('.edit_records').click(function(){
		uni_modal("Edit Record","manage_records.php?id="+$(this).attr('data-id'),"mid-large")
		
	})
	$('.delete_records').click(function(){
		_conf("Are you sure to delete this Person?","delete_records",[$(this).attr('data-id')])
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
	$('#print').click(function(){
		start_load()
		$.ajax({
			url:"print_records.php",
			method:"POST",
			data:{from : '<?php echo $from ?>' , to : "<?php echo $to ?>"},
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
	$('#filter').click(function(){
		location.replace("index.php?page=records&from="+$('[name="from"]').val()+"&to="+$('[name="to"]').val())
	})

	function delete_records($id){
		start_load()
		$.ajax({
			url:'ajax.php?action=delete_records',
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