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
		$cb->setChecked($this->getConfigValue('display', true));
		$form->addItem($cb);

		
		// PDLitfass Info message
		$litfass_message = new ilTextAreaInputGUI($pl->txt("message"), "message");
		$litfass_message->setRequired(true);
		$litfass_message->setValue($this->getConfigValue('message')); //??
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
			$litfass_message = $form->getInput("message");
			$cb = $form->getInput("show_block");
			$this->storeConfigValue("1", "1", $litfass_message);	
			
			ilUtil::sendSuccess($pl->txt("saving_invoked"), true);
			$ilCtrl->redirect($this, "configure");
		}
		else
		{
			$form->setValuesByPost();
			$tpl->setContent($form->getHtml());
		}
	}


		protected function storeConfigValue($id, $display, $message)
		{
			global $ilDB;
			
			if($this->getConfigValue($message, false) === false)
				$sql = "INSERT INTO `ui_uihk_litfass_config` (`id`,`display`,`message`)
						VALUES (
							{$ilDB->quote($id, "text")},
							{$ilDB->quote($display, "text")},
							{$ilDB->quote($message, "text")})";
			else
				$sql = "UPDATE `ui_uihk_litfass_config`
				SET `value` = {$ilDB->quote($value, "text")}
				WHERE `name` = {$ilDB->quote($name, "text")}";
			return $ilDB->manipulate($sql);
		}
		
		protected function getConfigValue($name, $default = '')
		{ 
			global $ilDB;
			$sql = "SELECT * 
					FROM `ui_uihk_litfass_config`
					WHERE `display` = {$ilDB->quote($name, "text")}";

			$result = $ilDB->query($sql);
			$row = $ilDB->fetchObject($result);
		
			if(!$row)
				return $default;
			else
				return $row->value;
			}

}
?>
