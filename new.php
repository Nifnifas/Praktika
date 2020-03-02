<?php

/*

DELETE.PHP

Deletes a specific entry from the 'players' table

*/



// connect to the database

include('connect-db.php');
include('authenticate.php');
list($vartotojas, $domain)=explode('@', $_SERVER['REMOTE_USER']);
unset($domain);
// number of results to show per page
if (authenticate($vartotojas))
{

$result = mysql_query("SELECT count(*) FROM ".$DB_table."");
$row = mysql_fetch_array( $result );
if($row['count(*)']!=0) {
		$result = mysql_query("SELECT MAX(id) FROM ".$DB_table."")
		or die(mysql_error());
		$nr = mysql_fetch_array( $result );
		$result = mysql_query("SELECT * FROM ".$DB_table." WHERE id = ".$nr['MAX(id)']."")
		or die(mysql_error());
		$nrdoc = mysql_fetch_array( $result );
		$nrnext = $nrdoc['RegNr'];
		//echo $nrdoc['RegNr'];
		list($rd, $Did)=explode('-', $nrdoc['RegNr']);
		$madenr = $Did +1;
		$fullnr = $rd . '-' . $madenr;
}
else
	{
		$fullnr = 'A-1';
	}
mysql_query("INSERT ".$DB_table." SET RegNr='$fullnr', Data=curdate(), Vartotojas='$vartotojas', IrasoData=CURRENT_TIMESTAMP")
	or die(mysql_error());
// redirect back to the view page
$result = mysql_query("SELECT MAX(id) FROM ".$DB_table."")
		or die(mysql_error());
		$nr = mysql_fetch_array( $result );
		$id = $nr['MAX(id)'];
		
header('Location: edit.php?id='.$id.'');
}
else 
{
	echo 'Nerastas vartotojas';
}


?>