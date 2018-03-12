<?php
// $Id:
?>


<link type="text/css" rel="stylesheet" href="<?php print $yui_base_url; ?>/calendar/assets/calendar.css">
<link type="text/css" rel="stylesheet" href="<?php print $yui_base_url; ?>/assets/skins/sam/skin.css">
<link type="text/css" rel="stylesheet" href="<?php print $yui_base_url; ?>/container/assets/container.css">
<script type="text/javascript" src="<?php print $yui_base_url; ?>/yuiloader/yuiloader.js"></script>
<?php print $js ?>
<br>
Please choose what you would like to do
<br>
<br>
<form method=get id="frmMain" name="frmMain">
<input type="hidden" name="q" id="q" value="">
<table>
    <tr>
        <td colspan="5" class="actionLabel">Your Timesheets:</td>
    </tr>
    <tr >
        <td>Start Date:</td>
        <td><div  class="popupcal" id="cal1" style="position:absolute"></div>
            <input type="text" size="8" name="start_date" id="start_date" readonly value="">
        </td>
        <td>End Date:</td>
        <td><div  class="popupcal" id="cal2" style="position:absolute"></div>
            <input type="text" size="8" name="end_date" id="end_date" readonly value="">
        </td>
        <td>
            <input type="button" id="enter_my_timesheet" style="display:none" value="View/Begin Timesheet Entry" onclick="checkFields('start_date,end_date',document.getElementById('frmMain'),'index.php','timesaver_entry');">

        </td>
    </tr>
    <tr>
        <td colspan=5>&nbsp;<span id="span_dummy1">Choose a start date to begin your timesheet entry...</span></td>
    </tr>

    <tr <?php print $approval_row; ?>>
        <td colspan="5" class="actionLabel">&nbsp;</td>
    </tr>


    <tr <?php print $approval_row; ?>>
        <td colspan="5" class="actionLabel">Enter a timesheet for someone else:</td>
    </tr>
    <tr <?php print $approval_row; ?>>
        <td>Start Date:</td>
        <td><div  class="popupcal" id="startdate2" style="position:absolute"></div>
            <input type="text" size="8" name="start_date2" id="start_date2" readonly value="">
        </td>
        <td>End Date:</td>
        <td><div  class="popupcal" id="enddate2" style="position:absolute"></div>
            <input type="text" size="8" name="end_date2" id="end_date2" readonly value="">
        </td>
        <td>
           <select name="otheremp" id="otheremp" onchange="if (document.getElementById('end_date2').value!='') {document.location='index.php?q=timesaver_entry&emp='+this.value+'&start_date=' + document.getElementById('start_date2').value+'&end_date=' + document.getElementById('end_date2').value + '&showAsTimesheet=1&sup=<?php print $uid?>'}else { alert('Choose date range first.');}">
           <?php print $other_employee_dropdown; ?>
           </select>
        </td>
    </tr>

    <tr <?php print $approval_row; ?>>
        <td colspan="5" class="actionLabel">&nbsp;</td>
    </tr>

    <tr <?php print $approval_row; ?>>
        <td colspan="5" class="actionLabel">Approve Timesheets:</td>
    </tr>
    <tr <?php print $approval_row; ?>>
        <td>Your Employees:</td>
        <td><select id="emp" name="emp" onchange="document.getElementById('emp').disabled=false;checkFields('emp',document.getElementById('frmMain'),'index.php','timesaver_approvals');">
        <?php print $employee_dropdown; ?>
        </select></td>

    </tr>
    <tr>
      
        <td></td>
        <td></td>
        <td></td>
    </tr>

    <?php print $reports_row; ?>


</table>
</form>

<div id="reportbyemployeepanel" style="height:250px;background-color:#FFFFFF;visibility:hidden;">
    <div class="hd" style="color:black;background-color:#e0ecd5"><?php print $report_by_employee_label; ?></div>
    <div class="bd" style="font-weight:bold;font-size:9pt;text-align:center"><br>

            <!-- custom generation parameters -->
            <div  class="popupcal" id="cal3" style="position:absolute"></div>
            Start Date: <input type="text" name="byEmpsdate" id="byEmpsdate" readonly  size=7>&nbsp;
            <div  class="popupcal" id="cale3" style="position:absolute"></div>
            End  Date: <input type="text" name="byEmpedate" id="byEmpedate" readonly  size=7><br><br>
             <div id="empreportdateranges">&nbsp;</div>
             <div id="whichsupervisortorunas" style="display:<?php print $is_finance_show_supervisors; ?>"><?php print "For Which Employee?"; ?><select name="whichmanager" id="whichmanager"><?php print $get_supervisors; ?></select></div>
             <div id="entryfilter"><?php print $show_unapproved; ?><input type="checkbox" id="byEmpShowUnapproved" checked>&nbsp;&nbsp;<?php print $show_rejected; ?><input type="checkbox" id="byEmpShowRejected"></div>
             <div><input type=button value="<?php print $report_by_employee_go; ?>" onclick="runByEmployeeReport();" id="byEmpGoButton" style="display:none"></div>
            <br><br>

            <div style="text-align:center;font-size:9pt" id="reportbyemployee_status"></div>

            <br><br>
    </div>
    <div class="ft"></div>
