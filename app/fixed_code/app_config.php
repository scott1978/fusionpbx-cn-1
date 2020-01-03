<?php

	//application details
		$apps[$x]['name'] = "Fixed Code";
		$apps[$x]['uuid'] = "1604c6b4-9c81-44be-a31a-87d934c80700";
		$apps[$x]['category'] = "Switch";
		$apps[$x]['subcategory'] = "";
		$apps[$x]['version'] = "1.0";
		$apps[$x]['license'] = "Mozilla Public License 1.1";
		$apps[$x]['url'] = "http://www.fusionpbx.com";
		$apps[$x]['description']['en-us'] = "Fixed Code";
		$apps[$x]['description']['ar-eg'] = "Fixed Code";
		$apps[$x]['description']['de-at'] = "Fixed Code";
		$apps[$x]['description']['de-ch'] = "Fixed Code";
		$apps[$x]['description']['de-de'] = "Fixed Code";
		$apps[$x]['description']['es-cl'] = "Fixed Code";
		$apps[$x]['description']['es-mx'] = "Fixed Code";
		$apps[$x]['description']['fr-ca'] = "Fixed Code";
		$apps[$x]['description']['fr-fr'] = "Fixed Code";
		$apps[$x]['description']['he-il'] = "Fixed Code";
		$apps[$x]['description']['it-it'] = "Fixed Code";
		$apps[$x]['description']['nl-nl'] = "Fixed Code";
		$apps[$x]['description']['pl-pl'] = "Fixed Code";
		$apps[$x]['description']['pt-br'] = "Fixed Code";
		$apps[$x]['description']['pt-pt'] = "Fixed Code";
		$apps[$x]['description']['ro-ro'] = "Fixed Code";
		$apps[$x]['description']['ru-ru'] = "Fixed Code";
		$apps[$x]['description']['sv-se'] = "Fixed Code";
		$apps[$x]['description']['uk-ua'] = "Fixed Code";

	//permission details
		$y=0;
		$apps[$x]['permissions'][$y]['name'] = "fixed_code_view";
		$apps[$x]['permissions'][$y]['menu']['uuid'] = "48a8d4ca-68e2-44e5-951a-71bf6c262b0f";
		$apps[$x]['permissions'][$y]['groups'][] = "superadmin";
		$apps[$x]['permissions'][$y]['groups'][] = "admin";
		$y++;
		$apps[$x]['permissions'][$y]['name'] = "fixed_code_add";
		$apps[$x]['permissions'][$y]['groups'][] = "superadmin";
		$apps[$x]['permissions'][$y]['groups'][] = "admin";
		$y++;
		$apps[$x]['permissions'][$y]['name'] = "fixed_code_advanced";
		$apps[$x]['permissions'][$y]['groups'][] = "superadmin";
		$y++;
		$apps[$x]['permissions'][$y]['name'] = "fixed_code_edit";
		$apps[$x]['permissions'][$y]['groups'][] = "superadmin";
		$apps[$x]['permissions'][$y]['groups'][] = "admin";
		$y++;
		$apps[$x]['permissions'][$y]['name'] = "fixed_code_delete";
		$apps[$x]['permissions'][$y]['groups'][] = "superadmin";
		$apps[$x]['permissions'][$y]['groups'][] = "admin";

	//schema details
		$y=0;
		$apps[$x]['db'][$y]['table']['name'] = "v_fixed_code";
		$apps[$x]['db'][$y]['table']['parent'] = "";
		$z=0;
		$apps[$x]['db'][$y]['fields'][$z]['name'] = "fixed_code";
		$apps[$x]['db'][$y]['fields'][$z]['type']['pgsql'] = "text";
		$apps[$x]['db'][$y]['fields'][$z]['type']['sqlite'] = "text";
		$apps[$x]['db'][$y]['fields'][$z]['type']['mysql'] = "char(36)";
		$apps[$x]['db'][$y]['fields'][$z]['key']['type'] = "primary";
		$z++;
		$apps[$x]['db'][$y]['fields'][$z]['name'] = "province";
		$apps[$x]['db'][$y]['fields'][$z]['type'] = "text";
		$apps[$x]['db'][$y]['fields'][$z]['description']['en-us'] = "";
		$z++;
		$apps[$x]['db'][$y]['fields'][$z]['name'] = "city";
		$apps[$x]['db'][$y]['fields'][$z]['type'] = "text";
		$apps[$x]['db'][$y]['fields'][$z]['description']['en-us'] = "";
		$z++;
		$apps[$x]['db'][$y]['fields'][$z]['name'] = "area_code";
		$apps[$x]['db'][$y]['fields'][$z]['type'] = "text";
		$apps[$x]['db'][$y]['fields'][$z]['description']['en-us'] = "";

?>