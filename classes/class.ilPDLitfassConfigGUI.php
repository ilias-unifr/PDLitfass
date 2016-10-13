<?php

include_once("./Services/Component/classes/class.ilPluginConfigGUI.php");
 
/**
 *
 * @author Marko Glaubitz, Johannes Heim <ilias@rz.uni-freiburg.de>
 * @version $Id$
 *
 */
class ilPDLitfassConfigGUI extends ilPluginConfigGUI
{
	/**
	* Handles all commmands, default is "configure"
	*/
	function performCommand($cmd)
	{

		switch ($cmd)
		{
			case "default":
				$this->$cmd();
				break;
			case "configure":
			case "save":
				$this->$cmd();
				break;

		}
	}

	/**
	 * Configure screen
	 */
	function configure()
	{
		global $tpl;

		$form = $this->initConfigurationForm();
		$tpl->setContent($form->getHTML());
	}
	
	//
	
	/**
	 * Init configuration form.
	 *
	 * @return object form object
	 */
	public function initConfigurationForm()
	{
		global $ilCtrl, $lng;
		
		$pl = $this->getPluginObject();
	
		include_once("Services/Form/classes/class.ilPropertyFormGUI.php");
		$form = new ilPropertyFormGUI();
	
		
		
		// Show Block?
		$cb = new ilCheckboxInputGUI($pl->txt("show_block"), "show_block");
		$cb -> setValue(1);
		$cb->setChecked($this->getConfigValue('show_block', false));
		$form->addItem($cb);

		
		// PDLitfass Info message
		$litfass_message = new ilTextInputGUI($pl->txt("litfass_message"), "litfass_message");
		$litfass_message->setRequired(true);
		$litfass_message->setMaxLength(300);
		$litfass_message->setSize(60);
		$litfass_message->setValue($this->getConfigValue('litfass_message')); //??
		$form->addItem($litfass_message);

		// Save Button
			
		$form->addCommandButton("save", $lng->txt("save"));
	                
		$form->setTitle($pl->txt("litfass_configuration"));
		$form->setFormAction($ilCtrl->getFormAction($this));
		
		return $form;
	}
	
	/**
	 * Save form input 	 *
	 */
	public function save()
	{
		global $tpl, $lng, $ilCtrl;
	
		$pl = $this->getPluginObject();
		
		$form = $this->initConfigurationForm();
		if ($form->checkInput())
		{
			$litfass_message = $form->getInput("litfass_message");
			$cb = $form->getInput("show_block");
	
			
			ilUtil::sendSuccess($pl->txt("saving_invoked"), true);
			$ilCtrl->redirect($this, "configure");
		}
		else
		{
			$form->setValuesByPost();
			$tpl->setContent($form->getHtml());
		}
	}


		protected function storeConfigValue($name, $value)
		{
			global $ilDB;
			
			if($this->getConfigValue($name, false) === false)
				$sql = "INSERT INTO `ui_uihk_litfass_config` (`name`,`value`)
						VALUES (
							{$ilDB->quote($name, "text")},
							{$ilDB->quote($value, "text")})";
			else
				$sql = "UPDATE `ui_uihk_litfass_config`
				SET `value` = {$ilDB->quote($value, "text")}
				WHERE `name` = {$ilDB->quote($name, "text")}";
			return $ilDB->manipulate($sql);
		}
		
		protected function getConfigValue($name, $default = '')
		{ 
			global $ilDB;
			$sql = "SELECT `value` 
					FROM `ui_uihk_litfass_config`
					WHERE `name` = {$ilDB->quote($name, "text")}";

			$result = $ilDB->query($sql);
			$row = $ilDB->fetchObject($result);
		
			if(!$row)
				return $default;
			else
				return $row->value;
			}

}
?>
