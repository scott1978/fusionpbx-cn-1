<?php

// header("content-type:text/html;charset=utf-8");

/*
	FusionPBX
	Version: MPL 1.1

	The contents of this file are subject to the Mozilla Public License Version
	1.1 (the "License"); you may not use this file except in compliance with
	the License. You may obtain a copy of the License at
	http://www.mozilla.org/MPL/

	Software distributed under the License is distributed on an "AS IS" basis,
	WITHOUT WARRANTY OF ANY KIND, either express or implied. See the License
	for the specific language governing rights and limitations under the
	License.

	The Original Code is FusionPBX

	The Initial Developer of the Original Code is
	Mark J Crane <markjcrane@fusionpbx.com>
	Portions created by the Initial Developer are Copyright (C) 2008-2018
	the Initial Developer. All Rights Reserved.

	Contributor(s):
	Mark J Crane <markjcrane@fusionpbx.com>
*/

//includes
	require_once "root.php";
	require_once "resources/redis.php";
	require_once "resources/require.php";
	require_once "resources/check_auth.php";

//check permissions
	if (permission_exists('landing_route_add') || permission_exists('landing_route_edit')) {
		//access granted
	}
	else {
		echo "access denied";
		exit;
	}

//add multi-lingual support
	$language = new text;
	$text = $language->get();

//action add or update
	if (isset($_REQUEST["id"])) {
		$action = "update";
		$route_uuid = trim($_REQUEST["id"]);
	}
	else {
		$action = "add";
	}

//get data by route_uuid
	if (count($_GET) > 0) {
		if (isset($route_uuid)) {
			$sql = "select * from v_landing_route where route_uuid='".$route_uuid."' limit 1";
			$prep_statement = $db->prepare(check_sql($sql));
			$prep_statement->execute();
			$result = $prep_statement->fetchAll(PDO::FETCH_NAMED);
			foreach ($result as &$row) {
				$route_name = $row["route_name"];
				$route_gateway = $row["route_gateway"];
				$route_type = $row["route_type"];
				$route_city = $row["route_city"];
				$route_telephone = $row["route_telephone"];
				$route_weekday = $row["route_weekday"];
				if (strlen($route_weekday) > 0) {
					$route_weeks = explode(",", $route_weekday);
				}
				$route_start_time = $row["route_start_time"];
				$route_end_time = $row["route_end_time"];
				$route_enabled = $row["route_enabled"];
				$network_uuid = $row["network_uuid"];
				$route_order = $row["route_order"];
				$route_description = $row["route_description"];
				break; //limit to 1 row
			}
		}
		
		$route_city_arry = explode(",", $route_city);
		$sql = "select * from v_province_city order by item_order";
		$prep_statement = $db->prepare(check_sql($sql));
		$prep_statement->execute();
		$result = $prep_statement->fetchAll(PDO::FETCH_NAMED);
		$province_city_length = 0;
		foreach ($result as $k => $v) {
			$province_city_length++;
			if (strlen($v['parent_id'])==0) {
				$province_city_list[$k]['isParent'] = true;
			}
			if (($route_city_arry[0] == '0') || in_array($v['id'], $route_city_arry)) {
				$province_city_list[$k]['checked'] = 'true';
			} else {
				$province_city_list[$k]['checked'] = 'false';
			}

			$province_city_list[$k]['id'] = $v['id'];
			$province_city_list[$k]['pId'] = $v['parent_id'];
			$province_city_list[$k]['name'] = $v['name'];
		}

		$province_city_list_str = json_encode($province_city_list);
		echo "come here";
		echo $province_city_list_str;
		exit(0);

		unset($sql, $prep_statement, $result, $row, $route_city_arry, $k, $v, $province_city_list);
	}

