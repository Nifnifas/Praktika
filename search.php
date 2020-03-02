<?php

/*

NEW.PHP

Allows user to create a new entry in the database

*/



// creates the new record form

// since this form is used multiple times in this file, I have made it a function that is easily reusable

function renderForm($Metai, $error)

{

?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "strict.dtd">

<html>

<head>

<title>REGISTRAS A</title>
<link rel="stylesheet" href="list.css">
</head>
<body bgcolor="#f2f2f2">

<?php

// if there are any errors, display them
if ($error != '')

{

echo '<div style="padding:4px; border:1px solid red; color:red;">'.$error.'</div>';

}
echo '<form action="" method="post">';
$filename = 'values.txt';
$eachlines = file($filename, FILE_IGNORE_NEW_LINES);//create an array
?>
<table id="header1">
<?php
echo '<tr><th><span style="float:center;">Jūs dirbate su sąrašais</span></th></td>';
echo "<tr><td><a href='index.php'>Pagrindinis puslapis</a></td></tr>";
echo "</table>";
?>
<table id="irasas">
<div>
<tr>
<td>
<strong>Metai *</strong>
</td>
<td>
<select name="Metai" id="fill" value="<?php echo $Metai; ?>" required/><br/>
  <option value=""> </option>
  <option value="2017">2016</option>
  <option value="2017">2017</option>
  <option value="2018">2018</option>
  <option value="2019">2019</option>
  <option value="2020">2020</option>
</select><br/>
</td>
</tr>
<tr>
<td>
* Privalomi laukai paieškai atlikti
</td>
<td>
</td>
</tr>
<tr>
<td>
<input type="submit"  id="btn" name="submit" value="Ieškoti">
</td>
<td>
</td>
</tr>

</table>
</div>

</form>

</body>

</html>

<?php

}









// connect to the database

include('connect-db.php');
include('authenticate.php');
list($vartotojas, $domain)=explode('@', $_SERVER['REMOTE_USER']);
unset($domain);
// number of results to show per page
if (authenticate($vartotojas))
{
// check if the form has been submitted. If it has, start to process the form and save it to the database

if (isset($_POST['submit']))

{
// get form data, making sure it is valid

$Metai = mysql_real_escape_string(htmlspecialchars($_POST['Metai']));

if ( $Metai == '')

{
$Metai_start=$Metai . "-01-01";
$Metai_end=$Metai . "-12-31";
// generate error message

$error = 'ERROR: Prašome užpildyti visus laukus!';



// if either field is blank, display the form again

renderForm($Metai, $error);

}

else

{

// save the data to the database
//SELECT * FROM `BaudziamosiosATP` WHERE `ApygIsnagrData` > '2017-12-14' AND `ApygIsnagrData` <= '2017-12-31'
//if ($Tipas == 'BAU/AP')
$querySearch = "SELECT * FROM ".$DB_table." WHERE Data >= '".$Metai."-01-01' and Data <= '".$Metai."-12-31'";
$result = mysql_query($querySearch) or die(mysql_error());
?>
<table id="header1">
<?php
echo '<tr><th><span style="float:center;">Jūs dirbate su registru A</span><th></th><th></th></th></tr>';
echo "<tr><td><b>".$Metai." metų sąrašas</b> | <a href='index.php?page=1'>Pagrindinis puslapis</a></tr></td>";
echo '<tr><td><a href="new.php"><img border="0" alt="Naujas irasas" src="images/new.png" width="50" height="50"></a></td><td><span style="float:center;">TEISMO PIRMININKO ĮSAKYMŲ DĖL ATOSTOGŲ, KOMANDIRUOČIŲ, MATERIALINIŲ PAŠALPŲ REGISTRAS A </span></td><td><span style="float:right;">Prisijungęs '. $vartotojas .'</span></td></tr>';
echo "</table>";
?>
<link rel="stylesheet" href="list.css">
<body bgcolor="#f2f2f2">
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
// once saved, redirect back to the view page

//header("Location: view.php");

}


// check to make sure both fields are entered
//turinio tikrinimui vieta
}

else

// if the form hasn't been submitted, display the form

{

renderForm('','');

}
}
else 
{
	echo 'Jums prieiga nesuteikta. Kreipkitės į sistemos administratorių';
}
?>