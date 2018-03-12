/*
Timesaver module Javascript library
*/

/* $Id:  */
var roundval=0.25;
var lasttshtml='';
var approvaltimer=0;
var savetimer;
var v1, v4, v5, v6, v7, ve3,ve4,ve6,ve7 ;

function checkFields(csv,formObject,action,qvalue) {
    var splitted;
    var x,cntr,flag;
    splitted=csv.split(',');
    flag=0;
    for (cntr=0;cntr<splitted.length;cntr++) {
        try{
            x=document.getElementById(splitted[cntr]);
            if (x.value=='')  flag=1;
        }catch(e) {}
    }
    if (flag) {
        alert(Drupal.t('There are missing dates. Please Try again.'));
    }else {
        formObject.q.value=qvalue;
        formObject.action=action;
        formObject.submit();
    }
}



//used as a flag to notify the end user that there are changes on the page
function changeflag() {
    var x,y;
    x=document.getElementById('changes_flag');
    y=document.getElementById('statusmessages');
    x.value=1;
    y.style.display="";
    y.innerHTML=Drupal.t("There have been changes.  Please save your changes before exiting this page.");
}

function delete_entries() {
    //we are looking for all items prefixed with CHK from 0 to the max number provided to us....
    YAHOO.nextide.container.panel1.center();
    var w,x,y,z,idlist,rowlist,parentTable,approvaluser,emp;
    idlist='';
    rowlist='';
    var maxcnt=document.getElementById('max_row_number').value;
    for (cntr=0;cntr<maxcnt;cntr++) {
        x=document.getElementById('chk'+cntr);
        if (x.checked) {
            y=document.getElementById('id'+cntr);
            if (y.value!='') {
                if (idlist!='') idlist+=",";
                idlist +=y.value;
            }
            if (rowlist!='') rowlist+=",";
            rowlist +=cntr;
        }
    }
    //we now simply purge the rowlist items from the display alltogether
    //however we have to ajax delete the idlist and then ditch them from the display too..
    //we'll just regenerate the timesheet...
    if (idlist=='' && rowlist=='') {
        document.getElementById('errormessage_content').innerHTML=Drupal.t("There are no items to remove");
        YAHOO.nextide.container.panel2.show();
    }else {
        approvaluser=document.getElementById('approved_by').value;
        emp=document.getElementById('emp').value;
        var postvars='&op=delete_entries&list=' + idlist + '&start_date=' + document.getElementById('start_date').value;
        postvars += '&end_date=' + document.getElementById('end_date').value + '&emp=' + emp + '&approved_by=' + approvaluser;
        YAHOO.util.Connect.asyncRequest('POST', ajax_url, {success:delete_entries_success,argument: { },timeout:50000}, postvars);
    }
}

function delete_entries_success(o) {
    if (o.responseXML != undefined) {
        var tsreplace=document.getElementById('timesheetdisplay');
        var x=o.argument[0];
        var info=o.argument[1];
        var html='';
        var root = o.responseXML.documentElement;
        var output = root.getElementsByTagName('output');
        var error = root.getElementsByTagName('error');
        for (cntr=0;cntr<output[0].childNodes.length;cntr++) {
            html +=output[0].childNodes[cntr].nodeValue;
        }
        tsreplace.innerHTML=html;
        html='';
        for (cntr=0;cntr<error[0].childNodes.length;cntr++) {
            html +=error[0].childNodes[cntr].nodeValue;
        }
        document.getElementById('statusmessages').style.display='';
        document.getElementById('statusmessages').innerHTML=html;
    }
}

