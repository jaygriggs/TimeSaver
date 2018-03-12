<?php
// $Id: byEmployee.php,v 1.9 Exp $
/**
 * @file
 * byEmployee report generation file.
 *
 * The byEmployee report uses the PHPExcel libraries to generate an excel5 or 2007 report.
 */
$_glob_fit_height = 3;
$_glob_fit_width = 1;

$ts=new Timesaver();
if ($whichManager>0) {
  $csv=$ts->get_csv_list_of_assigned_employees($whichManager,true);
}else {
  $csv=$ts->get_all_uids_which_have_supervisors();
}

if ($csv=='') $csv='0';

$sql  ="SELECT {users}.uid FROM {users} LEFT OUTER JOIN {timesaver_extra_user_data} ON {users}.uid = {timesaver_extra_user_data}.uid WHERE {users}.uid IN ($csv) AND ";
$sql .="( {timesaver_extra_user_data}.special_exclusion <>1 OR {timesaver_extra_user_data}.special_exclusion is null)";

$countsql  ="SELECT count({users}.uid) FROM {users} LEFT OUTER JOIN {timesaver_extra_user_data} ON {users}.uid = {timesaver_extra_user_data}.uid WHERE {users}.uid IN ($csv) AND ";
$countsql .="( {timesaver_extra_user_data}.special_exclusion <>1 OR {timesaver_extra_user_data}.special_exclusion is null)";

