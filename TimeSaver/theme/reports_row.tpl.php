<?php
// $Id:
?>
    <tr >
        <td colspan="5">&nbsp;</td>
    </tr>
    <tr>
        <td colspan="5" class="actionLabel"><?php print t('Choose a report to run:'); ?></td>
    </tr>

    <tr>
        <td colspan="5">
            <table>
                <tr><td nowrap=true>
                    <input type=button value="<?php print $report_by_employee_label; ?>" onclick="document.getElementById('empreportdateranges').innerHTML='';document.getElementById('reportbyemployee_status').innerHTML='';document.getElementById('byEmpsdate').value='';;document.getElementById('byEmpedate').value='';document.getElementById('byEmpGoButton').style.display='none';YAHOO.nextide.container.panel20.show();document.getElementById('reportbyemployee_status').innerHTML='';" id="reportByEmployeeButton" style='display:none'>


                </td></tr>
</table>
        </td>
    </tr>
