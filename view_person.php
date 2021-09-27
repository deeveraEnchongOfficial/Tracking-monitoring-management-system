<?php
include 'db_connect.php';
require 'assets/barcode/vendor/autoload.php';
extract($_POST);

$generator = new Picqer\Barcode\BarcodeGeneratorPNG();
$qry = $conn->query("SELECT *,concat(lastname,', ',firstname,' ',middlename) as name, concat(address,', ',street,', ',baranggay,', ',city,', ',state,', ',zip_code) as caddress FROM persons where id= ".$_GET['id']);
foreach($qry->fetch_array() as $k => $val){
	$$k=$val;
}
?>
<div class="container-fluid">
<div class="row">
<div class="col-lg-4">
<div class="container-fluid" id="toPrint">
<style type="text/css">
	#bcode-field{
		width:calc(100%) ;
    	align-items: center;

	}
	#bcode{
		max-height: inherit;
		max-width: inherit;
		width:calc(100%) ;
		height: 10vh;
	}
	#bcode-label {
   font-weight: 700;
    font-size: 17px;
    text-align: justify;
    text-align-last: justify;
    text-justify: inter-word;
	}
	#dfield p{
		margin: unset
	}
	#uni_modal .modal-footer{
		display: none;
	}
	#uni_modal .modal-footer.display{
		display: block;
	}
	.text-center{
		text-align:center;
	}
</style>
	<div class="" id="bcode-field">
		<img id="bcode" src="data:image/png;base64,<?php echo base64_encode($generator->getBarcode($tracking_id, "C128")) ?>">
		<div id="bcode-label"><?php echo preg_replace('/([0-9])/s','$1 ', $tracking_id); ?></div>
	</div>
	<br>
	<div class="col-lg-12 text-center" id="dfield">
	<p><large><b><?php echo $name ?></b></large></p>
	<hr>
	<p><small><b><?php echo $caddress ?></b></small></p>
	</div>
</div>
</div>
<div class="col-lg-8">
	<table class="table table-bordered">
		<thead>
			<tr>
				<th class="text-center">#</th>
				<th class="">Date</th>
				<th class="">Establishment</th>
				<th class="">Address</th>
				<th class="">Temperature</th>
			</tr>
		</thead>
		<tbody>
			<?php 
			$i = 1;
			$tracks = $conn->query("SELECT t.*,e.address,e.name as ename FROM person_tracks t inner join establishments e on e.id = t.establishment_id where t.person_id = '$id' order by t.id desc");
			while($row=$tracks->fetch_assoc()):
			?>
			<tr>
				
				<td class="text-center"><?php echo $i++ ?></td>
				<td class="">
					 <p> <b><?php echo date("M d,Y h:i A",strtotime($row['date_created'])) ?></b></p>
				</td>
				<td class="">
					 <p> <b><?php echo ucwords($row['ename']) ?></b></p>
				</td>
				<td class="">
					 <p> <b><?php echo $row['address'] ?></b></p>
				</td>
				<td class="text-right">
					 <p> <b><?php echo $row['temperature'] ?>&#730;</b></p>
				</td>
			</tr>
			<?php endwhile; ?>
		</tbody>
	</table>
</div>
</div>
</div>
<div class="modal-footer display">
	<div class="row">
		<div class="col-lg-12">
			<button class="btn btn-sm btn-secondary col-md-3 float-right" type="button" data-dismiss="modal">Close</button>
			<button class="btn btn-sm btn-success col-md-3 float-right mr-2" type="button" id="print"><i class="fa fa-print"></i> Print</button>
		</div>
	</div>
</div>
<script type="text/javascript">
	$('#print').click(function(){
		var nw = window.open("","_blank","height=700,width=900")
		var content = $('#toPrint').clone()
			nw.document.write(content.html())
			nw.document.close()
			nw.print()
			setTimeout(function(){
				nw.close();
			},500)
	})
</script>