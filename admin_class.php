<?php
session_start();
ini_set('display_errors', 1);
Class Action {
	private $db;

	public function __construct() {
		ob_start();
   	include 'db_connect.php';
    
    $this->db = $conn;
	}
	function __destruct() {
	    $this->db->close();
	    ob_end_flush();
	}

	function login(){
		extract($_POST);
		$qry = $this->db->query("SELECT * FROM users where username = '".$username."' and password = '".md5($password)."' ");
		if($qry->num_rows > 0){
			foreach ($qry->fetch_array() as $key => $value) {
				if($key != 'passwors' && !is_numeric($key))
					$_SESSION['login_'.$key] = $value;
			}
				return 1;
		}else{
			return 3;
		}
	}
	function logout(){
		session_destroy();
		foreach ($_SESSION as $key => $value) {
			unset($_SESSION[$key]);
		}
		header("location:login.php");
	}

	function save_user(){
		extract($_POST);
		$data = " name = '$name' ";
		$data .= ", username = '$username' ";
		if(!empty($password))
		$data .= ", password = '".md5($password)."' ";
		$data .= ", type = '$type' ";
		if($type == 1)
			$establishment_id = 0;
		$data .= ", establishment_id = '$establishment_id' ";
		$chk = $this->db->query("Select * from users where username = '$username' and id !='$id' ")->num_rows;
		if($chk > 0){
			return 2;
			exit;
		}
		if(empty($id)){
			$save = $this->db->query("INSERT INTO users set ".$data);
		}else{
			$save = $this->db->query("UPDATE users set ".$data." where id = ".$id);
		}
		if($save){
			return 1;
		}
	}
	function delete_user(){
		extract($_POST);
		$delete = $this->db->query("DELETE FROM users where id = ".$id);
		if($delete)
			return 1;
	}
	function signup(){
		extract($_POST);
		$data = " name = '$name' ";
		$data .= ", contact = '$contact' ";
		$data .= ", address = '$address' ";
		$data .= ", username = '$email' ";
		$data .= ", password = '".md5($password)."' ";
		$data .= ", type = 3";
		$chk = $this->db->query("SELECT * FROM users where username = '$email' ")->num_rows;
		if($chk > 0){
			return 2;
			exit;
		}
			$save = $this->db->query("INSERT INTO users set ".$data);
		if($save){
			$qry = $this->db->query("SELECT * FROM users where username = '".$email."' and password = '".md5($password)."' ");
			if($qry->num_rows > 0){
				foreach ($qry->fetch_array() as $key => $value) {
					if($key != 'passwors' && !is_numeric($key))
						$_SESSION['login_'.$key] = $value;
				}
			}
			return 1;
		}
	}

	function save_settings(){
		extract($_POST);
		$data = " name = '".str_replace("'","&#x2019;",$name)."' ";
		$data .= ", email = '$email' ";
		$data .= ", contact = '$contact' ";
		$data .= ", about_content = '".htmlentities(str_replace("'","&#x2019;",$about))."' ";
		if($_FILES['img']['tmp_name'] != ''){
						$fname = strtotime(date('y-m-d H:i')).'_'.$_FILES['img']['name'];
						$move = move_uploaded_file($_FILES['img']['tmp_name'],'assets/img/'. $fname);
					$data .= ", cover_img = '$fname' ";

		}
		
		// echo "INSERT INTO system_settings set ".$data;
		$chk = $this->db->query("SELECT * FROM system_settings");
		if($chk->num_rows > 0){
			$save = $this->db->query("UPDATE system_settings set ".$data);
		}else{
			$save = $this->db->query("INSERT INTO system_settings set ".$data);
		}
		if($save){
		$query = $this->db->query("SELECT * FROM system_settings limit 1")->fetch_array();
		foreach ($query as $key => $value) {
			if(!is_numeric($key))
				$_SESSION['setting_'.$key] = $value;
		}

			return 1;
				}
	}

	
	function save_establishment(){
		extract($_POST);
		$data = " name = '$name' ";
		$data .= ", address = '$address' ";
		if(empty($id)){
			$save = $this->db->query("INSERT INTO establishments set ".$data);
		}else{
			$save = $this->db->query("UPDATE establishments set ".$data." where id=".$id);
		}
		if($save)
			return 1;
	}
	function delete_establishment(){
		extract($_POST);
		$delete = $this->db->query("DELETE FROM establishments where id = ".$id);
		if($delete)
			return 1;
	}
	
	function save_person(){
		extract($_POST);
		$data = " lastname = '$lastname' ";
		$data .= ", firstname = '$firstname' ";
		$data .= ", middlename = '$middlename' ";
		$data .= ", address = '$address' ";
		$data .= ", street = '$street' ";
		$data .= ", baranggay = '$baranggay' ";
		$data .= ", city = '$city' ";
		$data .= ", state = '$state' ";
		$data .= ", zip_code = '$zip_code' ";
		$cwhere ='';
		
		if(empty($id)){
			$tracking_id = mt_rand(1,9999999999);
			$tracking_id  = sprintf("%'010d\n", $tracking_id);
			$i= 1;
			while($i == 1){
				$check = $this->db->query("SELECT * FROM persons where tracking_id ='$tracking_id' ")->num_rows;
				if($check > 0){
					$tracking_id = mt_rand(1,9999999999);
					$tracking_id  = sprintf("%'010d\n", $tracking_id);
				}else{
					$i = 0;
				}
			}
		$data .= ", tracking_id = '$tracking_id' ";
			$save = $this->db->query("INSERT INTO persons set ".$data);
		}else{
			$save = $this->db->query("UPDATE persons set ".$data." where id=".$id);
		}
		if($save)
			return 1;
	}
	function delete_person(){
		extract($_POST);
		$delete = $this->db->query("DELETE FROM persons where id = ".$id);
		if($delete)
			return 1;
	}
	function save_track(){
		extract($_POST);
		$data = " person_id = '$person_id' ";
		$data .= ", establishment_id = '$establishment_id' ";
		$data .= ", temperature = '$temperature' ";
		

		if(empty($id)){
			$save = $this->db->query("INSERT INTO person_tracks set ".$data);
			
		}else{
			$save = $this->db->query("UPDATE person_tracks set ".$data." where id=".$id);
		}
		if($save){

			return json_encode(array("status"=>1,"id"=>$id));
		}
	}
	function delete_track(){
		extract($_POST);
		$delete = $this->db->query("DELETE FROM person_tracks where id = ".$id);
		if($delete){
				return 1;
			}
	}
	function get_pdetails(){
		extract($_POST);
		$get = $this->db->query("SELECT *,concat(lastname,', ',firstname,' ',middlename) as name, concat(address,', ',street,', ',baranggay,', ',city,', ',state,', ',zip_code) as caddress FROM persons where tracking_id = $tracking_id ");
		$data = array();
		if($get->num_rows > 0){
			foreach($get->fetch_array() as $k => $v){
				$data['status'] = 1;
				if(!is_numeric($k)){
					if($k == 'name')
						$v = ucwords($v);
					$data[$k]=$v;
				}
			}
		}else{
			$data['status'] = 2;
		}
		return json_encode($data);
		
	}
}