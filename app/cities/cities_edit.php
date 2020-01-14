<?php
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
	require_once "resources/require.php";
	require_once "resources/check_auth.php";

//check permissions
	if (permission_exists('cities_add') || permission_exists('cities_edit')) {
		//access granted
	}
	else {
		// echo "access denied";
		// exit; // todo
	}

//add multi-lingual support
	$language = new text;
	$text = $language->get();

//action add or update
	if (isset($_REQUEST["id"])) {
		$action = "update";
		$city_uuid = trim($_REQUEST["id"]);
	}
	else {
		$action = "add";
	}

//get data by id
	if (count($_GET) > 0 && isset($city_uuid)) {
		$sql = "select * from v_province_city where city_uuid='".$city_uuid."' limit 1";
		$prep_statement = $db->prepare(check_sql($sql));
		$prep_statement->execute();
		$result = $prep_statement->fetchAll(PDO::FETCH_NAMED);
		foreach ($result as &$row) {
			$id = $row["id"];
			$parent_id = $row["parent_id"];
			$name = $row["name"];
			$fixed_code = $row["fixed_code"];
			$item_type = $row["item_type"];
			$item_order = $row["item_order"];
			break; //limit to 1 row
		}
		unset ($prep_statement);
	}

//get http post variables and set them to php variables
	if (count($_POST) > 0) {

		//set the variables
			$city_uuid = trim($_POST["city_uuid"]);
			$id = trim($_POST["id"]);
			$parent_id = trim($_POST["parent_id"]);
			$name = trim($_POST["name"]);
			$fixed_code = trim($_POST["fixed_code"]);
			$item_type = trim($_POST["item_type"]);
			$item_order = trim($_POST["item_order"]);
	}

//process the http post 
	if (count($_POST) > 0 && strlen($_POST["persistformvar"]) == 0) {

		//get the uuid
			if ($action == "update" && isset($_POST["city_uuid"])) {
				$city_uuid = trim($_POST["city_uuid"]);
			}

		//check for all required data
			$msg = '';
			if (strlen($id) == 0) { $msg .= $text['message-required']." ".$text['label-id']."<br>\n"; }
			if (strlen($name) == 0) { $msg .= $text['message-required']." ".$text['label-name']."<br>\n"; }
			if ($item_type == '3' && strlen($fixed_code) == 0) { $msg .= $text['message-required']." ".$text['label-fixed_code']."<br>\n"; }

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
				$sql = "insert into v_province_city ";
				$sql .= "(";
				$sql .= "city_uuid, ";
				$sql .= "id, ";
				$sql .= "parent_id, ";
				$sql .= "name, ";
				$sql .= "fixed_code, ";
				$sql .= "item_type, ";
				$sql .= "item_order ";
				$sql .= ") ";
				$sql .= "values ";
				$sql .= "(";
				$sql .= "'".uuid()."', ";
				$sql .= "'$id', ";
				$sql .= "'$parent_id', ";
				$sql .= "'$name', ";
				$sql .= "'$fixed_code', ";
				$sql .= "'$item_type', ";
				$sql .= "'$item_order' ";
				$sql .= ")";
				$db->exec(check_sql($sql));
				unset($sql);
			}

		// update
			if ($action == "update" && isset($fixed_code)) {
				$sql = "update v_province_city set id='$id', parent_id='$parent_id', name='$name', ";
				$sql .= "fixed_code='$fixed_code', item_type='$item_type', item_order='$item_order' ";
				$sql .= "where city_uuid='$city_uuid'";
				$db->exec(check_sql($sql));
				unset($sql);
			}

		//redirect the user
			if ($action == "add") {
				messages::add($text['message-add']);
			}
			if ($action == "update") {
				messages::add($text['message-update']);
			}
			header("Location: cities.php");
			return;

	} //(count($_POST) > 0 && strlen($_POST["persistformvar"]) == 0)

//show the header
	require_once "resources/header.php";
	