//insert a new row essentially at the TOP of the secondary list of elements to enter data into...
//this may seem like a complex function, but its VERY simple to add rows dynamically this way
//this is a very specific function for this particular instance of the Timesaver application
function insertTask(datestamp,rownumber,tester) {
        var allfields="id,uid,datestamp,isSecondaryRow,skip,chk,timesaver_activity_id,project_id,regHourSelect,regMinSelect,regAMSelect,regMilitary,regular_time,time_1_5,time_2_0,evening_hours,stat_time,vacation_time_used,sick_time,bereavement,jury_duty,other,comment,totalhrs,OThrs,unpaid_hrs";
        var fieldArray=allfields.split(",");
        var td,input,select,originalSelect;
        var x=document.getElementById('row'+rownumber);
        var tdForRowspan=document.getElementById('rowspancolumn'+rownumber);
        var nextRowNumber=new Number(document.getElementById('max_row_number').value);
        var currentSpan=tdForRowspan.rowSpan;
        var newrow;
        if (tester=='') {
            document.getElementById('max_row_number').value=nextRowNumber+1;
            tdForRowspan.rowSpan=currentSpan+1;
            var parentTable=x.parentNode;
            //test to see where we should insert this row...
            var test=true;
            var nextdt;
            var testcntr=1;
            var thisdt=document.getElementById('datestamp'+rownumber).value;
            var stuff=0;
            var a,b;
            while (test) {
                a=new Number(rownumber);
                stuff=a+testcntr;
                try{
                    var nextdt=document.getElementById('datestamp'+stuff).value;
                }catch(e) {
                    test=false;
                }
                if (nextdt!=thisdt) {
                    test=false;
                }else {
                    if (test)
                        testcntr+=1;
                }
            }
            //end test
            newrow = parentTable.insertRow(x.rowIndex+testcntr);
            newrow.className="newrow";
            newrow.id="row"+ nextRowNumber;
            //we now have a special format here where we add 1 row with proper elements to the row
            //first 4 are hidden
            for (cntr=0;cntr<fieldArray.length;cntr++) {  //just loop the number of fields
                td=newrow.insertCell(-1);   //insert new cell in the next physical position
                if (cntr<4 ) {//hidden control fields
                    td.style.display="none";
                }//end if cntr<4
                if (cntr>4) {
                    td.className="entryRowMiddleCell";
                }
                if (cntr==5) {
                    td.className="entryRowLeftCell";
                }
                if (cntr==fieldArray.length) { //last field
                    td.className="entryRowRightCell";
                }
                if (cntr!=4 && cntr!=5 && cntr!=6 && cntr!=7 && cntr!=8 ) { //skip this if its the spacing cell, checkbox or drop downs
                    if (cntr==20) {//comment field
                        input=generateInput(fieldArray[cntr]+nextRowNumber,10,'','text',true);
                        input.onclick= function() { commentPanel(this) } ;
                    }else {
                        if (cntr==3) {//this is the "isSecondaryRow" column that is a subtle control to let us know that this is not the primary row.
                            input=generateInput(fieldArray[cntr]+nextRowNumber,2,'1','text','');  //we're putting a '1' in the value for this field to say YES this is a secondary row
                        }else if (cntr==2) {//datestamp column......
                            input=generateInput(fieldArray[cntr]+nextRowNumber,2,datestamp,'text','');  //fill this column with the datestamp from this row
                        }else {
                            input=generateInput(fieldArray[cntr]+nextRowNumber,2,'','text','');//just create an empty textbox
                        }
                    }
                    td.appendChild(input); //add this input box to the new TD
                }else {//we still need drop downs and checkboxes though!
                    if (cntr==5) {  //add a checkbox
                        input=generateInput(fieldArray[cntr]+nextRowNumber,1,'','checkbox','');
                        td.appendChild(input);
                    }else if (cntr>5) {//these are the drop downs which we'll just use the top row to copy them from
                        //for items 6, 7, 8, we're going to use row 0's selects and just copy them
                        //originalSelect=document.getElementById(fieldArray[cntr]+x.rowIndex);  //grab the parent's row's select boxes
                        originalSelect=document.getElementById(fieldArray[cntr]+rownumber);  //grab the parent's row's select boxes
                        select=originalSelect.cloneNode(true);
                        select.name=fieldArray[cntr]+nextRowNumber;
                        select.id=fieldArray[cntr]+nextRowNumber;
                        select.readOnly=false;
                        select.disabled=false;
                        if (cntr==6) {  //if this is the Timesaver activity column
                        select.onchange=function() {
                                                        saveTimesheet2();
                                                        selectProject(this.value,nextRowNumber);
                                                        selectTask(this.value,nextRowNumber);
                                                   };
                        }
                        if (cntr==7) {//if this is the project column
                            td.id="projectTD"+nextRowNumber;
                        }
                        if (cntr==8) {//if this is the task column
                            td.id="taskTD"+nextRowNumber;
                        }
					
						
                        td.appendChild(select);
                    }//end else for checkboxes
                }//end if cntr!=4
            }//end for loop
        }else {
            alert(Drupal.t("This entry is locked."));
        }
}//end function

//generates an input tag
function generateInput(name, size, value,type, readonly) {
    var newinput=document.createElement("input");
    newinput.type=type
    newinput.name=name;
    newinput.id=name;
    newinput.size=size;
    newinput.value=value;
    if (readonly) newinput.readOnly=true; 
    if (type=='text' && !readonly) {
            newinput.onchange=function() { changeflag();  roundVal(this); };
    }else {
        newinput.onchange=changeflag;
    }

    return newinput;
	
}


var postSaveTarget;




$("#input").on("keypress keyup",function(){
    if($(this).val() == '0'){
      $(this).val('');  
    }
});
 
 
 
function postSaveExit() {
    window.location.href = postSaveTarget;
}

function saveTimesheet(redirect) {
    document.getElementById('savepanel').style.display='';
    document.getElementById('savestatus').innerHTML=Drupal.t('Please wait while your entries save...');
    YAHOO.nextide.container.panel6.center();
    YAHOO.nextide.container.panel6.show();
    var formObject = document.getElementById('frm_timesheet');
    YAHOO.util.Connect.setForm(formObject);
    document.getElementById('statusmessages').style.display='';
    document.getElementById('statusmessages').innerHTML=Drupal.t('Please wait while your entries save...');
    var postvars='&q=timesaver_ajax&op=savetimesheet'+adj_postfix;
    if (!redirect) {
    YAHOO.util.Connect.asyncRequest('POST', ajax_url, {success:saveTimesheetSuccess,argument: {},timeout:500000}, postvars);
    } else {
        postSaveTarget = redirect;
        YAHOO.util.Connect.asyncRequest('POST', ajax_url, {success:postSaveExit,argument: {},timeout:500000}, postvars);
    }
}

