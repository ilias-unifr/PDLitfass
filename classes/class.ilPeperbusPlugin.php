<?php

include_once("./Services/UIComponent/classes/class.ilUserInterfaceHookPlugin.php");
 
/**
 * Adapted from Example user interface plugin
 *
 * @author Marko Glaubitz, Johannes Heim <ilias@rz.uni-freiburg.de>
 * @version $Id$
 *
 */
class ilPeperbusPlugin extends ilUserInterfaceHookPlugin
{
	function getPluginName()
	{
		return "Peperbus";
	}
}

?>
