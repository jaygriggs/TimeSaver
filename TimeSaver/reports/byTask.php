<?php
// $Id: byTask.php,v 1.9
/**
 * @file
 * byTask report generation file.
 *
 * The byTask report uses the PHPExcel libraries to generate an excel5 or 2007 report.
 */
$totalsArray=array();
$ts=new Timesaver();
if ($whichManager>0) {
  $csv=$ts->get_csv_list_of_assigned_employees($whichManager,true);
}
else {
  $csv=$ts->get_all_uids_which_have_supervisors();
}
if ($csv=='') $csv='0';

$timesthru=0;

$obj_php_excel->setActiveSheetIndex($timesthru);
$obj_php_excel->getActiveSheet()->setTitle(listkeeper_value(variable_get('timesaver_list_id_tasks', 0),$listkeeper_id,1));

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
$obj_php_excel->getActiveSheet()->setCellValue('H1', t('Report by Task'));
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

//start columns for output..
//starts at A7

$obj_php_excel->getActiveSheet()->getRowDimension('8')->setRowHeight(3);
$obj_php_excel->getActiveSheet()->getStyle('A7')->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
$obj_php_excel->getActiveSheet()->getStyle('A7')->getBorders()->getRight()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
$obj_php_excel->getActiveSheet()->getStyle('A7')->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
$obj_php_excel->getActiveSheet()->getStyle('A7')->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
$obj_php_excel->getActiveSheet()->getColumnDimension('A')->setWidth(10.71);
$obj_php_excel->getActiveSheet()->getStyle('B7')->getFont()->setName('Arial');
$obj_php_excel->getActiveSheet()->setCellValue('B7', t('Date'));
$obj_php_excel->getActiveSheet()->getColumnDimension('B')->setWidth(12);
$obj_php_excel->getActiveSheet()->getStyle('B7')->getFont()->setBold(true);
$obj_php_excel->getActiveSheet()->getStyle('B7')->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
$obj_php_excel->getActiveSheet()->getStyle('B7')->getBorders()->getRight()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
$obj_php_excel->getActiveSheet()->getStyle('B7')->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
$obj_php_excel->getActiveSheet()->getStyle('B7')->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
$obj_php_excel->getActiveSheet()->getStyle('B7')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$obj_php_excel->getActiveSheet()->getStyle('C7')->getFont()->setName('Arial');
$obj_php_excel->getActiveSheet()->setCellValue('C7', t('Task'));
$obj_php_excel->getActiveSheet()->getColumnDimension('C')->setWidth(4.14);
$obj_php_excel->getActiveSheet()->getStyle('C7')->getFont()->setBold(true);
$obj_php_excel->getActiveSheet()->getStyle('C7')->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
$obj_php_excel->getActiveSheet()->getStyle('C7')->getBorders()->getRight()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
$obj_php_excel->getActiveSheet()->getStyle('C7')->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
$obj_php_excel->getActiveSheet()->getStyle('C7')->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
$obj_php_excel->getActiveSheet()->getStyle('C7')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$obj_php_excel->getActiveSheet()->getStyle('C7')->getAlignment()->setTextRotation(-90);

$colnum=4;
$skipheadercol=0;
foreach ($_TIMESAVER_LANG_REPORT_COLUMNS as $key=>$val) {
  if ($skipheadercol>3) {
    $obj_php_excel->getActiveSheet()->getStyle($_TIMESAVER_CONF['report_columns'][$colnum].'7')->getAlignment()->setTextRotation(-90);
    $obj_php_excel->getActiveSheet()->getStyle($_TIMESAVER_CONF['report_columns'][$colnum].'7')->getFont()->setName('Arial');
    $obj_php_excel->getActiveSheet()->setCellValue($_TIMESAVER_CONF['report_columns'][$colnum].'7', $_TIMESAVER_LANG_REPORT_COLUMNS[$key]);
    if ($key=='comment') {
      $obj_php_excel->getActiveSheet()->getColumnDimension($_TIMESAVER_CONF['report_columns'][$colnum])->setWidth(12);
      $obj_php_excel->getActiveSheet()->getStyle($_TIMESAVER_CONF['report_columns'][$colnum].'7')->getAlignment()->setTextRotation(0);
      $obj_php_excel->getActiveSheet()->getStyle($_TIMESAVER_CONF['report_columns'][$colnum].'7')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
    }
    else {
      $obj_php_excel->getActiveSheet()->getColumnDimension($_TIMESAVER_CONF['report_columns'][$colnum])->setWidth(4.14);
    }
    $obj_php_excel->getActiveSheet()->getStyle($_TIMESAVER_CONF['report_columns'][$colnum].'7')->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
    $obj_php_excel->getActiveSheet()->getStyle($_TIMESAVER_CONF['report_columns'][$colnum].'7')->getBorders()->getRight()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
    $obj_php_excel->getActiveSheet()->getStyle($_TIMESAVER_CONF['report_columns'][$colnum].'7')->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
    $obj_php_excel->getActiveSheet()->getStyle($_TIMESAVER_CONF['report_columns'][$colnum].'7')->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
    $colnum+=1;
  }
  $skipheadercol+=1;
}
$obj_php_excel->getActiveSheet()->getColumnDimension('F')->setWidth(6.5);
//now to add the data
$oneDay=86400;
$rowCounter=9; //we start the output at row 9

