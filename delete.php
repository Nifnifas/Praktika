<?php
	if (isset($_GET['id']) && is_numeric($_GET['id'])){
		$id = $_GET['id'];
		$name = $_GET['name'];
		include('config.php');
		include('connect-db.php');
		session_start();
		$ds = connectToAD($server);
		$result = bindAD($ds, $userdn, $userpw);
		if($result){
			$basedn = "OU=$name," . $basedn;
            $filter = array("ou", "description");
			$sr = ldap_list($ds, $basedn, "ou=$id", $filter);
			$info = ldap_get_entries($ds, $sr);
			$entrydn = 'OU=' . $info[0]["ou"][0] . ',' . $basedn;
			$delete = recursive_ldap_delete($ds, $entrydn, true);
			if($delete){
				$_SESSION["msg"] = "Įrašas ištrintas sėkmingai!";
				header("Location: view.php?name=$name");
			} else {
				$_SESSION["msg"] = "Klaida! Jūs neturite tam teisių.";
				header("Location: add.php?name=$name");
			}
		}
	} else if(isset($_GET['id']) && !is_numeric($_GET['id'])) {
		$name = $_GET['id'];
		include('config.php');
		include('connect-db.php');
		session_start();
		$ds = connectToAD($server);
		$result = bindAD($ds, $userdn, $userpw);
		if($result){
            $filter = array("ou", "description");
			$sr = ldap_list($ds, $basedn, "ou=$name", $filter);
			$info = ldap_get_entries($ds, $sr);
			$entrydn = 'OU=' . $info[0]["ou"][0] . ',' . $basedn;
			$delete = recursive_ldap_delete($ds, $entrydn, true);
			if($delete){
				$_SESSION["msg"] = "Aplankalas ištrintas sėkmingai!";
				header("Location: all.php");
			} else {
				$_SESSION["msg"] = "Klaida! Jūs neturite tam teisių.";
				header("Location: all.php");
			}
		}
	} 
	else {
		echo "ERROR 404";
	}		
?>