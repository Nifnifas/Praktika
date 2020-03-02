<?php
    $ds = ldap_connect("WIN-7HITSPV9HGH.mycompany.com") or die ("Could not connect to LDAP server");  // assuming the LDAP server is on this host
    ldap_set_option($ds, LDAP_OPT_PROTOCOL_VERSION, 3);
    ldap_set_option($ds, LDAP_OPT_REFERRALS, 0);
    $username = 'CN=Administrator,CN=Users,DC=mycompany,DC=com';
    $psw = 'Admin123';
    if ($ds) {
        $number = count($_POST["name"]);  
        if($number > 0)  
        {
            // bind with appropriate dn to give update access
            $r = ldap_bind($ds, $username, $psw);
            if($r){
                $description = "";
                for($i=0; $i<$number; $i++)  
                {  
                    if(trim($_POST["name"][$i] != ''))  
                    {
                        $description .= htmlspecialchars($_POST["name"][$i]) . ";";
                    }  
                }
                $userid = htmlspecialchars($_POST["ou"]);
                $registry["ou"] = $userid;
                $registry["description"] = $description;
                $registry["objectClass"] = "organizationalUnit";
                $result = ldap_add($ds, "ou=$userid,OU=Registrai,OU=TableSet,DC=mycompany,DC=com", $registry);  
                echo "Data Inserted Successfully";
            }
            else{
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
   