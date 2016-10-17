<?php


include_once("./Services/UIComponent/classes/class.ilUIHookPluginGUI.php");
include_once("class.ilPDLitfassConfigGUI.php");

/**
 * Addapted from User interface hook class plugin example
 *
 * @author "Johannes Heim, Marko Glaubitz, <ilias@rz.uni-freiburg.de>"
 * @version $Id$
 * @ingroup ServicesUIComponent
 */


        function showBlock($id)
        {    
                $showBlock =  ilPDLitfassConfigGUI::getConfigValue($id);
    
                if ($showBlock[display]) 
                        return true;
                else
                        return false;
        }


class ilPDLitfassUIHookGUI extends ilUIHookPluginGUI
{

	/**
	 * Modify HTML output of GUI elements. Modifications modes are:
	 * - ilUIHookPluginGUI::KEEP (No modification)
	 * - ilUIHookPluginGUI::REPLACE (Replace default HTML with your HTML)
	 * - ilUIHookPluginGUI::APPEND (Append your HTML to the default HTML)
	 * - ilUIHookPluginGUI::PREPEND (Prepend your HTML to the default HTML)
	 *
	 * @param string $a_comp component
	 * @param string $a_part string that identifies the part of the UI that is handled
	 * @param string $a_par array of parameters (depend on $a_comp and $a_part)
	 *
	 * @return array array with entries "mode" => modification mode, "html" => your html
	 */

	function getHTML($a_comp, $a_part, $a_par = array())
	{

		// add things to the personal desktop overview

		$show_Block = showBlock(ilPDLitfassConfigGUI::getcurrentID());

		if ($a_comp == "Services/PersonalDesktop" && $a_part == "center_column" && $show_Block == true)
		{
			// $a_par["personal_desktop_gui"] is ilPersonalDesktopGUI object
			
			return array("mode" => ilUIHookPluginGUI::PREPEND,
				"html" => $this->getLitfassHTML(ilPDLitfassConfigGUI::getcurrentID()));
		}

		return array("mode" => ilUIHookPluginGUI::KEEP, "html" => "");
	}
	

	/**
	* gather Messages
	*
	* @return string HTML of lifass-Block
	*/
	function getLitfassHTML($id)
	{
	$message =  ilPDLitfassConfigGUI::getConfigValue($id);		
		return $message[message];
	}

	function showBlock($id)
	{	
		$showBlock =  ilPDLitfassConfigGUI::getConfigValue($id);
	
		if ($showBlock[display]) 
			return true;
		else
			return false;
	}

	/**
	 * Modify GUI objects, before they generate ouput
	 *
	 * @param string $a_comp component
	 * @param string $a_part string that identifies the part of the UI that is handled
	 * @param string $a_par array of parameters (depend on $a_comp and $a_part)
	 */
/**	function modifyGUI($a_comp, $a_part, $a_par = array())
	{
		// currently only implemented for $ilTabsGUI
		
		// tabs hook
		// note that you currently do not get information in $a_comp
		// here. So you need to use general GET/POST information
		// like $_GET["baseClass"], $ilCtrl->getCmdClass/getCmd
		// to determine the context.
		if ($a_part == "tabs")
		{
			// $a_par["tabs"] is ilTabsGUI object
			
			// add a tab (always)
			$a_par["tabs"]->addTab("PDLitfass", "PDLitass", "PDLitfass");
		}
	}
*/  
}
?>