//show the content
	echo "<form method='post' name='frm' action=''>\n";
	echo "<table width='100%' border='0' cellpadding='0' cellspacing='0'>\n";
	echo "<tr>\n";
	if ($action == "add") {
		echo "<td align='left' width='30%' nowrap='nowrap' valign='top'><b>".$text['header-cities-add']."</b></td>\n";
	}
	if ($action == "update") {
		echo "<td align='left' width='30%' nowrap='nowrap' valign='top'><b>".$text['header-cities-edit']."</b></td>\n";
	}
	echo "<td width='70%' align='right' valign='top'>";
	echo "	<input type='button' class='btn' alt='".$text['button-back']."' onclick=\"window.location='cities.php'\" value='".$text['button-back']."'>";
	echo "	<input type='submit' class='btn' value='".$text['button-save']."'>\n";
	echo "</td>\n";
	echo "</tr>\n";
	echo "<tr>\n";
	echo "<td align='left' colspan='2'>\n";
	echo "</td>\n";
	echo "</tr>\n";

	// id
	echo "<tr>\n";
	echo "<td class='vncellreq' valign='top' align='left' nowrap='nowrap'>\n";
	echo "	".$text['label-id']."\n";
	echo "</td>\n";
	echo "<td class='vtable' align='left'>\n";
	echo "	<input class='formfld' type='text' name='id' maxlength='255' value=\"".escape($id)."\" required='required'>\n";
		echo "<br />\n";
		echo $text['description-id']."\n";
	echo "</td>\n";
	echo "</tr>\n";

	// parent_id
	echo "<tr>\n";
	echo "<td class='vncell' valign='top' align='left' nowrap='nowrap'>\n";
	echo "	".$text['label-parent_id']."\n";
	echo "</td>\n";
	echo "<td class='vtable' align='left'>\n";
	echo "	<input class='formfld' type='text' name='parent_id' value=\"".escape($parent_id)."\">\n";
		echo "<br />\n";
		echo $text['description-parent_id']."\n";
	echo "</td>\n";
	echo "</tr>\n";

	// name
	echo "<tr>\n";
	echo "<td class='vncellreq' valign='top' align='left' nowrap='nowrap'>\n";
	echo "	".$text['label-name']."\n";
	echo "</td>\n";
	echo "<td class='vtable' align='left'>\n";
	echo "	<input class='formfld' type='text' name='name' value=\"".escape($name)."\">\n";
		echo "<br />\n";
		echo $text['description-name']."\n";
	echo "</td>\n";
	echo "</tr>\n";

	// fixed_code
	echo "<tr>\n";
	echo "<td class='vncellreq' valign='top' align='left' nowrap='nowrap'>\n";
	echo "	".$text['label-fixed_code']."\n";
	echo "</td>\n";
	echo "<td class='vtable' align='left'>\n";
	echo "	<input class='formfld' type='text' name='fixed_code' value=\"".escape($fixed_code)."\">\n";
		echo "<br />\n";
		echo $text['description-fixed_code']."\n";
	echo "</td>\n";
	echo "</tr>\n";

	// item_type
	echo "<tr>\n";
	echo "<td class='vncell' valign='top' align='left' nowrap='nowrap'>\n";
	echo "	".$text['label-item_type']."\n";
	echo "</td>\n";
	echo "<td class='vtable' align='left'>\n";
	echo "	<select class='formfld' name='item_type'>\n";
	switch ($item_type) {
		case "1" :	$selected[1] = "selected='selected'";	break;
		case "2" :  $selected[2] = "selected='selected'";	break;
		default :	$selected[3] = "selected='selected'";	break;
	}
	echo "	<option value='1' ".$selected[1].">".$text['label-country']."</option>\n";
	echo "	<option value='2' ".$selected[2].">".$text['label-province']."</option>\n";
	echo "	<option value='3' ".$selected[3].">".$text['label-city']."</option>\n";
	unset($selected);
	echo "	</select>\n";
	echo "<br />\n";
	echo $text['description-item_type']."\n";
	echo "</td>\n";
	echo "</tr>\n";

	// item_order
	echo "<tr>\n";
	echo "<td class='vncell' valign='top' align='left' nowrap='nowrap'>\n";
	echo "	".$text['label-item_order']."\n";
	echo "</td>\n";
	echo "<td class='vtable' align='left'>\n";
	echo "	<input class='formfld' type='text' name='item_order' value=\"".escape($item_order)."\">\n";
		echo "<br />\n";
		echo $text['description-item_order']."\n";
	echo "</td>\n";
	echo "</tr>\n";

	echo "	<tr>\n";
	echo "		<td colspan='2' align='right'>\n";
	if ($action == "update") {
		echo "		<input type='hidden' name='city_uuid' value='".escape($city_uuid)."'>\n";
	}
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
