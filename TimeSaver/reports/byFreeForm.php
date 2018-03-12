<?php
// $Id: byFreeForm.php,v 1.9 
/**
 * @file
 * byFreeForm report generation file.
 *
 * The byFreeForm report uses the PHPExcel libraries to generate an excel5 or 2007 report.
 */
$totalsArray=array();
$ts=new Timesaver();
if ($whichManager>0) {
  $csv=$ts->get_csv_list_of_assigned_employees($whichManager,true);
}else {
  $csv=$ts->get_all_uids_which_have_supervisors();
}
if ($csv=='') $csv='0';

$timesthru=0;

$obj_php_excel->setActiveSheetIndex($timesthru);
$obj_php_excel->getActiveSheet()->setTitle(t('Free Form'));

//set the top left A1 cell to the logo image
$obj_drawing = new PHPExcel_Worksheet_Drawing();
$obj_drawing->setName('Logo');
$obj_drawing->setDescription('Logo');
$obj_drawing->setPath(drupal_get_path('module','Timesaver') . '/images/logo.png');
$obj_drawing->setHeight(36);
$obj_drawing->setCoordinates('A1');
$obj_drawing->setWorksheet($obj_php_excel->getActiveSheet());
$obj_php_excel->getActiveSheet()->getRowDimension('1')->setRowHeight(27.75);
//end top left image

//generate header of report
$obj_php_excel->getActiveSheet()->setCellValue('H1', t('Free Form Report'));
$obj_php_excel->getActiveSheet()->getStyle('H1')->getFont()->setName('Arial');
$obj_php_excel->getActiveSheet()->getStyle('H1')->getFont()->setBold(true);
$obj_php_excel->getActiveSheet()->getStyle('H1')->getFont()->setSize(14);
$obj_php_excel->getActiveSheet()->getStyle('S2')->getFont()->setName('Arial');
$obj_php_excel->getActiveSheet()->setCellValue('S2', t('Period From'));
$obj_php_excel->getActiveSheet()->getStyle('S3')->getFont()->setName('Arial');
$obj_php_excel->getActiveSheet()->setCellValue('S3', t('Period To'));
$obj_php_excel->getActiveSheet()->getStyle('U2')->getFont()->setName('Arial');
$obj_php_excel->getActiveSheet()->setCellValue('U2', $start_date);
$obj_php_excel->getActiveSheet()->getStyle('U3')->getFont()->setName('Arial');
$obj_php_excel->getActiveSheet()->setCellValue('U3', $end_date);
$obj_php_excel->getActiveSheet()->mergeCells('O2:P2');
$obj_php_excel->getActiveSheet()->mergeCells('O3:P3');
//end of header information....

$obj_php_excel->getActiveSheet()->getColumnDimension('A')->setWidth(10.71);
$obj_php_excel->getActiveSheet()->getColumnDimension('B')->setWidth(15);
$obj_php_excel->getActiveSheet()->getColumnDimension('C')->setWidth(10.71);
$obj_php_excel->getActiveSheet()->getColumnDimension('D')->setWidth(10.71);
$obj_php_excel->getActiveSheet()->getColumnDimension('E')->setWidth(10.71);
$obj_php_excel->getActiveSheet()->getColumnDimension('F')->setWidth(10.71);
//column names:
$rowCounter=8;
$xlscol=1;
foreach ($_TIMESAVER_LANG_REPORT_FREE_FORM_COLUMNS as $dbcol=>$label) {
  $obj_php_excel->getActiveSheet()->setCellValue($_TIMESAVER_CONF['report_columns'][$xlscol] . $rowCounter, $label);
  $obj_php_excel->getActiveSheet()->getStyle($_TIMESAVER_CONF['report_columns'][$xlscol] . $rowCounter)->getFont()->setBold(true);
  $xlscol+=1;
}
//now to add the data
$oneDay=86400;
$rowCounter=9; //we start the output at row 9

//we will select all of the data from the timesheet table and dump it out
$sql  ="SELECT FROM_UNIXTIME(a.datestamp,'%%Y/%%m/%%d') as 'thedate', a.uid, c.emp_number,a.* ";
$sql .="FROM {timesaver_timesheet_entry} a ";
$sql .="LEFT JOIN {timesaver_extra_user_data} c on a.uid=c.uid ";
$sql .="WHERE a.uid in ($csv) ";
$sql .="AND datestamp>=(%d-4600) AND datestamp<=(%d+4600) AND ( approved=1 ";
if ($showUNApproved!=1) { //if $showUNApproved==1 then do not filter on approved
  //placeholder for any future requirements
}else {
  $sql .=" OR (approved=1 OR approved=0) ";
}
if ($showRejected==1) {
  $sql .="OR rejected=1 ";
}else {
  $sql .="OR rejected=0 ";
}
$sql .=") ORDER BY datestamp ASC";

$res=db_query($sql, $start_stamp, $end_stamp);
while ($A=db_fetch_array($res)) {
  //now cycle thru each element in the select
  $xlscol=1;
  foreach ($_TIMESAVER_LANG_REPORT_FREE_FORM_COLUMNS as $dbcol=>$label) {
    switch($dbcol) {
      case 'total_reg_hours':
        $obj_php_excel->getActiveSheet()->setCellValue($_TIMESAVER_CONF['report_columns'][$xlscol] . $rowCounter, $ts->get_total_hrs_from_id($A['id']));
        break;
      case 'project_number':
        $output=listkeeper_value(variable_get('timesaver_list_id_projects', 0),$A['project_id'],1);
        $obj_php_excel->getActiveSheet()->setCellValue($_TIMESAVER_CONF['report_columns'][$xlscol] . $rowCounter, $output );
        break;
      case 'timesaver_activity_id':
        $output=listkeeper_value(variable_get('timesaver_list_id_activities', 0),$A['timesaver_activity_id'],0);
        $obj_php_excel->getActiveSheet()->setCellValue($_TIMESAVER_CONF['report_columns'][$xlscol] . $rowCounter, $output );
        break;
      case 'project_id':
        $output=listkeeper_value(variable_get('timesaver_list_id_projects', 0),$A['project_id'],1);
        $obj_php_excel->getActiveSheet()->setCellValue($_TIMESAVER_CONF['report_columns'][$xlscol] . $rowCounter, $output );
        break;
      case 'task_id':
        $output=listkeeper_value(variable_get('timesaver_list_id_tasks', 0),$A['task_id'],1);
        $obj_php_excel->getActiveSheet()->setCellValue($_TIMESAVER_CONF['report_columns'][$xlscol] . $rowCounter, $output );
        break;
      case 'fullname':
        $empinf=timesaver_get_user_data($A['uid']);
        $obj_php_excel->getActiveSheet()->setCellValue($_TIMESAVER_CONF['report_columns'][$xlscol] . $rowCounter, $empinf['emp_name'] );
        break;
      default:
        $obj_php_excel->getActiveSheet()->setCellValue($_TIMESAVER_CONF['report_columns'][$xlscol] . $rowCounter, $A[$dbcol]);
        break;
    }


    $xlscol+=1;
  }
  $rowCounter +=1;
}
$endRowCounter=$rowCounter-1;
