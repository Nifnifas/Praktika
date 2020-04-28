<html>
<?php
if (isset($_GET['name'])) {
        $name = $_GET['name'];
        include('config.php');
        include('connect-db.php');
        session_start();
        $ds = connectToAD($server);
        $result = bindAD($ds, $userdn, $userpw);
        ?>

<head>

    <title>REGISTRAS <?= $name ?></title>
    <link rel="stylesheet" href="list.css">
</head>

<body bgcolor="#f2f2f2">

    <?php

echo '<form action="view.php?name=';
echo $name;
echo '"';
echo 'method="post">';
?>
    <table id="header1">
        <?php
echo '<tr><th><span style="float:center;">Jūs dirbate su registru ';
echo $name;
echo '</span></th></td>';
echo "<tr><td><a href='all.php'>Pagrindinis puslapis</a></td></tr>";
echo "</table>";
?>
        <table id="irasas">
            <div>
                <tr>
                    <td>
                        <strong>Paieškos kriterijus</strong>
                    </td>
                    <td>
                        <select name="Metai" id="fill" value="<?php echo $Metai; ?>" required /><br />
                        <option value=""> </option>
                        <option value="2016">2016</option>
                        <option value="2017">2017</option>
                        <option value="2018">2018</option>
                        <option value="2019">2019</option>
                        <option value="2020">2020</option>
                        </select><br />
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
                        <input type="submit" id="btn" name="submit" value="Ieškoti">
                    </td>
                    <td>
                    </td>
                </tr>

        </table>
        </div>

        </form>

</body>
<?php } ?>

</html>