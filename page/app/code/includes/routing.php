<?phpif (!isset($_SERVER["REQUEST_URI"])) die("Keine REQUEST-URI vom Apache erhalten!");if (!isset($_SERVER["SCRIPT_NAME"])) die("Keine SCRIPT_NAME vom Apache erhalten!");$_SERVER["REQUEST_URI2"] = substr($_SERVER["REQUEST_URI"],strlen($_SERVER["SCRIPT_NAME"])-10);$p = strpos($_SERVER["REQUEST_URI2"],"?");if (!$p) $_SERVER["REQUEST_URIpure"] = $_SERVER["REQUEST_URI2"]; else $_SERVER["REQUEST_URIpure"] = substr($_SERVER["REQUEST_URI2"],0, $p);switch ($_SERVER["REQUEST_URIpure"]) {	case "/robots.txt":		PageEngine::html("txt_robots");		exit(1);	case "/":	case "/index.php":	case "/index.html":		PageEngine::html("page_index");		exit(1);	case "/suche":	case "/suche.html":		PageEngine::html("page_suche");		exit(1);	case "/anfrage.html":		PageEngine::html("page_anfrage");		exit(1);	case "/cron":		include($_ENV["basepath"]."/app/code/crons/cron.php");		exit(1);	case "/admin":		if (!MyUser::hasAdminRight()) PageEngine::html("page_403");		PageEngine::runController("settings");		PageEngine::html("admin/page_settings"); exit(1);}/*** jetzt kommen die langsamen pregs ***/if (preg_match("@^/admin/@", $_SERVER["REQUEST_URIpure"], $treffer)) {	if (!MyUser::hasAdminRight()) PageEngine::html("page_403");	/*if ($treffer[2]."" == "") $treffer[2] = "0.1";	if (isset($treffer[6])) $_ENV["API"]["format"] = $treffer[6];	elseif (!isset($_ENV["API"]["format"]) AND isset($_REQUEST["format"])) $_ENV["API"]["format"] = $_REQUEST["format"];	$_ENV["API"]["namespace"] = $treffer[3];	$_ENV["API"]["method"] = $treffer[4];	require($_ENV["basepath"]."/app/api/".$treffer[2]."/main.php");*/	exit(1);}if (preg_match("@^/api(/([0-9\.]+))?/([a-z]+)/([a-z]+)(\.(json|xml|plain|txt|php|jsonac|html|json-in-script|successcode))?$@", $_SERVER["REQUEST_URIpure"], $treffer)) {	if ($treffer[2]."" == "") $treffer[2] = "0.1";	if (isset($treffer[6])) $_ENV["API"]["format"] = $treffer[6];	elseif (!isset($_ENV["API"]["format"]) AND isset($_REQUEST["format"])) $_ENV["API"]["format"] = $_REQUEST["format"];	$_ENV["API"]["namespace"] = $treffer[3];	$_ENV["API"]["method"] = $treffer[4];	require($_ENV["basepath"]."/app/api/".$treffer[2]."/main.php");	exit(1);}if (preg_match("@^/firma/([0-9]+)(/.)?@", $_SERVER["REQUEST_URIpure"], $treffer)) {	PageEngine::html("page_firma", array("id" => $treffer[1])); exit(1);}if (preg_match("@^/anfrage/([0-9]+)(/.)?@", $_SERVER["REQUEST_URIpure"], $treffer)) {	PageEngine::html("page_anfrage", array("id" => $treffer[1])); exit(1);}Observer::Raise("Error_404",array("path" => $_SERVER["REQUEST_URIpure"]));PageEngine::html("page_404");exit(1);