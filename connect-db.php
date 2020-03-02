<?php
    function connectToAD(){
        $conn = ldap_connect("WIN-7HITSPV9HGH.mycompany.com") or die ("Could not connect to LDAP server");
        return $conn;
    }

    function bindAD($conn){
        if($conn){
            ldap_set_option($conn, LDAP_OPT_PROTOCOL_VERSION, 3);
            ldap_set_option($conn, LDAP_OPT_REFERRALS, 0);
            $userCreds = 'CN=Administrator,CN=Users,DC=mycompany,DC=com';
            $psw = 'Admin123';
            $result = ldap_bind($conn, $userCreds, $psw);
            return $result;
        }
        return $conn;
    }

    function recursive_ldap_delete($ds, $dn, $recursive){
        if($recursive == false){
            return(ldap_delete($ds,$dn));
        }else{
            //searching for sub entries
            $sr=ldap_list($ds,$dn,"ObjectClass=*",array(""));
            $info = ldap_get_entries($ds, $sr);
            for($i=0;$i<$info['count'];$i++){
                //deleting recursively sub entries
                $result=recursive_ldap_delete($ds,$info[$i]['dn'],$recursive);
                if(!$result){
                    //return result code, if delete fails
                    return($result);
                }
            }
            return(ldap_delete($ds,$dn));
        }
    }

    function autoIncrement($ds, $dn){
        $autoIncValue = 0;
        $filterAll = array("ou", "description");
        $source = ldap_list($ds, $dn, "ou=*", $filterAll);
        //$source=ldap_list($ds,$dn,"ObjectClass=*",array(""));
        $info = ldap_get_entries($ds, $source);
        if($info["count"] > 0){
            for($i = 0; $i < $info["count"]; $i++){
                if($info[$i]["ou"][0]){
                    $autoIncValue = $info[$i]["ou"][0];
                }
            }
            $autoIncValue++;
            return $autoIncValue;
        }
        return $autoIncValue;
    }
?>