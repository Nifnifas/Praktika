<?php

/*

NEW.PHP

Allows user to create a new entry in the database

*/



// creates the new record form

// since this form is used multiple times in this file, I have made it a function that is easily reusable

function renderForm($teisejas, $BylosNr, $BylosPNr, $Isdata, $ABylosNr, $AIsdata, $PakPan, $Pastabos, $error)

{

?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "strict.dtd">

<html>

<head>

<title>Registrai</title>
<link rel="stylesheet" href="list.css">
</head>
  <link rel="stylesheet" href="jquery-ui.css">
  <script src="jquery.min.js"></script>
  <script src="jquery-ui.js"></script>

  <script>
  $( function() {
    $( "#datepicker" ).datepicker(
	{
		'format': 'yyyy-m-d',
        'autoclose': true
	}
	);
  } );
  </script>
    <script>
  $( function() {
    $( "#datepicker2" ).datepicker(
	{
		'format': 'yyyy-m-d',
        'autoclose': true
	}
	);
  } );
  </script>
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
echo "<tr><td><a href='view.php'>Civilinių bylų sąrašas</a></td></tr>";
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
  <option value="2017">2017</option>
  <option value="2018">2018</option>
</select><br/>
</td>
</tr>
<tr>
<td>
<strong>Tipas *</strong>
</td>
<td>
<select name="Tipas" id="fill" value="<?php echo $Tipas; ?>" required/><br/>
  <option value=""> </option>
  <option value="CIV">Civilinės bylos</option>
</select><br/>
</td>
</tr>
<tr>
<td>
* Privalomi laukai
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
$Tipas = mysql_real_escape_string(htmlspecialchars($_POST['Tipas']));

$Metai = mysql_real_escape_string(htmlspecialchars($_POST['Metai']));

if ($Tipas == '' || $Metai == '')

{
$Metai_start=$Metai . "-01-01";
$Metai_end=$Metai . "-12-31";
// generate error message

$error = 'ERROR: Prašome užpildyti visus laukus!';



// if either field is blank, display the form again

renderForm($Tipas, $Metai, $error);

}

else if ($Tipas == 'CIV')

{

// save the data to the database
//SELECT * FROM `BaudziamosiosATP` WHERE `ApygIsnagrData` > '2017-12-14' AND `ApygIsnagrData` <= '2017-12-31'
//if ($Tipas == 'BAU/AP')
$querySearch = "SELECT * FROM Civilines WHERE ApygIsnagrData >= '".$Metai."-01-01' and ApygIsnagrData <= '".$Metai."-12-31'";
$result = mysql_query($querySearch) or die(mysql_error());
?>
<table id="header1">
<?php
echo '<tr><th><span style="float:center;">Jūs dirbate su civilinėmis bylomis</span></th></td>';
echo "<tr><td><a href='index.php'>Pagrindinis puslapis</a> | <a href='view.php'>Visas sąrašas</a></tr></td>";
echo '<tr><td><a href="new.php"><img border="0" alt="Naujas irasas" src="images/new.png" width="50" height="50"></a><span style="float:right;">Prisijungęs '. $vartotojas .'</span></tr></td>';
echo "</table>";
?>
<link rel="stylesheet" href="list.css">
<body bgcolor="#f2f2f2">
<table id="sarasas">
<?php

echo "<tr> <th>Nr.</th> <th>Teisėjas</th> <th>Bylos Nr.</th> <th>Bylos teisminio proceso Nr.</th> <th>Išnagrinėjimo data</th> <th>Apygardos Bylos Nr.</th> <th>Apygardos Išnagrinėjimo Data</th> <th>Pakeistas / Panaikintas</th> <th>Pastabos</th>";
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

echo '<td>' . $i . '</td>';

echo '<td>' . $row['Teisejas'] . '</td>';

echo '<td>' . $row['BylosNr'] . '</td>';

echo '<td>' . $row['BylosTPN'] . '</td>';

echo '<td>' . $row['IsnagrData'] . '</td>';

echo '<td>' . $row['ApygBylosNr'] . '</td>';

echo '<td>' . $row['ApygIsnagrData'] . '</td>';

echo '<td>' . $row['PakeistasPanaikintas'] . '</td>';

$output = str_split($row['Pastabos'] , 50);

echo '<td>';
$PCount = 0;
while ($output[$PCount])
{
	echo '<p class="small">' . $output[$PCount] . '</p>';
	$PCount = $PCount + 1;
}
echo '</td>';

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

renderForm('','','','','','','','','');

}
}
else 
{
	echo 'Nerastas vartotojas';
}
?>