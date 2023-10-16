<?php
include('classes/DB.php');
if (isset($_POST['createaccount'])) {
        $username = $_POST['username'];
        $password = $_POST['password'];
        $email = $_POST['email'];
		$role = $_POST['status'];
        if (!DB::query('SELECT username FROM users WHERE username=:username', array(':username'=>$username))) {
                if (strlen($username) >= 3 && strlen($username) <= 32) {
                        if (preg_match('/[a-zA-Z0-9_]+/', $username)) {
                            if (strlen($password) >= 6 && strlen($password) <= 60) {
                                if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
                                        DB::query('INSERT INTO users VALUES (\'\', :username, :password, :email, :role)', array(':username'=>$username, ':password'=>password_hash($password, PASSWORD_BCRYPT), ':email'=>$email, ':role'=>$role));
										$cstrong = True;
                                        $token = bin2hex(openssl_random_pseudo_bytes(64, $cstrong));
										DB::query('INSERT INTO login_tokens VALUES (\'\', :token, :user_id)', array(':token'=>sha1($token), ':user_id'=>$user_id));
										setcookie("SNID", $token, time() + 60 * 60 * 24 * 7, '/', NULL, NULL, TRUE);
										setcookie("SNID_", '1', time() + 60 * 60 * 24 * 3, '/', NULL, NULL, TRUE);
										
										
									switch($role) {
										case "officer":
											header("Location: http://localhost/cad/dispatch.php");
											break;
										case "opp":
											header("Location: http://localhost/cad/opp.php");
											break;
										case "fire":
											header("Location: http://localhost/cad/fire.php");
											break;
										case "civilian":
											header("Location: http://localhost/cad/civilian.php");
											break;
										default:
											echo "something is wrong";
									}
										
                                } else {
                                        echo 'Invalid email!';
                                }
                        } else {
                                echo 'Invalid password!';
                        }
                        } else {
                                echo 'Invalid username';
                        }
                } else {
                        echo 'Invalid username';
                }
        } else {
                echo 'User already exists!';
        }
}
?>

<h1>Register</h1>
<form action="create-account.php" method="post">
<input type="text" name="username" value="" placeholder="Username ..."><p />
<input type="password" name="password" value="" placeholder="Password ..."><p />
<input type="email" name="email" value="" placeholder="someone@somesite.com"><p />
<select name="status">
    <option value="officer">Dispatch</option>
    <option value="opp">OPP</option>
	<option value="fire">Fire Department</option>
	<option value="civilian">Civilian</option>
</select>
<input type="submit" name="createaccount" value="Create Account">
</form>