function saveTimesheet2(redirect) {
    document.getElementById('savepanel').style.display='';
    document.getElementById('savestatus').innerHTML=Drupal.t('Updating Please Save!');
    YAHOO.nextide.container.panel6.center();
    YAHOO.nextide.container.panel6.show();
    var formObject = document.getElementById('frm_timesheet');
    YAHOO.util.Connect.setForm(formObject);
    document.getElementById('statusmessages').style.display='';
    document.getElementById('statusmessages').innerHTML=Drupal.t('Updating Please Save!');
    var postvars='&q=timesaver_ajax&op=savetimesheet'+adj_postfix;
    if (!redirect) {
    YAHOO.util.Connect.asyncRequest('POST', ajax_url, {success:saveTimesheetSuccess,argument: {},timeout:500000}, postvars);
    } else {
        postSaveTarget = redirect;
        YAHOO.util.Connect.asyncRequest('POST', ajax_url, {success:postSaveExit,argument: {},timeout:500000}, postvars);
    }
}

function saveApprovalTimesheet() {
    document.getElementById('savepanel').style.display='';
    document.getElementById('savestatus').innerHTML=Drupal.t('Please wait while your entries save...');
    YAHOO.nextide.container.panel6.center();
    YAHOO.nextide.container.panel6.show();
    var formObject = document.getElementById('frm_timesheet');
    YAHOO.util.Connect.setForm(formObject);
    document.getElementById('statusmessages').style.display='';
    document.getElementById('statusmessages').innerHTML=Drupal.t('Please wait while your entries save...');
    var postvars='&op=saveapprovaltimesheet';
    YAHOO.util.Connect.asyncRequest('POST', ajax_url, {success:saveTimesheetSuccess,argument: {},timeout:500000}, postvars);
}

function saveTimesheetSuccess(o) {
    YAHOO.util.Connect.setForm();
    if (o.responseXML != undefined) {
        var tsreplace=document.getElementById('timesheetdisplay');
        var x=o.argument[0];
        var info=o.argument[1];
        var html='';
        var root = o.responseXML.documentElement;
        var output = root.getElementsByTagName('output');
        var error = root.getElementsByTagName('error');
        for (cntr=0;cntr<output[0].childNodes.length;cntr++) {
            html +=output[0].childNodes[cntr].nodeValue;
        }
        try{
            if (html!='') tsreplace.innerHTML=html;
        }catch(e) {}

        html='';
        for (cntr=0;cntr<error[0].childNodes.length;cntr++) {
            html +=error[0].childNodes[cntr].nodeValue;
        }
        document.getElementById('statusmessages').style.display='';
        document.getElementById('statusmessages').innerHTML=html;
        try{
            YAHOO.nextide.container.panel6.hide();
        }catch(e) {}
    }
}

function lockentries(datestamp,isdisabled, uid) {
    var operation='lockentries';
    if (isdisabled=='') {
        operation='unlockentries';
    }
    if (uid=='' || uid==null) uid=0;
    document.getElementById('statusmessages').style.display='';
    document.getElementById('statusmessages').innerHTML=Drupal.t('Please wait while saving');
    var url = site_url+'/index.php?q=timesaver_ajax&op=' + operation + '&datestamp=' + datestamp + "&start_date=" + document.getElementById('start_date').value + "&end_date=" + document.getElementById('end_date').value + "&emp=" + uid;
    YAHOO.util.Connect.asyncRequest('GET', url, {success:saveTimesheetSuccess,argument: { },timeout:50000});
}

function commentPanel(textbox) {
    document.getElementById('commententrypanel').style.display='';
    YAHOO.nextide.container.panel3.center();
    YAHOO.nextide.container.panel3.show();
    var x=document.getElementById('commententryarea');
    var y=document.getElementById('hiddenTextboxName');
    x.value=textbox.value;
    y.value=textbox.id;
}


function submitComment() {
    var x=document.getElementById('commententryarea');
    var y=document.getElementById('hiddenTextboxName').value;
    var z=document.getElementById(y);
    z.value=x.value;
    x.value='';
    document.getElementById('commententrypanel').style.display='none';
    YAHOO.nextide.container.panel3.hide();
}

function approveItem(empid,start,end,checked) {
	var postvars;
    if (checked) {
        postvars='&op=approve_range&emp=' + empid + '&start=' + start + '&end=' + end;
    }else {
        postvars='&op=unapprove_range&emp=' + empid + '&start=' + start + '&end=' + end;
    }
    YAHOO.util.Connect.asyncRequest('POST', ajax_url, { success:approveAllCheckedSuccess,argument: { },timeout:50000 }, postvars);
}

function lockItem(empid,start,end,obj) {
	var postvars
    if (obj.checked) {
        postvars='&op=lock_range&emp=' + empid + '&start=' + start + '&end=' + end;
    }else {
        postvars='&op=unlock_range&emp=' + empid + '&start=' + start + '&end=' + end;
    }
    YAHOO.util.Connect.asyncRequest('POST', ajax_url, { success:lockItemSuccess,argument: [ obj ],timeout:50000 }, postvars);
    var msg=document.getElementById('approvalstatusmessages');
    msg.style.display='';
    msg.innerHTML=Drupal.t('Please Wait...');
}


function lockItemSuccess(o) {
    if (o.responseXML != undefined) {
        var msg=document.getElementById('approvalstatusmessages');
        var x=o.argument[0];
        var info=o.argument[1];
        var outputhtml='';
        var html='';
        var root = o.responseXML.documentElement;
        var output = root.getElementsByTagName('output');
        for (cntr=0;cntr<output[0].childNodes.length;cntr++) {
            outputhtml +=output[0].childNodes[cntr].nodeValue;
        }
        html=''
        var error = root.getElementsByTagName('error');
        for (cntr=0;cntr<error[0].childNodes.length;cntr++) {
            html +=error[0].childNodes[cntr].nodeValue;
        }
        if (outputhtml=='' && html!='') {
            x.checked=false;
        }
        msg.style.display='';
        msg.innerHTML=html;
        setTimeout("document.getElementById('approvalstatusmessages').style.display='none'",2000);
    }
}


