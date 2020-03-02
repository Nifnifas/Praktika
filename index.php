<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">

<html>

<head>

<title>REGISTRAS A</title>
<link rel="stylesheet" href="list.css">
</head>

<body bgcolor="#f2f2f2">



<?php

/*

VIEW-PAGINATED.PHP

Displays all data from 'players' table

This is a modified version of view.php that includes pagination

*/



// connect to the database

include('connect-db.php');
include('auth.php');
//list($vartotojas, $domain)=explode('@', $_SERVER['REMOTE_USER']);
$vartotojas = "admin";
//unset($domain);
// number of results to show per page
if (true)
{
$per_page = 20;

$result = mysqli_query($connection, "SELECT * FROM ".$DB_table." ORDER BY ID DESC");

$total_results = mysqli_num_rows($result);

$total_pages = ceil($total_results / $per_page);



// check if the 'page' variable is set in the URL (ex: view-paginated.php?page=1)

if (isset($_GET['page']) && is_numeric($_GET['page']))

{

$show_page = $_GET['page'];



// make sure the $show_page value is valid

if ($show_page > 0 && $show_page <= $total_pages)

{

$start = ($show_page -1) * $per_page;

$end = $start + $per_page;

}

else

{

// error - show first set of results

$start = 0;

$end = $per_page;

}

}

else

{

// if page isn't set, show first set of results

$start = 0;

$end = $per_page;

}



// display pagination


echo '<table id="header1">';
echo '<tr><th><span style="float:center;">Jūs dirbate su registru A</span><th></th><th></th></th></tr>';
echo "<tr><td><a href='page.php'>Visas sąrašas</a> | <a href='search.php'>Paieška</a> | <b>Puslapis:</b> ";
for ($i = 1; $i <= $total_pages; $i++)

{

echo "<a href='index.php?page=$i'>$i</a> ";

}

echo "</td></tr>";
echo '</table>';

echo '<table id="header">';
echo '<tr><td><a href="new.php"><img border="0" alt="Naujas irasas" src="images/new.png" width="50" height="50"></a></td><td><span style="float:center;">TEISMO PIRMININKO ĮSAKYMŲ DĖL ATOSTOGŲ, KOMANDIRUOČIŲ, MATERIALINIŲ PAŠALPŲ REGISTRAS A </span></td><td><span style="float:right;">Prisijungęs '. $vartotojas .'</span></td></tr>';
echo "</table>";


// display data in table
?>
<table id="sarasas">
<?php
//echo "<table border='1' cellpadding='10' id='sarasas'>";

echo "<tr> <th>Reg. Nr.</th> <th>Data</th> <th>Prašymo pavadinimas</th> <th>Bylos, į kurią įdėtas dokumentas, nuoroda</th> <th>Pastabos</th> ";

if (authenticate_admin($vartotojas))
{
echo "<th>Darbuotojas</th>";
}
echo "<th>Irašo data</th>";
if (authenticate_admin($vartotojas))
{
echo "<th></th> <th></th>";
}
echo "</tr>";


// loop through results of database query, displaying them in the table

for ($i = $start; $i < $end; $i++)

{

// make sure that PHP doesn't try to show results that don't exist


if ($i == $total_results) { break; }

$j=$i+1;
// echo out the contents of each row into a table

echo "<tr>";

echo '<td>' . mysql_result($result, $i, 'RegNr') . '</td>';

echo '<td>' . mysql_result($result, $i, 'Data') . '</td>';

echo '<td>' . mysql_result($result, $i, 'Pavadinimas') . '</td>';

echo '<td>' . mysql_result($result, $i, 'Bylos_nuoroda') . '</td>';

echo '<td>' . mysql_result($result, $i, 'Pastabos') . '</td>';

if (authenticate_admin($vartotojas))
{
echo '<td>' . mysql_result($result, $i, 'Vartotojas') . '</td>';
}

echo '<td>' . mysql_result($result, $i, 'IrasoData') . '</td>';

if (authenticate_admin($vartotojas))
{

echo '<td><a href="edit.php?id=' . mysql_result($result, $i, 'ID') . '"><img border="0" alt="edit" src="images/edit-document.jpg" width="20" height="20"></a></td>';

echo '<td><a href="delete.php?id=' . mysql_result($result, $i, 'ID') . '"><img border="0" alt="delete" src="images/delete-button.jpg" width="20" height="20"></a></td>';

}

echo "</tr>";
}

// close table>

echo "</table>";



// pagination
echo '<table id="header1">';
echo '<tr><td><a href="new.php"><img border="0" alt="Naujas irasas" src="images/new.png" width="50" height="50"></a><span style="float:right;">Prisijungęs '. $vartotojas .'</span></tr></td>';
echo "</table>";
}
else 
{
	echo 'Nerastas vartotojas';
}

?>


</body>

</html>