//we now cycle through each user, generating outputtable rows for each user for THIS task

$sql  ="SELECT a.uid FROM {timesaver_timesheet_entry} a ";
$sql .="LEFT OUTER JOIN {timesaver_extra_user_data} b ON a.uid=b.uid ";
$sql .="WHERE a.uid in ($csv) AND ";
$sql .="( b.special_exclusion <>1 OR b.special_exclusion is null) ";
$sql .="GROUP BY a.uid ";
$res=db_query($sql);

while ($A=db_fetch_array($res)) {
  //echo out who this is
  $empinf=timesaver_get_user_data($A['uid']);
  $obj_php_excel->getActiveSheet()->setCellValue('A' . $rowCounter, $empinf['emp_name']);
  $obj_php_excel->getActiveSheet()->getStyle('A' . $rowCounter)->getFont()->setBold(true);
  $rowCounter+=1;
  //now, for THIS user, select out of the timesheet records where the TASKID==$listkeeper_id
  //we will also use our filter information
  $sql  ="SELECT * FROM {timesaver_timesheet_entry} WHERE uid='%d' AND task_id='%d' ";
  $sql .="AND datestamp>=(%d-4600) AND datestamp<=(%d+4600) AND (approved=1 ";
  if ($showUNApproved!=1) { //if $showUNApproved==1 then do not filter on approved
    // kept this clause in here as this is for future use (if needed)
  }
  else {
    $sql .=" OR (approved=1 OR approved=0) ";
  }
  if ($showRejected==1) {
    $sql .="OR rejected=1 ";
  }
  else {
    $sql .="OR rejected=0 ";
  }
  $sql .=") ORDER BY datestamp ASC";
  $perUserRes=db_query($sql, $A['uid'], $listkeeper_id, $start_stamp, $end_stamp);
  //now cycle thrue each of these..
  $taskcount=1;
  $startingRowCount=$rowCounter;
  while ($X=db_fetch_array($perUserRes)) {
    $obj_php_excel->getActiveSheet()->setCellValue('A' . $rowCounter, date('l',$X['datestamp']));
    $obj_php_excel->getActiveSheet()->setCellValue('B' . $rowCounter, date('Y/m/d',$X['datestamp']));
    $obj_php_excel->getActiveSheet()->setCellValue('C' . $rowCounter, $taskcount);
    //and now for each column, starting at offset 5
    $colcount=0;
    $xlscol=4;  //column d
    foreach ($_TIMESAVER_LANG_REPORT_COLUMNS as $dbcol => $name) {
      if ($colcount>3) { //skip out 0-4
        if ($dbcol=='total_reg_hours') {
          $obj_php_excel->getActiveSheet()->setCellValue($_TIMESAVER_CONF['report_columns'][$xlscol] . $rowCounter, ($ts->get_total_hrs_from_id($X['id'])));
        }
        else {
          $obj_php_excel->getActiveSheet()->setCellValue($_TIMESAVER_CONF['report_columns'][$xlscol] . $rowCounter, ($X[$dbcol]));
        }
        $xlscol+=1;
      }
      $colcount+=1;
    }

    $taskcount+=1;
    $rowCounter+=1;
  }
  $endRowCounter=$rowCounter-1;
  //give a totals line....
  //totals line goes from $startingRowCount to $endRowCounter
  $colcount=0;
  $xlscol=4;  //column d
  foreach ($_TIMESAVER_LANG_REPORT_COLUMNS as $dbcol => $name) {
    if ($colcount>3 && $dbcol!='comment' && $startingRowCount != $rowCounter) { //skip out 0-4
      $obj_php_excel->getActiveSheet()->setCellValue($_TIMESAVER_CONF['report_columns'][$xlscol] . $rowCounter, "=SUM({$_TIMESAVER_CONF['report_columns'][$xlscol]}{$startingRowCount}:{$_TIMESAVER_CONF['report_columns'][$xlscol]}{$endRowCounter})");
      $obj_php_excel->getActiveSheet()->getStyle($_TIMESAVER_CONF['report_columns'][$xlscol] . $rowCounter)->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
      $obj_php_excel->getActiveSheet()->getStyle($_TIMESAVER_CONF['report_columns'][$xlscol] . $rowCounter)->getBorders()->getRight()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
      $obj_php_excel->getActiveSheet()->getStyle($_TIMESAVER_CONF['report_columns'][$xlscol] . $rowCounter)->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
      $obj_php_excel->getActiveSheet()->getStyle($_TIMESAVER_CONF['report_columns'][$xlscol] . $rowCounter)->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
      $xlscol+=1;
    }
    elseif ($colcount>4 && $dbcol!='comment' && $startingRowCount == $rowCounter) {
      $obj_php_excel->getActiveSheet()->setCellValue($_TIMESAVER_CONF['report_columns'][$xlscol] . $rowCounter, "0");
      $obj_php_excel->getActiveSheet()->getStyle($_TIMESAVER_CONF['report_columns'][$xlscol] . $rowCounter)->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
      $obj_php_excel->getActiveSheet()->getStyle($_TIMESAVER_CONF['report_columns'][$xlscol] . $rowCounter)->getBorders()->getRight()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
      $obj_php_excel->getActiveSheet()->getStyle($_TIMESAVER_CONF['report_columns'][$xlscol] . $rowCounter)->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
      $obj_php_excel->getActiveSheet()->getStyle($_TIMESAVER_CONF['report_columns'][$xlscol] . $rowCounter)->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
      $xlscol+=1;
    }
    $colcount+=1;
  }

  $totalsArray[]=$rowCounter;
  $rowCounter+=2;
}//end while $A    //cycling thru each user