function approveItemSuccess(o) {
    if (o.responseXML != undefined) {
        var msg=document.getElementById('approvalstatusmessages');
        var x=o.argument[0];
        var info=o.argument[1];
        var html='';
        var root = o.responseXML.documentElement;
        var output = root.getElementsByTagName('output');
        var error = root.getElementsByTagName('error');
        for (cntr=0;cntr<error[0].childNodes.length;cntr++) {
            html +=error[0].childNodes[cntr].nodeValue;
        }
        msg.style.display='';
        msg.innerHTML=html;
        setTimeout("document.getElementById('approvalstatusmessages').style.display='none'",2000);
    }
}

function checkAll(checked) {
    var y;
    var x=document.getElementById('allids').value;
    var splitted=x.split(",");
    if (checked) {
        for (cntr=0;cntr<splitted.length;cntr++) {
            try{
                y=document.getElementById('approve'+splitted[cntr]);
                y.checked=true;
            }catch(e) {}
        }
    }else {
        document.getElementById('frm_timesheet').reset();
    }
}

function approveAllChecked() {
    var formObject = document.getElementById('frm_timesheet');
    YAHOO.util.Connect.setForm(formObject);
    document.getElementById('approvalstatusmessages').style.display='';
    document.getElementById('approvalstatusmessages').innerHTML='Please wait while saving.';
    var postvars='&op=approveallchecked';
    YAHOO.util.Connect.asyncRequest('POST', ajax_url, {success:approveAllCheckedSuccess,argument: {},timeout:50000}, postvars);
}

function approveAllCheckedSuccess(o) {
    YAHOO.util.Connect.setForm();
    if (o.responseXML != undefined) {
        var tsreplace=document.getElementById('timesheetdisplay');
        var x=o.argument[0];
        var info=o.argument[1];
        var root = o.responseXML.documentElement;
        var output = root.getElementsByTagName('output');
        var error = root.getElementsByTagName('error');
        html='';
        for (cntr=0;cntr<error[0].childNodes.length;cntr++) {
            html +=error[0].childNodes[cntr].nodeValue;
        }
        document.getElementById('approvalstatusmessages').style.display='';
        document.getElementById('approvalstatusmessages').innerHTML=html;
        html='';
        for (cntr=0;cntr<output[0].childNodes.length;cntr++) {
            html +=output[0].childNodes[cntr].nodeValue;
        }
        //document.getElementById('approvalrows').innerHTML=html;
        lasttshtml=html;
    }
}

function singleItemApproveOrRejectSuccess(o) {
    YAHOO.util.Connect.setForm();
    if (o.responseXML != undefined) {
        var tsreplace=document.getElementById('timesheetdisplay');
        var x=o.argument[0];
        var info=o.argument[1];
        var root = o.responseXML.documentElement;
        var output = root.getElementsByTagName('output');
        var error = root.getElementsByTagName('error');
        html='';
        for (cntr=0;cntr<error[0].childNodes.length;cntr++) {
            html +=error[0].childNodes[cntr].nodeValue;
        }
        document.getElementById('approvalstatusmessages').style.display='';
        document.getElementById('approvalstatusmessages').innerHTML=html;
        setTimeout("document.getElementById('approvalstatusmessages').style.display='none'",2000);
        html='';
        for (cntr=0;cntr<output[0].childNodes.length;cntr++) {
            html +=output[0].childNodes[cntr].nodeValue;
        }
        if (html!='') {
            try{
                document.getElementById('timesheetdisplay').innerHTML=html;
                lasttshtml=html;
                setTimeout("document.getElementById('approvalstatusmessages').style.display='none'",2000);
            }catch(e) { }
        }
    }
}


function doApprovalPageApprove(chkapprove,chkreject,row,id) {
    var x=document.getElementById(chkapprove);
    var y=document.getElementById(chkreject);
    var uid=document.getElementById('uid'+row).value;
    var z=document.getElementById("id"+row);
    if (z.value=='') { //nothing entered...
        alert(Drupal.t('Sorry, but you cannot approve a blank entry.'));
        x.checked=false;
    }else {  //stuff is entered
        approvaltimer=approvaltimer+1;
        clearTimeout(savetimer);
        savetimer=setTimeout("swapInNewTs()",2000);
        if (x.checked) {
        //    approve_single_item(id,true,uid);
        }else {
        //    approve_single_item(id,false,uid);
        }
    }//end elseif
}

function doApprovalPageReject(chkapprove,chkreject,row,id) {
    var x=document.getElementById(chkapprove);
    var y=document.getElementById(chkreject);
    var uid=document.getElementById('uid'+row).value;
    document.getElementById('hiddenUID').value=uid;
    document.getElementById('hiddenTextboxName').value=id;
    document.getElementById('hiddenROW').value=row;
    document.getElementById('rejectentryarea').value='';
    var z=document.getElementById("id"+row);
    if (z.value=='') { //nothing entered...
        alert(Drupal.t('Sorry, but you cannot reject a blank entry.'));
        y.checked=false;
    }else {  //stuff is entered
        if (y.checked) {
            YAHOO.nextide.container.panel10.center();
            YAHOO.nextide.container.panel10.show();
        }else {
            reject_single_item(id,false,uid);
        }
    }//end elseif
}


