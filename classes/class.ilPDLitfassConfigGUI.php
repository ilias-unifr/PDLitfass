<?php

include_once("./Services/Component/classes/class.ilPluginConfigGUI.php");

require_once('./Services/Form/classes/class.ilPropertyFormGUI.php');
require_once('./Services/Component/classes/class.ilPluginConfigGUI.php');
require_once('./Services/Utilities/classes/class.ilConfirmationGUI.php');
require_once('./Customizing/global/plugins/Services/UIComponent/UserInterfaceHook/PDLitfass/classes/class.ilPDLitfassFunctions.php');



#require_once('./Services/Component/classes/class.ilPluginConfigGUI.php');
#require_once('./Services/Form/classes/class.ilPropertyFormGUI.php');
#require_once('./Services/Utilities/classes/class.ilConfirmationGUI.php');
#require_once('./Services/jQuery/classes/class.iljQueryUtil.php');



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
		global $ilCtrl, $lng, $ilDB;
		
		$pl = $this->getPluginObject();

		$id = $this->getcurrentID();

		$config_values = $this->getConfigValue($id);
	
		include_once("Services/Form/classes/class.ilPropertyFormGUI.php");
		$form = new ilPropertyFormGUI();
		
		// Show message id
		$message_id = new ilNonEditableValueGUI($pl->txt("message_id"), "message_id");
		$message_id->setValue("test ");
		$form->addItem($message_id);

		
		// Show Block?
		$cb = new ilCheckboxInputGUI($pl->txt("show_block"), "show_block");
		$cb -> setValue(1);
		//$checked = $this->getConfigValue($id); 
		$cb->setChecked($config_values[display]);
		$form->addItem($cb);
		
		//PDLitfass Info Title
                $litfass_title =  new ilTextInputGUI($pl->txt("litfass_title"), "litfass_title");
                $litfass_title->setRequired(true);
                //$littitle = $this->getConfigValue($id);
                $litfass_title->setValue($config_values[title]);
                $form->addItem($litfass_title);
		
		// PDLitfass Info message
		$litfass_message = new ilTextAreaInputGUI($pl->txt("litfass_message"), "litfass_message");
