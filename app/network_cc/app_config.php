<?php

	//application details
		$apps[$x]['name'] = "Network CC";
		$apps[$x]['uuid'] = "f22192d5-f792-4e0d-b48f-d287ea5246c1";
		$apps[$x]['category'] = "Switch";
		$apps[$x]['subcategory'] = "";
		$apps[$x]['version'] = "1.0";
		$apps[$x]['license'] = "Mozilla Public License 1.1";
		$apps[$x]['url'] = "http://www.fusionpbx.com";
		$apps[$x]['description']['en-us'] = "Network cc";
		$apps[$x]['description']['ar-eg'] = "Network cc.";
		$apps[$x]['description']['de-at'] = "Network cc";
		$apps[$x]['description']['de-ch'] = "Network cc";
		$apps[$x]['description']['de-de'] = "Network cc";
		$apps[$x]['description']['es-cl'] = "Network cc";
		$apps[$x]['description']['es-mx'] = "Network cc";
		$apps[$x]['description']['fr-ca'] = "Network cc";
		$apps[$x]['description']['fr-fr'] = "Network cc";
		$apps[$x]['description']['he-il'] = "Network cc";
		$apps[$x]['description']['it-it'] = "Network cc";
		$apps[$x]['description']['nl-nl'] = "Network cc";
		$apps[$x]['description']['pl-pl'] = "Network cc";
		$apps[$x]['description']['pt-br'] = "Network cc";
		$apps[$x]['description']['pt-pt'] = "Network cc";
		$apps[$x]['description']['ro-ro'] = "Network cc";
		$apps[$x]['description']['ru-ru'] = "Network cc";
		$apps[$x]['description']['sv-se'] = "Network cc";
		$apps[$x]['description']['uk-ua'] = "Network cc";

	//permission details
		$y=0;
		$apps[$x]['permissions'][$y]['name'] = "network_cc_view";
		$apps[$x]['permissions'][$y]['menu']['uuid'] = "f62de776-e123-4e94-b195-1111e1930b0f";
		$apps[$x]['permissions'][$y]['groups'][] = "superadmin";
		$apps[$x]['permissions'][$y]['groups'][] = "admin";
		$y++;
		$apps[$x]['permissions'][$y]['name'] = "network_cc_add";
		$apps[$x]['permissions'][$y]['groups'][] = "superadmin";
		$apps[$x]['permissions'][$y]['groups'][] = "admin";
		$y++;
		$apps[$x]['permissions'][$y]['name'] = "network_cc_advanced";
		$apps[$x]['permissions'][$y]['groups'][] = "superadmin";
		$y++;
		$apps[$x]['permissions'][$y]['name'] = "network_cc_edit";
		$apps[$x]['permissions'][$y]['groups'][] = "superadmin";
		$apps[$x]['permissions'][$y]['groups'][] = "admin";
		$y++;
		$apps[$x]['permissions'][$y]['name'] = "network_cc_delete";
		$apps[$x]['permissions'][$y]['groups'][] = "superadmin";
		$apps[$x]['permissions'][$y]['groups'][] = "admin";

	//schema details
		$y=0;
		$apps[$x]['db'][$y]['table']['name'] = "v_network_cc";
		$apps[$x]['db'][$y]['table']['parent'] = "";
		$z=0;
		$apps[$x]['db'][$y]['fields'][$z]['name'] = "network_uuid";
		$apps[$x]['db'][$y]['fields'][$z]['type']['pgsql'] = "uuid";
		$apps[$x]['db'][$y]['fields'][$z]['type']['sqlite'] = "text";
		$apps[$x]['db'][$y]['fields'][$z]['type']['mysql'] = "char(36)";
		$apps[$x]['db'][$y]['fields'][$z]['key']['type'] = "primary";
		$z++;
		$apps[$x]['db'][$y]['fields'][$z]['name'] = "network_name";
		$apps[$x]['db'][$y]['fields'][$z]['type'] = "text";
		$apps[$x]['db'][$y]['fields'][$z]['description']['en-us'] = "";
		$z++;
		$apps[$x]['db'][$y]['fields'][$z]['name'] = "network_caller";
		$apps[$x]['db'][$y]['fields'][$z]['type'] = "text";
		$apps[$x]['db'][$y]['fields'][$z]['description']['en-us'] = "";
		$z++;
		$apps[$x]['db'][$y]['fields'][$z]['name'] = "network_update_time";
		$apps[$x]['db'][$y]['fields'][$z]['type'] = "timestamp";
		$apps[$x]['db'][$y]['fields'][$z]['description']['en-us'] = "";
		$z++;
		$apps[$x]['db'][$y]['fields'][$z]['name'] = "network_enabled";
		$apps[$x]['db'][$y]['fields'][$z]['type'] = "text";
		$apps[$x]['db'][$y]['fields'][$z]['description']['en-us'] = "";
		$z++;
		$apps[$x]['db'][$y]['fields'][$z]['name'] = "network_description";
		$apps[$x]['db'][$y]['fields'][$z]['type'] = "text";
		$apps[$x]['db'][$y]['fields'][$z]['description']['en-us'] = "";

?>