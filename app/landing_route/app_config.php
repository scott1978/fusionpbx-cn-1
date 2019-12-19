<?php

	//application details
		$apps[$x]['name'] = "Landing Route";
		$apps[$x]['uuid'] = "726230a9-0837-4966-ab6e-ec938cf6f8ff";
		$apps[$x]['category'] = "Switch";
		$apps[$x]['subcategory'] = "";
		$apps[$x]['version'] = "1.0";
		$apps[$x]['license'] = "Mozilla Public License 1.1";
		$apps[$x]['url'] = "http://www.fusionpbx.com";
		$apps[$x]['description']['en-us'] = "The landing route will route by local.";
		$apps[$x]['description']['ar-eg'] = "The landing route will route by local.";
		$apps[$x]['description']['de-at'] = "The landing route will route by local.";
		$apps[$x]['description']['de-ch'] = "The landing route will route by local.";
		$apps[$x]['description']['de-de'] = "The landing route will route by local.";
		$apps[$x]['description']['es-cl'] = "The landing route will route by local.";
		$apps[$x]['description']['es-mx'] = "The landing route will route by local.";
		$apps[$x]['description']['fr-ca'] = "The landing route will route by local.";
		$apps[$x]['description']['fr-fr'] = "The landing route will route by local.";
		$apps[$x]['description']['he-il'] = "The landing route will route by local.";
		$apps[$x]['description']['it-it'] = "The landing route will route by local.";
		$apps[$x]['description']['nl-nl'] = "The landing route will route by local.";
		$apps[$x]['description']['pl-pl'] = "The landing route will route by local.";
		$apps[$x]['description']['pt-br'] = "The landing route will route by local.";
		$apps[$x]['description']['pt-pt'] = "The landing route will route by local.";
		$apps[$x]['description']['ro-ro'] = "The landing route will route by local.";
		$apps[$x]['description']['ru-ru'] = "The landing route will route by local.";
		$apps[$x]['description']['sv-se'] = "The landing route will route by local.";
		$apps[$x]['description']['uk-ua'] = "The landing route will route by local.";

	//permission details
		$y=0;
		$apps[$x]['permissions'][$y]['name'] = "landing_route_view";
		$apps[$x]['permissions'][$y]['menu']['uuid'] = "3876ac58-7b37-49d2-8c34-9cf443b1b613";
		$apps[$x]['permissions'][$y]['groups'][] = "superadmin";
		$apps[$x]['permissions'][$y]['groups'][] = "admin";
		$y++;
		$apps[$x]['permissions'][$y]['name'] = "landing_route_add";
		$apps[$x]['permissions'][$y]['groups'][] = "superadmin";
		$apps[$x]['permissions'][$y]['groups'][] = "admin";
		$y++;
		$apps[$x]['permissions'][$y]['name'] = "landing_route_advanced";
		$apps[$x]['permissions'][$y]['groups'][] = "superadmin";
		$y++;
		$apps[$x]['permissions'][$y]['name'] = "landing_route_edit";
		$apps[$x]['permissions'][$y]['groups'][] = "superadmin";
		$y++;
		$apps[$x]['permissions'][$y]['name'] = "landing_route_delete";
		$apps[$x]['permissions'][$y]['groups'][] = "superadmin";
		$apps[$x]['permissions'][$y]['groups'][] = "admin";
		$y++;
		$apps[$x]['permissions'][$y]['name'] = "landing_route_copy";
		$apps[$x]['permissions'][$y]['groups'][] = "superadmin";

?>