//get http post variables and set them to php variables
	if (count($_POST) > 0) {

		//set the variables
			$route_name = trim($_POST["route_name"]);
			$route_gateway = trim($_POST["route_gateway"]);
			$route_type = trim($_POST["route_type"]);
			if (strlen($route_type) == 0) {
				$route_type = "1";
			}
			$route_city = trim($_POST["route_city"]);
			if ($route_type != "1") {
				$route_city = "";
			}
			$route_telephone = trim($_POST["route_telephone"]);
			$route_weekday = implode(",", $_POST["route_weeks"]);
			$route_start_time = trim($_POST["route_start_time"]);
			if ((strlen($route_start_time) == 0) || (preg_match("/[0-2][0-9]:[0-5][0-9]/", $route_start_time) == 0)) {
				$route_start_time = "00:00";
			}
			$route_end_time = trim($_POST["route_end_time"]);
			if ((strlen($route_end_time) == 0) || (preg_match("/[0-2][0-9]:[0-5][0-9]/", $route_end_time) == 0)) {
				$route_end_time = "23:59";
			}
			$route_enabled = trim($_POST["route_enabled"]);
			if (strlen($route_enabled) == 0) {
				$route_enabled = "false";
			}
			$network_uuid = trim($_POST["network_uuid"]);
			$route_order = trim($_POST["route_order"]);
			if (strlen($route_order) == 0) {
				$route_order = "999";
			}
			$route_description = trim($_POST["route_description"]);
	}

//process the http post 
	if (count($_POST) > 0 && strlen($_POST["persistformvar"]) == 0) {

		//get the uuid
			if ($action == "update" && isset($_POST["route_uuid"])) {
				$route_uuid = trim($_POST["route_uuid"]);
			}
			else {
				$route_uuid = uuid();
			}

		//check for all required data
			$msg = '';
			if (strlen($route_name) == 0) { $msg .= $text['message-required']." ".$text['label-route_name']."<br>\n"; }

		//show the message
			if (strlen($msg) > 0 && strlen($_POST["persistformvar"]) == 0) {
				require_once "resources/header.php";
				require_once "resources/persist_form_var.php";
				echo "<div align='center'>\n";
				echo "<table><tr><td>\n";
				echo $msg."<br />";
				echo "</td></tr></table>\n";
				persistformvar($_POST);
				echo "</div>\n";
				require_once "resources/footer.php";
				return;
			}

		// add
			if ($action == "add") {
				$route_update_time = date('Y-m-d H:i:s');
				$sql = "insert into v_landing_route ";
				$sql .= "(";
				$sql .= "route_uuid, ";
				$sql .= "route_name, ";
				$sql .= "route_gateway, ";
				$sql .= "route_weekday, ";
				$sql .= "route_start_time, ";
				$sql .= "route_end_time, ";
				$sql .= "route_enabled, ";
				if (strlen($network_uuid) > 0) {
					$sql .= "network_uuid, ";
				}
				$sql .= "route_type, ";
				$sql .= "route_city, ";
				$sql .= "route_telephone, ";
				$sql .= "route_order, ";
				$sql .= "route_update_time, ";
				$sql .= "route_description ";
				$sql .= ") ";
				$sql .= "values ";
				$sql .= "(";
				$sql .= "'".uuid()."', ";
				$sql .= "'$route_name', ";
				$sql .= "'$route_gateway', ";
				$sql .= "'$route_weekday', ";
				$sql .= "'$route_start_time', ";
				$sql .= "'$route_end_time', ";
				$sql .= "'$route_enabled', ";
				if (strlen($network_uuid) > 0) {
					$sql .= "'$network_uuid', ";
				}
				$sql .= "'$route_type', ";
				$sql .= "'$route_city', ";
				$sql .= "'$route_telephone', ";
				$sql .= "'$route_order', ";
				$sql .= "'$route_update_time', ";
				$sql .= "'$route_description' ";
				$sql .= ")";
				$db->exec(check_sql($sql));
				unset($sql, $route_update_time);

				if (($route_enabled == "true") && (strlen($network_uuid)) > 0) {
					$redis->lpush($rds_pbx_rule_watch, time());
				}
			}

			// update
			if ($action == "update" && isset($route_uuid)) {
				$route_update_time = date('Y-m-d H:i:s');
				$sql = "update v_landing_route set route_name='$route_name', route_gateway='$route_gateway', ";
				$sql .= "route_weekday='$route_weekday', route_start_time='$route_start_time', ";
				$sql .= "route_end_time='$route_end_time', route_enabled='$route_enabled', ";
				if (strlen($network_uuid) == 0) {
					$sql .= "network_uuid=NULL, ";
				} else {
					$sql .= "network_uuid='$network_uuid', ";
				}
				$sql .= "route_type='$route_type', route_city='$route_city', route_telephone='$route_telephone', ";
				$sql .= "route_order='$route_order', route_update_time='$route_update_time', route_description='$route_description' ";
				$sql .= "where route_uuid='$route_uuid'";
				$db->exec(check_sql($sql));
				unset($sql, $route_update_time);

				$redis->lpush($rds_pbx_rule_watch, time());
			}


		//redirect the user
			if ($action == "add") {
				messages::add($text['message-add']);
			}
			if ($action == "update") {
				messages::add($text['message-update']);
			}
			header("Location: route.php");
			return;

	} //(count($_POST) > 0 && strlen($_POST["persistformvar"]) == 0)

