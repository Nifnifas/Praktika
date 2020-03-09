<?php
	//kolkas nera apsaugos, errorai resfreshinant page, nera redirect
	if (isset($_GET['id']) && is_numeric($_GET['id'])){
		$id = $_GET['id'];
		$name = $_GET['name']; // ifas turi buti ir su situo
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
				$_SESSION["msg"] = "Irasas istrintas sekmingai!";
				header("Location: view.php?name=$name");
			} else {
				$_SESSION["msg"] = "Klaida! Jus neturite tam teisiu.";
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
				$_SESSION["msg"] = "Aplankalas istrintas sekmingai!";
				header("Location: all.php");
			} else {
				$_SESSION["msg"] = "Klaida! Jus neturite tam teisiu.";
				header("Location: all.php");
			}
		}
	} 
	else {
		echo "ERROR";
	}		
?>