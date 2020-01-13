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
	require_once "resources/redis.php";
	require_once "resources/require.php";
	require_once "resources/check_auth.php";

//check permissions
	if (permission_exists('mobile_code_add') || permission_exists('mobile_code_edit')) {
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
		$mobile_prefix = trim($_REQUEST["id"]);
	}
	else {
		$action = "add";
	}

//get data by mobile_prefix
	if (count($_GET) > 0 && isset($mobile_prefix)) {
		$sql = "select * from v_mobile_code where mobile_prefix='".$mobile_prefix."' limit 1";
		$prep_statement = $db->prepare(check_sql($sql));
		$prep_statement->execute();
		$result = $prep_statement->fetchAll(PDO::FETCH_NAMED);
		foreach ($result as &$row) {
			$mobile_prefix = $row["mobile_prefix"];
			$province = $row["province"];
			$city = $row["city"];
			$fixed_code = $row["fixed_code"];
			$mobile_isp = $row["mobile_isp"];
			break; //limit to 1 row
		}
		unset ($prep_statement);
	}

//get http post variables and set them to php variables
	if (count($_POST) > 0) {

		//set the variables
			$mobile_prefix = trim($_POST["mobile_prefix"]);
			$province = trim($_POST["province"]);
			$city = trim($_POST["city"]);
			$fixed_code = trim($_POST["fixed_code"]);
			$mobile_isp = trim($_POST["mobile_isp"]);
	}

//process the http post 
	if (count($_POST) > 0 && strlen($_POST["persistformvar"]) == 0) {

		//get the uuid
			if ($action == "update" && isset($_POST["mobile_prefix"])) {
				$mobile_prefix = trim($_POST["mobile_prefix"]);
			}
			else {
				$mobile_prefix = trim($_POST["mobile_prefix"]);
			}

		//check for all required data
			$msg = '';
			if (strlen($mobile_prefix) == 0) { $msg .= $text['message-required']." ".$text['label-mobile_prefix']."<br>\n"; }
			if (strlen($fixed_code) == 0) { $msg .= $text['message-required']." ".$text['label-fixed_code']."<br>\n"; }

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
				$sql = "insert into v_mobile_code ";
				$sql .= "(";
				$sql .= "mobile_prefix, ";
				$sql .= "province, ";
				$sql .= "city, ";
				$sql .= "fixed_code, ";
				$sql .= "mobile_isp ";
				$sql .= ") ";
				$sql .= "values ";
				$sql .= "(";
				$sql .= "'$mobile_prefix', ";
				$sql .= "'$province', ";
				$sql .= "'$city', ";
				$sql .= "'$fixed_code', ";
				$sql .= "'$mobile_isp' ";
				$sql .= ")";
				$db->exec(check_sql($sql));
				unset($sql);

				$redis->hset($rds_pbx_mobile_code, $mobile_prefix, $fixed_code);
			}

		// update
			if ($action == "update" && isset($mobile_prefix)) {
				
				$sql = "update v_mobile_code set province='$province', city='$city', ";
				$sql .= "fixed_code='$fixed_code', mobile_isp='$mobile_isp' where mobile_prefix='$mobile_prefix'";
				$db->exec(check_sql($sql));
				unset($sql);

				$redis->hset($rds_pbx_mobile_code, $mobile_prefix, $fixed_code);
			}

		//redirect the user
			if ($action == "add") {
				messages::add($text['message-add']);
			}
			if ($action == "update") {
				messages::add($text['message-update']);
			}
			header("Location: mobile_code.php");
			return;

	} //(count($_POST) > 0 && strlen($_POST["persistformvar"]) == 0)

//show the header
	require_once "resources/header.php";
	
