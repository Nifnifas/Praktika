<?php

/*

NEW.PHP

Allows user to create a new entry in the database

*/



// creates the new record form

// since this form is used multiple times in this file, I have made it a function that is easily reusable

function renderForm($regnr, $data, $pavad, $byl_link, $Pastabos, $error, $flag)

{

?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "strict.dtd">

<html>

<head>

<title>Naujas įrašas. Registras A</title>
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
include('connect-db.php');
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
if($flag =='1')
{}
else
{
	mysql_query("INSERT ".$DB_table." SET RegNr='$fullnr'")
	or die(mysql_error());
	$flag ='1';
}
?>
<table id="irasas">
<div>



<tr>
<td>
<strong>Reg. Nr. *</strong>
</td>
<td>


<?php
echo ' <input type="text" id="fill" name="RegNr" value='.$fullnr.'><br/>';
?>
</td>
</tr>

<tr>
<td>
<strong>Data *</strong>
</td>
<td>
<input type="text" name="Data" id="datepicker" value="<?php echo $data; ?>" required/><br/>
</td>
</tr>

<tr>
<td>
<strong>Dokumento pavadinimas (antraštė) *</strong>
</td>
<td>
<input type="text" id="fill" name="Pavad" value="<?php echo $pavad; ?>" required/><br/>
</tr>

<tr>
<td>
<strong>Bylos, į kurią įdėtas (kuriai priskirtas) dokumentas, nuoroda *</strong>
</td>
<td>
<input type="text" id="fill" name="Byl_link" value="<?php echo $byl_link; ?>" required/><br/>
</td>
</tr>


<tr>
<td>
<strong>Pastabos</strong>
</td>
<td>
<input type="text" id="fill" name="Pastabos" value="<?php echo $Pastabos; ?>"<br/>
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

$regnr = mysql_real_escape_string(htmlspecialchars($_POST['RegNr']));

$data = mysql_real_escape_string(htmlspecialchars($_POST['Data']));

$pavad = mysql_real_escape_string(htmlspecialchars($_POST['Pavad']));

$byl_link = mysql_real_escape_string(htmlspecialchars($_POST['Byl_link']));

$Pastabos = mysql_real_escape_string(htmlspecialchars($_POST['Pastabos']));

// check to make sure both fields are entered
//turinio tikrinimui vieta
if ($regnr == '' || $data == '' || $pavad== '' || $byl_link== '' )

{

// generate error message

$error = 'ERROR: Prašome užpildyti visus laukus!';



// if either field is blank, display the form again

renderForm($regnr, $data, $pavad, $byl_link, $Pastabos, $error, $flag);

}

else

{

// save the data to the database

mysql_query("UPDATE ".$DB_table." SET Data='$data', Pastabos='$Pastabos', Pavadinimas='$pavad', Bylos_nuoroda='$byl_link', Vartotojas='$vartotojas' WHERE RegNr='".$regnr."'")

or die(mysql_error());


// once saved, redirect back to the view page

header("Location: index.php");

}

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