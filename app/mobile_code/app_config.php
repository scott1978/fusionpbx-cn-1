<?php

	//application details
		$apps[$x]['name'] = "Mobile Code";
		$apps[$x]['uuid'] = "df5f574c-45ae-4c5c-8f0a-189d66e84d53";
		$apps[$x]['category'] = "Switch";
		$apps[$x]['subcategory'] = "";
		$apps[$x]['version'] = "1.0";
		$apps[$x]['license'] = "Mozilla Public License 1.1";
		$apps[$x]['url'] = "http://www.fusionpbx.com";
		$apps[$x]['description']['en-us'] = "Mobile Code";
		$apps[$x]['description']['ar-eg'] = "Mobile Code";
		$apps[$x]['description']['de-at'] = "Mobile Code";
		$apps[$x]['description']['de-ch'] = "Mobile Code";
		$apps[$x]['description']['de-de'] = "Mobile Code";
		$apps[$x]['description']['es-cl'] = "Mobile Code";
		$apps[$x]['description']['es-mx'] = "Mobile Code";
		$apps[$x]['description']['fr-ca'] = "Mobile Code";
		$apps[$x]['description']['fr-fr'] = "Mobile Code";
		$apps[$x]['description']['he-il'] = "Mobile Code";
		$apps[$x]['description']['it-it'] = "Mobile Code";
		$apps[$x]['description']['nl-nl'] = "Mobile Code";
		$apps[$x]['description']['pl-pl'] = "Mobile Code";
		$apps[$x]['description']['pt-br'] = "Mobile Code";
		$apps[$x]['description']['pt-pt'] = "Mobile Code";
		$apps[$x]['description']['ro-ro'] = "Mobile Code";
		$apps[$x]['description']['ru-ru'] = "Mobile Code";
		$apps[$x]['description']['sv-se'] = "Mobile Code";
		$apps[$x]['description']['uk-ua'] = "Mobile Code";

	//permission details
		$y=0;
		$apps[$x]['permissions'][$y]['name'] = "mobile_code_view";
		$apps[$x]['permissions'][$y]['menu']['uuid'] = "48a8d4ca-68e2-44e5-951a-71bf6c262b0f";
		$apps[$x]['permissions'][$y]['groups'][] = "superadmin";
		$apps[$x]['permissions'][$y]['groups'][] = "admin";
		$y++;
		$apps[$x]['permissions'][$y]['name'] = "mobile_code_add";
		$apps[$x]['permissions'][$y]['groups'][] = "superadmin";
		$apps[$x]['permissions'][$y]['groups'][] = "admin";
		$y++;
		$apps[$x]['permissions'][$y]['name'] = "mobile_code_edit";
		$apps[$x]['permissions'][$y]['groups'][] = "superadmin";
		$apps[$x]['permissions'][$y]['groups'][] = "admin";
		$y++;
		$apps[$x]['permissions'][$y]['name'] = "mobile_code_delete";
		$apps[$x]['permissions'][$y]['groups'][] = "superadmin";
		$apps[$x]['permissions'][$y]['groups'][] = "admin";

	//schema details
		$y=0;
		$apps[$x]['db'][$y]['table']['name'] = "v_mobile_code";
		$apps[$x]['db'][$y]['table']['parent'] = "";
		$z=0;
		$apps[$x]['db'][$y]['fields'][$z]['name'] = "mobile_prefix";
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
		$apps[$x]['db'][$y]['fields'][$z]['name'] = "mobile_isp";
		$apps[$x]['db'][$y]['fields'][$z]['type'] = "numeric";
		$apps[$x]['db'][$y]['fields'][$z]['description']['en-us'] = "";

?>