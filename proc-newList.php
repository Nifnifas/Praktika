<?php
    include('config.php');
    include('connect-db.php');
    $ds = connectToAD($server);
    if ($ds) {
        $number = count($_POST["name"]);  
        if($number > 0)  
        {
            // bind with appropriate dn to give update access
            $result = bindAD($ds);
            if($result) {
                // default field, auto-generated
                $description = "ID;";
                $inputType = "Text;";
                for($i=0; $i<$number; $i++) {  
                    if(trim($_POST["name"][$i] != '')) {
                        $description .= htmlspecialchars($_POST["name"][$i]) . ";";
                    }
                    if(trim($_POST["formType"][$i] != '')) {
                        $inputType .= htmlspecialchars($_POST["formType"][$i]) . ";";
                    }  
                }
                // default field, auto-generated
                $description .= "Iraso data;";
                $inputType .= "Date;";
                $userid = htmlspecialchars($_POST["ou"]);
                $registry["ou"] = $userid;
                $registry["description"] = $description;
                $registry["street"] = $inputType;
                $registry["objectClass"] = "organizationalUnit";
                $result = ldap_add($ds, "ou=$userid," . $basedn, $registry);  
                echo "Aplankalas sekmingai sukurtas!";
            }
            else {
                echo "You do not have rights to add new Registry.";
            }
            ldap_close($ds);
        } else {  
            echo "Please Fill in fields";  
        }  
    } else {
        echo "Unable to connect to LDAP server";
    }
?>
   