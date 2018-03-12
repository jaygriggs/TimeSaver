<?php
// $Id:
?>
<style>
<!--
#dsidebar-left { display: none; }
-->
</style>
<link type="text/css" rel="stylesheet" href="<?php print $yui_base_url; ?>/calendar/assets/calendar.css">
<link type="text/css" rel="stylesheet" href="<?php print $yui_base_url; ?>/assets/skins/sam/skin.css">
<link type="text/css" rel="stylesheet" href="<?php print $yui_base_url; ?>/container/assets/container.css">
<script type="text/javascript" src="<?php print $yui_base_url; ?>/yuiloader/yuiloader.js"></script>

<br>
<div style="margin-left:15px"><?php print $tshome; ?> - <?php print t('Timesheet Entry'); ?></div>
<br>

<div class="statusmessage" id="statusmessages" style="display:none"></div>
<br>
<form method=post id="frm_timesheet">
<div id="timesheetdisplay">
<?php print $timesheet?>
</div>
<input type="hidden" name="start_date" id="start_date" value="<?php print $start_date; ?>">
<input type="hidden" name="end_date" id="end_date" value="<?php print $end_date; ?>">
<input type="hidden" name="approved_by" id="approved_by" value="<?php print $approved_by; ?>">
<input type='hidden' name='emp' id='emp' value='<?php print $emp; ?>'>
</form>

<BR>

<div id="deletemessage" style="overflow:hidden;background-color:#FFFFFF;visibility:hidden;">
    <div class="hd" style="color:black;background-color:#e0ecd5"><?php print t('Delete these items?'); ?></div>
    <div class="bd">
        <div style="padding-top:10px;font-size:8pt;font-family:Verdana;width:100%;text-align:center">
            <?php print t('This will delete the selected entries.'); ?>
            <br>
            <input type="button" value="Continue With Delete?" onclick="YAHOO.nextide.container.panel1.hide();delete_entries();"> &nbsp;&nbsp;<input type="button" value="Cancel" onclick="YAHOO.nextide.container.panel1.hide();">
        </div>
    </div>
    <div class="ft"></div>
</div>

<div id="errormessage" style="overflow:hidden;background-color:#FFFFFF;visibility:hidden;">
    <div class="hd" style="color:black;background-color:#e0ecd5"><?php print t('There has been an error'); ?></div>
    <div class="bd" style="font-weight:bold;font-size:12pt;color:red"><br>
        <div id="errormessage_content" style="padding-top:10px;font-size:8pt;font-family:Verdana;width:100%;text-align:center">
        </div>
        <div style="text-align:center;font-size:9pt"><input type="button" value="Continue" onclick="YAHOO.nextide.container.panel2.hide();"></div>
        </br><br>
    </div>
    <div class="ft"></div>
</div>



<div id="commententrypanel"  style="overflow:hidden;background-color:#FFFFFF;visibility:hidden;">
    <div class="hd" style="color:black;background-color:#e0ecd5"><?php print t('Comment Entry/Edit'); ?></div>
    <div class="bd" style="font-weight:bold;font-size:12pt;color:red"><br>
        <div id="" style="padding-top:10px;font-size:8pt;font-family:Verdana;width:100%;text-align:center">
            <textarea <?php print $disable_on_lock; ?> id="commententryarea" name="commententryarea" rows="5" cols="75" wrap="on">
            </textarea>
            <input type="hidden" name="hiddenTextboxName" id="hiddenTextboxName">
            <br>
            <input type="button" value="Submit Comment" onclick="submitComment();" style="<?php print $disable; ?>">
        </div>

    </div>
    <div class="ft"></div>
</div>

<div id="savepanel"  style="overflow:hidden;background-color:#FFFFFF;visibility:hidden;">
    <div class="hd" style="color:black;background-color:#e0ecd5"><?php print t('Saving the timesheet... please wait....'); ?></div>
    <div class="bd" style="font-weight:bold;font-size:12pt;color:red"><br>
        <div id="savestatus" style="padding-top:10px;font-size:8pt;font-family:Verdana;width:100%;text-align:center">
            	<?php print t('Saving.. please wait'); ?>
        </div>

    </div>
    <div class="ft"></div>
</div>

<div id="rejectionreasonpanel" style="overflow:hidden;background-color:#FFFFFF;visibility:hidden;">
    <div class="hd" style="color:black;background-color:#e0ecd5"><?php print t('Rejection Reason'); ?></div>
    <div class="bd" style="font-weight:bold;font-size:12pt;color:red"><br>
        <div id="rejectionreason" style="padding-top:10px;font-size:8pt;font-family:Verdana;width:100%;text-align:center">

        </div>
        <br>
    </div>
    <div class="ft"></div>
</div>

<script language="javascript">
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

function cmtpanel() {
        YAHOO.namespace("nextide.container");
        YAHOO.nextide.container.panel3 = new YAHOO.widget.Panel("commententrypanel", { width:"600px", visible:false, constraintoviewport:true, x:300, y:600, modal:false } );
        YAHOO.nextide.container.panel3.render();
        YAHOO.nextide.container.panel3.center();
    }

function rejectionreasonpanel() {
        YAHOO.namespace("nextide.container");
        YAHOO.nextide.container.panel5 = new YAHOO.widget.Panel("rejectionreasonpanel", { width:"600px", visible:false, constraintoviewport:true, x:300, y:600, modal:false } );
        YAHOO.nextide.container.panel5.render();
        YAHOO.nextide.container.panel5.center();
    }

function savepanel() {
        YAHOO.namespace("nextide.container");
        YAHOO.nextide.container.panel6 = new YAHOO.widget.Panel("savepanel", { width:"600px", visible:false, constraintoviewport:true, x:300, y:600, modal:false } );
        YAHOO.nextide.container.panel6.render();
        YAHOO.nextide.container.panel6.center();
    }
var useYuiLoader=true;
if (useYuiLoader) {
    (function() {
        var loader = new YAHOO.util.YUILoader({
            base: '<?php print $yui_base_url; ?>'+'/',
            require: ["animation","calendar","connection","container","containercore","dom","menu","element","event","yahoo","yuiloader"],
            loadOptional: true,
            filter: "MIN",
            allowRollup: true,
            onFailure: function(o) {
                  alert("The required javascript libraries could not be loaded.  Please refresh your page and try again.");
            },
            onSuccess: function() {
                init_entry_page();
            }
        });
    // Load the files using the insert() method.
    loader.insert();
    })();
}else {
    init_entry_page();
}

</script>