<?php

class API_search {
	
	public static function autocompletefirmen($data){
		if (!isset($data["q"])) throw new APIException("Parameterfehler (q,term)",50);
		$db = new SQL(0);
		$rows = $db->cmdrows(0, 'SELECT `name` FROM `dienst_info` WHERE `name` LIKE "%{0}%" LIMIT 0,10', array($data["q"]));
		$out = array();
		foreach ($rows as $row){
			$out[] = $row["name"];
		}
		return $out;
	}


}