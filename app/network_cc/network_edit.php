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
	require_once "resources/constant.php";
	require_once "resources/require.php";
	require_once "resources/check_auth.php";

//check permissions
	if (permission_exists('network_cc_add') || permission_exists('network_cc_edit')) {
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
		$network_uuid = trim($_REQUEST["id"]);
	}
	else {
		$action = "add";
	}

//get data by network_uuid
	if (count($_GET) > 0 && isset($network_uuid)) {
		$sql = "select * from v_network_cc where network_uuid='".$network_uuid."' limit 1";
		$prep_statement = $db->prepare(check_sql($sql));
		$prep_statement->execute();
		$result = $prep_statement->fetchAll(PDO::FETCH_NAMED);
		foreach ($result as &$row) {
			$network_name = $row["network_name"];
			$network_caller = $row["network_caller"];
			$network_address = $row["network_address"];
			$network_enabled = $row["network_enabled"];
			$network_description = $row["network_description"];
			break; //limit to 1 row
		}
		unset ($prep_statement);
	}

//get http post variables and set them to php variables
	if (count($_POST) > 0) {

		//set the variables
			$network_name = trim($_POST["network_name"]);
			$network_caller = trim($_POST["network_caller"]);
			$network_address = trim($_POST["network_address"]);
			$network_enabled = trim($_POST["network_enabled"]);
			$network_description = trim($_POST["network_description"]);
	}

