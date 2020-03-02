<?php

/*

EDIT.PHP

Allows user to edit specific entry in database

*/



// creates the edit record form

// since this form is used multiple times in this file, I have made it a function that is easily reusable

function renderForm($id, $regnr, $data, $Pavadinimas, $Bylos_nuoroda, $Pastabos, $error)

{

?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">

<html>

<head>

<title>Įrašo redagavimas A registre</title>
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

?>



<form action="" method="post">

<input type="hidden" name="id" value="<?php echo $id; ?>"/>

<table id="irasas">

<?php
if (isset($_GET['id']) && is_numeric($_GET['id']) && $_GET['id'] > 0)

{

// query db

include('connect-db.php');
// query db

$id = $_GET['id'];
$result = mysql_query("SELECT * FROM ".$DB_table." WHERE id=$id ")
or die(mysql_error());

$row = mysql_fetch_array($result);
}
?>
<div>

<tr>
<td>
<strong>Reg. Nr. *</strong>
</td>
<td>

<?php
echo ' <input type="text" id="fill" name="RegNr" value='.$regnr.'><br/>';
?>
</td>
</tr>

<tr>
<td>
<strong>Data *</strong>
</td>
<td>
<input type="text" name="Data" id="datepicker" value="<?php echo $data; ?>" /><br/>
</td>
</tr>

<tr>
<td>
<strong>Dokumento pavadinimas (antraštė) *</strong>
</td>
<td>
<input type="text" id="fill" name="Pavadinimas" value="<?php echo $Pavadinimas; ?>" /><br/>
</tr>

<tr>
<td>
<strong>Bylos, į kurią įdėtas (kuriai priskirtas) dokumentas, nuoroda *</strong>
</td>
<td>
<input type="text" id="fill" name="Bylos_nuoroda" value="<?php echo $Bylos_nuoroda; ?>" /><br/>
</td>
</tr>

<tr>
<td>
<strong>Pastabos</strong>
</td>
<td>
<input type="text" id="fill" name="Pastabos" value="<?php echo $Pastabos; ?>" /><br/>
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
<input type="submit"  id="btn" name="submit" value="Išsaugoti">
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


// check if the form has been submitted. If it has, process the form and save it to the database

include('connect-db.php');
include('authenticate.php');
setlocale(LC_CTYPE, 'lt_LT');
list($vartotojas, $domain)=explode('@', $_SERVER['REMOTE_USER']);
unset($domain);
// number of results to show per page
if (authenticate_admin($vartotojas))
{
	if (isset($_POST['submit']))

{

// confirm that the 'id' value is a valid integer before getting the form data

if (is_numeric($_POST['id']))

{

// get form data, making sure it is valid

$id = $_POST['id'];

$regnr = mysql_real_escape_string(htmlspecialchars($_POST['RegNr']));

$data = mysql_real_escape_string(htmlspecialchars($_POST['Data']));

$Pavadinimas = mysql_real_escape_string(htmlspecialchars($_POST['Pavadinimas']));

$Bylos_nuoroda = mysql_real_escape_string(htmlspecialchars($_POST['Bylos_nuoroda']));

$Pastabos = mysql_real_escape_string(htmlspecialchars($_POST['Pastabos']));


// check that firstname/lastname fields are both filled in

//turinio tikrinimui vieta
if ($regnr == '' || $data == '' || $Pavadinimas == '' || $Bylos_nuoroda == '')

{

// generate error message

$error = 'ERROR: Prašome užpildyti visus laukus ir įrašyti pastabą!';



//error, display form

renderForm($id, $regnr, $data, $Pavadinimas, $Bylos_nuoroda, $Pastabos, $error);

}

else

{

// save the data to the database

mysql_query("UPDATE ".$DB_table." SET RegNr='$regnr', Data='$data', Pavadinimas='$Pavadinimas', Bylos_nuoroda='$Bylos_nuoroda', Pastabos='$Pastabos', Vartotojas='$vartotojas', IrasoData=CURRENT_TIMESTAMP WHERE id='$id'")

or die(mysql_error());



// once saved, redirect back to the view page

header("Location: index.php");

}

}

else

{

// if the 'id' isn't valid, display an error

echo 'Error!';

}

}

else

// if the form hasn't been submitted, get the data from the db and display the form

{



// get the 'id' value from the URL (if it exists), making sure that it is valid (checing that it is numeric/larger than 0)

if (isset($_GET['id']) && is_numeric($_GET['id']) && $_GET['id'] > 0)

{

// query db

$id = $_GET['id'];

$result = mysql_query("SELECT * FROM ".$DB_table." WHERE id=$id")

or die(mysql_error());

$row = mysql_fetch_array($result);



// check that the 'id' matches up with a row in the databse

if($row)

{



// get data from db

$regnr = $row['RegNr'];

$data= $row['Data'];

$Pavadinimas = $row['Pavadinimas'];

$Bylos_nuoroda = $row['Bylos_nuoroda'];

$Pastabos = $row['Pastabos'];


// show form

renderForm($id, $regnr, $data, $Pavadinimas, $Bylos_nuoroda, $Pastabos, '');

}

else

// if no match, display result

{

echo "No results!";

}

}

else

// if the 'id' in the URL isn't valid, or if there is no 'id' value, display an error

{

echo 'Error!';

}

}
}
else 
{
	echo 'Nerastas vartotojas';
}
?>