//show the header
	require_once "resources/header.php";

	echo "<script src=\"".PROJECT_PATH."/resources/jquery/jquery-1.11.1.js\"></script>\n";
	echo "<script src=\"".PROJECT_PATH."/resources/jquery/ztree/jquery.ztree.core.min.js\"></script>\n";
	echo "<script src=\"".PROJECT_PATH."/resources/jquery/ztree/jquery.ztree.excheck.min.js\"></script>\n";
	echo "<link rel=\"stylesheet\" href=\"".PROJECT_PATH."/resources/bootstrap/css/zTreeStyle/zTreeStyle.css\" />\n";
	echo "<script type='text/javascript'>\n";
	echo "    var setting = {\n";
	echo "        check: {\n";
	echo "            enable: true,\n";
	echo "            chkboxType: { 'Y': 'ps', 'N': 'ps' }\n";
	echo "        },\n";
	echo "        data: {\n";
	echo "            simpleData: {\n";
	echo "                enable: true\n";
	echo "            }\n";
	echo "        },\n";
	echo "        callback: {\n";
	echo "            beforeCheck: true,\n";
	echo "            onCheck: onCheck\n";
	echo "        }\n";
	echo "    };\n";
	echo "    var zNodes = $province_city_list_str;\n";
	echo "    $(document).ready(function () {\n";
	echo "        $.fn.zTree.init($(\"#route_city_tree\"), setting, zNodes);\n";
	echo "        onCheck();\n";
	echo "    });\n";
	echo "    function onCheck(e, treeId, treeNode) {\n";
	echo "        var treeObj = $.fn.zTree.getZTreeObj('route_city_tree');\n";
	echo "        var nodes = treeObj.getCheckedNodes(true);\n";
	echo "        var choose = '';\n";
	echo "        if (nodes.length == $province_city_length) {\n";
	echo "            choose = '0';\n";
	echo "        } else {\n";
	echo "            for (var i = 0; i < nodes.length; i++) {\n";
	echo "                if (nodes[i].id != null) {\n";
	echo "                    if (i == (nodes.length - 1)) {\n";
	echo "                        choose += nodes[i].id;\n";
	echo "                    } else {\n";
	echo "                        choose += nodes[i].id + ',';\n";
	echo "                    }\n";
	echo "                }\n";
	echo "            }\n";
	echo "        }\n";
	echo "        $('#route_city').val(choose);\n";
	echo "    }\n";
	echo "</script>\n";
	

