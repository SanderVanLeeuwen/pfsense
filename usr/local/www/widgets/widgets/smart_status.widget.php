<?php
/*
	smart_status.widget.php
	Copyright (C) 2013-2015 Electric Sheep Fencing, LP
	Copyright 2012 mkirbst @ pfSense Forum
	Part of pfSense widgets (https://www.pfsense.org)
	All rights reserved.

	Redistribution and use in source and binary forms, with or without
	modification, are permitted provided that the following conditions are met:

	1. Redistributions of source code must retain the above copyright notice,
	   this list of conditions and the following disclaimer.

	2. Redistributions in binary form must reproduce the above copyright
	   notice, this list of conditions and the following disclaimer in the
	   documentation and/or other materials provided with the distribution.

	THIS SOFTWARE IS PROVIDED ``AS IS'' AND ANY EXPRESS OR IMPLIED WARRANTIES,
	INCLUDING, BUT NOT LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY
	AND FITNESS FOR A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE
	AUTHOR BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY,
	OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF
	SUBSTITUTE GOODS OR SERVICES; LOSS OF USE, DATA, OR PROFITS; OR BUSINESS
	INTERRUPTION) HOWEVER CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN
	CONTRACT, STRICT LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE)
	ARISING IN ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE
	POSSIBILITY OF SUCH DAMAGE.
*/

require_once("guiconfig.inc");
require_once("pfsense-utils.inc");
require_once("functions.inc");
require_once("/usr/local/www/widgets/include/smart_status.inc");
?>

<table class="table table-striped">
	<thead>
		<tr>
			<th></th>
			<th><?=gettext("Drive")?></th>
			<th><?=gettext("Ident")?></th>
			<th><?=gettext("SMART Status")?></th>
		</tr>
	</thead>
	<tbody>
<?php
$devs = array();
## Get all adX, daX, and adaX (IDE, SCSI, and AHCI) devices currently installed
$devs = get_smart_drive_list();

foreach($devs as $dev):
	$dev_ident = exec("diskinfo -v /dev/$dev | grep ident	| awk '{print $1}'"); ## get identifier from drive
	$dev_state = trim(exec("smartctl -H /dev/$dev | awk -F: '/^SMART overall-health self-assessment test result/ {print $2;exit}
/^SMART Health Status/ {print $2;exit}'")); ## get SMART state from drive
	switch ($dev_state) {
		case "PASSED":
		case "OK":
			$icon = 'ok';
			break;
		case "":
			$dev_state = "Unknown";
			$icon = 'question';
			break;
		default:
			$icon = 'remove';
			break;
	}
?>
		<tr>
			<td><i class="icon icon-<?=$icon?>-sign"></i></td>
			<td><?=$dev?></td>
			<td><?=$dev_ident?></td>
			<td><?=ucfirst($dev_state)?></td>
		</tr>
<?php endforeach; ?>
	</tbody>
</table>
