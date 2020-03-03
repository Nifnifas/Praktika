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
    <title>REGISTRO <?= $id ?> redagavimas</title>
    <link rel="stylesheet" href="list.css">
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
                                    ?>
                                   <tr>
                                        <td>
                                            <strong><?php echo $columns[0]; ?></strong>
                                        </td>
                                        <td>
                                             <input type="text" name="<?=0?>" placeholder="<?=$columns[0]?>" class="form-control" readonly />
                                        </td>
                                   </tr>
                    <?php
                                    for($i = 1; $i < count($columns)-1; $i++){
                                        ?>
                                        <tr>
                                             <td>
                                                  <strong><?php echo $columns[$i]; ?></strong>
                                             </td>
                                             <td>
                                                  <input type="text" name="<?=$i?>" placeholder="<?=$columns[$i]?>" class="form-control" />
                                             </td>
                                        </tr>
                    <?php
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