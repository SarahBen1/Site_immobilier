<?php


	// ----- FONCTION AMELIORATION DEBUG ------

function debug($var){
	echo '<div style="background:grey; color:white; padding: 5 px;">';
	$trace = debug_backtrace(); // Retourne un array contenant les infos dur la ligne éxécutée
	$info = array_shift($trace); // Extrait la 1ere valeur d'un ARRAY

	echo 'Le debug a été demandé dans le fichier ' . $info['file'] . ' à la ligne ' . $info['line'] . '.<hr/>';

	echo '<pre>';
	print_r($var);
	echo '</pre>';

	echo '</div>';
}
?>