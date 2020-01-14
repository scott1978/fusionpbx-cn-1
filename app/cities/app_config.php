<?php

	//application details
		$apps[$x]['name'] = "Cities";
		$apps[$x]['uuid'] = "b33a6dcc-2c9b-4486-b24d-90f62499948e";
		$apps[$x]['category'] = "Switch";
		$apps[$x]['subcategory'] = "";
		$apps[$x]['version'] = "1.0";
		$apps[$x]['license'] = "Mozilla Public License 1.1";
		$apps[$x]['url'] = "http://www.fusionpbx.com";
		$apps[$x]['description']['en-us'] = "Cities";
		$apps[$x]['description']['ar-eg'] = "Cities";
		$apps[$x]['description']['de-at'] = "Cities";
		$apps[$x]['description']['de-ch'] = "Cities";
		$apps[$x]['description']['de-de'] = "Cities";
		$apps[$x]['description']['es-cl'] = "Cities";
		$apps[$x]['description']['es-mx'] = "Cities";
		$apps[$x]['description']['fr-ca'] = "Cities";
		$apps[$x]['description']['fr-fr'] = "Cities";
		$apps[$x]['description']['he-il'] = "Cities";
		$apps[$x]['description']['it-it'] = "Cities";
		$apps[$x]['description']['nl-nl'] = "Cities";
		$apps[$x]['description']['pl-pl'] = "Cities";
		$apps[$x]['description']['pt-br'] = "Cities";
		$apps[$x]['description']['pt-pt'] = "Cities";
		$apps[$x]['description']['ro-ro'] = "Cities";
		$apps[$x]['description']['ru-ru'] = "Cities";
		$apps[$x]['description']['sv-se'] = "Cities";
		$apps[$x]['description']['uk-ua'] = "Cities";

	//permission details
		$y=0;
		$apps[$x]['permissions'][$y]['name'] = "cities_view";
		$apps[$x]['permissions'][$y]['menu']['uuid'] = "c14af579-6f69-4df2-ba28-c547cde4ccdc";
		$apps[$x]['permissions'][$y]['groups'][] = "superadmin";
		$apps[$x]['permissions'][$y]['groups'][] = "admin";
		$y++;
		$apps[$x]['permissions'][$y]['name'] = "cities_add";
		$apps[$x]['permissions'][$y]['groups'][] = "superadmin";
		$apps[$x]['permissions'][$y]['groups'][] = "admin";
		$y++;
		$apps[$x]['permissions'][$y]['name'] = "cities_edit";
		$apps[$x]['permissions'][$y]['groups'][] = "superadmin";
		$apps[$x]['permissions'][$y]['groups'][] = "admin";
		$y++;
		$apps[$x]['permissions'][$y]['name'] = "cities_delete";
		$apps[$x]['permissions'][$y]['groups'][] = "superadmin";
		$apps[$x]['permissions'][$y]['groups'][] = "admin";

	//schema details
		$y=0;
		$apps[$x]['db'][$y]['table']['name'] = "v_province_city";
		$apps[$x]['db'][$y]['table']['parent'] = "";
		$z=0;
		$apps[$x]['db'][$y]['fields'][$z]['name'] = "city_uuid";
		$apps[$x]['db'][$y]['fields'][$z]['type']['pgsql'] = 'uuid';
		$apps[$x]['db'][$y]['fields'][$z]['type']['sqlite'] = 'text';
		$apps[$x]['db'][$y]['fields'][$z]['type']['mysql'] = 'char(36)';
		$apps[$x]['db'][$y]['fields'][$z]['key']['type'] = 'primary';
		$z++;
		$apps[$x]['db'][$y]['fields'][$z]['name'] = "id";
		$apps[$x]['db'][$y]['fields'][$z]['type'] = "int4";
		$apps[$x]['db'][$y]['fields'][$z]['description']['en-us'] = "";
		$z++;
		$apps[$x]['db'][$y]['fields'][$z]['name'] = "parent_id";
		$apps[$x]['db'][$y]['fields'][$z]['type'] = "int4";
		$apps[$x]['db'][$y]['fields'][$z]['description']['en-us'] = "";
		$z++;
		$apps[$x]['db'][$y]['fields'][$z]['name'] = "name";
		$apps[$x]['db'][$y]['fields'][$z]['type'] = "text";
		$apps[$x]['db'][$y]['fields'][$z]['description']['en-us'] = "";
		$z++;
		$apps[$x]['db'][$y]['fields'][$z]['name'] = "fixed_code";
		$apps[$x]['db'][$y]['fields'][$z]['type'] = "text";
		$apps[$x]['db'][$y]['fields'][$z]['description']['en-us'] = "";
		$z++;
		$apps[$x]['db'][$y]['fields'][$z]['name'] = "item_type";
		$apps[$x]['db'][$y]['fields'][$z]['type'] = "int2";
		$apps[$x]['db'][$y]['fields'][$z]['description']['en-us'] = "";
		$z++;
		$apps[$x]['db'][$y]['fields'][$z]['name'] = "item_order";
		$apps[$x]['db'][$y]['fields'][$z]['type'] = "int2";
		$apps[$x]['db'][$y]['fields'][$z]['description']['en-us'] = "";

?>