$res=db_query($sql);
$countres=db_query($countsql);
$nrows=db_result($countres);
$timesthru=0;
while ($A=db_fetch_array($res)) {
  $empinfo=timesaver_get_user_data($A['uid']);
  if ($timesthru>0) {
    $obj_php_excel->createSheet();
  }
  $obj_php_excel->setActiveSheetIndex($timesthru);
  $emp_number=$empinfo['emp_number'];
  $fullname=$empinfo['emp_name'];
  $obj_php_excel->getActiveSheet()->setTitle($fullname . ' - #' . $emp_number);
  $obj_php_excel->getActiveSheet()->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE);
  $obj_php_excel->getActiveSheet()->getPageSetup()->setPaperSize(PHPExcel_Worksheet_PageSetup::PAPERSIZE_LEGAL);
  $obj_php_excel->getActiveSheet()->getPageSetup()->setFitToHeight(3);
  $obj_php_excel->getActiveSheet()->getPageSetup()->setFitToWidth(1);
  $obj_php_excel->getActiveSheet()->getPageSetup()->setRowsToRepeatAtTopByStartAndEnd(1, 9);
  $obj_php_excel->getActiveSheet()->getHeaderFooter()->setOddFooter('&L&P of &N');
  $obj_php_excel->getActiveSheet()->getHeaderFooter()->setEvenFooter('&L&P of &N');

  $obj_php_excel->getActiveSheet()->getHeaderFooter()->setOddHeader("&R&14 {$emp_number}\n{$fullname}");
  $obj_php_excel->getActiveSheet()->getHeaderFooter()->setEvenHeader("&R&14 {$emp_number}\n{$fullname}");

  //set the top left A1 cell to the logo image
  $obj_drawing = new PHPExcel_Worksheet_Drawing();
  $obj_drawing->setName('Logo');
  $obj_drawing->setDescription('Logo');
  $obj_drawing->setPath(drupal_get_path('module','Timesaver') . '/images/logo.png');
  $obj_drawing->setHeight(45);
  $obj_drawing->setCoordinates('A1');
  $obj_drawing->setWorksheet($obj_php_excel->getActiveSheet());
  $obj_php_excel->getActiveSheet()->getRowDimension('1')->setRowHeight(40);
  //end top left image

  //generate header of report
  $obj_php_excel->getActiveSheet()->setCellValue('Q3', t('Emp. Name'));
  $obj_php_excel->getActiveSheet()->getStyle('Q3')->getFont()->setName('Arial');
  $obj_php_excel->getActiveSheet()->setCellValue('I1', t('Report by Employee'));
  $obj_php_excel->getActiveSheet()->getStyle('I1')->getFont()->setName('Arial');
  $obj_php_excel->getActiveSheet()->getStyle('I1')->getFont()->setBold(true);
  $obj_php_excel->getActiveSheet()->getStyle('I1')->getFont()->setSize(10);
  $obj_php_excel->getActiveSheet()->setCellValue('Q2', t('Emp. Number'));
  $obj_php_excel->getActiveSheet()->getStyle('Q2')->getFont()->setName('Arial');
  $obj_php_excel->getActiveSheet()->getStyle('Q5')->getFont()->setName('Arial');
  $obj_php_excel->getActiveSheet()->setCellValue('Q4', t('Supervisor name'));
  $obj_php_excel->getActiveSheet()->getStyle('Q4')->getFont()->setName('Arial');
  $obj_php_excel->getActiveSheet()->getStyle('X3')->getFont()->setName('Arial');
  $obj_php_excel->getActiveSheet()->setCellValue('X3', $empinfo['emp_name']);
  $obj_php_excel->getActiveSheet()->getStyle('X3')->getFont()->setSize(10);
  $obj_php_excel->getActiveSheet()->getStyle('X4')->getFont()->setName('Arial');
  $supuid=intval($ts->get_supervisor_uid($A['uid']));
  $supname=$ts->get_user_full_name($supuid);
  $obj_php_excel->getActiveSheet()->setCellValue('X4', $supname);
  $obj_php_excel->getActiveSheet()->getStyle('X2')->getFont()->setName('Arial');
  $obj_php_excel->getActiveSheet()->setCellValue('X2', $empinfo['emp_number']);
  $obj_php_excel->getActiveSheet()->getStyle('X2')->getFont()->setSize(10);
  $obj_php_excel->getActiveSheet()->getStyle('X5')->getFont()->setName('Arial');
  $obj_php_excel->getActiveSheet()->getStyle('Q6')->getFont()->setName('Arial');
  $obj_php_excel->getActiveSheet()->setCellValue('Q6', t('Period From'));
  $obj_php_excel->getActiveSheet()->getStyle('Q7')->getFont()->setName('Arial');
  $obj_php_excel->getActiveSheet()->setCellValue('Q7', t('Period To'));
  $obj_php_excel->getActiveSheet()->getStyle('X6')->getFont()->setName('Arial');
  $obj_php_excel->getActiveSheet()->setCellValue('X6', $start_date);
  $obj_php_excel->getActiveSheet()->getStyle('X7')->getFont()->setName('Arial');
  $obj_php_excel->getActiveSheet()->setCellValue('X7', $end_date);
  $obj_php_excel->getActiveSheet()->mergeCells('Q2:W2');
  $obj_php_excel->getActiveSheet()->mergeCells('Q3:W3');
  $obj_php_excel->getActiveSheet()->mergeCells('Q4:W4');
  $obj_php_excel->getActiveSheet()->mergeCells('Q5:W5');
  $obj_php_excel->getActiveSheet()->mergeCells('Q6:W6');
  $obj_php_excel->getActiveSheet()->mergeCells('Q7:W7');
  $obj_php_excel->getActiveSheet()->getStyle('Q2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
  $obj_php_excel->getActiveSheet()->getStyle('Q3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
  $obj_php_excel->getActiveSheet()->getStyle('Q4')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
  $obj_php_excel->getActiveSheet()->getStyle('Q5')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
  $obj_php_excel->getActiveSheet()->getStyle('Q6')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
  $obj_php_excel->getActiveSheet()->getStyle('Q7')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
  $obj_php_excel->getActiveSheet()->getStyle('X2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
  $obj_php_excel->getActiveSheet()->getStyle('X3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
  $obj_php_excel->getActiveSheet()->getStyle('X4')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
  $obj_php_excel->getActiveSheet()->getStyle('X5')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
  $obj_php_excel->getActiveSheet()->getStyle('X6')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
  $obj_php_excel->getActiveSheet()->getStyle('X7')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);

  //end of header information....
  
  //start columns for output..
  //starts at A7

  $obj_php_excel->getActiveSheet()->getRowDimension('10')->setRowHeight(3);
  $obj_php_excel->getActiveSheet()->getStyle('A9')->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
  $obj_php_excel->getActiveSheet()->getStyle('A9')->getBorders()->getRight()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
  $obj_php_excel->getActiveSheet()->getStyle('A9')->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
  $obj_php_excel->getActiveSheet()->getStyle('A9')->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
  $obj_php_excel->getActiveSheet()->getColumnDimension('A')->setWidth(10.71);
  $obj_php_excel->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
  $obj_php_excel->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
  $obj_php_excel->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);
  $obj_php_excel->getActiveSheet()->getColumnDimension('F')->setAutoSize(true);
  $obj_php_excel->getActiveSheet()->getStyle('B9')->getFont()->setName('Arial');
  $obj_php_excel->getActiveSheet()->setCellValue('B9', t('Date'));
  $obj_php_excel->getActiveSheet()->getColumnDimension('B')->setWidth(12);
  $obj_php_excel->getActiveSheet()->getStyle('B9')->getFont()->setBold(true);
  $obj_php_excel->getActiveSheet()->getStyle('B9')->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
  $obj_php_excel->getActiveSheet()->getStyle('B9')->getBorders()->getRight()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
  $obj_php_excel->getActiveSheet()->getStyle('B9')->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
  $obj_php_excel->getActiveSheet()->getStyle('B9')->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
  $obj_php_excel->getActiveSheet()->getStyle('B9')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
  $colnum=3;
	
  foreach ($_TIMESAVER_LANG_REPORT_COLUMNS as $key=>$val)

  {
    $obj_php_excel->getActiveSheet()->getStyle($_TIMESAVER_CONF['report_columns'][$colnum].'9')->getAlignment()->setTextRotation(-90);
    $obj_php_excel->getActiveSheet()->getStyle($_TIMESAVER_CONF['report_columns'][$colnum].'9')->getAlignment()->setHorizontal('center');
    $obj_php_excel->getActiveSheet()->getStyle($_TIMESAVER_CONF['report_columns'][$colnum].'9')->getFont()->setName('Arial');
    $obj_php_excel->getActiveSheet()->setCellValue($_TIMESAVER_CONF['report_columns'][$colnum].'9', $_TIMESAVER_LANG_REPORT_COLUMNS[$key]);

	if ($key=='statAMSelect') {
	  $obj_php_excel->getActiveSheet()->getColumnDimension($_TIMESAVER_CONF['report_columns'][$colnum])->setWidth(2.5);
	}
    if ($key=='comment') {
      $obj_php_excel->getActiveSheet()->getColumnDimension($_TIMESAVER_CONF['report_columns'][$colnum])->setWidth(36);
      $obj_php_excel->getActiveSheet()->getStyle($_TIMESAVER_CONF['report_columns'][$colnum].'9')->getAlignment()->setTextRotation(0);
      $obj_php_excel->getActiveSheet()->getStyle($_TIMESAVER_CONF['report_columns'][$colnum].'9')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
    }
	
    else {
      $obj_php_excel->getActiveSheet()->getColumnDimension($_TIMESAVER_CONF['report_columns'][$colnum])->setWidth(5.25);
    }
    $obj_php_excel->getActiveSheet()->getStyle($_TIMESAVER_CONF['report_columns'][$colnum].'9')->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
    $obj_php_excel->getActiveSheet()->getStyle($_TIMESAVER_CONF['report_columns'][$colnum].'9')->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
    $colnum+=1;
  }
  //special cases to the for loop above
  $obj_php_excel->getActiveSheet()->getStyle('E9')->getBorders()->getRight()->setBorderStyle(PHPExcel_Style_Border::BORDER_THICK);
  $obj_php_excel->getActiveSheet()->getStyle('M9')->getBorders()->getRight()->setBorderStyle(PHPExcel_Style_Border::BORDER_THICK);
  $obj_php_excel->getActiveSheet()->getStyle('M9')->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THICK);
  $obj_php_excel->getActiveSheet()->getStyle('M10')->getBorders()->getRight()->setBorderStyle(PHPExcel_Style_Border::BORDER_THICK);
  $obj_php_excel->getActiveSheet()->getStyle('M10')->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THICK);
  $obj_php_excel->getActiveSheet()->getStyle('T9')->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THICK);
  $obj_php_excel->getActiveSheet()->getStyle('T10')->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THICK);
  $obj_php_excel->getActiveSheet()->getStyle('X9')->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THICK);
  $obj_php_excel->getActiveSheet()->getStyle('X10')->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THICK);
  $obj_php_excel->getActiveSheet()->getStyle('A11')->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
  $obj_php_excel->getActiveSheet()->getStyle('B11')->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
  $obj_php_excel->getActiveSheet()->getStyle('R9')->getFont()->setBold(true);
  for ($colnum=2; $colnum<=6; $colnum++) {
    $obj_php_excel->getActiveSheet()->getStyle($_TIMESAVER_CONF['report_columns'][$colnum] . '9')->getAlignment()->setTextRotation(0);
    $obj_php_excel->getActiveSheet()->getStyle($_TIMESAVER_CONF['report_columns'][$colnum] . '9')->getAlignment()->setHorizontal('center');
    $obj_php_excel->getActiveSheet()->getStyle($_TIMESAVER_CONF['report_columns'][$colnum] . '9')->getAlignment()->setVertical('center');
    $obj_php_excel->getActiveSheet()->getStyle($_TIMESAVER_CONF['report_columns'][$colnum] . '9')->getFont()->setBold(true);
  }

  $obj_php_excel->getActiveSheet()->getColumnDimension('F')->setWidth(6.5);
  //now to add the data
  $oneDay=86400;
  $rowCounter=11; //we start the output at row 9
  for ($cntr=0;$cntr<$numberOfDays;$cntr++) {
    //formulate the date
    $dt=$start_stamp+($oneDay*$cntr);

    $testdate=strftime("%Y/%m/%d 00:00:00",$dt);
    $testintdate=strtotime($testdate);

    if ($testintdate==$dt-3600) {
      $dt=$testintdate;
    }
    elseif ($testintdate==$dt) {

    }
    else {
      $dt+=3600;
    }

    $today=date("w",$dt);
    $day=$_TIMESAVER_CONF['day_offsets'][$today];
    $daystring=date("Y/m/d",$dt);
    $obj_php_excel->getActiveSheet()->setCellValue('A' . $rowCounter, $day);
    $obj_php_excel->getActiveSheet()->setCellValue('B' . $rowCounter, $daystring);
    $obj_php_excel->getActiveSheet()->getStyle('A' . $rowCounter)->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
    $obj_php_excel->getActiveSheet()->getStyle('A'. $rowCounter)->getBorders()->getRight()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
    $obj_php_excel->getActiveSheet()->getStyle('A' . $rowCounter)->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
    $obj_php_excel->getActiveSheet()->getStyle('A' . $rowCounter)->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
    $obj_php_excel->getActiveSheet()->getStyle('C' . $rowCounter)->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
    $obj_php_excel->getActiveSheet()->getStyle('B'. $rowCounter)->getBorders()->getRight()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
    $obj_php_excel->getActiveSheet()->getStyle('B' . $rowCounter)->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
    $obj_php_excel->getActiveSheet()->getStyle('B' . $rowCounter)->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);

    //now, fetch each date's entries here
    //but take the filters into account...
    $lowerextent=$dt-14400;
    $upperextent=$dt+14400;

    $sql  ="SELECT * FROM {timesaver_timesheet_entry} WHERE uid='{$A['uid']}'  ";
    $sql .="AND datestamp<='{$upperextent}' AND datestamp>='{$lowerextent}' AND ( approved=1 ";

    $countsql  ="SELECT count(*) FROM {timesaver_timesheet_entry} WHERE uid='{$A['uid']}'  ";
    $countsql .="AND datestamp<='{$upperextent}' AND datestamp>='{$lowerextent}' AND ( approved=1 ";
    if ($showUNApproved!=1) { //if $showUNApproved==1 then do not filter on approved
      //just a placeholder for now.
    }
    else {
      $sql .=" OR (approved=1 OR approved=0) ";
      $countsql .=" OR (approved=1 OR approved=0) ";
    }
    if ($showRejected==1) {
      $sql .="OR rejected=1 ";
      $countsql .="OR rejected=1 ";
    }
    else {
      $sql .="OR rejected=0 ";
      $countsql .="OR rejected=0 ";
    }
    $sql .=") ORDER BY datestamp ASC";
    $countsql .=") ORDER BY datestamp ASC";

    $perUserRes=DB_query($sql);
    $countres=db_query($countsql);
    $numrows=db_result($countres);

    $colnum=4;  //set it to the next writable column we're after
    if ($numrows==0) {
      $obj_php_excel->getActiveSheet()->setCellValue('C' . $rowCounter, 1);
      $obj_php_excel->getActiveSheet()->getStyle('C' . $rowCounter)->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
      $obj_php_excel->getActiveSheet()->getStyle('C'. $rowCounter)->getBorders()->getRight()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
      $obj_php_excel->getActiveSheet()->getStyle('C' . $rowCounter)->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
      $obj_php_excel->getActiveSheet()->getStyle('C' . $rowCounter)->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
      foreach ($_TIMESAVER_LANG_REPORT_COLUMNS as $key=>$value) {  //cycle thru the available columns
        if ($key!='task_number') {
          switch($key) {
            case 'comment':    //these case items are lookups... thus we'll just leave them blank
            case 'timesaver_activity_id':
            case 'project_id':
            case 'task_id':
              break;
            default:
              $obj_php_excel->getActiveSheet()->setCellValue($_TIMESAVER_CONF['report_columns'][$colnum] . $rowCounter, '');
              break;
          }
          if ($key == 'total_reg_hours') {
            $obj_php_excel->getActiveSheet()->getStyle($_TIMESAVER_CONF['report_columns'][$colnum] . $rowCounter)->getFont()->setBold(true);
          }
          if ($colnum >= 7 || $colnum <= 20) {
       
			$obj_php_excel->getActiveSheet()->getStyle('H' . $rownum)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_CUSTOM1);
			$obj_php_excel->getActiveSheet()->getStyle('K' . $rownum)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_CUSTOM1);
			$obj_php_excel->getActiveSheet()->getStyle('O' . $rownum)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_CUSTOM1);
			$obj_php_excel->getActiveSheet()->getStyle('R' . $rownum)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_CUSTOM1);
			$obj_php_excel->getActiveSheet()->getStyle('I' . $rownum)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_TEXT);
          }
          if ($key == 'standby') {
             $obj_php_excel->getActiveSheet()->getStyle('H' . $rownum)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_CUSTOM1);
			 $obj_php_excel->getActiveSheet()->getStyle('K' . $rownum)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_CUSTOM1);
			 $obj_php_excel->getActiveSheet()->getStyle('O' . $rownum)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_CUSTOM1);
			 $obj_php_excel->getActiveSheet()->getStyle('R' . $rownum)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_CUSTOM1);
			 $obj_php_excel->getActiveSheet()->getStyle('I' . $rownum)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_TEXT);
          }
          $obj_php_excel->getActiveSheet()->getStyle($_TIMESAVER_CONF['report_columns'][$colnum] . $rowCounter, $day)->getAlignment()->setHorizontal('center');
          $obj_php_excel->getActiveSheet()->getStyle($_TIMESAVER_CONF['report_columns'][$colnum] . $rowCounter, $day)->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
          $obj_php_excel->getActiveSheet()->getStyle($_TIMESAVER_CONF['report_columns'][$colnum] . $rowCounter, $day)->getBorders()->getRight()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
          $obj_php_excel->getActiveSheet()->getStyle($_TIMESAVER_CONF['report_columns'][$colnum] . $rowCounter, $day)->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
          $obj_php_excel->getActiveSheet()->getStyle($_TIMESAVER_CONF['report_columns'][$colnum] . $rowCounter, $day)->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
          $colnum+=1;
        }//end if
      } //end foreach
      $rowCounter+=1;
    }
    else { //cycle thru the rows
      $XX=db_fetch_array($perUserRes);
      for ($taskCounter=0;$taskCounter<$numrows;$taskCounter++) {
        $colnum=4;  //again, setting it to the next writable column we're after
        $obj_php_excel->getActiveSheet()->getStyle('A' . $rowCounter)->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $obj_php_excel->getActiveSheet()->getStyle('A' . $rowCounter)->getBorders()->getRight()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $obj_php_excel->getActiveSheet()->getStyle('A' . $rowCounter)->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $obj_php_excel->getActiveSheet()->getStyle('A' . $rowCounter)->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $obj_php_excel->getActiveSheet()->getStyle('B' . $rowCounter)->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $obj_php_excel->getActiveSheet()->getStyle('B' . $rowCounter)->getBorders()->getRight()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $obj_php_excel->getActiveSheet()->getStyle('B' . $rowCounter)->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $obj_php_excel->getActiveSheet()->getStyle('B' . $rowCounter)->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $obj_php_excel->getActiveSheet()->setCellValue('C' . $rowCounter, $taskCounter+1); //task number column
        $obj_php_excel->getActiveSheet()->getStyle('C' . $rowCounter)->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $obj_php_excel->getActiveSheet()->getStyle('C'. $rowCounter)->getBorders()->getRight()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $obj_php_excel->getActiveSheet()->getStyle('C' . $rowCounter)->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $obj_php_excel->getActiveSheet()->getStyle('C' . $rowCounter)->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        
				
		
		foreach ($_TIMESAVER_LANG_REPORT_COLUMNS as $key=>$value) {  //we now fill in each column
          if ($key!='task_number') {
            switch($key) {
              case 'project_number':
                $output=listkeeper_value(4,$XX['project_id'],1);
                $obj_php_excel->getActiveSheet()->setCellValue($_TIMESAVER_CONF['report_columns'][$colnum] . $rowCounter, $output );
                break;
              case 'timesaver_activity_id':
                $output=listkeeper_value(2,$XX['timesaver_activity_id'],0);
                $obj_php_excel->getActiveSheet()->setCellValue($_TIMESAVER_CONF['report_columns'][$colnum] . $rowCounter, $output );
                break;
              case 'task_id':
                $output=listkeeper_value(3,$XX['task_id'],1);
                $obj_php_excel->getActiveSheet()->setCellValue($_TIMESAVER_CONF['report_columns'][$colnum] . $rowCounter, $output );
                break;
              case 'total_reg_hours':
                $output=$ts->get_total_hrs_from_id($XX['id']);
                $obj_php_excel->getActiveSheet()->setCellValue($_TIMESAVER_CONF['report_columns'][$colnum] . $rowCounter, ($output==0)?'':$output );
                break;
              default:
			  
			$keyExcelValue = ($XX[$key]=='')?'':$XX[$key];
         if(strcmp($keyExcelValue,"NO")==0)  {
             $keyExcelValue = '';
         }	   
       if ($keyExcelValue=='0') {
             $keyExcelValue = '';
	    }
		 $obj_php_excel->getActiveSheet()->setCellValue($_TIMESAVER_CONF['report_columns'][$colnum] . $rowCounter, $keyExcelValue);
  
				
			     break;
					
            }
            $obj_php_excel->getActiveSheet()->getStyle($_TIMESAVER_CONF['report_columns'][$colnum] . $rowCounter)->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
	
            if ($key == 'total_reg_hours') {
              $obj_php_excel->getActiveSheet()->getStyle($_TIMESAVER_CONF['report_columns'][$colnum] . $rowCounter)->getFont()->setBold(true);
            }
            if ($colnum >= 7 || $colnum <= 30)			{
			
            $obj_php_excel->getActiveSheet()->getStyle('H' . $colnum)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_CUSTOM1);
		
			$obj_php_excel->getActiveSheet()->getStyle('K' . $colnum)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_CUSTOM1);
			$obj_php_excel->getActiveSheet()->getStyle('O' . $colnum)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_CUSTOM1);
			$obj_php_excel->getActiveSheet()->getStyle('R' . $colnum)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_CUSTOM1);
			$obj_php_excel->getActiveSheet()->getStyle('I' . $colnum)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_TEXT);
            }
			
			
            if ($key == 'standby') {
              $obj_php_excel->getActiveSheet()->getStyle('H' . $colnum)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_CUSTOM1);
			  $obj_php_excel->getActiveSheet()->getStyle('K' . $colnum)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_CUSTOM1);
			  $obj_php_excel->getActiveSheet()->getStyle('O' . $colnum)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_CUSTOM1);
			  $obj_php_excel->getActiveSheet()->getStyle('R' . $colnum)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_CUSTOM1);
			  $obj_php_excel->getActiveSheet()->getStyle('I' . $colnum)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_TEXT);
            }
			
            $obj_php_excel->getActiveSheet()->getStyle($_TIMESAVER_CONF['report_columns'][$colnum] . $rowCounter)->getAlignment()->setHorizontal('center');
            $obj_php_excel->getActiveSheet()->getStyle($_TIMESAVER_CONF['report_columns'][$colnum] . $rowCounter)->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
            $colnum+=1;
          }//end if
        } //end foreach
        $XX=db_fetch_array($perUserRes);
        $rowCounter+=1;
      }//end for loop
    }//end else
  }//end loop for days

  //$rowCounter holds the LAST row count.
  //now show the totals area
  $finalRowCount=$rowCounter-1;
  //put a thick border around the entire main protion
  for ($colnum = 1; $colnum <= 20; $colnum++) {
    $obj_php_excel->getActiveSheet()->getStyle($_TIMESAVER_CONF['report_columns'][$colnum] . '9')->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_THICK);
    $obj_php_excel->getActiveSheet()->getStyle($_TIMESAVER_CONF['report_columns'][$colnum] . $finalRowCount)->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THICK);
  }
  for ($rownum = 9; $rownum < $rowCounter; $rownum++) {

    $obj_php_excel->getActiveSheet()->getStyle('A' . $rownum)->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THICK);
    $obj_php_excel->getActiveSheet()->getStyle('X' . $rownum)->getBorders()->getRight()->setBorderStyle(PHPExcel_Style_Border::BORDER_THICK);
    $obj_php_excel->getActiveSheet()->getStyle('U' . $rownum)->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THICK);
    $obj_php_excel->getActiveSheet()->getStyle('U' . $rownum)->getBorders()->getRight()->setBorderStyle(PHPExcel_Style_Border::BORDER_THICK);
    $obj_php_excel->getActiveSheet()->getStyle('T' . $rownum)->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THICK);
	$obj_php_excel->getActiveSheet()->getStyle('X' . $rownum)->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THICK);
	$obj_php_excel->getActiveSheet()->getStyle('M' . $rownum)->getBorders()->getRight()->setBorderStyle(PHPExcel_Style_Border::BORDER_THICK);
    $obj_php_excel->getActiveSheet()->getStyle('M' . $rownum)->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THICK);
	$obj_php_excel->getActiveSheet()->getStyle('F' . $rownum)->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THICK);
	$obj_php_excel->getActiveSheet()->getStyle('J' . $rownum)->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THICK);
	$obj_php_excel->getActiveSheet()->getStyle('Q' . $rownum)->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THICK);
    $obj_php_excel->getActiveSheet()->getStyle('U' . 9)->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_THICK);
    $obj_php_excel->getActiveSheet()->getStyle('U' . $colnum)->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THICK);
	$obj_php_excel->getActiveSheet()->getStyle('V' . 9)->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_THICK);
    $obj_php_excel->getActiveSheet()->getStyle('V' . $colnum)->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THICK);
	$obj_php_excel->getActiveSheet()->getStyle('W' . 9)->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_THICK);
    $obj_php_excel->getActiveSheet()->getStyle('W' . $colnum)->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THICK);
	$obj_php_excel->getActiveSheet()->getStyle('X' . 9)->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_THICK);
    $obj_php_excel->getActiveSheet()->getStyle('X' . $colnum)->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THICK);
    $obj_php_excel->getActiveSheet()->getStyle('F' . $rownum)->getBorders()->getRight()->setBorderStyle(PHPExcel_Style_Border::BORDER_THICK);
  }
  
  $rowCounter++;
  $obj_php_excel->getActiveSheet()->setCellValue('F' . $rowCounter, 'Total Hours');
  $obj_php_excel->getActiveSheet()->getStyle('F' . $rowCounter)->getFont()->setSize(12);
  $obj_php_excel->getActiveSheet()->getStyle('F' . $rowCounter)->getFont()->setBold(true);
  $obj_php_excel->getActiveSheet()->getStyle('F' . $rowCounter)->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_THICK);
  $obj_php_excel->getActiveSheet()->getStyle('F' . $rowCounter)->getBorders()->getRight()->setBorderStyle(PHPExcel_Style_Border::BORDER_THICK);
  $obj_php_excel->getActiveSheet()->getStyle('F' . $rowCounter)->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THICK);
  //now for each column that is being displayed...
  $colnum=7;
  foreach ($_TIMESAVER_LANG_REPORT_COLUMNS as $key=>$value) {
    switch($key) {
      case 'timesaver_activity_id':    //these case items are lookups... thus we'll just leave them blank
      case 'project_id':
      case 'task_id':
      case 'task_number':
      case 'project_number':
        break;
      case 'comment':    //these case items are lookups... thus we'll just leave them blank
        $obj_php_excel->getActiveSheet()->getStyle($_TIMESAVER_CONF['report_columns'][$colnum] . $rowCounter)->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_THICK);
        $obj_php_excel->getActiveSheet()->getStyle($_TIMESAVER_CONF['report_columns'][$colnum] . $rowCounter)->getBorders()->getRight()->setBorderStyle(PHPExcel_Style_Border::BORDER_THICK);
        $obj_php_excel->getActiveSheet()->getStyle($_TIMESAVER_CONF['report_columns'][$colnum] . $rowCounter)->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $colnum+=1;
        break;
      default:
        $obj_php_excel->getActiveSheet()->setCellValue($_TIMESAVER_CONF['report_columns'][$colnum] . $rowCounter, "=SUM({$_TIMESAVER_CONF['report_columns'][$colnum]}11:{$_TIMESAVER_CONF['report_columns'][$colnum]}{$finalRowCount})");
        if ($key != 'standby') {
          $obj_php_excel->getActiveSheet()->getStyle($_TIMESAVER_CONF['report_columns'][$colnum] . $rowCounter);
        }
        
        $obj_php_excel->getActiveSheet()->getStyle($_TIMESAVER_CONF['report_columns'][$colnum] . $rowCounter)->getFont()->setSize(12);
        $obj_php_excel->getActiveSheet()->getStyle($_TIMESAVER_CONF['report_columns'][$colnum] . $rowCounter)->getFont()->setBold(true);
        $obj_php_excel->getActiveSheet()->getStyle($_TIMESAVER_CONF['report_columns'][$colnum] . $rowCounter)->getAlignment()->setHorizontal('center');
        $obj_php_excel->getActiveSheet()->getStyle($_TIMESAVER_CONF['report_columns'][$colnum] . $rowCounter)->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_THICK);
        $colnum+=1;
        break;
    }
  }//end foreach
  $obj_php_excel->getActiveSheet()->getStyle('X' . $rowCounter)->getBorders()->getRight()->setBorderStyle(PHPExcel_Style_Border::BORDER_THICK);
  $obj_php_excel->getActiveSheet()->getStyle('X' . $rowCounter)->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THICK);


  $obj_php_excel->getActiveSheet()->getStyle('F' . $rowCounter)->getFont()->setSize(12);
  $obj_php_excel->getActiveSheet()->getStyle('F' . $rowCounter)->getFont()->setBold(true);
  $obj_php_excel->getActiveSheet()->getStyle('F' . $rowCounter)->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THICK);
  $obj_php_excel->getActiveSheet()->getStyle('F' . $rowCounter)->getBorders()->getRight()->setBorderStyle(PHPExcel_Style_Border::BORDER_THICK);
  $obj_php_excel->getActiveSheet()->getStyle('F' . $rowCounter)->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THICK);
  //now for each column that is being displayed...
  $colnum=7;

  foreach ($_TIMESAVER_LANG_REPORT_COLUMNS as $key=>$value) {
    switch($key) {
      case 'timesaver_activity_id':    //these case items are lookups... thus we'll just leave them blank
      case 'project_id':
      case 'task_id':
      case 'task_number':
      case 'project_number':
        break;
      case 'comment':    //these case items are lookups... thus we'll just leave them blank
        $obj_php_excel->getActiveSheet()->getStyle($_TIMESAVER_CONF['report_columns'][$colnum] . $rowCounter)->getBorders()->getRight()->setBorderStyle(PHPExcel_Style_Border::BORDER_THICK);
        $obj_php_excel->getActiveSheet()->getStyle($_TIMESAVER_CONF['report_columns'][$colnum] . $rowCounter)->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THICK);
        $colnum+=1;
        break;
      default:
        $obj_php_excel->getActiveSheet()->getStyle($_TIMESAVER_CONF['report_columns'][$colnum] . $rowCounter)->getFont()->setBold(true);
        $obj_php_excel->getActiveSheet()->getStyle($_TIMESAVER_CONF['report_columns'][$colnum] . $rowCounter)->getFont()->setSize(12);
        $obj_php_excel->getActiveSheet()->getStyle($_TIMESAVER_CONF['report_columns'][$colnum] . $rowCounter)->getAlignment()->setHorizontal('center');
        $obj_php_excel->getActiveSheet()->getStyle($_TIMESAVER_CONF['report_columns'][$colnum] . $rowCounter)->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THICK);

        $colnum+=1;
        break;
    }
  }//end foreach
  $obj_php_excel->getActiveSheet()->getStyle('X' . $rowCounter)->getBorders()->getRight()->setBorderStyle(PHPExcel_Style_Border::BORDER_THICK);
  $obj_php_excel->getActiveSheet()->getStyle('X' . $rowCounter)->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THICK);

  //and now for the by-project hours breakdown area
  $output=$ts->select_by_project_hours_by_user($A['uid'],$start_stamp,$end_stamp);
  //m29 is the project number, n29 is the total hours and so on
  $prjrow=$finalRowCount+2;
  $obj_php_excel->getActiveSheet()->mergeCells("A{$prjrow}:D{$prjrow}");
  $obj_php_excel->getActiveSheet()->setCellValue('A' . $prjrow, t('TOTAL PER PROJECT'));
  $obj_php_excel->getActiveSheet()->getStyle('A' . $prjrow)->getFont()->setUnderline(UNDERLINE_SINGLE);
  $obj_php_excel->getActiveSheet()->getStyle('A' . $prjrow)->getFont()->setSize(12);
  $obj_php_excel->getActiveSheet()->getStyle('A' . $prjrow)->getFont()->setBold(true);
  $obj_php_excel->getActiveSheet()->getStyle('A' . $prjrow)->getAlignment()->setHorizontal('center');
  $prjrow++;
  $prowStart = $prjrow;
  $flag = false;
  foreach ($output as $key=>$value) {
    $obj_php_excel->getActiveSheet()->mergeCells("A{$prjrow}:C{$prjrow}");
    $obj_php_excel->getActiveSheet()->setCellValue('A' . $prjrow, $key);
    $obj_php_excel->getActiveSheet()->setCellValue('D' . $prjrow, $value);
    $prjrow+=1;
    $flag = true;
  }
  if ($flag) {
    $prowEnd = $prjrow - 1;
    $obj_php_excel->getActiveSheet()->setCellValue('D' . $prjrow, "=SUM(D$prowStart:D$prowEnd)");
    $obj_php_excel->getActiveSheet()->getStyle('D' . $prjrow)->getFont()->setBold(true);
  }

  $timesthru+=1;
}//end of each user loop