//$rowCounter holds the LAST row count.
//now show the totals area
$finalRowCount=$rowCounter-1;
$rowCounter+=2;
$obj_php_excel->getActiveSheet()->setCellValue('A' . $rowCounter, 'Overall Total:');
//now for each column that is being displayed...

$colcount=0;
$xlscol=4;  //column d

foreach ($_TIMESAVER_LANG_REPORT_COLUMNS as $dbcol => $name) {
  if ($colcount>4 && $dbcol!='comment') { //skip out 0-4
    $sumstring='';
    foreach ($totalsArray as $val) {
      if ($sumstring!='') $sumstring .=",";
      $sumstring.=$_TIMESAVER_CONF['report_columns'][$xlscol] . $val;
    }

    $obj_php_excel->getActiveSheet()->setCellValue($_TIMESAVER_CONF['report_columns'][$xlscol] . $rowCounter, "=SUM({$sumstring})");
    $obj_php_excel->getActiveSheet()->getStyle($_TIMESAVER_CONF['report_columns'][$xlscol] . $rowCounter)->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
    $obj_php_excel->getActiveSheet()->getStyle($_TIMESAVER_CONF['report_columns'][$xlscol] . $rowCounter)->getBorders()->getRight()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
    $obj_php_excel->getActiveSheet()->getStyle($_TIMESAVER_CONF['report_columns'][$xlscol] . $rowCounter)->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
    $obj_php_excel->getActiveSheet()->getStyle($_TIMESAVER_CONF['report_columns'][$xlscol] . $rowCounter)->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);

    $xlscol+=1;
  }
  $colcount+=1;
}

$obj_php_excel->getActiveSheet()->setCellValue('I' . ($finalRowCount+10), t('Date'));
$obj_php_excel->getActiveSheet()->setCellValue('M' . ($finalRowCount+10), date('Y/m/d h:m A'));
$obj_php_excel->getActiveSheet()->mergeCells("M" . ($finalRowCount+10) . ":Q" . ($finalRowCount+10));
$obj_php_excel->getActiveSheet()->getStyle("M" . ($finalRowCount+10))->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
$obj_php_excel->getActiveSheet()->getStyle("N" . ($finalRowCount+10))->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
$obj_php_excel->getActiveSheet()->getStyle("O" . ($finalRowCount+10))->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
$obj_php_excel->getActiveSheet()->getStyle("P" . ($finalRowCount+10))->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
$obj_php_excel->getActiveSheet()->getStyle("Q" . ($finalRowCount+10))->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