//		$litfass_message = new ilPageEditorGUI($pl->txt("litfass_message"), "litfass_message");
		$litfass_message->setRequired(true);
		//$litmessage =	$this->getConfigValue($id);
		$litfass_message->setValue($config_values[message]);
		$form->addItem($litfass_message);

		// Save Button
		$form->addCommandButton("save", $lng->txt("save"));
		$form->setTitle($pl->txt("litfass_configuration"));
		$form->setFormAction($ilCtrl->getFormAction($this));

		//Role Selector

                $global_roles = self::getRoles(ilRbacReview::FILTER_ALL_GLOBAL);
                $locale_roles = self::getRoles(ilRbacReview::FILTER_ALL_LOCAL);
		$employee =	new ilCheckboxInputGUI($pl->txt("employee"), "employee");
		$employee -> setValue(1);
		//$checked = $this->getConfigValue($id);
		$employee->setChecked($config_values[employee]);
		$form->addItem($employee);	
		
                $student =     new ilCheckboxInputGUI($pl->txt("student"), "student");
                $student -> setValue(1);
                //$checked = $this->getConfigValue($id);
                $student->setChecked($config_values[student]);
                $form->addItem($student); 	

		//Role Mapping
		
			//Employees

                $employee_roles = new ilTextInputGUI($pl->txt("employee_roles"), "employee_roles");
                $employee_roles->setRequired(true);
                //$eroles =   $this->getConfigValue($id);
                $employee_roles->setValue($config_values[eroles]);
                $form->addItem($employee_roles);

			//Students
	        $students_roles = new ilTextInputGUI($pl->txt("students_roles"), "students_roles");
                $students_roles->setRequired(true);
                //$sroles =   $this->getConfigValue($id);
                $students_roles->setValue($config_values[sroles]);
                $form->addItem($students_roles);

	                        //Students
                $info = new ilNonEditableValueGUI($pl->txt("info"), "info");
                //$sroles =   $this->getConfigValue($id);

		$roles = getRoles(2,1);
		//print_r( $roles);

                $info->setValue(implode("  |   ",$roles));
                $form->addItem($info);

               //  radio position
                $rad = new ilRadioGroupInputGUI($lng->txt("lit_position"), "position");
                $rad_op1 = new ilRadioOption($lng->txt("lit_left"), "left");

                $rad->addOption($rad_op1);
                $rad_op2 = new ilRadioOption($lng->txt("lit_center"), "center");
                $rad->addOption($rad_op2);
                $rad_op3 = new ilRadioOption($lng->txt("lit_right"), "right");
		$rad->addOption($rad_op3);
		$rad->setValue($config_values[position]);

		$form->addItem($rad);
		return $form;
	}
	
	/**
	 * Save form input 	 *
	 */

	public function save()
	{
		global $tpl, $lng, $ilCtrl, $ilDB;
	
		$pl = $this->getPluginObject();
		
		$form = $this->initConfigurationForm();
		if ($form->checkInput())
		{
			$litfass_message = $form->getInput("litfass_message");
			$litfass_title = $form->getInput("litfass_title"); 	
			$cb = $form->getInput("show_block");
			$id = $ilDB->nextID('ui_uihk_litfass_config');				
			$employee = $form->getInput("employee");
			$student = $form->getInput("student");

			$eroles = $form->getInput("employee_roles");
			$sroles = $form->getInput("students_roles");

			$position = $form->getInput("position");
			
			echo $position;
			// store Values

			$this->storeConfigValue($id, $cb, $litfass_message, $litfass_title,$employee,$student,$eroles,$sroles,$position);	
			ilUtil::sendSuccess($pl->txt("saving_invoked"), true);
			$ilCtrl->redirect($this, "configure");
		}
		else
		{
			$form->setValuesByPost();
			$tpl->setContent($form->getHtml());
		}
	}


		protected function storeConfigValue($id, $display, $litfass_message, $litfass_title, $employee, $student, $eroles, $sroles,$position)
		{
			global $ilDB;
			
		//	if($this->getConfigValue('1'))
				$sql = "INSERT INTO `ui_uihk_litfass_config` (`id`,`display`,`message`, `title`, `employee`,`student`,`eroles`,`sroles`,`position`)
						VALUES (
							{$ilDB->quote($id, "text")},
							{$ilDB->quote($display, "text")},
							{$ilDB->quote($litfass_message, "text")},
							{$ilDB->quote($litfass_title, "text")},
							{$ilDB->quote($employee, "text")},
							{$ilDB->quote($student, "text")},
							{$ilDB->quote($eroles, "text")},
							{$ilDB->quote($sroles, "text")},
							{$ilDB->quote($position, "text")})";
			
		//	else
		//		$sql = "UPDATE `ui_uihk_litfass_config`
		//		SET `message` = {$ilDB->quote($message, "text")}
		//		WHERE `id` = {$ilDB->quote($id, "text")}";
			return $ilDB->manipulate($sql);
			
		}
		
		public function getConfigValue($id)
		{ 
			if (!$id)
				$id = 1;
 
			global $ilDB;
	
			$sql = $ilDB->query("SELECT *   
					FROM `ui_uihk_litfass_config`
					WHERE `id` = $id");

			$row = $ilDB->fetchAssoc($sql);
			//print_r($row);	
			return $row;	

		}

		public function getcurrentID()
		{
  
                         global $ilDB;
         
                         $sql = $ilDB->query("SELECT *   
                                         FROM `ui_uihk_litfass_config_seq`
                                         ");
 
                         $row = $ilDB->fetchAssoc($sql);
                         //print_r($row);        
                         return $row[sequence]; 
		}

		public function initPermissionSelectionForm() {
                $this->pl = ilPDLitfassPlugin::getInstance();
		$pl = $this->getPluginObject();
		$global_roles = self::getRoles(ilRbacReview::FILTER_ALL_GLOBAL);
                $locale_roles = self::getRoles(ilRbacReview::FILTER_ALL_LOCAL);
                $ro = new ilRadioGroupInputGUI($this->pl->txt('permission_type'), 'permission_type');
                $ro->setRequired(true);
                foreach (ctrlmmMenu::getAllPermissionsAsArray() as $k => $v) {
                        $option = new ilRadioOption($v, $k);
                        switch ($k) {
                                case ctrlmmMenu::PERM_NONE :
                                        break;
                                case ctrlmmMenu::PERM_ROLE :
                                case ctrlmmMenu::PERM_ROLE_EXEPTION :
                                        $se = new ilMultiSelectInputGUI($this->pl->txt('perm_input'), 'permission_' . $k);
                                        $se->setWidth(400);
                                        $se->setOptions($global_roles);
                                        $option->addSubItem($se);
                                        // Variante mit MultiSelection
                                        $se = new ilMultiSelectInputGUI($this->pl->txt('perm_input_locale'), 'permission_locale_' . $k);
                                        $se->setWidth(400);
                                        $se->setOptions($locale_roles);
                                        // $option->addSubItem($se);
                                        // Variante mit TextInputGUI
                                        $te = new ilTextInputGUI($this->pl->txt('perm_input_locale'), 'permission_locale_' . $k);
                                        $te->setInfo($this->pl->txt('perm_input_locale_info'));
                                        $option->addSubItem($te);
                                        break;
                                case ctrlmmMenu::PERM_REF_WRITE :
                                case ctrlmmMenu::PERM_REF_READ :
                                        $te = new ilTextInputGUI($this->pl->txt('perm_input'), 'permission_' . $k);
                                        $option->addSubItem($te);
                                        break;
                                case ctrlmmMenu::PERM_USERID :
                                        $te = new ilTextInputGUI($this->pl->txt('perm_input_user'), 'permission_user_' . $k);
                                        $te->setInfo($this->pl->txt('perm_input_user_info'));
                                        $option->addSubItem($te);
                                        break;
                        }
                        $ro->addOption($option);
                }
               // $this->addItem($ro);
		return $ro;
        }

       
	 public static function getRoles($filter, $with_text = true) {
                global $rbacreview;
                $opt = array();
                $role_ids = array();
                foreach ($rbacreview->getRolesByFilter($filter) as $role) {
                        $opt[$role['obj_id']] = $role['title'] . ' (' . $role['obj_id'] . ')';
                        $role_ids[] = $role['obj_id'];
                }
                if ($with_text) {
                        return $opt;
                } else {
                        return $role_ids;
                }
        }

        public static function getAllPermissionsAsArray() {
                $fooClass = new ReflectionClass('ctrlmmMenu');
                $names = array();
                foreach ($fooClass->getConstants() as $name => $value) {
                        $b = strpos($name, 'PERM_REF_') === false;
                        if (strpos($name, 'PERM_') === 0) {
                                $names[$value] = ilCtrlMainMenuPlugin::getInstance()->txt(strtolower($name));
                        }
                }

                return $names;
        }


}
?>
