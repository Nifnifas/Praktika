<?php
    $ds = ldap_connect("iff65.mycompany.local") or die ("Could not connect to LDAP server");  // assuming the LDAP server is on this host
    ldap_set_option($ds, LDAP_OPT_PROTOCOL_VERSION, 3);
    ldap_set_option($ds, LDAP_OPT_REFERRALS, 0);
    $username = 'CN=iff65,CN=Users,DC=mycompany,DC=local';
    $psw = 'Studentai123';
    if ($ds) {
        // bind with appropriate dn to give update access
        $r = ldap_bind($ds, $username, $psw);
        if($r){
            // prepare data
            $info["cn"] = "John";
            $info["objectClass"] = "inetOrgPerson";
            $r = ldap_add($ds, 'CN=John,OU=Registrai,OU=TableSet,DC=mycompany,DC=local', $info);
            echo "TRUE";
        }
        else{
            echo "FALSE";
        }
        ldap_close($ds);
    } else {
        echo "Unable to connect to LDAP server";
    }
?>