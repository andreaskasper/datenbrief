<?php

$_ENV["basepath"] = dirname(dirname(dirname(dirname(__FILE__))));
$_ENV["datenbrief"]["version"] = "0.0.1 alpha";

/*
 * Mit dieser Funktion werden Klassen anhand ihres Namens automatisch geladen. Das Ergebnis spiegelt den Erfolg der Ausführung
 * @param string $class_name Name der Klasse, die geladen werden muss
 * @return boolean
 */
spl_autoload_register(function($class_name) {
	$prio = array();
	if (substr($class_name,0,4) == "API_") {
		require_once($_ENV["basepath"]."/app/api/0.1/classes/".substr($class_name,4,999).".php");
		return true;
	}
	
	$prio[] = $_ENV["basepath"]."/app/code/helper/default/".$class_name.".php";
	$prio[] = $_ENV["basepath"]."/app/code/classes/class.".$class_name.".php";
	

	foreach ($prio as $file) {
		if (file_exists($file)) {
			require($file);
			return true;
		}
	}
	if (isset($_GET["debug"])) throw new Exception("Klasse ".$class_name." kann nicht gefunden werden!");
	return false;
});

srand();
//SiteConfig::load(0);
Session::init();

$config = SiteConfig::get(0);
if (isset($config["baseurlpath"])) $_ENV["baseurlpath"] = $config["baseurlpath"]; else $_ENV["baseurlpath"] = substr($_SERVER["SCRIPT_NAME"],0,-10);
if (isset($config["baseurl"])) $_ENV["baseurl"] = $config["baseurl"]; else $_ENV["baseurl"] = $_SERVER["REQUEST_SCHEME"]."://".$_SERVER["HTTP_HOST"].$_ENV["baseurlpath"];

//Bugvermeidung
$_REQUEST = array_merge($_GET, $_POST);

i18n::init(isset($_GET["_lang"]) ? $_GET["_lang"] : (isset($config["language"]) ? $config["language"] : "de_DE"));


function get_path($file) {
	return str_replace("//", "/", $_ENV["baseurlpath"].$file);
}

function html($txt) {
	return htmlentities($txt, 3, "UTF-8");
}