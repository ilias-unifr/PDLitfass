<?php

include_once("./Services/UIComponent/classes/class.ilUserInterfaceHookPlugin.php");
 
/**
 * Adapted from Example user interface plugin
 *
 * @author Marko Glaubitz, Johannes Heim <ilias@rz.uni-freiburg.de>
 * @version $Id$
 *
 */
class ilPDLitfassPlugin extends ilUserInterfaceHookPlugin
{
	function getPluginName()
	{
		return "PDLitfass";
	}

	protected static $config_cache;

	protected static $plugin_cache;

/**

	        * @return ilCtrlMainMenuPlugin
         */
        public static function getInstance() {
                if (!isset(self::$plugin_cache)) {
                        self::$plugin_cache = new ilPDLitfassPlugin();
                }

                return self::$plugin_cache;
        }


        protected function uninstallCustom()
        {
		global $ilDB;
        	$ilDB->dropTable('ui_uihk_litfass_config');				
        }




	protected function getRoles() 
	{
		$rbacreview;
		$roles = $rbacreview->getRolesByFilter(2, $ilUser->getId());
		return roles;
	}

}

?>
