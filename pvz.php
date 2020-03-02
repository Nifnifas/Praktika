<?php
 
//LDAP Bind paramters, need to be a normal AD User account.
	// active airectory server
	$ldap_host = "localhost";
 
	// active directory DN (base location of ldap search)
	$ldap_dn = "OU=Registrai, OU=TableSet, DC=mycompany,DC=local";
 
	// domain, for purposes of constructing $user
	$ldap_usr_dom = '@mycompany';
	$ldaprdn = 'iff65';
	$ldappass = 'Studentai123';
	// connect to active directory
	$ldap = ldap_connect($ldap_host);
 
	// configure ldap params
	ldap_set_option($ldap,LDAP_OPT_PROTOCOL_VERSION,3);
	ldap_set_option($ldap,LDAP_OPT_REFERRALS,0);
 
	// verify user and password
	if($bind = @ldap_bind($ldap, $ldaprdn.$ldap_usr_dom, $ldappass)) {
 
		//Get standard users and contacts
		$search_filter = "(&(objectCategory=person)(objectClass=user)(!(userAccountControl:1.2.840.113556.1.4.803:=2)))";
		
		//Connect to LDAP
		$result = ldap_search($ldap, $ldap_dn, $search_filter);
	
    if (FALSE !== $result){
		$entries = ldap_get_entries($ldap, $result);
		
		// Uncomment the below if you want to write all entries to debug somethingthing 
		//var_dump($entries);
		
		//Create a table to display the output 
		echo '<h2>AD Rezultatai</h2></br>';
		echo '<table border = "1"><tr bgcolor="#cccccc"><td>Pavardė</td><td>Vardas</td><td>Grupės</td><td>Telefono Nr.</td><td>Telefono Nr.</td><td>Elektroninis paštass</td></tr>';
		
		foreach ($entries as $key => $row) {
			$sn[$key]  = $row['sn'];
			$givenname[$key] = $row['givenname'];
		}
		array_multisort($sn, SORT_ASC, $givenname, SORT_ASC, $entries);
		//For each account returned by the search
		for ($x=0; $x<$entries['count']; $x++){
			
			//
			//Retrieve values from Active Directory
			//
			
			//Windows Usernaame
			
			$hidden = array("disable", "useris", "testinis", "A.Sistema", "http-srv69-intranet", "O.Vartotojas", "P.Vartotojas", "H.Teisėjas");
		if (!in_array($entries[$x]['samaccountname'][0], $hidden)){
			/*if (!empty($entries[$x]['memberof'][0])) {
				foreach($entries[$x]['memberof'] as $grps) {
					if(strpos($grps, "Teisėjai visi")) {*/
			$LDAP_samaccountname = "";
			if (!empty($entries[$x]['samaccountname'][0])) {
				$LDAP_samaccountname = $entries[$x]['samaccountname'][0];
				if ($LDAP_samaccountname == "NULL"){
					$LDAP_samaccountname= "";
				}
			} else {
				//#There is no samaccountname s0 assume this is an AD contact record so generate a unique username
				
				$LDAP_uSNCreated = $entries[$x]['usncreated'][0];
				$LDAP_samaccountname= "CONTACT_" . $LDAP_uSNCreated;
			}
			
			//Last Name
			$LDAP_LastName = "";
			
			if (!empty($entries[$x]['sn'][0])) {
				$LDAP_LastName = $entries[$x]['sn'][0];
				if ($LDAP_LastName == "NULL"){
					$LDAP_LastName = "";
				}
			}
			
			//First Name
			$LDAP_FirstName = "";
			
			if (!empty($entries[$x]['givenname'][0])) {
				$LDAP_FirstName = $entries[$x]['givenname'][0];
				if ($LDAP_FirstName == "NULL"){
					$LDAP_FirstName = "";
				}
			}
		}
			/*	}
			}
		}*/
		} //END for loop
	} //END FALSE !== $result
	
	ldap_unbind($ldap); // Clean up after ourselves.
	echo("</table>"); //close the table
 
} //END ldap_bind

?>