<?php
include('./classes/DB.php');
include('./classes/Login.php');
if (!Login::isLoggedIn()) {
        die("Not logged in.");
}
if (isset($_POST['confirm'])) {
		if (isset($_COOKIE['SNID'])) {
				DB::query('DELETE FROM login_tokens WHERE token=:token', array(':token'=>sha1($_COOKIE['SNID'])));
		}
		setcookie('SNID', '1', time()-3600);
		setcookie('SNID_', '1', time()-3600);
		
		header("Location: http://localhost/cad/login.php");
}
?>
<h1>Logout of your Account?</h1>
<p>Are you sure you'd like to logout?</p>
<form action="logout.php" method="post">
        <input type="submit" name="confirm" value="Confirm">
</form>