<?php include 'db_connect.php' ?>
<style>
   span.float-right.summary_icon {
    font-size: 3rem;
    position: absolute;
    right: 1rem;
    color: #ffffff96;
}
</style>

<div class="containe-fluid">

	<div class="row mt-3 ml-3 mr-3">
			<div class="col-lg-12">
    			<div class="card">
    				<div class="card-body">
    				<?php echo "Welcome back ". $_SESSION['login_name']."!"  ?>
    					<hr>	

                        <div class="row">
                            <div class="col-lg-8 offset-2">
                                <div class="container-fluid">
                                <form action="" id="manage-records">
                                    <input type="hidden" name="id" value="<?php echo isset($id) ? $id :'' ?>">
                                    <div class="form-group">
                                        <label for="" class="control-label">Tracking ID</label>
                                        <input type="number" class="form-control" id="tracking_id" name="tracking_id"  value="<?php echo isset($tracking_id) ? $tracking_id :'' ?>" required autocomplete="off">
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <button class="btn btn-sm btn-primary btn-sm col-sm-2 btn-block float-right" type="button" id="check">Check</button>
                                        </div>
                                    </div>
                                    <div id="details" <?php echo isset($id) ? "style='display:block'" : 'style="display:none"' ?>>
                                        <p><b>Name: <span id="name"><?php echo isset($id) ? ucwords($name) : '' ?></span></b></p>
                                        <p><b>Address: <span id="address"><?php echo isset($id) ? $caddress : '' ?></span></b></p>
                                        <input type="hidden" name="person_id" value="<?php echo isset($person_id) ? $person_id : '' ?>">

                                        <div class="form-group">
                                            <label for="" class="control-label">Temperature</label>
                                            <input type="text" class="form-control" name="temperature"  value="<?php echo isset($temperature) ? $temperature :'' ?>" required>
                                        </div>
                                        <?php if($_SESSION['login_type'] == 1): ?>
                                        <div class="form-group">
                                            <label for="" class="control-label">Establishment</label>
                                            <select name="establishment_id" id="" class="custom-select select2">
                                                <option value=""></option>
                                        <?php 
                                            $establishment = $conn->query("SELECT * FROM establishments order by name asc");
                                            while($row= $establishment->fetch_assoc()):
                                        ?>
                                                <option value="<?php echo $row['id'] ?>" <?php echo isset($establishment_id) && $row['id'] == $establishment_id ? 'selected' : '' ?>><?php echo $row['name'] ?></option>
                                        <?php endwhile; ?>
                                            </select>
                                        </div>
                                        <?php else: ?>
                                        <input type="hidden" name="establishment_id" value="<?php echo isset($establishment_id) ? $establishment_id : $_SESSION['login_establishment_id'] ?>">
                                        <?php endif; ?>
                                <hr>
                                <div class="row">
                                    <div class="col-md-12">
                                    <button class="btn btn-sm btn-secondary btn-block col-sm-2 float-right" type="button" onlclick='reset_form()'>Cancel</button>
                                        <button class="btn btn-sm btn-primary btn-block col-sm-2 float-right mt-0 mr-2">Save</button>

                                    </div>
                                    
                                </div>
                                </div>

                                </form>
                            </div>
                            </div>
                        </div>      			
    				</div>
    				
    				
    		      </div>
                </div>
	</div>
<hr>

</div>
<script>
	$('#manage-records').submit(function(e){
        e.preventDefault()
        start_load()
        $.ajax({
            url:'ajax.php?action=save_track',
            data: new FormData($(this)[0]),
            cache: false,
            contentType: false,
            processData: false,
            method: 'POST',
            type: 'POST',
            success:function(resp){
                resp=JSON.parse(resp)
                if(resp.status==1){
                    alert_toast("Data successfully saved",'success')
                    setTimeout(function(){
                        location.reload()
                    },800)

                }
                
            }
        })
    })
    $('#tracking_id').on('keypress',function(e){
        if(e.which == 13){
            get_person()
        }
    })
    $('#check').on('click',function(e){
            get_person()
    })
    function get_person(){
            start_load()
        $.ajax({
                url:'ajax.php?action=get_pdetails',
                method:"POST",
                data:{tracking_id : $('#tracking_id').val()},
                success:function(resp){
                    if(resp){
                        resp = JSON.parse(resp)
                        if(resp.status == 1){
                            $('#name').html(resp.name)
                            $('#address').html(resp.address)
                            $('[name="person_id"]').val(resp.id)
                            $('#details').show()
                            end_load()

                        }else if(resp.status == 2){
                            alert_toast("Unknow tracking id.",'danger');
                            end_load();
                        }
                    }
                }
            })
    }
</script>