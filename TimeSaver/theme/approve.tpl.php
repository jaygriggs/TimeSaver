<?php
// $Id:
?>
<style>
#timesheetdisplay {
min-width:1500px;
margin-left:auto;
margin-right:auto;
}
	
</style>

<link type="text/css" rel="stylesheet" href="<?php print $yui_base_url; ?>/calendar/assets/calendar.css">
<link type="text/css" rel="stylesheet" href="<?php print $yui_base_url; ?>/assets/skins/sam/skin.css">
<link type="text/css" rel="stylesheet" href="<?php print $yui_base_url; ?>/container/assets/container.css">
<script type="text/javascript" src="<?php print $yui_base_url; ?>/yuiloader/yuiloader.js"></script>
<div><?php print $tshome?> - <?php print $approval_url; ?></div>
<div>(Showing the last 60 days of 2-week approval windows)</div>
<div class="statusmessage" id="approvalstatusmessages" style="display:none"></div>
<div class="statusmessage" id="statusmessages" style="display:none">test</div>
<br>
<div id="suppressdisplayall" style="display:none">
<table id="approvalAllTable" style="<?php print $approval_all_table_style; ?>">
<tr>
    <td style="text-align:center"><input type="checkbox" id="chkAll" onclick="checkAll(this.checked)"><?php print t('Click here to toggle all Approved on/off'); ?></td>
</tr>
<tr>
    <td style="text-align:center"><input type="button" id="go" value="Approve all checked" onclick="approveAllChecked()"></td>
</tr>

</table>
</div>
<table id="filterTable" style="<?php print $approval_all_table_style; ?>">
    <tr>
        <td style="display:none"><?php print $filter_complete_out; ?><input type=checkbox <?php print $hide_fully_approved_check; ?> onclick="document.location='<?php print $site_url; ?>/index.php?q=timesaver_approvals&emp=<?php print $emp; ?>&start_date=<?php print $start_date; ?>&end_date=<?php print $end_date; ?>&hidefullyapproved='+this.checked"></td>
    </tr>
</table>

<table id="userinformation" style="<?php print $userinformation_table_style; ?>" class="userInformationTable">
<tr>
    <td><?php print $employee_label; ?></td>
    <td><?php print $emp_name?></td>
</tr>
<tr>
    <td><?php print $employee_number; ?></td>
    <td><?php print $emp_number; ?></td>
</tr>
<tr>
    <td><?php print $employee_supervisor; ?></td>
    <td><?php print $emp_sup; ?></td>
</tr>

</table>

<form id="frm_timesheet" name="frm_timesheet" >
<div id="timesheetdisplay">
<?php print $approval_rows; ?>
</div>
<input type='hidden' name='emp' id='emp' value='<?php print $emp; ?>'>
<input type="hidden" name="start_date" id="start_date" value="<?php print $start_date; ?>">
<input type="hidden" name="end_date" id="end_date" value="<?php print $end_date; ?>">
<input type="hidden" name="approved_by" id="approved_by" value="<?php print $approved_by; ?>">

</form>

<div id="rejectMessage" style="overflow:hidden;background-color:#FFFFFF;visibility:hidden;">
    <div class="hd" style="color:black;background-color:#e0ecd5"><?php print $reject_panel_title; ?></div>
    <div class="bd" style="font-weight:bold;font-size:12pt;color:red"><br>
        <div id="" style="padding-top:10px;font-size:8pt;font-family:Verdana;width:100%;text-align:center">
            <textarea id="rejectentryarea" name="rejectentryarea" rows="5" cols="75" wrap="on"></textarea>
            <input type="hidden" name="hiddenTextboxName" id="hiddenTextboxName"><input type="hidden" name="hiddenUID" id="hiddenUID"><input type="hidden" name="hiddenROW" id="hiddenROW">
            <br>
            <input type="button" value="Reject" onclick="reject_single_item(document.getElementById('hiddenTextboxName').value,true,document.getElementById('hiddenUID').value);" >
        </div>

    </div>
    <div class="ft"></div>
</div>

<div id="commententrypanel"  style="overflow:hidden;background-color:#FFFFFF;visibility:hidden;">
    <div class="hd" style="color:black;background-color:#e0ecd5"><?php print t('Comment'); ?></div>
    <div class="bd" style="font-weight:bold;font-size:12pt;color:red"><br>
        <div id="" style="padding-top:10px;font-size:8pt;font-family:Verdana;width:100%;text-align:center">
            <textarea <?php print $comment_disable; ?> id="commententryarea" name="commententryarea" rows="5" cols="75" wrap="on">
            </textarea>
            <input type="hidden" name="hiddenTextboxName" id="hiddenTextboxName">
            <br>
            <input type="button" value="<?php print t('Submit'); ?>" onclick="submitComment();" style="<?php print $comment_edit_disable; ?>">
        </div>

    </div>
    <div class="ft"></div>
