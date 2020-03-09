<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">

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

        if ($result) {
            $dn = "OU=$name," . $basedn;
            $filter = array("ou", "description");
            $sr = ldap_list($ds, $dn, "ou=*", $filter);
            $info = ldap_get_entries($ds, $sr);
            //pagination
            $per_page = 4;
            $total_results = $info["count"];
            $total_pages = ceil($total_results / $per_page);
            // check if the 'page' variable is set in the URL (ex: view-paginated.php?page=1)
            if (isset($_GET['page']) && is_numeric($_GET['page'])) {
                $show_page = $_GET['page'];
                if ($show_page > 0 && $show_page <= $total_pages) {
                    $start = ($show_page -1) * $per_page;
                    $end = $start + $per_page;
                }
                else {
                    $start = 0;
                    $end = $per_page;
                }
            } else {
                // if page isn't set, show first set of results, or if 'all' is set as 'true' show all results
                $start = 0;
                if (isset($_GET['all'])) {
                    $all = $_GET['all'];
                    if($all){
                        $end = $info["count"];
                    } else {
                        $end = $per_page;
                    }
                } else {
                    $end = $per_page;
                }
            }
    ?>

    <table id="header1">
        <tr>
            <th><span style="float:center;">Jūs dirbate su registru <?= $name ?></span>
            <th></th>
            <th></th>
            </th>
        </tr>
        <?php 
            if($_SESSION["msg"] != ""){
                ?>
                <tr>
                    <th>
                        <span onclick="this.parentElement.style.display='none';">&times;</span>
                        <?= $_SESSION["msg"] ?>
                    </th>
                </tr>
                <?php
                clearSession();
            }
        ?>
        <tr>
            <td><a href="add.php?name=<?= $name ?>">Prideti irasa</a> |
                <a href="view.php?name=<?php echo $name; ?>">Atnaujinti</a> |
                <a href="all.php">Atgal</a> | <a href='view.php?name=<?= $name ?>&all=true'>Visas sąrašas</a> | <a href='search.php'>Paieška</a> |
                <b>Puslapis:</b>
                <?php 
                    for ($i = 1; $i <= $total_pages; $i++) {
                    ?>
                        <a href="view.php?name=<?= $name ?>&page=<?= $i ?>"><?php echo $i; ?></a>
                    <?php
                    }
                ?>
            </td>
        </tr>
    </table>


    <table id="sarasas">
        <thead>
            <tr>
                <?php
                                        $str_arr = getColumns($ds, $basedn, $name); 
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
                                    //for ($i = 0; $i < $info["count"]; $i++) {
                                    for ($i = $start; $i < $end; $i++) {
                                        if ($i == $total_results) { break; }
                                        $id = $info[$i]["ou"][0];
                                        $dn = "OU=$id,OU=$name," . $basedn;
                                        $filter2 = array("ou", "description");
                                        $sr2 = ldap_list($ds, $dn, "ou=*", $filter2);
                                        $info2 = ldap_get_entries($ds, $sr2);
                                    ?>
            <tr>
                <?php
                                            for ($j = 0; $j < $info2["count"]; $j++) {
                                            ?>
                <td><?php /*echo array_search("pirmas", $info2[2]["ou"], false) */ echo $info2[$j]["description"][0]?>
                </td>
                <?php
                                            }
                                            ?>
                <td><a href="edit.php?name=<?= $name ?>&id=<?= $info[$i]["ou"][0] ?>"><img border="0" alt="edit"
                            src="images/edit-document.jpg" width="20" height="20"></a> | <a
                        href="delete.php?name=<?= $name ?>&id=<?= $info[$i]["ou"][0] ?>"><img border="0" alt="delete"
                            src="images/delete-button.jpg" width="20" height="20"></a></td>
            </tr>
            <?php
                                    }
                                }
                                
                            else {
                                ?>
            <tr>
                <td align="center" colspan="<?php echo $count+1; ?>">Registru nera!</td>
            </tr>
            <?php
                            }
                            ?>
        </tbody>
    </table>
    <?php
                        } else {
                            echo "You do not have rights to view Registries.";
                        }
                        ldap_close($ds);
                    }
?>
</body>

</html>