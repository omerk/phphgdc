<?php

require("common.php");
require("header.php");

if ( isset($_POST['username']) && isset($_POST['password']) ){
	$username = clean_input( $_POST['username'] );
	$password = clean_input( $_POST['password'] );

	$socket = init_socket();
	if( $socket == false ){
		die("Can't init connection.");
	}

	// dummy read to get rid of the 'banner'	
	read_socket($socket);

	if( hgd_login($socket, $username, $password) ){
		$_SESSION['username'] = $username;
		$_SESSION['password'] = $password;
		header("Location: index.php");
	} else {
		echo "Login failed.";
	}

} elseif ( isset($_SESSION['username']) && isset($_SESSION['password']) ) {
?>

	<div id="uploadform">
		<p>Logged in as <?php echo $_SESSION['username']; ?> | <a href="logout.php">(Logout)</a></p>

		<form method="post" action="process.php" enctype="multipart/form-data">
		<p>File:<input name="uploadfile" type="file" size="50" /><br />
		<input type="submit" name="Submit" value="Upload" />
		</p>
	</form>


<?php } else { ?>

	<div id="loginform">
		<form method="post" action="index.php">
			<p>Login to HGD</p>
		
			<p>
			<img src="gfx/user.png" />
			<input name="username" type="text" id="login_username" /><br />
			<img src="gfx/key.png" />
			<input name="password" type="password" id="login_password" /><br />
			<input type="submit" name="Submit" value="Login" id="login_submit" />
			</p>
		</form>
	</div>

<?php
}

require("footer.php");

?>