</div>
<div id="deletemessage" style="overflow:hidden;background-color:#FFFFFF;visibility:hidden;">
    <div class="hd" style="color:black;background-color:#e0ecd5"><?php print t('Delete these items?'); ?></div>
    <div class="bd">
        <div style="padding-top:10px;font-size:8pt;font-family:Verdana;width:100%;text-align:center">
            <?php print t('This will delete this entry permanently'); ?>
            <br>
            <input type="button" value="<?php print t('Continue with Delete'); ?>" onclick="YAHOO.nextide.container.panel1.hide();delete_entries();"> &nbsp;&nbsp;<input type="button" value="<?php print t('Cancel'); ?>" onclick="YAHOO.nextide.container.panel1.hide();">
        </div>
    </div>
    <div class="ft"></div>
</div>

<div id="errormessage" style="overflow:hidden;background-color:#FFFFFF;visibility:hidden;">
    <div class="hd" style="color:black;background-color:#e0ecd5"><?php print $error_message_panel_title; ?></div>
    <div class="bd" style="font-weight:bold;font-size:12pt;color:red"><br>
        <div id="errormessage_content" style="padding-top:10px;font-size:8pt;font-family:Verdana;width:100%;text-align:center">
        </div>
        <div style="text-align:center;font-size:9pt"><input type="button" value="<?php print $continue_button; ?>" onclick="YAHOO.nextide.container.panel2.hide();"></div>
        </br><br>
    </div>
    <div class="ft"></div>
</div>


<div id="savepanel"  style="overflow:hidden;background-color:#FFFFFF;visibility:hidden;">
    <div class="hd" style="color:black;background-color:#e0ecd5"><?php print t('Saving the timesheet... please wait....'); ?></div>
    <div class="bd" style="font-weight:bold;font-size:12pt;color:red"><br>
        <div id="savestatus" style="padding-top:10px;font-size:8pt;font-family:Verdana;width:100%;text-align:center">
            	Saving.. please wait
        </div>

    </div>
    <div class="ft"></div>
</div>

<script language="javascript">

function closepanel10() {
    var x=document.getElementById('hiddenROW');
    var y=document.getElementById('chkreject' + x.value);
    y.checked=false;
    YAHOO.nextide.container.panel10.cfg.setProperty("visible", false);
}

function rejectpanel() {
        YAHOO.namespace("nextide.container");
        YAHOO.nextide.container.panel10 = new YAHOO.widget.Panel("rejectMessage", { width:"600px", visible:false, constraintoviewport:true, x:300, y:600, modal:true } );
        YAHOO.nextide.container.panel10.render();
        YAHOO.nextide.container.panel10.center();
        YAHOO.nextide.container.panel10.hideEvent.subscribe(closepanel10);

    }

function cmtpanel() {
        YAHOO.namespace("nextide.container");
        YAHOO.nextide.container.panel3 = new YAHOO.widget.Panel("commententrypanel", { width:"600px", visible:false, constraintoviewport:true, x:300, y:600, modal:true } );
        YAHOO.nextide.container.panel3.render();
        YAHOO.nextide.container.panel3.center();
    }

function delmsgpanel() {
        YAHOO.namespace("nextide.container");
        YAHOO.nextide.container.panel1 = new YAHOO.widget.Panel("deletemessage", { width:"600px", visible:false, constraintoviewport:true, x:300, y:600, modal:true } );
        YAHOO.nextide.container.panel1.render();
        YAHOO.nextide.container.panel1.center();
    }

function errmsgpanel() {
        YAHOO.namespace("nextide.container");
        YAHOO.nextide.container.panel2 = new YAHOO.widget.Panel("errormessage", { width:"600px", visible:false, constraintoviewport:true, x:300, y:600, modal:true } );
        YAHOO.nextide.container.panel2.render();
        YAHOO.nextide.container.panel2.center();
    }


function savepanel() {
        YAHOO.namespace("nextide.container");
        YAHOO.nextide.container.panel6 = new YAHOO.widget.Panel("savepanel", { width:"600px", visible:false, constraintoviewport:true, x:300, y:600, modal:true } );
        YAHOO.nextide.container.panel6.render();
        YAHOO.nextide.container.panel6.center();
    }
useYuiLoader=true;
if (useYuiLoader) {
    (function() {
        var loader = new YAHOO.util.YUILoader({
            base: '<?php print $yui_base_url; ?>/',
            require: ["animation","calendar","connection","container","containercore","dom","menu","element","event","yahoo","yuiloader"],
            loadOptional: true,
            filter: "MIN",
            allowRollup: true,
            onFailure: function(o) {
                  alert("The required javascript libraries could not be loaded.  Please refresh your page and try again.");
            },
            onSuccess: function() {
                init_approval_page();
            }
        });

    // Load the files using the insert() method.
    loader.insert();
    })();
}else {
    init_approval_page();
}
</script>