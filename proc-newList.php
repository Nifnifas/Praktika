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
                $error = false;
                for($i=0; $i<$number; $i++) {  
                    if(trim($_POST["name"][$i] != '')) {
                        $description .= htmlspecialchars($_POST["name"][$i]) . ";";
                    } 
                    if(trim($_POST["formType"][$i] != '')) {
                        $inputType .= htmlspecialchars($_POST["formType"][$i]) . ";";
                    }
                    if(trim($_POST["name"][$i] == '')) {
                        $error = true;
                    }
                }
                if($error){
                    echo "Klaida! Blogai įvesti duomenys.";
                } else {
                    // default field, auto-generated
                    $description .= "Iraso data;";
                    $inputType .= "Date;";
                    $userid = htmlspecialchars($_POST["ou"]);
                    if($userid == ''){
                        $error = true;
                        echo "Klaida! Blogai įvesti duomenys.";
                    } else {
                        $registry["ou"] = $userid;
                        $registry["description"] = $description;
                        $registry["street"] = $inputType;
                        $registry["objectClass"] = "organizationalUnit";
                        $result = ldap_add($ds, "ou=$userid," . $basedn, $registry);  
                        $_SESSION["msg"] = "Aplankalas sėkmingai sukurtas!";
                        echo "Aplankalas sėkmingai sukurtas!";
                    }
                }
            }
            else {
                $_SESSION["msg"] = "Jūs neturite tam teisių.";
                echo "Jūs neturite tam teisių!";
            }
            ldap_close($ds);
            //header("Location: all.php");
        } else {  
            echo "Užpildykite visus laukelius.";  
        }  
    } else {
        echo "Neįmanoma prisijungti prie serverio.";
    }
?>