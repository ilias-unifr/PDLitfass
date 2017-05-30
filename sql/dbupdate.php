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
		'roles'=>array(
			'type'=>'text',
			'length'=>15
		),
		'employee'=>array(
			'type'=>'integer',
			'length'=>2
		),
               'student'=>array(
               		'type'=>'integer',
               		'length'=>2
                ),
               'eroles'=>array(
                        'type'=>'text',
                        'length'=>300
                ),
               'sroles'=>array(
                        'type'=>'text',
                        'length'=>300
                ),	
	       'position'=>array(
                        'type'=>'text',
                        'length'=>10	));
	
	$ilDB->createTable('ui_uihk_litfass_config', $fields);
	$ilDB->addPrimaryKey('ui_uihk_litfass_config', array('id'));
	$ilDB->createSequence('ui_uihk_litfass_config');


?>



<#2>
<?php
if(!$ilDB->tableColumnExists('ui_uihk_litfass_config', 'position'))
{
	$ilDB->addTableColumn('ui_uihk_litfass_config', 'position',
		array(
			'type'    => 'text',
			'length'  => '15'
		)
	);
}
?>

<#3>
<?php
//if(!$ilDB->tableExists('ui_uihk_litfass_messages_ids'))
{
		$fields = array(
		'message_id'=>array(
			'type'	=>	'integer',
			'length'=>	2
		),
		'user_id'=>array(
			'type'	=>'integer',
			'length'=>	2 
		)
	);

	$ilDB->createTable('ui_uihk_litfass_m_ids', $fields);

}
?>
