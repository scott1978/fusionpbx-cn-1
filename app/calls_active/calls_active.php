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
	Portions created by the Initial Developer are Copyright (C) 2008-2019
	the Initial Developer. All Rights Reserved.

	Contributor(s):
	Mark J Crane <markjcrane@fusionpbx.com>
*/
//includes
	include "root.php";
	require_once "resources/require.php";
	require_once "resources/check_auth.php";

//check permissions
	if (permission_exists('call_active_view')) {
		//access granted
	}
	else {
		echo "access denied";
		exit;
	}

//add multi-lingual support
	$language = new text;
	$text = $language->get();

//get the HTTP values and set as variables
	$show = trim($_REQUEST["show"]);
	if ($show != "all") { $show = ''; }

//show the header
	$document['title'] = $text['title'];
	require_once "resources/header.php";

//load gateways into a session variable
	$sql = "select gateway_uuid, domain_uuid, gateway from v_gateways where enabled = 'true'";
	$prep_statement = $db->prepare($sql);
	if ($prep_statement) {
		$prep_statement->execute();
		$result = $prep_statement->fetchAll(PDO::FETCH_NAMED);
		foreach ($result as $row) {
			$_SESSION['gateways'][$row['gateway_uuid']] = $row['gateway'];
		}
	}
	unset($sql, $prep_statement, $result, $row);

//ajax for refresh
	?>
	<script type="text/javascript">
	//define refresh function, initial start
		var refresh = 1500;
		var source_url = 'calls_active_inc.php?';
		var timer_id;
		<?php
		if ($show == 'all') {
			echo "source_url = source_url + '&show=all';";
		}
		if (isset($_REQUEST["debug"])) {
			echo "source_url = source_url + '&debug';";
		}
		?>
		var ajax_get = function () {
			$.ajax({
				url: source_url, success: function(response){
					$("#ajax_reponse").html(response);
				}
			});
			timer_id = setTimeout(ajax_get, refresh);
		};

		refresh_start();

	//refresh controls
		function refresh_stop() {
			clearTimeout(timer_id);
			document.getElementById('refresh_state').innerHTML = "<img src='resources/images/refresh_paused.png' style='width: 16px; height: 16px; border: none; margin-top: 1px; cursor: pointer;' onclick='refresh_start();' alt=\"<?php echo $text['label-refresh_enable']?>\" title=\"<?php echo $text['label-refresh_enable']?>\">";
		}

		function refresh_start() {
			if (document.getElementById('refresh_state')) { document.getElementById('refresh_state').innerHTML = "<img src='resources/images/refresh_active.gif' style='width: 16px; height: 16px; border: none; margin-top: 3px; cursor: pointer;' alt=\"<?php echo $text['label-refresh_pause']?>\" title=\"<?php echo $text['label-refresh_pause']?>\">"; }
			ajax_get();
		}

	//call controls
		function hangup(uuid) {
			if (confirm("<?php echo $text['confirm-hangup']?>")) {
				send_cmd('calls_exec.php?command=hangup&uuid='+uuid);
			}
		}

		function send_cmd(url) {
			if (window.XMLHttpRequest) {// code for IE7+, Firefox, Chrome, Opera, Safari
				xmlhttp=new XMLHttpRequest();
			}
			else {// code for IE6, IE5
				xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
			}
			xmlhttp.open("GET",url,false);
			xmlhttp.send(null);
			document.getElementById('cmd_reponse').innerHTML=xmlhttp.responseText;
		}

	</script>

<?php
echo "<div id='ajax_reponse'></div>\n";
echo "<div id='time_stamp' style='visibility:hidden'>".date('Y-m-d-s')."</div>\n";
echo "<br><br><br>";

require_once "resources/footer.php";

/*
// deprecated functions for this page

	function get_park_cmd(uuid, context) {
		cmd = \"uuid_transfer \"+uuid+\" -bleg *6000 xml \"+context;
		return escape(cmd);
	}

	function get_record_cmd(uuid, prefix, name) {
		cmd = \"uuid_record \"+uuid+\" start ".$_SESSION['switch']['recordings']['dir']."/".$_SESSION['domain_name']."/archive/".date("Y")."/".date("M")."/".date("d")."/\"+uuid+\".wav\";
		return escape(cmd);
	}
*/
?>
