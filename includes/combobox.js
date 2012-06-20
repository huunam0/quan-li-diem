 
    function DropDownTextToBox(objDropdown, strTextboxId,objChild) {
        document.getElementById(strTextboxId).value = objDropdown.options[objDropdown.selectedIndex].value;
        //DropDownIndexClear(objDropdown.id);
        //document.getElementById(strTextboxId).focus();
		<?php
		?>
    }
    function DropDownIndexClear(strDropdownId) {
        if (document.getElementById(strDropdownId) != null) {
            document.getElementById(strDropdownId).selectedIndex = -1;
        }
		//luutru=this.value;
    }
	
	
 