//show the content
	echo "<form method='post' name='frm' action=''>\n";
	echo "<table width='100%' border='0' cellpadding='0' cellspacing='0'>\n";
	echo "<tr>\n";
	if ($action == "add") {
		echo "<td align='left' width='30%' nowrap='nowrap' valign='top'><b>".$text['header-landing-route-add']."</b></td>\n";
	}
	if ($action == "update") {
		echo "<td align='left' width='30%' nowrap='nowrap' valign='top'><b>".$text['header-landing-route-edit']."</b></td>\n";
	}
	echo "<td width='70%' align='right' valign='top'>";
	echo "	<input type='button' class='btn' alt='".$text['button-back']."' onclick=\"window.location='route.php'\" value='".$text['button-back']."'>";
	echo "	<input type='submit' class='btn' value='".$text['button-save']."'>\n";
	echo "</td>\n";
	echo "</tr>\n";
	echo "<tr>\n";
	echo "<td align='left' colspan='2'>\n";
	// echo $text['description-destinations']."<br /><br />\n";
	echo "</td>\n";
	echo "</tr>\n";

	// route_name
	echo "<tr>\n";
	echo "<td class='vncellreq' valign='top' align='left' nowrap='nowrap'>\n";
	echo "	".$text['label-route_name']."\n";
	echo "</td>\n";
	echo "<td class='vtable' align='left'>\n";
	echo "	<input class='formfld' type='text' name='route_name' maxlength='255' value=\"".escape($route_name)."\" required='required'>\n";
		echo "<br />\n";
		echo $text['description-route_name']."\n";
	echo "</td>\n";
	echo "</tr>\n";

	// route_gateway
	echo "<tr>\n";
	echo "<td class='vncell' valign='top' align='left' nowrap='nowrap'>\n";
	echo "	".$text['label-route_gateway']."\n";
	echo "</td>\n";
	echo "<td class='vtable' align='left'>\n";
	echo "	<input class='formfld' type='text' name='route_gateway' value=\"".escape($route_gateway)."\">\n";
		echo "<br />\n";
		echo $text['description-route_gateway']."\n";
	echo "</td>\n";
	echo "</tr>\n";

	// route_type
	echo "<tr>\n";
	echo "<td class='vncell' valign='top' align='left' nowrap='nowrap'>\n";
	echo "	".$text['label-route_type']."\n";
	echo "</td>\n";
	echo "<td class='vtable' align='left'>\n";
	echo "	<select class='formfld' name='route_type' id='route_type'>\n";
	switch ($route_type) {
		case "1" : 	$selected[1] = "selected='selected'";	break;
		case "2" : 	$selected[2] = "selected='selected'";	break;
	}
	echo "	<option value='1' ".$selected[1].">".$text['label-route_city']."</option>\n";
	echo "	<option value='2' ".$selected[2].">".$text['label-route_telephone']."</option>\n";
	unset($selected);
	echo "	</select>\n";
	echo "<br />\n";
	echo $text['description-route_type']."\n";
	echo "</td>\n";
	echo "</tr>\n";

	// // route_city
	echo "<tr name='route_city_group' id='route_city_group'>\n";
	echo "<td class='vncell' valign='top' align='left' nowrap='nowrap'>\n";
	echo "	".$text['label-route_city']."\n";
	echo "</td>\n";
	echo "<td class='vtable' align='left'>\n";
	echo "  <ul id='route_city_tree' class='ztree'></ul>\n";
	echo "</td>\n";
	echo "</tr>\n";

	// route_telephone
	echo "<tr name='route_telephone_group' id='route_telephone_group'>\n";
	echo "<td class='vncell' valign='top' align='left' nowrap='nowrap'>\n";
	echo "	".$text['label-route_telephone']."\n";
	echo "</td>\n";
	echo "<td class='vtable' align='left'>\n";
	echo "	<input class='formfld' type='text' name='route_telephone' id='route_telephone' value=\"".escape($route_telephone)."\">\n";
		echo "<br />\n";
		echo $text['description-route_telephone']."\n";
	echo "</td>\n";
	echo "</tr>\n";

	// route_weeks
	echo "<tr>\n";
	echo "<td class='vncell' valign='top' align='left' nowrap='nowrap'>\n";
	echo "	".$text['label-route_weekday']."\n";
	echo "</td>\n";
	echo "<td class='vtable' align='left'>\n";
	for ($i=0;$i<7;$i++) {
		if ($i == 1) {
			$show_week_name = "周一 、 ";
		} else if ($i == 2) {
			$show_week_name = "周二 、 ";
		} else if ($i == 3) {
			$show_week_name = "周三 、 ";
		} else if ($i == 4) {
			$show_week_name = "周四 、 ";
		} else if ($i == 5) {
			$show_week_name = "周五 、 ";
		} else if ($i == 6) {
			$show_week_name = "周六 ";
		} else {
			$show_week_name = "周日 、 ";
		}

		$is_in = in_array($i, $route_weeks);
		if ($is_in) {
			echo "	<input type='checkbox' name='route_weeks[]' value='$i' checked='checked' /> $show_week_name \n";
		} else {
			echo "	<input type='checkbox' name='route_weeks[]' value='$i' /> $show_week_name \n";
		}
	}
	echo "<br />\n";
	echo $text['description-route_weekday']."\n";
	echo "</td>\n";
	echo "</tr>\n";

	// route_start_time
	echo "<tr>\n";
	echo "<td class='vncell' valign='top' align='left' nowrap='nowrap'>\n";
	echo "	".$text['label-route_start_time']."\n";
	echo "</td>\n";
	echo "<td class='vtable' align='left'>\n";
	echo "	<input class='formfld' type='text' name='route_start_time' maxlength='255' value=\"".escape($route_start_time)."\" >\n";
		echo "<br />\n";
		echo $text['description-route_start_time']."\n";
	echo "</td>\n";
	echo "</tr>\n";

	// route_end_time
	echo "<tr>\n";
	echo "<td class='vncell' valign='top' align='left' nowrap='nowrap'>\n";
	echo "	".$text['label-route_end_time']."\n";
	echo "</td>\n";
	echo "<td class='vtable' align='left'>\n";
	echo "	<input class='formfld' type='text' name='route_end_time' maxlength='255' value=\"".escape($route_end_time)."\" >\n";
		echo "<br />\n";
		echo $text['description-route_end_time']."\n";
	echo "</td>\n";
	echo "</tr>\n";

	// route_enabled
	echo "<tr>\n";
	echo "<td class='vncell' valign='top' align='left' nowrap='nowrap'>\n";
	echo "	".$text['label-route_enabled']."\n";
	echo "</td>\n";
	echo "<td class='vtable' align='left'>\n";
	echo "	<select class='formfld' name='route_enabled'>\n";
	switch ($route_enabled) {
		case "true" :	$selected[1] = "selected='selected'";	break;
		default :	$selected[2] = "selected='selected'";	break;
	}
	echo "	<option value='true' ".$selected[1].">".$text['label-true']."</option>\n";
	echo "	<option value='false' ".$selected[2].">".$text['label-false']."</option>\n";
	unset($selected);
	echo "	</select>\n";
	echo "<br />\n";
	echo $text['description-route_enabled']."\n";
	echo "</td>\n";
	echo "</tr>\n";

	// network_uuid
	echo "<tr>\n";
	echo "<td class='vncell' valign='top' align='left' nowrap='nowrap'>\n";
	echo "	".$text['label-network_name']."\n";
	echo "</td>\n";
	echo "<td class='vtable' align='left'>\n";
	$table_name = 'v_network_cc'; $field_name = 'network_name'; $field_value = 'network_uuid';
	$sql_where_optional = "where network_enabled='true'"; $field_current_value = $network_uuid;
	echo html_select($db, $table_name, $field_name, $sql_where_optional, $field_current_value, $field_value);
	echo "		<br />\n";
	echo "		".$text['description-network_name']."\n";
	echo "</td>\n";
	echo "</tr>\n";

	// route_order
	echo "<tr>\n";
	echo "<td class='vncell' valign='top' align='left' nowrap='nowrap'>\n";
	echo "	".$text['label-route_order']."\n";
	echo "</td>\n";
	echo "<td class='vtable' align='left'>\n";
	echo "	<input class='formfld' type='text' name='route_order' maxlength='255' value=\"".escape($route_order)."\" >\n";
		echo "<br />\n";
		echo $text['description-route_order']."\n";
	echo "</td>\n";
	echo "</tr>\n";

	// route_description
	echo "<tr>\n";
	echo "<td class='vncell' valign='top' align='left' nowrap='nowrap'>\n";
	echo "	".$text['label-route_description']."\n";
	echo "</td>\n";
	echo "<td class='vtable' align='left'>\n";
	echo "	<input class='formfld' type='text' name='route_description' maxlength='255' value=\"".escape($route_description)."\" >\n";
	echo "<br />\n";
	echo $text['description-route_description']."\n";
	echo "</td>\n";
	echo "</tr>\n";

	echo "	<tr>\n";
	echo "		<td colspan='2' align='right'>\n";
	if ($action == "update") {
		echo "		<input type='hidden' name='route_uuid' value='".escape($route_uuid)."'>\n";
	}
	echo "		    <input type='hidden' id='route_city' name='route_city' >\n";
	echo "			<br>";
	echo "			<input type='submit' class='btn' value='".$text['button-save']."'>\n";
	echo "		</td>\n";
	echo "	</tr>";
	echo "</table>";
	echo "<br><br>";
	echo "</form>";

//include the footer
	require_once "resources/footer.php";

?>