//show the content
	echo "<form method='post' name='frm' action=''>\n";
	echo "<table width='100%' border='0' cellpadding='0' cellspacing='0'>\n";
	echo "<tr>\n";
	if ($action == "add") {
		echo "<td align='left' width='30%' nowrap='nowrap' valign='top'><b>".$text['header-mobile-code-add']."</b></td>\n";
	}
	if ($action == "update") {
		echo "<td align='left' width='30%' nowrap='nowrap' valign='top'><b>".$text['header-mobile-code-edit']."</b></td>\n";
	}
	echo "<td width='70%' align='right' valign='top'>";
	echo "	<input type='button' class='btn' alt='".$text['button-back']."' onclick=\"window.location='mobile_code.php'\" value='".$text['button-back']."'>";
	echo "	<input type='submit' class='btn' value='".$text['button-save']."'>\n";
	echo "</td>\n";
	echo "</tr>\n";
	echo "<tr>\n";
	echo "<td align='left' colspan='2'>\n";
	echo "</td>\n";
	echo "</tr>\n";

	// mobile_prefix
	echo "<tr>\n";
	echo "<td class='vncellreq' valign='top' align='left' nowrap='nowrap'>\n";
	echo "	".$text['label-mobile_prefix']."\n";
	echo "</td>\n";
	echo "<td class='vtable' align='left'>\n";
	if ($action == "add") {
		echo "	<input class='formfld' type='text' name='mobile_prefix' maxlength='255' value=\"".escape($mobile_prefix)."\" required='required'>\n";
		echo "<br />\n";
		echo $text['description-mobile_prefix']."\n";
	}
	if ($action == "update") {
		echo "	<input class='formfld' type='text' name='mobile_prefix' maxlength='255' value=\"".escape($mobile_prefix)."\" readonly='readonly'>\n";
		echo "<br />\n";
		echo $text['description-mobile_prefix'].$text['description-readonly']."\n";
	}
		
	echo "</td>\n";
	echo "</tr>\n";

	// province
	echo "<tr>\n";
	echo "<td class='vncell' valign='top' align='left' nowrap='nowrap'>\n";
	echo "	".$text['label-province']."\n";
	echo "</td>\n";
	echo "<td class='vtable' align='left'>\n";
	echo "	<input class='formfld' type='text' name='province' value=\"".escape($province)."\">\n";
		echo "<br />\n";
		echo $text['description-province']."\n";
	echo "</td>\n";
	echo "</tr>\n";

	// city
	echo "<tr>\n";
	echo "<td class='vncell' valign='top' align='left' nowrap='nowrap'>\n";
	echo "	".$text['label-city']."\n";
	echo "</td>\n";
	echo "<td class='vtable' align='left'>\n";
	echo "	<input class='formfld' type='text' name='city' value=\"".escape($city)."\">\n";
		echo "<br />\n";
		echo $text['description-city']."\n";
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

	// mobile_isp
	echo "<tr>\n";
	echo "<td class='vncell' valign='top' align='left' nowrap='nowrap'>\n";
	echo "	".$text['label-mobile_isp']."\n";
	echo "</td>\n";
	echo "<td class='vtable' align='left'>\n";
	echo "	<select class='formfld' name='mobile_isp' id='mobile_isp'>\n";
	switch ($mobile_isp) {
		case "1" : 	$selected[1] = "selected='selected'";	break;
		case "2" : 	$selected[2] = "selected='selected'";	break;
		case "3" : 	$selected[2] = "selected='selected'";	break;
	}
	echo "	<option value='1' ".$selected[1].">".$text['label-yi_dong']."</option>\n";
	echo "	<option value='2' ".$selected[2].">".$text['label-lian_tong']."</option>\n";
	echo "	<option value='3' ".$selected[3].">".$text['label-dian_xin']."</option>\n";
	unset($selected);
	echo "	</select>\n";
	echo "<br />\n";
	echo $text['description-mobile_isp']."\n";
	echo "</td>\n";
	echo "</tr>\n";

	echo "	<tr>\n";
	echo "		<td colspan='2' align='right'>\n";
	if ($action == "update") {
		echo "		<input type='hidden' name='mobile_prefix' value='".escape($mobile_prefix)."'>\n";
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
