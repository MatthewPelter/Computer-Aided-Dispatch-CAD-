<?php
include('classes/DB.php');
include('classes/Login.php');

if (Login::isLoggedIn()) {
        $userid = Login::isLoggedIn();
		die('Already Logged In');
		echo "<a href='cadindex.php'>Main Hub</a>";
}


if (isset($_POST['login'])) {
        $username = $_POST['username'];
        $password = $_POST['password'];
		$role = DB::query('SELECT role FROM users WHERE username=:username', array(':username'=>$username));
        if (DB::query('SELECT username FROM users WHERE username=:username', array(':username'=>$username))) {
                if (password_verify($password, DB::query('SELECT password FROM users WHERE username=:username', array(':username'=>$username))[0]['password'])) {
                        $cstrong = True;
                        $token = bin2hex(openssl_random_pseudo_bytes(64, $cstrong));
                        $user_id = DB::query('SELECT id FROM users WHERE username=:username', array(':username'=>$username))[0]['id'];
                        DB::query('INSERT INTO login_tokens VALUES (\'\', :token, :user_id)', array(':token'=>sha1($token), ':user_id'=>$user_id));
                        setcookie("SNID", $token, time() + 60 * 60 * 24 * 7, '/', NULL, NULL, TRUE);
                        setcookie("SNID_", '1', time() + 60 * 60 * 24 * 3, '/', NULL, NULL, TRUE);
						
						if ($role == "officer"){
							header("Location: http://localhost/cad/dispatch.php");
						} elseif ($role == "opp") {
							header("Location: http://localhost/cad/opp.php");
						} elseif ($role == "fire") {
							header("Location: http://localhost/cad/fire.php");
						} elseif ($role == "civilian") {
							header("Location: http://localhost/cad/civilian.php");
						}
                } else {
                        echo 'Incorrect Password!';
                }
        } else {
                echo 'User not registered!';
        }
}
?>
<h1>Login to your account</h1>
<form action="login.php" method="post">
<input type="text" name="username" value="" placeholder="Username ..."><p />
<input type="password" name="password" value="" placeholder="Password ..."><p />
<select name="status">
    <option value="officer">Dispatch</option>
    <option value="opp">OPP</option>
	<option value="fire">Fire Department</option>
	<option value="civilian">Civilian</option>
</select>
<input type="submit" name="login" value="Login">
</form>