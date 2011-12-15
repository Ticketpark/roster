<?php

	define('dire', '../../');
	include(dire . '_env/exec.php');
	
	$id = vGET('id');
	$search = vGET('search');
	
	$query = mysql_query('SELECT * FROM `task` WHERE `id`="'.$id.'"') or sqlError(__FILE__,__LINE__,__FUNCTION__);
	$task = mysql_fetch_array($query);
	
	if(isset($search))
		$task = str_replace($search, '<hl>'.$search.'</hl>', $task);

	$tire = array();
	$query = mysql_query('SELECT * FROM `tire`') or sqlError(__FILE__,__LINE__,__FUNCTION__);
	while($fetch=mysql_fetch_array($query))
		$tire[$fetch[0]] = $fetch[1];
	
	$location = array();
	$query = mysql_query('SELECT * FROM `location`') or sqlError(__FILE__,__LINE__,__FUNCTION__);
	while($fetch=mysql_fetch_array($query))
		$location[$fetch[0]] = $fetch[1];
	
	$tasklist = array();
	$query = mysql_query('SELECT * FROM `tasklist`') or sqlError(__FILE__,__LINE__,__FUNCTION__);
	while($fetch=mysql_fetch_array($query))
		$tasklist[$fetch[0]] = $fetch[1];
	
	write_header('Details Auftrag '.$id);
	
	?>
	
			<div class="formpoint">
		
			<h4>Kunde</h4>
		
			<table id="form" width="100%" style="border: 0;">
			
				<tr>
					<td width="20%">Firma</td>
					<td width="30%"><h3><?=$task['company']?></h3></td>
					<td width="20%">Name</td>
					<td width="30%"><h3><?=$task['name']?></h3></td>
				</tr>
				<tr>
					<td>Natel</td>
					<td><h3><?=$task['mobile']?></h3></td>
					<td>Auftragsnr.<br>AllPneu</td>
					<td><h3><?=$task['allpneu_task']?></h3></td>
				</tr>
				
			</table>
			
			</div><div class="formpoint">
			
			<h4>Pneu / Rad</h4>
			
			<table id="form" width="100%" style="border: 0;">
			
				<tr>
					<td style="width: 160px;">Typ</td>
					<td><h3><?=@$tire[$task['tire']]?></h3></td>
				</tr>
				<tr>
					<td style="vertical-align: top;">Standort</td>
					<td><h3><?=@$location[$task['location']]?></h3></td>
				</tr>
				<tr>
					<td style="vertical-align: top;">Auftrag</td>
					<td><h3><ul><?php 
					 				$todo = explode(',', $task['task']);
					 				foreach($todo as $t) {
					 					if($t>0)
					 						print '<li>'.$tasklist[$t].'</li>';
					 				}
					 		?></ul></h3></td>
				</tr>
				
			</table>
			
			</div><div class="formpoint">
			
			<h4>Auftragsinfo</h4>
			
			<table id="form" width="100%" style="border: 0;">
			
				<tr>
					<td style="width: 160px;">Termin</td>
					<td><h3><?=date('d.m.Y H:i', $task['duetime'])?></h3></td>
				</tr>
				<tr>
					<td style="vertical-align: top;">Reservieren</td>
					<td><h3><?=$task['reserve']?></h3></td>
				</tr>
				<tr>
					<td style="vertical-align: top;">Info an</td>
					<td><h3><?=$task['infouser']?></h3></td>
				</tr>
				<tr>
					<td style="vertical-align: top;">Bemerkungen</td>
					<td><h3><?=$task['comments']?></h3></td>
				</tr>
				
			</table>
								
			</div>
	
	<?php
	
	write_footer();
	
?>