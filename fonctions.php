<?php
// Tronquer une chaine de caractère
function trunkString($str, $max) {

	if(strlen($str) > $max)
	{
		// On la raccourci
		$str = substr($str, 0, $max);
		$last_space = strrpos($str, " ");
		
		// Et on ajouter les ... à la fin de la chaine
		$str = substr($str, 0, $last_space)."...";
		echo '<p>'. $str .'</p>';
	}

	else
	{
		echo '<p>'. $str .'</p>';
	}
}
?>