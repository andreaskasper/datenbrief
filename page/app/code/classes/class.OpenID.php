<?php//require_once(dirname(__FILE__)."/OpenID/OpenID.php");class OpenID {	public function OpenID() {		$path_extra = dirname(__FILE__)."/OpenID2";		$path = ini_get('include_path');		$path = $path_extra . PATH_SEPARATOR . $path;		ini_set('include_path', $path);			require_once(dirname(__FILE__)."/OpenID2/Auth/OpenID/Consumer.php");		require_once(dirname(__FILE__)."/OpenID2/Auth/OpenID/FileStore.php");		require_once(dirname(__FILE__)."/OpenID2/Auth/OpenID/SReg.php");		require_once(dirname(__FILE__)."/OpenID2/Auth/OpenID/PAPE.php");			}		public function getConsumer() {		$store_path = $_ENV["basepath"]."/app/cache/openid_store";		if (!file_exists($store_path) &&!mkdir($store_path, true)) die("Verzeichnis kann nicht erstellt werden");		$store = new Auth_OpenID_FileStore($store_path);		$consumer = new Auth_OpenID_Consumer($store);		$response = $consumer->complete($_ENV["baseurl"]."/account/signin?action=login_yahoo");		if ($response->status == "failure" AND $response->endpoint."" == "") { //Authentifizieren			$auth_request = $consumer->begin("https://me.yahoo.com/");			if (!$auth_request) { die("Authentication error; not a valid OpenID.");    }			$sreg_request = Auth_OpenID_SRegRequest::build( array('nickname'), array('fullname', 'email'));			$auth_request->addExtension($sreg_request);			$redirect_url = $auth_request->redirectURL($ENV["baseurl"], $_ENV["baseurl"]."/account/signin?action=login_yahoo");			print_r($redirect_url);		}		return $consumer;	}}