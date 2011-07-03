<?php

error_reporting(E_ALL & ~E_NOTICE);

define("UPLOAD_DIR", "uploads/");
define("HGD_SERVER", "127.0.0.1");
define("HGD_PORT", 6633);

session_start();

function init_socket(){

	$socket = @socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
	$conn = @socket_connect($socket, HGD_SERVER, HGD_PORT);

	if ( ($socket === false) || ($conn === false) ) {
		return false;
	} else {
		return $socket;
	}

}


function read_socket($socket){

	$response = "";
	while ($recv = socket_read($socket, 1000)) {
		$response .= $recv;
		if (strpos($recv, "\n") !== false) break;
	}

	return $response;

}


function write_socket($socket, $data){

	socket_write($socket, $data, strlen($data));

}


function hgd_login($socket, $username, $password){

	$logincmd = "user|" . $username . "|" . $password . "\r\n";
	write_socket($socket, $logincmd);

	$response = read_socket($socket);
	if (stripos($response, "ok") === false){
		return false;
	} else {
		return true;
	}

}


function hgd_play($socket, $filename, $username, $password){

	if( !hgd_login($socket, $username, $password) ){
		return false;
	}

	$filesize = filesize($filename);
	$filenamex = explode("/", $filename);

	$filecmd = "q|" . $filenamex[(count($filenamex)-1)] . "|" . $filesize . "\r\n";
	write_socket($socket, $filecmd);

	$response = read_socket($socket);
	if (stripos($response, "ok") === false){
		return false;
	} else {
		// actually send file now
		$total_sent = 0;
		$handle = fopen($filename, "r");
		while ($total_sent < $filesize){
			write_socket($socket, fread($handle, 1000));
			$total_sent += 1000;
		}
		fclose($handle);
	}
	unset($reponse);

	$response = read_socket($socket);
	if(stripos($response, "ok") === false){
		return false;
	} else {
		return true;
	}
	
}


function hgd_version($socket){

	$response = read_socket($socket);
	$response = explode("|", $response);
	return $response[1];

}


function clean_input($str){

	// FIXME: Actually *clean* input
	return $str;

}

?>

