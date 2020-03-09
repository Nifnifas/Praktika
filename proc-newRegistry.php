<?php
    include('config.php');
    include('connect-db.php');
    $ds = connectToAD($server);
    $result = bindAD($ds);

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
        echo "Success";
    } else {
        echo "You do not have rights to add new Registry.";
    }
    ldap_close($ds);
?>