function reject_single_item(id,rejected,uid) {
    var x=document.getElementById('rejectentryarea');
    var startdate=document.getElementById('start_date').value;
    var enddate=document.getElementById('end_date').value;
    var postvars;
    if (rejected) {
        postvars='&op=rejectitem&id='+id + '&comment=' + x.value + '&start_date=' + startdate + '&end_date=' + enddate + '&uid=' + uid;
    }else {
        postvars='&id='+id+ '&comment=' + x.value+ '&start_date=' + startdate + '&end_date=' + enddate + '&uid=' + uid;
    }
    YAHOO.util.Connect.asyncRequest('POST', ajax_url, {success:singleItemApproveOrRejectSuccess,argument: {},timeout:50000},postvars);
    YAHOO.nextide.container.panel10.hide();
}

function successRejectWrapper(o) {
  swapInNewTs();
}

function approve_single_item(id,approved,uid) {
    var startdate=document.getElementById('start_date').value;
    var enddate=document.getElementById('end_date').value;
    var postvars;
    if (approved) {
        postvars='&id='+ id + '&start_date=' + startdate + '&end_date=' + enddate + '&uid=' + uid;
    }else {
        postvars='&id='+id + '&start_date=' + startdate + '&end_date=' + enddate + '&uid=' + uid;
    }
    YAHOO.util.Connect.asyncRequest('POST', ajax_url, {success:singleItemApproveOrRejectSuccess,argument: {},timeout:50000}, postvars);
}


function showReason(id) {
    YAHOO.nextide.container.panel5.show();
    x=document.getElementById('rejectionreason');
    x.innerHTML='{rejection_reason_loading}';
    var postvars="&op=getrejectionreason&id="+id;
    YAHOO.util.Connect.asyncRequest('POST', ajax_url, {success:getRejectionReasonSuccess,argument: {},timeout:50000}, postvars);
}

function getRejectionReasonSuccess(o) {
    if (o.responseXML != undefined) {
        var tsreplace=document.getElementById('timesheetdisplay');
        var x=o.argument[0];
        var info=o.argument[1];
        var root = o.responseXML.documentElement;
        var output = root.getElementsByTagName('output');
        var error = root.getElementsByTagName('error');
        html='';
        for (cntr=0;cntr<output[0].childNodes.length;cntr++) {
            html +=output[0].childNodes[cntr].nodeValue;
        }
        var rr=document.getElementById('rejectionreason');
        rr.innerHTML=html;
    }
}

function clearReject(id,uid) {
    var startdate=document.getElementById('start_date').value;
    var enddate=document.getElementById('end_date').value;
    var postvars="&id=" + id + '&start_date=' + startdate + '&end_date=' + enddate + '&uid=' + uid;
    YAHOO.util.Connect.asyncRequest('POST', ajax_url, {success:saveTimesheetSuccess,argument: {},timeout:50000}, postvars);
}

function selectProject(selected,row) {
        var postvars="&op=getproject&id=" + selected + '&row=' +row;
        YAHOO.util.Connect.asyncRequest('POST', ajax_url, { success:selectProjectSuccess,argument: [ row ],timeout:50000 }, postvars);
}

function selectTask(selected,row) {
        var postvars="&op=gettask&id=" + selected + '&row=' +row;
        YAHOO.util.Connect.asyncRequest('POST', ajax_url, { success:selectTaskSuccess,argument: [ row ],timeout:50000 }, postvars);
}

function selectTaskSuccess(o) {
    if (o.responseXML != undefined) {
        var x=o.argument[0];
        var optionreplace=document.getElementById('taskTD' + x);
        var root = o.responseXML.documentElement;
        var output = root.getElementsByTagName('output');
        var error = root.getElementsByTagName('error');
        html='';
        for (cntr=0;cntr<output[0].childNodes.length;cntr++) {
            html +=output[0].childNodes[cntr].nodeValue;
        }
        optionreplace.innerHTML=html;
    }
}

function selectProjectSuccess(o) {
    if (o.responseXML != undefined) {
        var x=o.argument[0];
        var optionreplace=document.getElementById('projectTD' + x);
        var root = o.responseXML.documentElement;
        var output = root.getElementsByTagName('output');
        var error = root.getElementsByTagName('error');
        html='';
        for (cntr=0;cntr<output[0].childNodes.length;cntr++) {
            html +=output[0].childNodes[cntr].nodeValue;
        }
        optionreplace.innerHTML=html;
    }
}


function runByEmployeeReport() {
    var x=document.getElementById('reportbyemployee_status');
    var y=document.getElementById('byEmpShowUnapproved');
    var z=document.getElementById('byEmpShowRejected');
    var showunapproved,showrejected;
    if (y.checked) {
        showunapproved=1;
    }else {
        showunapproved=0;
    }
    if (z.checked) {
        showrejected=1;
    }else {
        showrejected=0;
    }
    x.innerHTML=Drupal.t('Please Wait...');
    var postvars="&op=byemployee&start_date=" + document.getElementById('byEmpsdate').value; 
    postvars+= '&end_date=' + document.getElementById('byEmpedate').value + '&whichmanager=' + document.getElementById('whichmanager').value;
    postvars+= '&showunapproved=' + showunapproved + '&showrejected=' + showrejected;
    YAHOO.util.Connect.asyncRequest('POST', reports_ajax_url, { success:runByEmployeeReportSuccess, failure:runByEmployeeReportFailure,argument: { } ,timeout:500000 }, postvars);
}

