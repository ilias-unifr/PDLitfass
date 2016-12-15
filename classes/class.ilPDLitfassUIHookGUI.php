<?php


include_once("./Services/UIComponent/classes/class.ilUIHookPluginGUI.php");
include_once("class.ilPDLitfassConfigGUI.php");
include_once("./Services/AccessControl/classes/class.ilRbacReview.php");

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
   		$student  = $showBlock[student];
		//echo  "student: " .$student;
		$employee = $showBlock[employee];
		//echo  "employee: " .$employee;
		$showBlockforRole = 0;

		//1= Mitarbeiter, 2=Studierende
		$whichRole = whichRole($id);
		//echo "Rolle: " . $whichRole;

		
                if ($showBlock[display] & ($showBlockforRole = 1)) 
		{
                	if (($whichRole == 2 & $student == 1))
			{
				return true;
			}
			if (($whichRole ==1 & $employee == 1))
			{
				return true;
			}
		}
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

//	$this->allow_moving = true;
	function getHTML($a_comp, $a_part, $a_par = array())
	{

		// add things to the personal desktop overview

		$show_Block = showBlock(ilPDLitfassConfigGUI::getcurrentID());
		
		
		if ($a_comp == "Services/PersonalDesktop" && $a_part == "center_column" && $show_Block == true)
		{
			// $a_par["personal_desktop_gui"] is ilPersonalDesktopGUI object
			
	            	global  $ilUser;
			global $rbacreview;
                    	$userid =  $ilUser->getId();
	//	    echo $userid;
			$assignedRoles = $rbacreview->assignedRoles($userid);
 			//print_r($assignedRoles);

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

		$pl = $this->getPluginObject();
		$db_row =  ilPDLitfassConfigGUI::getConfigValue($id);		
		$btpl = $pl->getTemplate("tpl.pdlitfass_block.html");	
		$btpl->setVariable("TITLE", $db_row[title]);
		$btpl->setVariable("PDLITFASS_MESSAGE", $db_row[message]);

		return $btpl->get();
	}


}
	           	global  $ilUser;
                   	global $rbacreview;
			global $roleid;



function getRoles($filter, $with_text = true) {
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

function whichRole($id) {
			global $default_role;
			global $ilUser;
			global $assignedRoles;
			global $rbacreview;
			
                    	$userid =  $ilUser->getId();
			
	           $assignedRoles = $rbacreview->assignedRoles($userid,1);
// print_r($assignedRoles);

	//	$needles_array = array("2", "238");
                $configValues =  ilPDLitfassConfigGUI::getConfigValue($id);

	  	$needles = ($configValues[eroles]);
		$needles_array = explode(",",$needles);

		              
                $sneedles = ($configValues[sroles]);
                $sneedles_array = explode(",",$sneedles);

//		print_r($needles_array);


		if (in_array_any($needles_array, $assignedRoles)){
//			echo "Rolle Mitarbeiter";
			return 1;
		}
//		$needles = array("266");
                if (in_array_any($sneedles_array, $assignedRoles)){
  //                      echo "Rolle Studierende";
                        return 2;
                } 

		}
function in_array_any($needles, $haystack) {
	   return !!array_intersect($needles, $haystack);
	}

?>
