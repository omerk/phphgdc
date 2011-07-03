<?php

require("common.php");

if ( !isset($_SESSION['username']) || !isset($_SESSION['password']) ){
	header("Location: index.php");
}

if ( is_uploaded_file($_FILES['uploadfile']['tmp_name']) ){ 
	$filename = UPLOAD_DIR . $_FILES['uploadfile']['name'];

	if ( move_uploaded_file($_FILES['uploadfile']['tmp_name'], $filename) ){
		echo "<p>File uploaded.</p>";
		
		$socket = init_socket();
		if( $socket == false ){
			die("Can't init connection.");
		}

		// dummy read to get rid of the 'banner'	
		read_socket($socket);

		if( hgd_play($socket, $filename, $_SESSION['username'], $_SESSION['password']) ){
			echo "Track queued. <a href=\"index.php\">Submit another one?</a>";
		} else {
			echo "There was a problem queueing the track, <a href=\"index.php\">Try again?</a>";
		}

		socket_close($socket);

	} else {
		die("Error uploading file."); 
	} 
} 

?>