function runByEmployeeReportFailure(o) {
//alert(o.responseText);
}


function runByEmployeeReportSuccess(o) {
    var x=document.getElementById('reportbyemployee_status');
    if (o.responseXML != undefined) {
        var arg=o.argument[0];
        var root = o.responseXML.documentElement;
        var output = root.getElementsByTagName('output');
        var error = root.getElementsByTagName('error');
        html='';
        for (cntr=0;cntr<output[0].childNodes.length;cntr++) {
            html +=output[0].childNodes[cntr].nodeValue;
        }
        x.innerHTML=html;
    }else {
        x.innerHTML='Error!';
    }
}

function runByFreeFormReport() {
    var x=document.getElementById('reportbyfreeform_status');
    var y=document.getElementById('byFreeFormShowUnapproved');
    var z=document.getElementById('byFreeFormShowRejected');
    var showunapproved,showrejected;
    if (y.checked) {
        showunapproved=1;
    }else {
        showunapproved=0;
    }
    if (z.checked) {
        showrejected=1;
    }else {
        showrejected=0;
    }
    x.innerHTML=Drupal.t('Please wait...');
    var postvars="&op=byfreeform&start_date=" + document.getElementById('byFreeFormsdate').value;
    postvars+= '&end_date=' + document.getElementById('byFreeFormedate').value;
    postvars+= '&whichmanager=' + document.getElementById('whichmanagerfreeform').value;
    postvars+= '&showunapproved=' + showunapproved + '&showrejected=' + showrejected;
    YAHOO.util.Connect.asyncRequest('POST', reports_ajax_url, { success:runByFreeFormReportSuccess,argument: { } ,timeout:500000 }, postvars);
}


function runByFreeFormReportSuccess(o) {
    var x=document.getElementById('reportbyfreeform_status');
    if (o.responseXML != undefined) {
        var arg=o.argument[0];
        var root = o.responseXML.documentElement;
        var output = root.getElementsByTagName('output');
        var error = root.getElementsByTagName('error');
        html='';
        for (cntr=0;cntr<output[0].childNodes.length;cntr++) {
            html +=output[0].childNodes[cntr].nodeValue;
        }
        x.innerHTML=html;
    }else {
        x.innerHTML=Drupal.t('Error!');
    }
}

function closeByFreeFormPanel() {
    YAHOO.nextide.container.panel60.hide();
}

function runByProjectReport() {
    var x=document.getElementById('reportbyproject_status');
    var y=document.getElementById('byProjectShowUnapproved');
    var z=document.getElementById('byProjectShowRejected');
    var showunapproved,showrejected;
    if (y.checked) {
        showunapproved=1;
    }else {
        showunapproved=0;
    }
    if (z.checked) {
        showrejected=1;
    }else {
        showrejected=0;
    }
    x.innerHTML=Drupal.t('Please wait...');
    var postvars="&op=byproject&start_date=" + document.getElementById('byProjectsdate').value + '&end_date=' + document.getElementById('byProjectedate').value + '&whichmanager=' + document.getElementById('whichmanagerproject').value + '&whichproject=' + document.getElementById('whichproject').value + '&showunapproved=' + showunapproved + '&showrejected=' + showrejected;
    YAHOO.util.Connect.asyncRequest('POST', reports_ajax_url, { success:runByProjectReportSuccess,argument: { } ,timeout:500000 }, postvars);
}


function runByProjectReportSuccess(o) {
    var x=document.getElementById('reportbyproject_status');
    if (o.responseXML != undefined) {
        var arg=o.argument[0];
        var root = o.responseXML.documentElement;
        var output = root.getElementsByTagName('output');
        var error = root.getElementsByTagName('error');
        html='';
        for (cntr=0;cntr<output[0].childNodes.length;cntr++) {
            html +=output[0].childNodes[cntr].nodeValue;
        }
        x.innerHTML=html;
    }else {
        x.innerHTML=Drupal.t('Error!');
    }
}


function runByTaskReport() {
    var x=document.getElementById('reportbytask_status');
    var y=document.getElementById('byTaskShowUnapproved');
    var z=document.getElementById('byTaskShowRejected');
    var showunapproved,showrejected;
    if (y.checked) {
        showunapproved=1;
    }else {
        showunapproved=0;
    }
    if (z.checked) {
        showrejected=1;
    }else {
        showrejected=0;
    }
    x.innerHTML=Drupal.t('Please wait...');
    postvars="&op=bytask&start_date=" + document.getElementById('byTasksdate').value + '&end_date=' + document.getElementById('byTaskedate').value + '&whichmanager=' + document.getElementById('whichmanagertask').value + '&whichtask=' + document.getElementById('whichtask').value + '&showunapproved=' + showunapproved + '&showrejected=' + showrejected;
    YAHOO.util.Connect.asyncRequest('POST', reports_ajax_url, { success:runByTaskReportSuccess,argument: { } ,timeout:500000 }, postvars);
}

