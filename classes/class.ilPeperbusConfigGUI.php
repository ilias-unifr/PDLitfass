<?php

include_once("./Services/Component/classes/class.ilPluginConfigGUI.php");
 
/**
 *
 * @author Marko Glaubitz, Johannes Heim <ilias@rz.uni-freiburg.de>
 * @version $Id$
 *
 */
class ilPeperbusConfigGUI extends ilPluginConfigGUI
{
	/**
	* Handles all commmands, default is "configure"
	*/
	function performCommand($cmd)
	{

		switch ($cmd)
		{
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
		global $ilCtrl;
		
		$pl = $this->getPluginObject();
	
		include_once("Services/Form/classes/class.ilPropertyFormGUI.php");
		$form = new ilPropertyFormGUI();
	
		
		
		// Show Block?
		$cb = new ilCheckboxInputGUI($pl->txt("show_block"), "show_block");
		$cb -> setValue(0);
		$cb->setChecked($this->getConfigValue('show_block', false));
		$form->addItem($cb);

		
		// Peperbus Info message
		$peperbus_message = new ilTextInputGUI($pl->txt("peperbus_message"), "peperbus_message");
		$peperbus_message->setRequired(true);
		$peperbus_message->setMaxLength(300);
		$peperbus_message->setSize(60);
		$peperbus_message->setValue($this->getConfigValue('peperbus_message')); //??
		$form->addItem($peperbus_message);

		// Save Button
			
		$form->addCommandButton("save", $lng->txt("save"));
	                
		$form->setTitle($pl->txt("peperbus_configuration"));
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
			$peperbus_message = $form->getInput("peperbus_message");
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


		protected function storeConfiguration($name, $value)
		{
			global $ilDB;
			
			if($this->getConfigValue($name, false) === false)
				$sql = "INSERT INTO `ui_uihk_peperbus_config` (`name`,`value`)
						VALUES (
							{$ilDB->quote($name, "text")},
							{$ilDB->quote($value, "text")})";
			else
				$sql = "UPDATE `ui_uihk_peperbus_config`
				SET `value` = {$ilDB->quote($value, "text")}
				WHERE `name` = {$ilDB->quote($name, "text")}";
			return $ilDB->manipulate($sql);
		}
		
		protected function getConfiguration($name, $default = '')
		{ 
			global $ilDB;
			$sql = "SELECT `value` 
					FROM `ui_uihk_peperbus_config`
					WHERE `name` = {$ilDB->quote($name, "text")}";

			$result = $ilDB->query($sql);
			$row = $ilDB->fetchObject($result);
		
			if(!$row)
				return $default;
			else
				return $row->value;
			}
}
}
?>
