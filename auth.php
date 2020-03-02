<?php
function authenticate($user) {
	if($user == "admin"){
		return true;
	}
	return false;
}

function authenticate_admin($user) {
	if($user == "admin"){
		return true;
	}
	return false;
}
?>