function runByTaskReportSuccess(o) {
    var x=document.getElementById('reportbytask_status');
    if (o.responseXML != undefined) {
        var arg=o.argument[0];
        var root = o.responseXML.documentElement;
        var output = root.getElementsByTagName('output');
        var error = root.getElementsByTagName('error');
        html='';
        for (cntr=0;cntr<output[0].childNodes.length;cntr++) {
            html +=output[0].childNodes[cntr].nodeValue;
        }
        x.innerHTML=html;
    }else {
        x.innerHTML=Drupal.t('Error!');
    }
}

function runByEmpAndTaskReport() {
    var x=document.getElementById('reportbyempandtask_status');
    var y=document.getElementById('byEmpAndTaskShowUnapproved');
    var z=document.getElementById('byEmpAndTaskShowRejected');
    var showunapproved,showrejected;
    if (y.checked) {
        showunapproved=1;
    }else {
        showunapproved=0;
    }
    if (z.checked) {
        showrejected=1;
    }else {
        showrejected=0;
    }
    x.innerHTML=Drupal.t('Please wait...');
    var postvars="&op=byempandtask&start_date=" + document.getElementById('byEmpAndTasksdate').value + '&end_date=' + document.getElementById('byEmpAndTaskedate').value + '&whichmanager=' + document.getElementById('whichmanagerempandtask').value + '&showunapproved=' + showunapproved + '&showrejected=' + showrejected;
    YAHOO.util.Connect.asyncRequest('POST', reports_ajax_url, { success:runByEmpAndTaskReportSuccess,argument: { } ,timeout:500000 }, postvars);
}

function runByEmpAndTaskReportSuccess(o) {
    var x=document.getElementById('reportbyempandtask_status');
    if (o.responseXML != undefined) {
        var arg=o.argument[0];
        var root = o.responseXML.documentElement;
        var output = root.getElementsByTagName('output');
        var error = root.getElementsByTagName('error');
        html='';
        for (cntr=0;cntr<output[0].childNodes.length;cntr++) {
            html +=output[0].childNodes[cntr].nodeValue;
        }
        x.innerHTML=html;
    }else {
        x.innerHTML=Drupal.t('Error!');
    }
}

function closeByEmpAndTaskPanel() {
    YAHOO.nextide.container.panel40.hide();
}

function closeByTaskPanel() {
    YAHOO.nextide.container.panel30.hide();
}

function closeByProjectPanel() {
    YAHOO.nextide.container.panel50.hide();
}

function closeByEmployeePanel() {
    YAHOO.nextide.container.panel20.hide();
}

function fetchSundayToSunday(dateobj,rangesobj,messageobj,gobtnobj,sdate,edate) {
    var x=document.getElementById(dateobj).value;
    var y=document.getElementById(messageobj);
    document.getElementById(rangesobj).innerHTML='';
    y.innerHTML=Drupal.t('Fetching Sunday to Sunday range...');
    var postvars="&op=getsundaytosunday&date=" + x;
    YAHOO.util.Connect.asyncRequest('POST', ajax_url, { success:fetchSundayToSundaySuccess,argument: [rangesobj,messageobj,gobtnobj,sdate,edate] ,timeout:50000 }, postvars);
}


function fetchSundayToSundaySuccess(o) {
    var rangeobj=o.argument[0];
    var messageobj=o.argument[1];
    var gobtn=o.argument[2];
    var sdate=o.argument[3];
    var edate=o.argument[4];
    var x=document.getElementById(rangeobj);
    document.getElementById(messageobj).innerHTML='';
    if (o.responseXML != undefined) {
        var root = o.responseXML.documentElement;
        var output = root.getElementsByTagName('output');
        var error = root.getElementsByTagName('error');
        html='';
        for (cntr=0;cntr<output[0].childNodes.length;cntr++) {
            html +=output[0].childNodes[cntr].nodeValue;
        }
        splitted=html.split(',');
        x.innerHTML=Drupal.t('From ') + splitted[0] + Drupal.t(' to ') + splitted[1];
        document.getElementById(gobtn).style.display='';
        document.getElementById(sdate).value=splitted[0];
        document.getElementById(edate).value=splitted[1];
    }else {
        x.innerHTML=Drupal.t('Error!');
    }
}

function swapInNewTs() {
    showSavePopup();
    saveApprovalTimesheet();
}

function showSavePopup() {
    document.getElementById('savepanel').style.display='';
    document.getElementById('savestatus').innerHTML=Drupal.t('Please wait while saving...');
    YAHOO.nextide.container.panel6.center();
    YAHOO.nextide.container.panel6.show();
}


