<?php
    include('config.php');
    include('connect-db.php');
    session_start();
    $ds = connectToAD($server);
    if ($ds) {
        $number = count($_POST["name"]);  
        if($number > 0)  
        {
            // bind with appropriate dn to give update access
            $result = bindAD($ds, $userdn, $userpw);
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
                $_SESSION["msg"] = "Aplankalas sekmingai sukurtas!";
                echo "Aplankalas sekmingai sukurtas!";
            }
            else {
                $_SESSION["msg"] = "Jus neturite tam teisiu.";
                echo "Jus neturite tam teisiu!";
            }
            ldap_close($ds);
            //header("Location: all.php");
        } else {  
            echo "Please Fill in fields";  
        }  
    } else {
        echo "Unable to connect to LDAP server";
    }
?>
   