<#1>
<?php
	$fields = array(
		'id'=>array(
			'type'=>'integer',
			'length'=>2
		),
		'display'=>array(
			'type'=>'integer',
			'length'=>2
		),
		'message'=>array(
			'type'=>'text',
			'length'=>300
		),
		'title'=>array(
			'type'=>'text',
			'length'=>300
		),
	);
	
	$ilDB->createTable('ui_uihk_litfass_config', $fields);
	$ilDB->addPrimaryKey('ui_uihk_litfass_config', array('id'));
	$ilDB->createSequence('ui_uihk_litfass_config');
?>