function init_index_page() {
	v1=new nexYUICal;
    v1.init('cal1','start_date');
    v1.setFormat (3,2,1,'/');  //day, month, year... this will create a dateoutput that is YYYY/mm/dd
    if (!enableautoenddate) {
        v2=new nexYUICal;
        v2.init('cal2','end_date');
        v2.setFormat (3,2,1,'/');  //day, month, year... this will create a dateoutput that is YYYY/mm/dd
    }
    v4=new nexYUICal;
    v4.init('startdate2','start_date2');
    v5=new nexYUICal;
    v5.init('enddate2','end_date2');

    v4.setFormat (3,2,1,'/');  //day, month, year... this will create a dateoutput that is YYYY/mm/dd
    v5.setFormat (3,2,1,'/');  //day, month, year... this will create a dateoutput that is YYYY/mm/dd

    if (enableautoenddate) {
        v1.setAfterFunction('fetchSundayToSunday("start_date","span_dummy1","span_dummy1","enter_my_timesheet","start_date","end_date")');
    }else {
        v2.setAfterFunction('turnOnButton("enter_my_timesheet")');
    }
    
    YAHOO.util.Event.onDOMReady(reportbyemppanel);
    YAHOO.util.Event.onDOMReady(reportbytaskpanel);
    YAHOO.util.Event.onDOMReady(reportbyprojectpanel);
    YAHOO.util.Event.onDOMReady(reportbyfreeformpanel);

    v3=new nexYUICal;
    v3.init('cal3','byEmpsdate');
    v3.setFormat (3,2,1,'/');  //day, month, year... this will create a dateoutput that is YYYY/mm/dd
    ve3=new nexYUICal;
    ve3.init('cale3','byEmpedate');
    ve3.setFormat (3,2,1,'/');  //day, month, year... this will create a dateoutput that is YYYY/mm/dd
    ve3.setAfterFunction('turnOnButton("byEmpGoButton")');
    
    v4=new nexYUICal;
    v4.init('cal4','byTasksdate');
    v4.setFormat (3,2,1,'/');  //day, month, year... this will create a dateoutput that is YYYY/mm/dd
    
    ve4=new nexYUICal;
    ve4.init('cale4','byTaskedate');
    ve4.setFormat (3,2,1,'/');  //day, month, year... this will create a dateoutput that is YYYY/mm/dd
    ve4.setAfterFunction('turnOnButton("byTaskGoButton")');
    
    v6=new nexYUICal;
    v6.init('cal5','byProjectsdate');
    v6.setFormat (3,2,1,'/');  //day, month, year... this will create a dateoutput that is YYYY/mm/dd
    ve6=new nexYUICal;
    ve6.init('cale5','byProjectedate');
    ve6.setFormat (3,2,1,'/');  //day, month, year... this will create a dateoutput that is YYYY/mm/dd
    ve6.setAfterFunction('turnOnButton("byProjectGoButton")');
        
    v7=new nexYUICal;
    v7.init('cal6','byFreeFormsdate');
    v7.setFormat (3,2,1,'/');  //day, month, year... this will create a dateoutput that is YYYY/mm/dd
    ve7=new nexYUICal;
    ve7.init('cale6','byFreeFormedate');
    ve7.setFormat (3,2,1,'/');  //day, month, year... this will create a dateoutput that is YYYY/mm/dd
    ve7.setAfterFunction('turnOnButton("byFreeFormGoButton")');
}

function init_approval_page() {
	YAHOO.util.Event.onDOMReady(delmsgpanel);
	YAHOO.util.Event.onDOMReady(rejectpanel);
	YAHOO.util.Event.onDOMReady(cmtpanel);
	YAHOO.util.Event.onDOMReady(errmsgpanel);
	YAHOO.util.Event.onDOMReady(savepanel);
}

function init_entry_page() {    
	YAHOO.util.Event.onDOMReady(delmsgpanel);
	YAHOO.util.Event.onDOMReady(errmsgpanel);
	YAHOO.util.Event.onDOMReady(cmtpanel);
	YAHOO.util.Event.onDOMReady(rejectionreasonpanel);
	YAHOO.util.Event.onDOMReady(savepanel);
}

function reportbyemppanel() {
    YAHOO.namespace("nextide.container");
    YAHOO.nextide.container.panel20 = new YAHOO.widget.Panel("reportbyemployeepanel", { width:"600px", visible:false, constraintoviewport:true, x:300, y:600, modal:true } );
    YAHOO.nextide.container.panel20.render();
    YAHOO.nextide.container.panel20.center();
    try{
        document.getElementById('reportByEmployeeButton').style.display='';
    }catch(e) {}
}

function reportbytaskpanel() {
    YAHOO.namespace("nextide.container");
    YAHOO.nextide.container.panel30 = new YAHOO.widget.Panel("reportbytaskpanel", { width:"600px", visible:false, constraintoviewport:true, x:300, y:600, modal:true } );
    YAHOO.nextide.container.panel30.render();
    YAHOO.nextide.container.panel30.center();
    try{
        document.getElementById('reportByTaskButton').style.display='';
    }catch(e) {}
}

function reportbyprojectpanel() {
    YAHOO.namespace("nextide.container");
    YAHOO.nextide.container.panel50 = new YAHOO.widget.Panel("reportbyprojectpanel", { width:"600px", visible:false, constraintoviewport:true, x:300, y:600, modal:true } );
    YAHOO.nextide.container.panel50.render();
    YAHOO.nextide.container.panel50.center();
    try{
        document.getElementById('reportByProjectButton').style.display='';
    }catch(e) {}
}

function reportbyfreeformpanel() {
    YAHOO.namespace("nextide.container");
    YAHOO.nextide.container.panel60 = new YAHOO.widget.Panel("reportbyfreeformpanel", { width:"600px", visible:false, constraintoviewport:true, x:300, y:600, modal:true } );
    YAHOO.nextide.container.panel60.render();
    YAHOO.nextide.container.panel60.center();
    try{
        document.getElementById('reportByFreeFormButton').style.display='';
        document.getElementById('fetching_reports_message').style.display='none';
    }catch(e) {}
}

function turnOnButton(btn) {
document.getElementById(btn).style.display='';
}
