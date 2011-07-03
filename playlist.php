<?php

require("common.php");

/*
if ( !isset($_SESSION['username']) || !isset($_SESSION['password']) ){
	header("Location: index.php");
}
*/

$socket = init_socket();
if( $socket == false ){
	die("Can't init connection.");
}

// dummy read to get rid of the 'banner'	
read_socket($socket);

write_socket($socket, "ls\r\n");
$response = read_socket($socket);
$response = explode("|", $response);

if($response[0] == "ok"){
	$playlistcount =  $response[1];
	
	if($playlistcount > 0){
		// FIXME: This currently doesn't work when $playlistcount > 1
		for($i=0; $i<$playlistcount; $i++){
			echo read_socket($socket);
		}
	} else {
		echo "No songs queued";
	}
}

socket_close($socket);

?>