//process the http post 
	if (count($_POST) > 0 && strlen($_POST["persistformvar"]) == 0) {

		//get the uuid
			if ($action == "update" && isset($_POST["network_uuid"])) {
				$network_uuid = trim($_POST["network_uuid"]);
			}
			else {
				$network_uuid = uuid();
			}

		//check for all required data
			$msg = '';
			if (strlen($network_name) == 0) { $msg .= $text['message-required']." ".$text['label-network_name']."<br>\n"; }
			if (strlen($network_caller) == 0) { $msg .= $text['message-required']." ".$text['label-network_caller']."<br>\n"; }
			if (strlen($network_address) == 0) { $msg .= $text['message-required']." ".$text['label-network_address']."<br>\n"; }

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
				$network_update_time = date('Y-m-d H:i:s');
				$sql = "insert into v_network_cc ";
				$sql .= "(";
				$sql .= "network_uuid, ";
				$sql .= "network_name, ";
				$sql .= "network_caller, ";
				$sql .= "network_address, ";
				$sql .= "network_update_time, ";
				$sql .= "network_enabled, ";
				$sql .= "network_description ";
				$sql .= ") ";
				$sql .= "values ";
				$sql .= "(";
				$sql .= "'".uuid()."', ";
				$sql .= "'$network_name', ";
				$sql .= "'$network_caller', ";
				$sql .= "'$network_address', ";
				$sql .= "'$network_update_time', ";
				$sql .= "'$network_enabled', ";
				$sql .= "'$network_description' ";
				$sql .= ")";
				$db->exec(check_sql($sql));
				unset($sql, $network_update_time);
			}

		// update
			if ($action == "update" && isset($network_uuid)) {
				// get data by uuid
				$sql = "select * from v_network_cc where network_uuid='".$network_uuid."' limit 1";
				$prep_statement = $db->prepare(check_sql($sql));
				$prep_statement->execute();
				$result = $prep_statement->fetchAll(PDO::FETCH_NAMED);
				foreach ($result as &$row) {
					$tmp_network_caller = $row["network_caller"];
					$tmp_network_address = $row["network_address"];
					$tmp_network_enabled = $row["network_enabled"];
					break; //limit to 1 row
				}
				unset ($sql, $prep_statement, $result);

				// 校验禁用
				if ($tmp_network_enabled == 'true' && $network_enabled == 'false' && strlen($tmp_network_caller) > 0) {
					$sql = "select count(route_uuid) as num_rows from v_landing_route where network_uuid = '$network_uuid' ";
					$prep_statement = $db->prepare($sql);
					$prep_statement->execute();
					$row = $prep_statement->fetch(PDO::FETCH_ASSOC);
					if ($row['num_rows'] > 0) {
						$msg = "更新失败";
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
				}

				// 更新数据
				$network_update_time = date('Y-m-d H:i:s');
				$sql = "update v_network_cc set network_name='$network_name', network_caller='$network_caller', ";
				$sql .= "network_address='$network_address', ";
				$sql .= "network_update_time='$network_update_time', network_enabled='$network_enabled', ";
				$sql .= "network_description='$network_description' where network_uuid='$network_uuid'";
				$db->exec(check_sql($sql));
				unset($sql, $network_update_time);

				if ($network_enabled == 'true' && (($tmp_network_caller != $network_caller) || ($tmp_network_address != $network_address)) {
					$redis = new Redis();
					$redis->connect($rds_ip, $rds_port);
					$redis->auth($rds_password);
					$redis->select($rds_db);
					$redis->lpush($rds_pbx_rule_watch, time());
					unset($redis);
				}

				unset($tmp_network_caller, $tmp_network_enabled);
			}

		//redirect the user
			if ($action == "add") {
				messages::add($text['message-add']);
			}
			if ($action == "update") {
				messages::add($text['message-update']);
			}
			header("Location: network.php");
			return;

	} //(count($_POST) > 0 && strlen($_POST["persistformvar"]) == 0)

//show the content
	echo "<form method='post' name='frm' action=''>\n";
	echo "<table width='100%' border='0' cellpadding='0' cellspacing='0'>\n";
	echo "<tr>\n";
	if ($action == "add") {
		echo "<td align='left' width='30%' nowrap='nowrap' valign='top'><b>".$text['header-network-cc-add']."</b></td>\n";
	}
	if ($action == "update") {
		echo "<td align='left' width='30%' nowrap='nowrap' valign='top'><b>".$text['header-network-cc-edit']."</b></td>\n";
	}
	echo "<td width='70%' align='right' valign='top'>";
	echo "	<input type='button' class='btn' alt='".$text['button-back']."' onclick=\"window.location='network.php'\" value='".$text['button-back']."'>";
	echo "	<input type='submit' class='btn' value='".$text['button-save']."'>\n";
	echo "</td>\n";
	echo "</tr>\n";
	echo "<tr>\n";
	echo "<td align='left' colspan='2'>\n";
	echo "</td>\n";
	echo "</tr>\n";

	// network_name
	echo "<tr>\n";
	echo "<td class='vncellreq' valign='top' align='left' nowrap='nowrap'>\n";
	echo "	".$text['label-network_name']."\n";
	echo "</td>\n";
	echo "<td class='vtable' align='left'>\n";
	echo "	<input class='formfld' type='text' name='network_name' maxlength='255' value=\"".escape($network_name)."\" required='required'>\n";
		echo "<br />\n";
		echo $text['description-network_name']."\n";
	echo "</td>\n";
	echo "</tr>\n";

	// network_caller
	echo "<tr>\n";
	echo "<td class='vncellreq' valign='top' align='left' nowrap='nowrap'>\n";
	echo "	".$text['label-network_caller']."\n";
	echo "</td>\n";
	echo "<td class='vtable' align='left'>\n";
	echo "	<input class='formfld' type='text' name='network_caller' value=\"".escape($network_caller)."\">\n";
		echo "<br />\n";
		echo $text['description-network_caller']."\n";
	echo "</td>\n";
	echo "</tr>\n";

	// network_caller
	echo "<tr>\n";
	echo "<td class='vncellreq' valign='top' align='left' nowrap='nowrap'>\n";
	echo "	".$text['label-network_address']."\n";
	echo "</td>\n";
	echo "<td class='vtable' align='left'>\n";
	echo "	<input class='formfld' type='text' name='network_address' value=\"".escape($network_address)."\">\n";
		echo "<br />\n";
		echo $text['description-network_address']."\n";
	echo "</td>\n";
	echo "</tr>\n";

	// network_enabled
	echo "<tr>\n";
	echo "<td class='vncell' valign='top' align='left' nowrap='nowrap'>\n";
	echo "	".$text['label-network_enabled']."\n";
	echo "</td>\n";
	echo "<td class='vtable' align='left'>\n";
	echo "	<select class='formfld' name='network_enabled'>\n";
	switch ($network_enabled) {
		case "true" :	$selected[1] = "selected='selected'";	break;
		default :	$selected[2] = "selected='selected'";	break;
	}
	echo "	<option value='true' ".$selected[1].">".$text['label-true']."</option>\n";
	echo "	<option value='false' ".$selected[2].">".$text['label-false']."</option>\n";
	unset($selected);
	echo "	</select>\n";
	echo "<br />\n";
	echo $text['description-network_enabled']."\n";
	echo "</td>\n";
	echo "</tr>\n";

	// network_description
	echo "<tr>\n";
	echo "<td class='vncell' valign='top' align='left' nowrap='nowrap'>\n";
	echo "	".$text['label-network_description']."\n";
	echo "</td>\n";
	echo "<td class='vtable' align='left'>\n";
	echo "	<input class='formfld' type='text' name='network_description' maxlength='255' value=\"".escape($network_description)."\" >\n";
	echo "<br />\n";
	echo $text['description-network_description']."\n";
	echo "</td>\n";
	echo "</tr>\n";

	echo "	<tr>\n";
	echo "		<td colspan='2' align='right'>\n";
	if ($action == "update") {
		echo "		<input type='hidden' name='network_uuid' value='".escape($network_uuid)."'>\n";
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
