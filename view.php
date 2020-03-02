<html>

<head>
    <title>View.php</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" />
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>
</head>

<body>

    <?php
    if (isset($_GET['name'])) {
        $name = $_GET['name'];
        include('connect-db.php');
        $ds = connectToAD();
        $result = bindAD($ds);

        if ($result) {
            $basedn = "OU=$name,OU=Registrai,OU=TableSet,DC=mycompany,DC=local";
            $filter = array("ou", "description");
            $sr = ldap_list($ds, $basedn, "ou=*", $filter);
            $info = ldap_get_entries($ds, $sr);
                /*$basedn = 'OU=Registrai,OU=TableSet,DC=mycompany,DC=local';
                $filter = array("ou", "description");
                $sr = ldap_list($ds, $basedn, "ou=$name", $filter);
                $info = ldap_get_entries($ds, $sr);
                $column = "";
                $column = explode(";", $info[0]["description"][0]);*/
    ?>
                <div class="container">
                    <br />
                    <br />
                    <h2 align="center">*<?php echo $name; ?>* registru sarasas</h2>
                    <a href="add.php?name=<?= $name ?>">Prideti irasa</a> | 
                    <a href="view.php?name=<?php echo $name; ?>">Atnaujinti</a> | 
                    <a href="all.php">Atgal</a>
                    <div class="form-group">
                        <table class="table">
                            <thead>
                                <tr>
                                    <?php
                                        //tokiem dalykam reiktu sukurti atskiras funkcijas kad padavus per parametrus gauciau reza
                                        $dn = "OU=Registrai,OU=TableSet,DC=mycompany,DC=local";
                                        $filterAll = array("ou", "description");
                                        $getList = ldap_list($ds, $dn, "ou=$name", $filterAll);
                                        $list = ldap_get_entries($ds, $getList);
                                        //explodina lievai nes yra prie duomenu galo kabliataskis
                                        $str_arr = explode(";", $list[0]["description"][0]); 
                                        $count =  count($str_arr)-1;
                                        for($i=0; $i < $count; $i++){
                                    ?>
                                            <th scope="col"><?php echo $str_arr[$i]; ?></th>
                                    <?php
                                        }
                                    ?>
                                    <th scope="col">Valdymas</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                if($info["count"] > 0){
                                    for ($i = 0; $i < $info["count"]; $i++) {
                                        $id = $info[$i]["ou"][0];
                                        $basedn2 = "OU=$id,OU=$name,OU=Registrai,OU=TableSet,DC=mycompany,DC=local";
                                        $filter2 = array("ou", "description");
                                        $sr2 = ldap_list($ds, $basedn2, "ou=*", $filter2);
                                        $info2 = ldap_get_entries($ds, $sr2);
                                    ?>
                                        <tr>
                                            <?php
                                            for ($j = 0; $j < $info2["count"]; $j++) {
                                            ?>
                                                <td><?php /*echo array_search("pirmas", $info2[2]["ou"], false) */ echo $info2[$j]["description"][0]?></td>
                                            <?php
                                            }
                                            ?>
                                            <td><a href="edit.php?name=<?= $name ?>&id=<?= $info[$i]["ou"][0] ?>">Redaguoti</a> | <a href="delete.php?id=<?= $info[$i]["description"][0] ?>">Istrinti</a></td>
                                        </tr>
    <?php
                                    }
                                }
                                
                            else {
                                ?>
                                    <tr><td align="center" colspan="<?php echo $count+1; ?>">Registru nera!</td></tr>
                                <?php
                            }
                            ?>
                            </tbody>
                            </table>
                        </div>
                    </div>
                    <?php
                        } else {
                            echo "You do not have rights to view Registries.";
                        }
                        ldap_close($ds);
                    }
?>
</body>

</html>