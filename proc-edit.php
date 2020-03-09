<?php
//reikia pirma patikrint ar kazkas postinama is viso.
    include('config.php');
    include('connect-db.php');
    session_start();
    $ds = connectToAD($server);
    $result = bindAD($ds, $userdn, $userpw);

    if ($result) {
        $columnCount = $_POST["count"];
        $name = $_POST["name"];
        $postResults = array();
        for ($i = 0; $i < $columnCount; $i++){
            $postResults[$i] = $_POST["$i"];
        }

        $dn = "OU=$postResults[0],OU=$name," . $basedn;
        $filter = array("ou", "description");
        $sr = ldap_list($ds, $dn, "ou=*", $filter);
        $info = ldap_get_entries($ds, $sr);

        for($i = 0; $i < $columnCount; $i++){
            $title = $info[$i]["ou"][0];
            //$registry["ou"] = $title;
            $registry["description"] = $_POST[$i];
            //$registry["objectClass"] = "organizationalUnit";
            ldap_mod_replace($ds, "OU=$title,OU=$postResults[0],OU=$name," . $basedn, $registry);
        }
        $_SESSION["msg"] = "Irasas sekmingai pakeistas!";
        header("Location: view.php?name=$name");
    } else {
        $_SESSION["msg"] = "Klaida! Jus neturite tam teisiu.";
        header("Location: add.php?name=$name");
    }
    ldap_close($ds);
?>