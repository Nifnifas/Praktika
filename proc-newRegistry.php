<?php
    include('config.php');
    include('connect-db.php');
    session_start();
    $ds = connectToAD($server);
    $result = bindAD($ds, $userdn, $userpw);

    if ($result) {
        $columnCount = $_POST["count"];
        $postResults = array();
        for ($i = 0; $i < $columnCount-1; $i++){
            $postResults[$i] = $_POST["$i"];
        }
        $name = $_POST["name"];
        $postResults[0] = autoIncrement($ds, "OU=$name," . $basedn);
        $postResults[$columnCount-1] = getDateAndTime();
        $registry["ou"] = $postResults[0];
        $registry["description"] = count($postResults);
        $registry["objectClass"] = "organizationalUnit";
        ldap_add($ds, "OU=$postResults[0],OU=$name," . $basedn, $registry);

        $filter = array("ou", "description");
        $sr = ldap_list($ds, $basedn, "ou=$name", $filter);
        $info = ldap_get_entries($ds, $sr);
        $str_arr = explode(";", $info[0]["description"][0]);  

        for($i = 0; $i < $columnCount; $i++){
            $title = $i . "_" . $str_arr[$i];
            $registry["ou"] = $title;
            $registry["description"] = $postResults[$i];
            $registry["objectClass"] = "organizationalUnit";
            ldap_add($ds, "OU=$title,OU=$postResults[0],OU=$name," . $basedn, $registry);
        }
        $_SESSION["msg"] = "Irasas pridetas sekmingai!";
        header("Location: view.php?name=$name");
    } else {
        $_SESSION["msg"] = "Klaida! Jus neturite tam teisiu.";
        header("Location: add.php?name=$name");
    }
    ldap_close($ds);
?>