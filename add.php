<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">

<html>
<?php
        if (isset($_GET['name'])) {
            $name = $_GET['name'];
            include('connect-db.php');
            $ds = connectToAD();
            $result = bindAD($ds);
    ?>

<head>
    <title>Naujo iraso kurimas</title>
    <link rel="stylesheet" href="list.css">
    <link rel="stylesheet" href="jquery-ui.css">
    <script src="jquery.min.js"></script>
    <script src="jquery-ui.js"></script>
</head>

<body bgcolor="#f2f2f2">

    <table id="header1">
        <tr>
            <th><span style="float:center;">Jūs kuriate registro <?= $name ?> nauja irasa</span>
            <th></th>
            <th></th>
            </th>
        </tr>
        <tr>
            <td>
                <a href="view.php?name=<?= $name ?>">Atgal</a>
            </td>
        </tr>
    </table>
        <form action="proc-newRegistry.php" method="post">
            <table id="irasas">
                <div>
                    <?php 
                                if($result){
                                    $dn = 'OU=Registrai,OU=TableSet,DC=mycompany,DC=com';
                                    $columns = getColumns($ds, $dn, $name);
                                    $inputTypes = getInputType($ds, $dn, $name);
                                    ?>
                                   <tr>
                                        <td>
                                            <strong><?php echo $columns[0]; ?></strong>
                                        </td>
                                        <td>
                                             <input type="text" name="<?=0?>" placeholder="<?=$columns[0]?> (Auto-gen)" class="form-control" readonly />
                                        </td>
                                   </tr>
                    <?php
                                    for($i = 1; $i < count($columns)-2; $i++){
                                        switch($inputTypes[$i]){
                                            case "Date":
                                            ?>
                                                <tr>
                                                    <td>
                                                        <strong><?php echo $columns[$i]; ?></strong>
                                                    </td>
                                                    <td>
                                                        <input type="text" name="<?=$i?>" id="datepicker<?php echo $i;?>" placeholder="<?php echo $columns[$i]; ?>" class="datepicker" required/><br/>
                                                    </td>
                                                </tr>
                                            <?php
                                            break;
                                            case "Text":
                                        ?>
                                            <tr>
                                                <td>
                                                    <strong><?php echo $columns[$i]; ?></strong>
                                                </td>
                                                <td>
                                                    <input type="text" name="<?=$i?>" placeholder="<?=$columns[$i]?>" class="form-control" required />
                                                </td>
                                            </tr>
                        <?php
                                            break;
                                        }
                                    }
                                    ?>
                    <input type="hidden" name="count" class="form-control" value="<?=count($columns)-1?>" />
                    <input type="hidden" name="name" class="form-control" value="<?=$name?>" />
                    <?php
                                }
                            } else {
                                 echo "ERROR 404";
                            }
                         ?>
                    <tr>
                         <td> 
                              <input type="submit" name="submit" id="submit" class="btn btn-info" value="Submit" />
                         </td>
                    </tr>
                </div>
            </table>
        </form>
    </body>

</html>

<script>
  $(function() {
    $( ".datepicker" ).datepicker({
		'format': 'yyyy-m-d',
        'autoclose': true
	});
  });
</script>