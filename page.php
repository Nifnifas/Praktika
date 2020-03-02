<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">

<html>

<head>

<title>REGISTRAS A	</title>
<link rel="stylesheet" href="list.css">
</head>

<body bgcolor="#f2f2f2">



<?php

/*

VIEW.PHP

Displays all data from 'players' table

*/



// connect to the database

include('connect-db.php');
include('authenticate.php');
setlocale(LC_CTYPE, 'lt_LT');
list($vartotojas, $domain)=explode('@', $_SERVER['REMOTE_USER']);
unset($domain);
// number of results to show per page
if (authenticate($vartotojas))
{


// get results from database

$result = mysql_query("SELECT * FROM ".$DB_table."")

or die(mysql_error());



// display data in table
?>
<table id="header1">
<?php
echo '<tr><th><span style="float:center;">Jūs dirbate su registru A</span><th></th><th></th></th></tr>';
echo "<tr><td><b>Visas sąrašas</b> | <a href='index.php?page=1'>Pagrindinis puslapis</a></tr></td>";
echo '<tr><td><a href="new.php"><img border="0" alt="Naujas irasas" src="images/new.png" width="50" height="50"></a></td><td><span style="float:center;">TEISMO PIRMININKO ĮSAKYMŲ DĖL ATOSTOGŲ, KOMANDIRUOČIŲ, MATERIALINIŲ PAŠALPŲ REGISTRAS A </span></td><td><span style="float:right;">Prisijungęs '. $vartotojas .'</span></td></tr>';
echo "</table>";

?>
<table id="sarasas">
<?php

echo "<tr> <th>Reg. Nr.</th> <th>Data</th> <th>Prašymo pavadinimas</th> <th>Bylos, į kurią įdėtas dokumentas, nuoroda</th> <th>Pastabos</th> ";
if (authenticate_admin($vartotojas))
{
echo "<th>Darbuotojas</th>";
}
echo "<th>Įrašo data</th>";
if (authenticate_admin($vartotojas))
{
echo "<th></th> <th></th>";
}
echo "</tr>";


$i = 0;
// loop through results of database query, displaying them in the table

while($row = mysql_fetch_array( $result )) {



// echo out the contents of each row into a table
$i=$i+1;

echo "<tr>";

echo '<td>' . $row['RegNr'] . '</td>';

echo '<td>' . $row['Data'] . '</td>';

echo '<td>' . $row['Pavadinimas'] . '</td>';

echo '<td>' . $row['Bylos_nuoroda'] . '</td>';

echo '<td>' . $row['Pastabos'] . '</td>';


if (authenticate_admin($vartotojas))
{
echo '<td>' . $row['Vartotojas'] . '</td>';
}

echo '<td>' . $row['IrasoData'] . '</td>';

if (authenticate_admin($vartotojas))
{

echo '<td><a href="edit.php?id=' . $row['ID'] . '"><img border="0" alt="edit" src="images/edit-document.jpg" width="20" height="20"></a></td>';

echo '<td><a href="delete.php?id=' . $row['ID'] . '"><img border="0" alt="delete" src="images/delete-button.jpg" width="20" height="20"></a></td>';

}

echo "</tr>";

}



// close table>

echo "</table>";

echo '<table id="header1">';
echo '<tr><td><a href="new.php"><img border="0" alt="Naujas irasas" src="images/new.png" width="50" height="50"></a><span style="float:right;">Prisijungęs '. $vartotojas .'</span></tr></td>';
echo "</table>";
//echo '<p><a href="new.php">Naujas įrašas</a></p>';
}
else 
{
	echo 'Jums prieiga nesuteikta. Kreipkitės į sistemos administratorių';

	
}
?>

</body>

</html>