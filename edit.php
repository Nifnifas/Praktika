<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">

<html>
    <?php
        if (isset($_GET['id']) && isset($_GET['name'])) {
            $id = $_GET['id'];
            $name = $_GET['name'];
            include('connect-db.php');
            $ds = connectToAD();
            $result = bindAD($ds);
    ?>

<head>
    <title>REGISTRO <?= $id ?> redagavimas</title>
    <link rel="stylesheet" href="list.css">
    <link rel="stylesheet" href="jquery-ui.css">
    <script src="jquery.min.js"></script>
    <script src="jquery-ui.js"></script>
</head>

<body bgcolor="#f2f2f2">
     
<table id="header1">
        <tr>
            <th><span style="float:center;">Jūs redaguojate registro <?= $name ?> irasa <?= $id ?></span>
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
                     <form action="proc-edit.php" method="post">
                        <table id="irasas">
                            <div>
                         <?php 
                                if($result){
                                    $basedn = "OU=$id,OU=$name,OU=Registrai,OU=TableSet,DC=mycompany,DC=com";
                                    $filter = array("ou", "description");
                                    $sr = ldap_list($ds, $basedn, "ou=*", $filter);
                                    $info = ldap_get_entries($ds, $sr);
                                    $dn = "OU=Registrai,OU=TableSet,DC=mycompany,DC=com";
                                    $columns = getColumns($ds, $dn, $name);
                                    $inputTypes = getInputType($ds, $dn, $name);
                                    ?>
                                    <tr>
                                        <td>
                                            <strong><?php echo $columns[0]; ?></strong>
                                        </td>
                                        <td>
                                            <input type="text" name="<?= 0 ?>" value="<?=$info[0]["description"][0]?>" class="form-control" readonly /> 
                                        </td>
                                    </tr>
                                    <?php
                                    for($i = 1; $i < $info["count"]-1; $i++){
                                        switch($inputTypes[$i]){
                                            case "Date":
                                                ?>
                                                <tr>
                                                    <td>
                                                        <strong><?php echo $columns[$i]; ?></strong>
                                                    </td>
                                                    <td>
                                                        <input type="text" name="<?=$i?>" id="datepicker<?php echo $i;?>" value="<?php echo $info[$i]["description"][0]; ?>" class="datepicker" required/><br/>
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
                                                        <input type="text" name="<?= $i ?>" value="<?=$info[$i]["description"][0]?>" class="form-control" /> 
                                                    </td>
                                                </tr>
                                        <?php
                                        }
                                    }
                                    ?>
                                        <input type="hidden" name="<?=$info["count"]-1?>" class="form-control" value="<?php echo getDateAndTime();?>" />
                                        <input type="hidden" name="count" class="form-control" value="<?=$info["count"]?>" />
                                        <input type="hidden" name="name" class="form-control" value="<?=$name?>" />
                                    <?php
                                }
                            
                         ?>
                            <tr>
                                <td>
                                    <input type="submit" name="submit" id="submit" class="btn btn-info" value="Issaugoti" />  
                                </td>
                            </tr>
                          </div>
                        </table>   
                     </form> 
           <?php
            }
          ?>  
           
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