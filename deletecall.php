<?php
	
	include('./classes/DB.php');
	// not the safest way of doing this. Better to use a post request to add 'some' security
	DB::query('DELETE FROM messages WHERE id=:dataid', array(':dataid'=>$_GET['id']));

	
?>