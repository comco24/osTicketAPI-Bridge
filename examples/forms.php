<?php

	require_once(DOCROOT. "tikety/app/private/api-conf.php");
	require_once(DOCROOT. "tikety/app/class/x_api.php");

	$api = new x_api(X_API_S_KEY, X_API_APP_ID, X_API_URL);

	// getOSTData() vracia jSON objekt s popisom vsetkych poli formulara
	$topic_data = $api->getOSTData($params['id']);
	$data = json_decode($topic_data);
	if ($data->result == 0) return '';
	
	// generateForm() spracuje jSON objekt a vygeneruje formular
	$temp = $api->generateForm($topic_data, FRM_BOOTSTRAP_4, $_SERVER['REQUEST_URI']);

	return $temp;
