<?php
function authenticate($user) {
	if(empty($user)) return false;
 
	// active airectory server
	$ldap_host = "dcnta01.teismai.local";
 
	// active directory DN (base location of ldap search)
	$ldap_dn = "OU=Kauno apylinkes teismas,DC=teismai,DC=local";
 
	// active directory user group name
	$ldap_user_group = "Grupes";
 
	// active directory manager group name
	$ldap_manager_group = "Registrų administratoriai";
	// domain, for purposes of constructing $user
	$ldap_usr_dom = '@teismai';
	$ldaprdn = 'username';
	$ldappass = 'username2222';
	// connect to active directory
	$ldap = ldap_connect($ldap_host);
 
	// configure ldap params
	ldap_set_option($ldap,LDAP_OPT_PROTOCOL_VERSION,3);
	ldap_set_option($ldap,LDAP_OPT_REFERRALS,0);
 
	// verify user and password
	if($bind = @ldap_bind($ldap, $ldaprdn.$ldap_usr_dom, $ldappass)) {
		// valid
		// check presence in groups
		$filter = "(sAMAccountName=".$user.")";
		$attr = array("memberof");
		$result = ldap_search($ldap, $ldap_dn, $filter, $attr) or exit("Unable to search LDAP server");
		$entries = ldap_get_entries($ldap, $result);
		ldap_unbind($ldap);
 
		// check groups
		foreach($entries[0]['memberof'] as $grps) {
			// is manager, break loop
			//echo '<td>' . $grps . '</td>';
			if(strpos($grps, $ldap_manager_group)) { $access = 2; break; }
			// is user
			//if(strpos($grps, $ldap_user_group)) $access = 1;
		}
 
		if($access != 0) {
			// establish session variables
			$_SESSION['user'] = $user;
			$_SESSION['access'] = $access;
			return true;
		} else {
			// user has no rights
			return false;
		}
 
	} else {
		// invalid name or password
		return false;
	}
}
function authenticate_admin($user) {
	if(empty($user)) return false;
 
	// active airectory server
	$ldap_host = "dcnta01.teismai.local";
 
	// active directory DN (base location of ldap search)
	$ldap_dn = "OU=Kauno apylinkes teismas,DC=teismai,DC=local";
 
	// active directory user group name
	$ldap_user_group = "Vartotojai";
 
	// active directory manager group name
	$ldap_manager_group = "Registrų administratoriai";
	$ldap_manager_group2 = "Administracija";
	// domain, for purposes of constructing $user
	$ldap_usr_dom = '@teismai';
	$ldaprdn = 'username';
	$ldappass = 'username2222';
	// connect to active directory
	$ldap = ldap_connect($ldap_host);
 
	// configure ldap params
	ldap_set_option($ldap,LDAP_OPT_PROTOCOL_VERSION,3);
	ldap_set_option($ldap,LDAP_OPT_REFERRALS,0);
 
	// verify user and password
	if($bind = @ldap_bind($ldap, $ldaprdn.$ldap_usr_dom, $ldappass)) {
		// valid
		// check presence in groups
		$filter = "(sAMAccountName=".$user.")";
		$attr = array("memberof");
		$result = ldap_search($ldap, $ldap_dn, $filter, $attr) or exit("Unable to search LDAP server");
		$entries = ldap_get_entries($ldap, $result);
		ldap_unbind($ldap);
 
		// check groups
		foreach($entries[0]['memberof'] as $grps) {
			// is manager, break loop
			//echo '<td>' . $grps . '</td>';
			if(strpos($grps, $ldap_manager_group2)) { $access = 2; break; }
			if(strpos($grps, $ldap_manager_group)) { $access = 2; break; }
			//if(strpos($grps, $ldap_manager_group1)) { $access = 2; break; }
			
			// is user
			//if(strpos($grps, $ldap_user_group)) $access = 1;
		}
 
		if($access != 0) {
			// establish session variables
			$_SESSION['user'] = $user;
			$_SESSION['access'] = $access;
			return true;
		} else {
			// user has no rights
			return false;
		}
 
	} else {
		// invalid name or password
		return false;
	}
}
?>