</div>

<div id="reportbyprojectpanel" style="height:250px;background-color:#FFFFFF;visibility:hidden;">
    <div class="hd" style="color:black;background-color:#e0ecd5"><?php print $report_by_project_label; ?></div>
    <div class="bd" style="font-weight:bold;font-size:9pt;text-align:center"><br>
            <!-- custom generation parameters -->
            <div  class="popupcal" id="cal5" style="position:absolute"></div>

            Start Date: <input type="text" name="byProjectsdate" id="byProjectsdate" readonly  size=7>&nbsp;
            <div  class="popupcal" id="cale5" style="position:absolute"></div>
            End  Date: <input type="text" name="byProjectedate" id="byProjectedate" readonly  size=7><br><br>

             <div id="projectreportdateranges">&nbsp;</div>
             <div id="whichsupervisortorunas3" style="display:<?php print $is_finance_show_supervisors; ?>"><?php print $for_which_supervisor; ?><select name="whichmanagerproject" id="whichmanagerproject"><?php print $get_supervisors; ?></select></div>

             <div id="whichprojecttorun" style="display:<?php print $is_finance_show_supervisors; ?>"><?php print $run_for_which_project; ?><select name="whichproject" id="whichproject"><?php print $get_projects; ?></select></div>

             <div id="entryfilter2"><?php print $show_unapproved; ?><input type="checkbox" id="byProjectShowUnapproved" checked>&nbsp;&nbsp;<?php print $show_rejected; ?><input type="checkbox" id="byProjectShowRejected"></div>
             <div><input type=button value="<?php print $report_by_project_go; ?>" onclick="runByProjectReport();" id="byProjectGoButton" style="display:none"></div>
            <br><br>

            <div style="text-align:center;font-size:9pt" id="reportbyproject_status"></div>

            <br><br>
    </div>
    <div class="ft"></div>
</div>

<div id="reportbyfreeformpanel" style="height:250px;background-color:#FFFFFF;visibility:hidden;">
    <div class="hd" style="color:black;background-color:#e0ecd5"><?php print $report_by_free_form_label; ?></div>
    <div class="bd" style="font-weight:bold;font-size:9pt;text-align:center"><br>

            <!-- custom generation parameters -->
            <div  class="popupcal" id="cal6" style="position:absolute"></div>


            Start Date: <input type="text" name="byFreeFormsdate" id="byFreeFormsdate" readonly  size=7>&nbsp;
            <div  class="popupcal" id="cale6" style="position:absolute"></div>
            End  Date: <input type="text" name="byFreeFormedate" id="byFreeFormedate" readonly  size=7><br><br>


             <div id="freeformreportdateranges">&nbsp;</div>
             <div id="whichsupervisortorunas5" style="display:<?php $is_finance_show_supervisors; ?>"><?php print $for_which_supervisor; ?><select name="whichmanagerfreeform" id="whichmanagerfreeform"><?php print $get_supervisors; ?></select></div>
             <div id="entryfilter4"><?php print $show_unapproved; ?><input type="checkbox" id="byFreeFormShowUnapproved" checked>&nbsp;&nbsp;<?php print $show_rejected; ?><input type="checkbox" id="byFreeFormShowRejected"></div>
             <div><input type=button value="<?php print $report_by_free_form_go; ?>" onclick="runByFreeFormReport();" id="byFreeFormGoButton" style="display:none"></div>
            <br><br>

            <div style="text-align:center;font-size:9pt" id="reportbyfreeform_status"></div>

            <br><br>
    </div>
    <div class="ft"></div>
</div>

<script language="Javascript">
var enableautoenddate=false;
if (true) {
        var timesaver_loader = new YAHOO.util.YUILoader({
            base: '<?php print $yui_base_url; ?>/',
            require: ["containercore","container","animation","calendar","connection","dom","menu","element","event","yahoo","yuiloader"],
            loadOptional: true,
            filter: "MIN",
            allowRollup: false,
            onFailure: function(o) {
                  alert("The required javascript libraries could not be loaded.  Please refresh your page and try again.");
            },
            onSuccess: function() {
                init_index_page();
            }
        });
    // Load the files using the insert() method.
    timesaver_loader.insert();

}else {
    init_index_page();
}
</script>
