<?php
	//kolkas nera apsaugos, errorai resfreshinant page, nera redirect, galima trinti tik iharcodinta A aplankale esancia info
	if (isset($_GET['id']) && is_numeric($_GET['id'])){
		$id = $_GET['id'];
		include('connect-db.php');
		$ds = connectToAD();
		$result = bindAD($ds);
		if($result){
			$basedn = 'OU=A aplankalas,OU=Registrai,OU=TableSet,DC=mycompany,DC=local';
            $filter = array("ou", "description");
			$sr = ldap_list($ds, $basedn, "description=$id", $filter);
			$info = ldap_get_entries($ds, $sr);
			$entrydn = 'OU=' . $info[0]["ou"][0] . ',' . $basedn;
			$delete = recursive_ldap_delete($ds, $entrydn, true);
			if($delete){
				echo "Deleted successfully.";
			} else {
				echo "ERROR";
			}
		}
	} else if(isset($_GET['id']) && !is_numeric($_GET['id'])) {
		$name = $_GET['id'];
		include('connect-db.php');
		$ds = connectToAD();
		$result = bindAD($ds);
		if($result){
			$basedn = 'OU=Registrai,OU=TableSet,DC=mycompany,DC=local';
            $filter = array("ou", "description");
			$sr = ldap_list($ds, $basedn, "ou=$name", $filter);
			$info = ldap_get_entries($ds, $sr);
			$entrydn = 'OU=' . $info[0]["ou"][0] . ',' . $basedn;
			$delete = recursive_ldap_delete($ds, $entrydn, true);
			if($delete){
				echo "Deleted successfully.";
			} else {
				echo "ERROR";
			}
		}
	} 
	else {
		echo "ERROR";
	}		
?>