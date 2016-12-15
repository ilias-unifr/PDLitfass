<?php
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




?>
