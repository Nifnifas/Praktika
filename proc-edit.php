<?php
//reikia pirma patikrint ar kazkas postinama is viso.
    include('connect-db.php');
    $ds = connectToAD();
    $result = bindAD($ds);

    if ($result) {
        $columnCount = $_POST["count"];
        $name = $_POST["name"];
        $postResults = array();
        for ($i = 0; $i < $columnCount; $i++){
            $postResults[$i] = $_POST["$i"];
        }

        $basedn = "OU=$postResults[0],OU=$name,OU=Registrai,OU=TableSet,DC=mycompany,DC=com";
        $filter = array("ou", "description");
        $sr = ldap_list($ds, $basedn, "ou=*", $filter);
        $info = ldap_get_entries($ds, $sr);

        for($i = 0; $i < $columnCount; $i++){
            $title = $info[$i]["ou"][0];
            //$registry["ou"] = $title;
            $registry["description"] = $_POST[$i];
            //$registry["objectClass"] = "organizationalUnit";
            ldap_mod_replace($ds, "OU=$title,OU=$postResults[0],OU=$name,OU=Registrai,OU=TableSet,DC=mycompany,DC=com", $registry);
        }
        echo "Success";
    } else {
        echo "You do not have rights to add new Registry.";
    }
    ldap_close($ds);
?>