 <?php
// $Id: timesheet_entry_row.tpl.php,v 1.1 Rev 635JayBeret
?>

<tr><td colspan="31" class="rowSpacing"></td></tr>
<tr id="row<?php print $rownumber; ?>" class="<?php print $evenodd; ?>"   title="<?php print $rejected_note; ?>" >
    <td style="display:none"><input type="text" size="2"  name="id<?php print $rownumber; ?>" id="id<?php print $rownumber; ?>" value="<?php print $id; ?>"></td>
    <td style="display:none"><input type="text" size="2"  name="uid<?php print $rownumber; ?>" id="uid<?php print $rownumber; ?>" value="<?php print $uid; ?>"></td>
    <td style="display:none"><input type="text" size="2"  name="datestamp<?php print $rownumber; ?>" id="datestamp<?php print $rownumber; ?>" value="<?php print $datestamp; ?>"></td>
    <td style="display:none"><input type="text" size="2"  name="isSecondaryRow<?php print $rownumber; ?>" id="isSecondaryRow<?php print $rownumber; ?>" value="0"></td>
    <td <?php print $rowspan_value?> id="rowspancolumn<?php print $rownumber; ?>">
        <table style="border-collapse:collapse" border="0" class="humanDateTable <?php print $day_colour; ?>">
        <tr>

            <td><?php print $human_date_day?></td>
            <td rowspan="2" valign="middle"><span onclick="insertTask('<?php print $datestamp; ?>','<?php print $rownumber; ?>','<?php print $disable_on_lock; ?>');"><img src="<?php print $images_dir; ?>/add.png" title="<?php print $add_this_entry; ?>"  style="<?php print $disabled_style; ?>"></span></td>
        </tr>
        <tr>
            <td><?php print $human_date_formatted; ?></td>
        </tr>
        </table>
    </td>
    <td></td>
    <td class="entryRowLeftCell" style="<?php print $rejected_style; ?>"><input type="checkbox" name="chk<?php print $rownumber; ?>" id="chk<?php print $rownumber; ?>" <?php print $disable_on_lock; ?>></td>
    <td class="entryRowMiddleCell" style="<?php print $rejected_style; ?>" id="activityTD">
        <select name="timesaver_activity_id<?php print $rownumber; ?>" id="timesaver_activity_id<?php print $rownumber; ?>" onchange="changeflag();selectProject(this.value,'<?php print $rownumber;  ?>');" <?php print $disable_on_lock; ?> class="dropdown_menus">
            <?php print $timesaver_activity_options; ?>
        </select>
    </td>
    <td class="entryRowMiddleCell" style="<?php print $rejected_style; ?>" id="projectTD<?php print $rownumber; ?>">
        <select name="project_id<?php print $rownumber; ?>" id="project_id<?php print $rownumber; ?>"  <?php print $disable_on_lock; ?> class="dropdown_menus">
            <?php print $project_options; ?>
        </select>
    </td>
	
    <td class="entryRowMiddleCell" style="<?php print $rejected_style; ?>"  onchange="changeflag();">
	
	<select name="regHourSelect<?php print $rownumber; ?>"  <?php print $disable_on_lock; ?>  value="<?php print $regHourSelect; ?>" >
  <option value="NO">--</option>
  
<?php

        $xhour = array('1'=> '1', '2'=>'2','3'=>'3','4'=>'4', '5'=>'5', '6'=>'6', '7'=>'7', '8'=>'8', '9'=>'9', '10'=>'10', '11'=>'11', '12'=>'00');
       foreach($xhour as $xkey=>$xvalue)
       {
           echo $xkey;
           for ($i = 0; $i <= 31; $i++) {
               if ($rownumber == $i) {
                   echo '
<option value="' . $xvalue . '"';
                   if (isset($_POST['regHourSelect' . $i]) && $_POST['regHourSelect' . $i] == $xvalue) {
                       echo ' selected="selected"';
                   }
				   if (isset($regHourSelect) && $regHourSelect == $xvalue) {
                         echo ' selected="selected"';
                    }
				   
                   echo ">" . $xkey . "</option>\n";
               }
           }
       }
       ?>
	
</select>

 <?php
 $regHourValue0 = $_POST['regHourSelect0'];
 $regHourValue1 = $_POST['regHourSelect1'];
 $regHourValue2 = $_POST['regHourSelect2'];
 $regHourValue3 = $_POST['regHourSelect3'];
 $regHourValue4 = $_POST['regHourSelect4'];
 $regHourValue5 = $_POST['regHourSelect5'];
 $regHourValue6 = $_POST['regHourSelect6'];
 $regHourValue7 = $_POST['regHourSelect7'];
 $regHourValue8 = $_POST['regHourSelect8'];
 $regHourValue9 = $_POST['regHourSelect9'];
 $regHourValue10 = $_POST['regHourSelect10'];
 $regHourValue11 = $_POST['regHourSelect11'];
 $regHourValue12 = $_POST['regHourSelect12'];
 $regHourValue13 = $_POST['regHourSelect13'];
 $regHourValue14 = $_POST['regHourSelect14'];
 $regHourValue15 = $_POST['regHourSelect15'];
 $regHourValue16 = $_POST['regHourSelect16'];
 $regHourValue17 = $_POST['regHourSelect17'];
 $regHourValue18 = $_POST['regHourSelect18'];
 $regHourValue19 = $_POST['regHourSelect19'];
 $regHourValue20 = $_POST['regHourSelect20'];
 $regHourValue21 = $_POST['regHourSelect21'];
 $regHourValue22 = $_POST['regHourSelect22'];
 $regHourValue23 = $_POST['regHourSelect23'];
 $regHourValue24 = $_POST['regHourSelect24'];
 $regHourValue25 = $_POST['regHourSelect25'];
 $regHourValue26 = $_POST['regHourSelect26'];
 $regHourValue27 = $_POST['regHourSelect27'];
 $regHourValue28 = $_POST['regHourSelect28'];
 $regHourValue29 = $_POST['regHourSelect29'];
 $regHourValue30 = $_POST['regHourSelect30'];
 $regHourValue31 = $_POST['regHourSelect31'];
 ?>

<select name="regMinSelect<?php print $rownumber; ?>" <?php print $disable_on_lock; ?> value="<?php print $regMinSelect;?>" >
<option value="NO">--</option>
 
    <?php
    $mi = array('00'=> '.00', '15'=>'.25','30'=>'.50','45'=>'.75');
    foreach($mi as $mikey=>$mivalue)
        {
            echo $mikey;
            for ($i = 0; $i <= 31; $i++) {
                if ($rownumber == $i) {
                    echo '
<option value="' . $mivalue . '"';
                    if (isset($_POST['regMinSelect' . $i]) && $_POST['regMinSelect' . $i] == $mivalue) {
                        echo ' selected="selected"';
                    }
					if (isset($regMinSelect) && $regMinSelect == $mivalue) {
                         echo ' selected="selected"';
                    }
                    echo ">" . $mikey . "</option>\n";
                }
            }
        }

echo "\n\n";
    ?>

</select>

 <?php 
 $regMinValue0 = $_POST['regMinSelect0'];
 $regMinValue1 = $_POST['regMinSelect1'];
 $regMinValue2 = $_POST['regMinSelect2'];
 $regMinValue3 = $_POST['regMinSelect3'];
 $regMinValue4 = $_POST['regMinSelect4'];
 $regMinValue5 = $_POST['regMinSelect5'];
 $regMinValue6 = $_POST['regMinSelect6'];
 $regMinValue7 = $_POST['regMinSelect7'];
 $regMinValue8 = $_POST['regMinSelect8'];
 $regMinValue9 = $_POST['regMinSelect9'];
 $regMinValue10 = $_POST['regMinSelect10'];
 $regMinValue11 = $_POST['regMinSelect11'];
 $regMinValue12 = $_POST['regMinSelect12'];
 $regMinValue13 = $_POST['regMinSelect13'];
 $regMinValue14 = $_POST['regMinSelect14'];
 $regMinValue15 = $_POST['regMinSelect15'];
 $regMinValue16 = $_POST['regMinSelect16'];
 $regMinValue17 = $_POST['regMinSelect17'];
 $regMinValue18 = $_POST['regMinSelect18'];
 $regMinValue19 = $_POST['regMinSelect19'];
 $regMinValue20 = $_POST['regMinSelect20'];
 $regMinValue21 = $_POST['regMinSelect21'];
 $regMinValue22 = $_POST['regMinSelect22'];
 $regMinValue23 = $_POST['regMinSelect23'];
 $regMinValue24 = $_POST['regMinSelect24'];
 $regMinValue25 = $_POST['regMinSelect25'];
 $regMinValue26 = $_POST['regMinSelect26'];
 $regMinValue27 = $_POST['regMinSelect27'];
 $regMinValue28 = $_POST['regMinSelect28'];
 $regMinValue29 = $_POST['regMinSelect29'];
 $regMinValue30 = $_POST['regMinSelect30'];
 $regMinValue31 = $_POST['regMinSelect31'];
 ?>

	<select name="regAMSelect<?php print $rownumber; ?>" <?php print $disable_on_lock; ?>  value="<?php print $regAMSelect;?>" >
  <option value="NO">--</option>

        <?php
        foreach ($ab = array("AM", "PM") as $a) {
            echo $a;
            for ($i = 0; $i <= 31; $i++) {
                if ($rownumber == $i) {
                    echo '
<option value="' . $a . '"';
                    if (isset($_POST['regAMSelect' . $i]) && $_POST['regAMSelect' . $i] == $a) {
                        echo ' selected="selected"';
                    }
					if (isset($regAMSelect) && $regAMSelect == $a) {
                        echo ' selected="selected"';
				    }
                    echo ">" . $a . "</option>\n";
                }
            }
        }

        ?>

</select>
	
 <?php 
 $regAMValue0 = $_POST['regAMSelect0'];
 $regAMValue1 = $_POST['regAMSelect1'];
 $regAMValue2 = $_POST['regAMSelect2'];
 $regAMValue3 = $_POST['regAMSelect3'];
 $regAMValue4 = $_POST['regAMSelect4'];
 $regAMValue5 = $_POST['regAMSelect5'];
 $regAMValue6 = $_POST['regAMSelect6'];
 $regAMValue7 = $_POST['regAMSelect7'];
 $regAMValue8 = $_POST['regAMSelect8'];
 $regAMValue9 = $_POST['regAMSelect9'];
 $regAMValue10 = $_POST['regAMSelect10'];
 $regAMValue11 = $_POST['regAMSelect11'];
 $regAMValue12 = $_POST['regAMSelect12'];
 $regAMValue13 = $_POST['regAMSelect13'];
 $regAMValue14 = $_POST['regAMSelect14'];
 $regAMValue15 = $_POST['regAMSelect15'];
 $regAMValue16 = $_POST['regAMSelect16'];
 $regAMValue17 = $_POST['regAMSelect17'];
 $regAMValue18 = $_POST['regAMSelect18'];
 $regAMValue19 = $_POST['regAMSelect19'];
 $regAMValue20 = $_POST['regAMSelect20'];
 $regAMValue21 = $_POST['regAMSelect21'];
 $regAMValue22 = $_POST['regAMSelect22'];
 $regAMValue23 = $_POST['regAMSelect23'];
 $regAMValue24 = $_POST['regAMSelect24'];
 $regAMValue25 = $_POST['regAMSelect25'];
 $regAMValue26 = $_POST['regAMSelect26'];
 $regAMValue27 = $_POST['regAMSelect27'];
 $regAMValue28 = $_POST['regAMSelect28'];
 $regAMValue29 = $_POST['regAMSelect29'];
 $regAMValue30 = $_POST['regAMSelect30'];
 $regAMValue31 = $_POST['regAMSelect31'];
 ?>
</td>
 
 <?php
 $regMilValue0 = $_POST['regMilitary0'];
 $regMilValue1 = $_POST['regMilitary1'];
 $regMilValue2 = $_POST['regMilitary2'];
 $regMilValue3 = $_POST['regMilitary3'];
 $regMilValue4 = $_POST['regMilitary4'];
 $regMilValue5 = $_POST['regMilitary5'];
 $regMilValue6 = $_POST['regMilitary6'];
 $regMilValue7 = $_POST['regMilitary7'];
 $regMilValue8 = $_POST['regMilitary8'];
 $regMilValue9 = $_POST['regMilitary9'];
 $regMilValue10 = $_POST['regMilitary10'];
 $regMilValue11 = $_POST['regMilitary11'];
 $regMilValue12 = $_POST['regMilitary12'];
 $regMilValue13 = $_POST['regMilitary13'];
 $regMilValue14 = $_POST['regMilitary14'];
 $regMilValue15 = $_POST['regMilitary15'];
 $regMilValue16 = $_POST['regMilitary16'];
 $regMilValue17 = $_POST['regMilitary17'];
 $regMilValue18 = $_POST['regMilitary18'];
 $regMilValue19 = $_POST['regMilitary19'];
 $regMilValue20 = $_POST['regMilitary20'];
 $regMilValue21 = $_POST['regMilitary21'];
 $regMilValue22 = $_POST['regMilitary22'];
 $regMilValue23 = $_POST['regMilitary23'];
 $regMilValue24 = $_POST['regMilitary24'];
 $regMilValue25 = $_POST['regMilitary25'];
 $regMilValue26 = $_POST['regMilitary26'];
 $regMilValue27 = $_POST['regMilitary27'];
 $regMilValue28 = $_POST['regMilitary28'];
 $regMilValue29 = $_POST['regMilitary29'];
 $regMilValue30 = $_POST['regMilitary30'];
 $regMilValue31 = $_POST['regMilitary31'];
 ?>
 
<?php 
$regMilitary0 = $regHourValue0 . $regMinValue0;
 if ($regAMValue0 == 'PM') {$regMilitary0 = $regMilitary0 + 12;}
  else if ($regHourValue0 == '12') {$regMilitary0 = $regMilitary0 - 12;}
$regMilitary1 = $regHourValue1 . $regMinValue1;
 if ($regAMValue1 == 'PM') {$regMilitary1 = $regMilitary1 + 12;}
 else if ($regHourValue1 == '12') {$regMilitary1 = $regMilitary1 - 12;}
$regMilitary2 = $regHourValue2 . $regMinValue2;
 if ($regAMValue2 == 'PM') {$regMilitary2 = $regMilitary2 + 12;}
 else if ($regHourValue2 == '12') {$regMilitary2 = $regMilitary2 - 12;}
$regMilitary3 = $regHourValue3 . $regMinValue3;
 if ($regAMValue3 == 'PM') {$regMilitary3 = $regMilitary3 + 12;}
 else if ($regHourValue3 == '12') {$regMilitary3 = $regMilitary3 - 12;}
$regMilitary4 = $regHourValue4 . $regMinValue4;
 if ($regAMValue4 == 'PM') {$regMilitary4 = $regMilitary4 + 12;}
 else if ($regHourValue4 == '12') {$regMilitary4 = $regMilitary4 - 12;}
$regMilitary5 = $regHourValue5 . $regMinValue5;
 if ($regAMValue5 == 'PM') {$regMilitary5 = $regMilitary5 + 12;}
 else if ($regHourValue5 == '12') {$regMilitary5 = $regMilitary5 - 12;}
$regMilitary6 = $regHourValue6 . $regMinValue6;
 if ($regAMValue6 == 'PM') {$regMilitary6 = $regMilitary6 + 12;}
 else if ($regHourValue6 == '12') {$regMilitary6 = $regMilitary6 - 12;}
$regMilitary7 = $regHourValue7 . $regMinValue7;
 if ($regAMValue7 == 'PM') {$regMilitary7 = $regMilitary7 + 12;}
 else if ($regHourValue7 == '12') {$regMilitary7 = $regMilitary7 - 12;}
$regMilitary8 = $regHourValue8 . $regMinValue8;
 if ($regAMValue8 == 'PM') {$regMilitary8 = $regMilitary8 + 12;}
 else if ($regHourValue8 == '12') {$regMilitary8 = $regMilitary8 - 12;}
$regMilitary9 = $regHourValue9 . $regMinValue9;
 if ($regAMValue9 == 'PM') {$regMilitary9 = $regMilitary9 + 12;}
 else if ($regHourValue9 == '12') {$regMilitary9 = $regMilitary9 - 12;}
$regMilitary10 = $regHourValue10 . $regMinValue10;
 if ($regAMValue10 == 'PM') {$regMilitary10 = $regMilitary10 + 12;}
else if ($regHourValue10 == '12') {$regMilitary10 = $regMilitary10 - 12;}
 $regMilitary11 = $regHourValue11 . $regMinValue11;
 if ($regAMValue11 == 'PM') {$regMilitary11 = $regMilitary11 + 12;}
else if ($regHourValue11 == '12') {$regMilitary11 = $regMilitary11 - 12;}
 $regMilitary12 = $regHourValue12 . $regMinValue12;
 if ($regAMValue12 == 'PM') {$regMilitary12 = $regMilitary12 + 12;}
else if ($regHourValue12 == '12') {$regMilitary12 = $regMilitary12 - 12;}
 $regMilitary13 = $regHourValue13 . $regMinValue13;
 if ($regAMValue13 == 'PM') {$regMilitary13 = $regMilitary13 + 12;}
 else if ($regHourValue13 == '12') {$regMilitary13 = $regMilitary13 - 12;}
$regMilitary14 = $regHourValue14 . $regMinValue14;
 if ($regAMValue14 == 'PM') {$regMilitary14 = $regMilitary14 + 12;}
 else if ($regHourValue14 == '12') {$regMilitary14 = $regMilitary14 - 12;}
$regMilitary15 = $regHourValue15 . $regMinValue15;
 if ($regAMValue15 == 'PM') {$regMilitary15 = $regMilitary15 + 12;}
else if ($regHourValue15 == '12') {$regMilitary15 = $regMilitary15 - 12;}
 $regMilitary16 = $regHourValue16 . $regMinValue16;
 if ($regAMValue16 == 'PM') {$regMilitary16 = $regMilitary16 + 12;}
 else if ($regHourValue16 == '12') {$regMilitary16 = $regMilitary16 - 12;}
$regMilitary17 = $regHourValue17 . $regMinValue17;
 if ($regAMValue17 == 'PM') {$regMilitary17 = $regMilitary17 + 12;}
 else if ($regHourValue17 == '12') {$regMilitary17 = $regMilitary17 - 12;}
$regMilitary18 = $regHourValue18 . $regMinValue18;
 if ($regAMValue18 == 'PM') {$regMilitary18 = $regMilitary18 + 12;}
 else if ($regHourValue18 == '12') {$regMilitary18 = $regMilitary18 - 12;}
$regMilitary19 = $regHourValue19 . $regMinValue19;
 if ($regAMValue19 == 'PM') {$regMilitary19 = $regMilitary19 + 12;}
else if ($regHourValue19 == '12') {$regMilitary19 = $regMilitary19 - 12;}
 $regMilitary20 = $regHourValue20 . $regMinValue20;
 if ($regAMValue20 == 'PM') {$regMilitary20 = $regMilitary20 + 12;}
else if ($regHourValue20 == '12') {$regMilitary20 = $regMilitary20 - 12;}
 $regMilitary21 = $regHourValue21 . $regMinValue21;
 if ($regAMValue21 == 'PM') {$regMilitary21 = $regMilitary21 + 12;}
else if ($regHourValue21 == '12') {$regMilitary21 = $regMilitary21 - 12;}
 $regMilitary22 = $regHourValue22 . $regMinValue22;
 if ($regAMValue22 == 'PM') {$regMilitary22 = $regMilitary22 + 12;}
 else if ($regHourValue22 == '12') {$regMilitary22 = $regMilitary22 - 12;}
$regMilitary23 = $regHourValue23 . $regMinValue23;
 if ($regAMValue23 == 'PM') {$regMilitary23 = $regMilitary23 + 12;}
else if ($regHourValue23 == '12') {$regMilitary23 = $regMilitary23 - 12;}
 $regMilitary24 = $regHourValue24 . $regMinValue24;
 if ($regAMValue24 == 'PM') {$regMilitary24 = $regMilitary24 + 12;}
 else if ($regHourValue24 == '12') {$regMilitary24 = $regMilitary24 - 12;}
$regMilitary25 = $regHourValue25 . $regMinValue25;
 if ($regAMValue25 == 'PM') {$regMilitary25 = $regMilitary25 + 12;}
 else if ($regHourValue25 == '12') {$regMilitary25 = $regMilitary25 - 12;}
$regMilitary26 = $regHourValue26 . $regMinValue26;
 if ($regAMValue26 == 'PM') {$regMilitary26 = $regMilitary26 + 12;}
else if ($regHourValue26 == '12') {$regMilitary26 = $regMilitary26 - 12;}
 $regMilitary27 = $regHourValue27 . $regMinValue27;
 if ($regAMValue27 == 'PM') {$regMilitary27 = $regMilitary27 + 12;}
else if ($regHourValue27 == '12') {$regMilitary27 = $regMilitary27 - 12;}
 $regMilitary28 = $regHourValue28 . $regMinValue28;
 if ($regAMValue28 == 'PM') {$regMilitary28 = $regMilitary28 + 12;}
else if ($regHourValue28 == '12') {$regMilitary28 = $regMilitary28 - 12;}
 $regMilitary29 = $regHourValue29 . $regMinValue29;
 if ($regAMValue29 == 'PM') {$regMilitary29 = $regMilitary29 + 12;}
else if ($regHourValue29 == '12') {$regMilitary29 = $regMilitary29 - 12;}
 $regMilitary30 = $regHourValue30 . $regMinValue30;
 if ($regAMValue30 == 'PM') {$regMilitary30 = $regMilitary30 + 12;}
 else if ($regHourValue30 == '12') {$regMilitary30 = $regMilitary30 - 12;}
$regMilitary31 = $regHourValue31 . $regMinValue31;
 if ($regAMValue31 == 'PM') {$regMilitary31 = $regMilitary31 + 12;}
 else if ($regHourValue31 == '12') {$regMilitary31 = $regMilitary31 - 12;}
?>

		   <input type="hidden" <?php print $disable_on_lock; ?> style="<?php print $rejected_style; ?>" size="2"  name="regMilitary<?php print $rownumber; ?>" value="<?php /** @noinspection PhpExpressionResultUnusedInspection */
           $regMilitary; ?>"></td>
	
	<input type="hidden" <?php print $disable_on_lock; ?> style="<?php print $rejected_style; ?>" size="2"  name="regular_time<?php print $rownumber; ?>" value="<?php print $regular_time; ?>"></td>

<td class="entryRowMiddleCell" style="<?php print $rejected_style; ?>"  onchange="changeflag();">
    <select name="statHourSelect<?php print $rownumber; ?>" <?php print $disable_on_lock; ?> value="<?php print $statHourSelect; ?>">
	<option value="NO" >--</option>
   
<?php

        $xhour = array('1'=> '1', '2'=>'2','3'=>'3','4'=>'4', '5'=>'5', '6'=>'6', '7'=>'7', '8'=>'8', '9'=>'9', '10'=>'10', '11'=>'11', '12'=>'00');
       foreach($xhour as $xkey=>$xvalue)
       {
           echo $xkey;
           for ($i = 0; $i <= 31; $i++) {
               if ($rownumber == $i) {
                   echo '
<option value="' . $xvalue . '"';
                   if (isset($_POST['statHourSelect' . $i]) && $_POST['statHourSelect' . $i] == $xvalue) {
                       echo ' selected="selected"';
                   }
				   if (isset($statHourSelect) && $statHourSelect == $xvalue) {
                         echo ' selected="selected"';
                    }
				   
                   echo ">" . $xkey . "</option>\n";
               }
           }
       }
       ?>
   
	</select>
	
 <?php 
 $statHourValue0 = $_POST['statHourSelect0'];
 $statHourValue1 = $_POST['statHourSelect1'];
 $statHourValue2 = $_POST['statHourSelect2'];
 $statHourValue3 = $_POST['statHourSelect3'];
 $statHourValue4 = $_POST['statHourSelect4'];
 $statHourValue5 = $_POST['statHourSelect5'];
 $statHourValue6 = $_POST['statHourSelect6'];
 $statHourValue7 = $_POST['statHourSelect7'];
 $statHourValue8 = $_POST['statHourSelect8'];
 $statHourValue9 = $_POST['statHourSelect9'];
 $statHourValue10 = $_POST['statHourSelect10'];
 $statHourValue11 = $_POST['statHourSelect11'];
 $statHourValue12 = $_POST['statHourSelect12'];
 $statHourValue13 = $_POST['statHourSelect13'];
 $statHourValue14 = $_POST['statHourSelect14'];
 $statHourValue15 = $_POST['statHourSelect15'];
 $statHourValue16 = $_POST['statHourSelect16'];
 $statHourValue17 = $_POST['statHourSelect17'];
 $statHourValue18 = $_POST['statHourSelect18'];
 $statHourValue19 = $_POST['statHourSelect19'];
 $statHourValue20 = $_POST['statHourSelect20'];
 $statHourValue21 = $_POST['statHourSelect21'];
 $statHourValue22 = $_POST['statHourSelect22'];
 $statHourValue23 = $_POST['statHourSelect23'];
 $statHourValue24 = $_POST['statHourSelect24'];
 $statHourValue25 = $_POST['statHourSelect25'];
 $statHourValue26 = $_POST['statHourSelect26'];
 $statHourValue27 = $_POST['statHourSelect27'];
 $statHourValue28 = $_POST['statHourSelect28'];
 $statHourValue29 = $_POST['statHourSelect29'];
 $statHourValue30 = $_POST['statHourSelect30'];
 $statHourValue31 = $_POST['statHourSelect31'];
 ?>

   <select name="statMinSelect<?php print $rownumber; ?>" <?php print $disable_on_lock; ?>  value="<?php print $statMinSelect;?>">
       <option value="NO">--</option>

	  <?php
        $mi = array('00'=> '.00', '15'=>'.25','30'=>'.50','45'=>'.75');
       foreach($mi as $mikey=>$mivalue)
       {
           echo $mikey;
           for ($i = 0; $i <= 31; $i++) {
               if ($rownumber == $i) {
                   echo '
<option value="' . $mivalue . '"';
                   if (isset($_POST['statMinSelect' . $i]) && $_POST['statMinSelect' . $i] == $mivalue) {
                       echo ' selected="selected"';
                   }
				   if (isset($statMinSelect) && $statMinSelect == $mivalue) {
                         echo ' selected="selected"';
                    }
				   
                   echo ">" . $mikey . "</option>\n";
               }
           }
       }
       ?>
</select>

 <?php 
 $statMinValue0 = $_POST['statMinSelect0'];
 $statMinValue1 = $_POST['statMinSelect1'];
 $statMinValue2 = $_POST['statMinSelect2'];
 $statMinValue3 = $_POST['statMinSelect3'];
 $statMinValue4 = $_POST['statMinSelect4'];
 $statMinValue5 = $_POST['statMinSelect5'];
 $statMinValue6 = $_POST['statMinSelect6'];
 $statMinValue7 = $_POST['statMinSelect7'];
 $statMinValue8 = $_POST['statMinSelect8'];
 $statMinValue9 = $_POST['statMinSelect9'];
 $statMinValue10 = $_POST['statMinSelect10'];
 $statMinValue11 = $_POST['statMinSelect11'];
 $statMinValue12 = $_POST['statMinSelect12'];
 $statMinValue13 = $_POST['statMinSelect13'];
 $statMinValue14 = $_POST['statMinSelect14'];
 $statMinValue15 = $_POST['statMinSelect15'];
 $statMinValue16 = $_POST['statMinSelect16'];
 $statMinValue17 = $_POST['statMinSelect17'];
 $statMinValue18 = $_POST['statMinSelect18'];
 $statMinValue19 = $_POST['statMinSelect19'];
 $statMinValue20 = $_POST['statMinSelect20'];
 $statMinValue21 = $_POST['statMinSelect21'];
 $statMinValue22 = $_POST['statMinSelect22'];
 $statMinValue23 = $_POST['statMinSelect23'];
 $statMinValue24 = $_POST['statMinSelect24'];
 $statMinValue25 = $_POST['statMinSelect25'];
 $statMinValue26 = $_POST['statMinSelect26'];
 $statMinValue27 = $_POST['statMinSelect27'];
 $statMinValue28 = $_POST['statMinSelect28'];
 $statMinValue29 = $_POST['statMinSelect29'];
 $statMinValue30 = $_POST['statMinSelect30'];
 $statMinValue31 = $_POST['statMinSelect31'];
 ?>
 
    <select name="statAMSelect<?php print $rownumber; ?>"<?php print $disable_on_lock; ?>  value="<?php print $statAMSelect;?>" onchange="saveTimesheet2();">
    <option value="NO" >--</option>
        <?php
        foreach ($ab = array("AM", "PM") as $a) {
            echo $a;
            for ($i = 0; $i <= 31; $i++) {
                if ($rownumber == $i) {
                    echo '
<option value="' . $a . '"';
                    if (isset($_POST['statAMSelect' . $i]) && $_POST['statAMSelect' . $i] == $a) {
                        echo ' selected="selected"';
                    }
					if (isset($statAMSelect) && $statAMSelect == $a) {
                        echo ' selected="selected"';
				}
                    echo ">" . $a . "</option>\n";
                }
            }
        }

        ?>
</select>

<?php 
 $statAMValue0 = $_POST['statAMSelect0'];
 $statAMValue1 = $_POST['statAMSelect1'];
 $statAMValue2 = $_POST['statAMSelect2'];
 $statAMValue3 = $_POST['statAMSelect3'];
 $statAMValue4 = $_POST['statAMSelect4'];
 $statAMValue5 = $_POST['statAMSelect5'];
 $statAMValue6 = $_POST['statAMSelect6'];
 $statAMValue7 = $_POST['statAMSelect7'];
 $statAMValue8 = $_POST['statAMSelect8'];
 $statAMValue9 = $_POST['statAMSelect9'];
 $statAMValue10 = $_POST['statAMSelect10'];
 $statAMValue11 = $_POST['statAMSelect11'];
 $statAMValue12 = $_POST['statAMSelect12'];
 $statAMValue13 = $_POST['statAMSelect13'];
 $statAMValue14 = $_POST['statAMSelect14'];
 $statAMValue15 = $_POST['statAMSelect15'];
 $statAMValue16 = $_POST['statAMSelect16'];
 $statAMValue17 = $_POST['statAMSelect17'];
 $statAMValue18 = $_POST['statAMSelect18'];
 $statAMValue19 = $_POST['statAMSelect19'];
 $statAMValue20 = $_POST['statAMSelect20'];
 $statAMValue21 = $_POST['statAMSelect21'];
 $statAMValue22 = $_POST['statAMSelect22'];
 $statAMValue23 = $_POST['statAMSelect23'];
 $statAMValue24 = $_POST['statAMSelect24'];
 $statAMValue25 = $_POST['statAMSelect25'];
 $statAMValue26 = $_POST['statAMSelect26'];
 $statAMValue27 = $_POST['statAMSelect27'];
 $statAMValue28 = $_POST['statAMSelect28'];
 $statAMValue29 = $_POST['statAMSelect29'];
 $statAMValue30 = $_POST['statAMSelect30'];
 $statAMValue31 = $_POST['statAMSelect31'];
 ?>
 </td>

 <?php
 $statMilValue0 = $_POST['statMilitary0'];
 $statMilValue1 = $_POST['statMilitary1'];
 $statMilValue2 = $_POST['statMilitary2'];
 $statMilValue3 = $_POST['statMilitary3'];
 $statMilValue4 = $_POST['statMilitary4'];
 $statMilValue5 = $_POST['statMilitary5'];
 $statMilValue6 = $_POST['statMilitary6'];
 $statMilValue7 = $_POST['statMilitary7'];
 $statMilValue8 = $_POST['statMilitary8'];
 $statMilValue9 = $_POST['statMilitary9'];
 $statMilValue10 = $_POST['statMilitary10'];
 $statMilValue11 = $_POST['statMilitary11'];
 $statMilValue12 = $_POST['statMilitary12'];
 $statMilValue13 = $_POST['statMilitary13'];
 $statMilValue14 = $_POST['statMilitary14'];
 $statMilValue15 = $_POST['statMilitary15'];
 $statMilValue16 = $_POST['statMilitary16'];
 $statMilValue17 = $_POST['statMilitary17'];
 $statMilValue18 = $_POST['statMilitary18'];
 $statMilValue19 = $_POST['statMilitary19'];
 $statMilValue20 = $_POST['statMilitary20'];
 $statMilValue21 = $_POST['statMilitary21'];
 $statMilValue22 = $_POST['statMilitary22'];
 $statMilValue23 = $_POST['statMilitary23'];
 $statMilValue24 = $_POST['statMilitary24'];
 $statMilValue25 = $_POST['statMilitary25'];
 $statMilValue26 = $_POST['statMilitary26'];
 $statMilValue27 = $_POST['statMilitary27'];
 $statMilValue28 = $_POST['statMilitary28'];
 $statMilValue29 = $_POST['statMilitary29'];
 $statMilValue30 = $_POST['statMilitary30'];
 $statMilValue31 = $_POST['statMilitary31'];
 ?>
 
<?php 
$statMilitary0 = $statHourValue0 . $statMinValue0;
 if ($statAMValue0 == 'PM') {$statMilitary0 = $statMilitary0 + 12;}
 else if ($statHourValue0 == '12') {$statMilitary0 = $statMilitary0 - 12;}
$statMilitary1 = $statHourValue1 . $statMinValue1;
 if ($statAMValue1 == 'PM') {$statMilitary1 = $statMilitary1 + 12;}
 else if ($statHourValue1 == '12') {$statMilitary1 = $statMilitary1 - 12;}
$statMilitary2 = $statHourValue2 . $statMinValue2;
 if ($statAMValue2 == 'PM') {$statMilitary2 = $statMilitary2 + 12;}
 else if ($statHourValue2 == '12') {$statMilitary2 = $statMilitary2 - 12;}
 $statMilitary3 = $statHourValue3 . $statMinValue3;
 if ($statAMValue3 == 'PM') {$statMilitary3 = $statMilitary3 + 12;}
 else if ($statHourValue3 == '12') {$statMilitary3 = $statMilitary3 - 12;}
$statMilitary4 = $statHourValue4 . $statMinValue4;
 if ($statAMValue4 == 'PM') {$statMilitary4 = $statMilitary4 + 12;}
 else if ($statHourValue4 == '12') {$statMilitary4 = $statMilitary4 - 12;}
$statMilitary5 = $statHourValue5 . $statMinValue5;
 if ($statAMValue5 == 'PM') {$statMilitary5 = $statMilitary5 + 12;}
 else if ($statHourValue5 == '12') {$statMilitary5 = $statMilitary5 - 12;}
$statMilitary6 = $statHourValue6 . $statMinValue6;
 if ($statAMValue6 == 'PM') {$statMilitary6 = $statMilitary6 + 12;}
else if ($statHourValue6 == '12') {$statMilitary6 = $statMilitary6 - 12;}
 $statMilitary7 = $statHourValue7 . $statMinValue7;
 if ($statAMValue7 == 'PM') {$statMilitary7 = $statMilitary7 + 12;}
else if ($statHourValue7 == '12') {$statMilitary7 = $statMilitary7 - 12;}
 $statMilitary8 = $statHourValue8 . $statMinValue8;
 if ($statAMValue8 == 'PM') {$statMilitary8 = $statMilitary8 + 12;}
else if ($statHourValue8 == '12') {$statMilitary8 = $statMilitary8 - 12;}
 $statMilitary9 = $statHourValue9 . $statMinValue9;
 if ($statAMValue9 == 'PM') {$statMilitary9 = $statMilitary9 + 12;}
else if ($statHourValue9 == '12') {$statMilitary9 = $statMilitary9 - 12;}
 $statMilitary10 = $statHourValue10 . $statMinValue10;
 if ($statAMValue10 == 'PM') {$statMilitary10 = $statMilitary10 + 12;}
 else if ($statHourValue10 == '12') {$statMilitary10 = $statMilitary10 - 12;}
$statMilitary11 = $statHourValue11 . $statMinValue11;
 if ($statAMValue11 == 'PM') {$statMilitary11 = $statMilitary11 + 12;}
else if ($statHourValue11 == '12') {$statMilitary11 = $statMilitary11 - 12;}
 $statMilitary12 = $statHourValue12 . $statMinValue12;
 if ($statAMValue12 == 'PM') {$statMilitary12 = $statMilitary12 + 12;}
 else if ($statHourValue12 == '12') {$statMilitary12 = $statMilitary12 - 12;}
$statMilitary13 = $statHourValue13 . $statMinValue13;
 if ($statAMValue13 == 'PM') {$statMilitary13 = $statMilitary13 + 12;}
else if ($statHourValue13 == '12') {$statMilitary13 = $statMilitary13 - 12;}
 $statMilitary14 = $statHourValue14 . $statMinValue14;
 if ($statAMValue14 == 'PM') {$statMilitary14 = $statMilitary14 + 12;}
else if ($statHourValue14 == '12') {$statMilitary14 = $statMilitary14 - 12;}
 $statMilitary15 = $statHourValue15 . $statMinValue15;
 if ($statAMValue15 == 'PM') {$statMilitary15 = $statMilitary15 + 12;}
else if ($statHourValue15 == '12') {$statMilitary15 = $statMilitary15 - 12;}
 $statMilitary16 = $statHourValue16 . $statMinValue16;
 if ($statAMValue16 == 'PM') {$statMilitary16 = $statMilitary16 + 12;}
else if ($statHourValue16 == '12') {$statMilitary16 = $statMilitary16 - 12;}
 $statMilitary17 = $statHourValue17 . $statMinValue17;
 if ($statAMValue17 == 'PM') {$statMilitary17 = $statMilitary17 + 12;}
else if ($statHourValue17 == '12') {$statMilitary17 = $statMilitary17 - 12;}
 $statMilitary18 = $statHourValue18 . $statMinValue18;
 if ($statAMValue18 == 'PM') {$statMilitary18 = $statMilitary18 + 12;}
 else if ($statHourValue18 == '12') {$statMilitary18 = $statMilitary18 - 12;}
$statMilitary19 = $statHourValue19 . $statMinValue19;
 if ($statAMValue19 == 'PM') {$statMilitary19 = $statMilitary19 + 12;}
 else if ($statHourValue19 == '12') {$statMilitary19 = $statMilitary19 - 12;}
$statMilitary20 = $statHourValue20 . $statMinValue20;
 if ($statAMValue20 == 'PM') {$statMilitary20 = $statMilitary20 + 12;}
 else if ($statHourValue20 == '12') {$statMilitary20 = $statMilitary20 - 12;}
$statMilitary21 = $statHourValue21 . $statMinValue21;
 if ($statAMValue21 == 'PM') {$statMilitary21 = $statMilitary21 + 12;}
 else if ($statHourValue21 == '12') {$statMilitary21 = $statMilitary21 - 12;}
$statMilitary22 = $statHourValue22 . $statMinValue22;
 if ($statAMValue22 == 'PM') {$statMilitary22 = $statMilitary22 + 12;}
else if ($statHourValue22 == '12') {$statMilitary22 = $statMilitary22 - 12;}
 $statMilitary23 = $statHourValue23 . $statMinValue23;
 if ($statAMValue23 == 'PM') {$statMilitary23 = $statMilitary23 + 12;}
 else if ($statHourValue23 == '12') {$statMilitary23 = $statMilitary23 - 12;}
$statMilitary24 = $statHourValue24 . $statMinValue24;
 if ($statAMValue24 == 'PM') {$statMilitary24 = $statMilitary24 + 12;}
else if ($statHourValue24 == '12') {$statMilitary24 = $statMilitary24 - 12;}
 $statMilitary25 = $statHourValue25 . $statMinValue25;
 if ($statAMValue25 == 'PM') {$statMilitary25 = $statMilitary25 + 12;}
else if ($statHourValue25 == '12') {$statMilitary25 = $statMilitary25 - 12;}
 $statMilitary26 = $statHourValue26 . $statMinValue26;
 if ($statAMValue26 == 'PM') {$statMilitary26 = $statMilitary26 + 12;}
else if ($statHourValue26 == '12') {$statMilitary26 = $statMilitary26 - 12;}
 $statMilitary27 = $statHourValue27 . $statMinValue27;
 if ($statAMValue27 == 'PM') {$statMilitary27 = $statMilitary27 + 12;}
 else if ($statHourValue27 == '12') {$statMilitary27 = $statMilitary27 - 12;}
$statMilitary28 = $statHourValue28 . $statMinValue28;
 if ($statAMValue28 == 'PM') {$statMilitary28 = $statMilitary28 + 12;}
else if ($statHourValue28 == '12') {$statMilitary28 = $statMilitary28 - 12;}
 $statMilitary29 = $statHourValue29 . $statMinValue29;
 if ($statAMValue29 == 'PM') {$statMilitary29 = $statMilitary29 + 12;}
else if ($statHourValue29 == '12') {$statMilitary29 = $statMilitary29 - 12;}
 $statMilitary30 = $statHourValue30 . $statMinValue30;
 if ($statAMValue30 == 'PM') {$statMilitary30 = $statMilitary30 + 12;}
 else if ($statHourValue30 == '12') {$statMilitary30 = $statMilitary30 - 12;}
$statMilitary31 = $statHourValue31 . $statMinValue31;
 if ($statAMValue31 == 'PM') {$statMilitary31 = $statMilitary31 + 12;}
else if ($statHourValue31 == '12') {$statMilitary31 = $statMilitary31 - 12;}
 ?>

		   <input type="hidden" <?php print $disable_on_lock; ?> style="<?php print $rejected_style; ?>" size="2"  name="statMilitary<?php print $rownumber; ?>" value="<?php $statMilitary; ?>"></td>
 

 <?php
if ($regMilitary0 <= $statMilitary0) {$BeforeLunchHours0 = $statMilitary0 - $regMilitary0;}
else {$BeforeLunchHours0 = (24 - $regMilitary0) + $statMilitary0;} 
if ($regMilitary1 <= $statMilitary1) {$BeforeLunchHours1 = $statMilitary1 - $regMilitary1;}
else {$BeforeLunchHours1 = (24 - $regMilitary1) + $statMilitary1;}
if ($regMilitary2 <= $statMilitary2) {$BeforeLunchHours2 = $statMilitary2 - $regMilitary2;}
else {$BeforeLunchHours2 = (24 - $regMilitary2) + $statMilitary2;}
if ($regMilitary3 <= $statMilitary3) {$BeforeLunchHours3 = $statMilitary3 - $regMilitary3;}
else {$BeforeLunchHours3 = (24 - $regMilitary3) + $statMilitary3;}
if ($regMilitary4 <= $statMilitary4) {$BeforeLunchHours4 = $statMilitary4 - $regMilitary4;}
else {$BeforeLunchHours4 = (24 - $regMilitary4) + $statMilitary4;}
if ($regMilitary5 <= $statMilitary5) {$BeforeLunchHours5 = $statMilitary5 - $regMilitary5;}
else {$BeforeLunchHours5 = (24 - $regMilitary5) + $statMilitary5;}
if ($regMilitary6 <= $statMilitary6) {$BeforeLunchHours6 = $statMilitary6 - $regMilitary6;}
else {$BeforeLunchHours6 = (24 - $regMilitary6) + $statMilitary6;}
if ($regMilitary7 <= $statMilitary7) {$BeforeLunchHours7 = $statMilitary7 - $regMilitary7;}
else {$BeforeLunchHours7 = (24 - $regMilitary7) + $statMilitary7;}
if ($regMilitary8 <= $statMilitary8) {$BeforeLunchHours8 = $statMilitary8 - $regMilitary8;}
else {$BeforeLunchHours8 = (24 - $regMilitary8) + $statMilitary8;}
if ($regMilitary9 <= $statMilitary9) {$BeforeLunchHours9 = $statMilitary9 - $regMilitary9;}
else {$BeforeLunchHours9 = (24 - $regMilitary9) + $statMilitary9;}
if ($regMilitary10 <= $statMilitary10) {$BeforeLunchHours10 = $statMilitary10 - $regMilitary10;}
else {$BeforeLunchHours10 = (24 - $regMilitary10) + $statMilitary10;}
if ($regMilitary11 <= $statMilitary11) {$BeforeLunchHours11 = $statMilitary11 - $regMilitary11;}
else {$BeforeLunchHours11 = (24 - $regMilitary11) + $statMilitary11;}
if ($regMilitary12 <= $statMilitary12) {$BeforeLunchHours12 = $statMilitary12 - $regMilitary12;}
else {$BeforeLunchHours12 = (24 - $regMilitary12) + $statMilitary12;}
if ($regMilitary13 <= $statMilitary13) {$BeforeLunchHours13 = $statMilitary13 - $regMilitary13;}
else {$BeforeLunchHours13 = (24 - $regMilitary13) + $statMilitary13;}
if ($regMilitary14 <= $statMilitary14) {$BeforeLunchHours14 = $statMilitary14 - $regMilitary14;}
else {$BeforeLunchHours14 = (24 - $regMilitary14) + $statMilitary14;}
if ($regMilitary15 <= $statMilitary15) {$BeforeLunchHours15 = $statMilitary15 - $regMilitary15;}
else {$BeforeLunchHours15 = (24 - $regMilitary15) + $statMilitary15;}
if ($regMilitary16 <= $statMilitary16) {$BeforeLunchHours16 = $statMilitary16 - $regMilitary16;}
else {$BeforeLunchHours16 = (24 - $regMilitary16) + $statMilitary16;}
if ($regMilitary17 <= $statMilitary17) {$BeforeLunchHours17 = $statMilitary17 - $regMilitary17;}
else {$BeforeLunchHours17 = (24 - $regMilitary17) + $statMilitary17;}
if ($regMilitary18 <= $statMilitary18) {$BeforeLunchHours18 = $statMilitary18 - $regMilitary18;}
else {$BeforeLunchHours18 = (24 - $regMilitary18) + $statMilitary18;}
if ($regMilitary19 <= $statMilitary19) {$BeforeLunchHours19 = $statMilitary19 - $regMilitary19;}
else {$BeforeLunchHours19 = (24 - $regMilitary19) + $statMilitary19;}
if ($regMilitary20 <= $statMilitary20) {$BeforeLunchHours20 = $statMilitary20 - $regMilitary20;}
else {$BeforeLunchHours20 = (24 - $regMilitary20) + $statMilitary20;}
if ($regMilitary21 <= $statMilitary21) {$BeforeLunchHours21 = $statMilitary21 - $regMilitary21;}
else {$BeforeLunchHours21 = (24 - $regMilitary21) + $statMilitary21;}
if ($regMilitary22 <= $statMilitary22) {$BeforeLunchHours22 = $statMilitary22 - $regMilitary22;}
else {$BeforeLunchHours22 = (24 - $regMilitary22) + $statMilitary22;}
if ($regMilitary23 <= $statMilitary23) {$BeforeLunchHours23 = $statMilitary23 - $regMilitary23;}
else {$BeforeLunchHours23 = (24 - $regMilitary23) + $statMilitary23;}
if ($regMilitary24 <= $statMilitary24) {$BeforeLunchHours24 = $statMilitary24 - $regMilitary24;}
else {$BeforeLunchHours24 = (24 - $regMilitary24) + $statMilitary24;}
if ($regMilitary25 <= $statMilitary25) {$BeforeLunchHours25 = $statMilitary25 - $regMilitary25;}
else {$BeforeLunchHours25 = (24 - $regMilitary25) + $statMilitary25;}
if ($regMilitary26 <= $statMilitary26) {$BeforeLunchHours26 = $statMilitary26 - $regMilitary26;}
else {$BeforeLunchHours26 = (24 - $regMilitary26) + $statMilitary26;}
if ($regMilitary27 <= $statMilitary27) {$BeforeLunchHours27 = $statMilitary27 - $regMilitary27;}
else {$BeforeLunchHours27 = (24 - $regMilitary27) + $statMilitary27;}
if ($regMilitary28 <= $statMilitary28) {$BeforeLunchHours28 = $statMilitary28 - $regMilitary28;}
else {$BeforeLunchHours28 = (24 - $regMilitary28) + $statMilitary28;}
if ($regMilitary29 <= $statMilitary29) {$BeforeLunchHours29 = $statMilitary29 - $regMilitary29;}
else {$BeforeLunchHours29 = (24 - $regMilitary29) + $statMilitary29;}
if ($regMilitary30 <= $statMilitary30) {$BeforeLunchHours30 = $statMilitary30 - $regMilitary30;}
else {$BeforeLunchHours30 = (24 - $regMilitary30) + $statMilitary30;}
if ($regMilitary31 <= $statMilitary31) {$BeforeLunchHours31 = $statMilitary31 - $regMilitary31;}
else {$BeforeLunchHours31 = (24 - $regMilitary31) + $statMilitary31;}
?>


<input type="hidden" <?php print $disable_on_lock; ?> style="<?php print $rejected_style; ?>" size="2" name="BeforeLunchHours<?php print $rownumber; ?>"   value="<?php print $BeforeLunchHours;   ?>"></td>

<?php
 $stat_time0 = $BeforeLunchHours0;
 $stat_time1 = $BeforeLunchHours1;
 $stat_time2 = $BeforeLunchHours2;
 $stat_time3 = $BeforeLunchHours3;
 $stat_time4 = $BeforeLunchHours4;
 $stat_time5 = $BeforeLunchHours5;
 $stat_time6 = $BeforeLunchHours6;
 $stat_time7 = $BeforeLunchHours7;
 $stat_time8 = $BeforeLunchHours8;
 $stat_time9 = $BeforeLunchHours9;
 $stat_time10 = $BeforeLunchHours10;
 $stat_time11 = $BeforeLunchHours11;
 $stat_time12 = $BeforeLunchHours12;
 $stat_time13 = $BeforeLunchHours13;
 $stat_time14 = $BeforeLunchHours14;
 $stat_time15 = $BeforeLunchHours15;
 $stat_time16 = $BeforeLunchHours16;
 $stat_time17 = $BeforeLunchHours17;
 $stat_time18 = $BeforeLunchHours18;
 $stat_time19 = $BeforeLunchHours19;
 $stat_time20 = $BeforeLunchHours20;
 $stat_time21 = $BeforeLunchHours21;
 $stat_time22 = $BeforeLunchHours22;
 $stat_time23 = $BeforeLunchHours23;
 $stat_time24 = $BeforeLunchHours24;
 $stat_time25 = $BeforeLunchHours25;
 $stat_time26 = $BeforeLunchHours26;
 $stat_time27 = $BeforeLunchHours27;
 $stat_time28 = $BeforeLunchHours28;
 $stat_time29 = $BeforeLunchHours29;
 $stat_time30 = $BeforeLunchHours30;
 $stat_time31 = $BeforeLunchHours31;
?>

<input type="hidden" <?php print $disable_on_lock; ?>  style="<?php print $rejected_style; ?>" size="2" name="stat_time<?php print $rownumber; ?>"   value="<?php 
if ($rownumber == '0') {print($stat_time0);}
else if ($rownumber == '1') {print($stat_time1);}
else if ($rownumber == '2') {print($stat_time2);}
else if ($rownumber == '3') {print($stat_time3);}
else if ($rownumber == '4') {print($stat_time4);}
else if ($rownumber == '5') {print($stat_time5);}
else if ($rownumber == '6') {print($stat_time6);}
else if ($rownumber == '7') {print($stat_time7);}
else if ($rownumber == '8') {print($stat_time8);}
else if ($rownumber == '9') {print($stat_time9);}
else if ($rownumber == '10') {print($stat_time10);}
else if ($rownumber == '11') {print($stat_time11);}
else if ($rownumber == '12') {print($stat_time12);}
else if ($rownumber == '13') {print($stat_time13);}
else if ($rownumber == '14') {print($stat_time14);}
else if ($rownumber == '15') {print($stat_time15);}
else if ($rownumber == '16') {print($stat_time16);}
else if ($rownumber == '17') {print($stat_time17);}
else if ($rownumber == '18') {print($stat_time18);}
else if ($rownumber == '19') {print($stat_time19);}
else if ($rownumber == '20') {print($stat_time20);}
else if ($rownumber == '21') {print($stat_time21);}
else if ($rownumber == '22') {print($stat_time22);}
else if ($rownumber == '23') {print($stat_time23);}
else if ($rownumber == '24') {print($stat_time24);}
else if ($rownumber == '25') {print($stat_time25);}
else if ($rownumber == '26') {print($stat_time26);}
else if ($rownumber == '27') {print($stat_time27);}
else if ($rownumber == '28') {print($stat_time28);}
else if ($rownumber == '29') {print($stat_time29);}
else if ($rownumber == '30') {print($stat_time30);}
else if ($rownumber == '31') {print($stat_time31);}
?>"></td>

	<td class="entryRowMiddleCell" style="<?php print $rejected_style; ?>"  onchange="changeflag();">


	<select name="vacationHourSelect<?php print $rownumber; ?>" <?php print $disable_on_lock; ?>  value="<?php print $vacationHourSelect; ?>">
<option value="NO" >--</option>
	
	<?php
    
        $xhour = array('1'=> '1', '2'=>'2','3'=>'3','4'=>'4', '5'=>'5', '6'=>'6', '7'=>'7', '8'=>'8', '9'=>'9', '10'=>'10', '11'=>'11', '12'=>'00');
       foreach($xhour as $xkey=>$xvalue)
       {
           echo $xkey;
           for ($i = 0; $i <= 31; $i++) {
               if ($rownumber == $i) {
                   echo '
<option value="' . $xvalue . '"';
                   if (isset($_POST['vacationHourSelect' . $i]) && $_POST['vacationHourSelect' . $i] == $xvalue) {
                       echo ' selected="selected"';
                   }
				   if (isset($vacationHourSelect) && $vacationHourSelect == $xvalue) {
                         echo ' selected="selected"';
                    }
				   
                   echo ">" . $xkey . "</option>\n";
               }
           }
       }
       ?>
	
</select>

 <?php 
 $vacationHourValue0 = $_POST['vacationHourSelect0'];
 $vacationHourValue1 = $_POST['vacationHourSelect1'];
 $vacationHourValue2 = $_POST['vacationHourSelect2'];
 $vacationHourValue3 = $_POST['vacationHourSelect3'];
 $vacationHourValue4 = $_POST['vacationHourSelect4'];
 $vacationHourValue5 = $_POST['vacationHourSelect5'];
 $vacationHourValue6 = $_POST['vacationHourSelect6'];
 $vacationHourValue7 = $_POST['vacationHourSelect7'];
 $vacationHourValue8 = $_POST['vacationHourSelect8'];
 $vacationHourValue9 = $_POST['vacationHourSelect9'];
 $vacationHourValue10 = $_POST['vacationHourSelect10'];
 $vacationHourValue11 = $_POST['vacationHourSelect11'];
 $vacationHourValue12 = $_POST['vacationHourSelect12'];
 $vacationHourValue13 = $_POST['vacationHourSelect13'];
 $vacationHourValue14 = $_POST['vacationHourSelect14'];
 $vacationHourValue15 = $_POST['vacationHourSelect15'];
 $vacationHourValue16 = $_POST['vacationHourSelect16'];
 $vacationHourValue17 = $_POST['vacationHourSelect17'];
 $vacationHourValue18 = $_POST['vacationHourSelect18'];
 $vacationHourValue19 = $_POST['vacationHourSelect19'];
 $vacationHourValue20 = $_POST['vacationHourSelect20'];
 $vacationHourValue21 = $_POST['vacationHourSelect21'];
 $vacationHourValue22 = $_POST['vacationHourSelect22'];
 $vacationHourValue23 = $_POST['vacationHourSelect23'];
 $vacationHourValue24 = $_POST['vacationHourSelect24'];
 $vacationHourValue25 = $_POST['vacationHourSelect25'];
 $vacationHourValue26 = $_POST['vacationHourSelect26'];
 $vacationHourValue27 = $_POST['vacationHourSelect27'];
 $vacationHourValue28 = $_POST['vacationHourSelect28'];
 $vacationHourValue29 = $_POST['vacationHourSelect29'];
 $vacationHourValue30 = $_POST['vacationHourSelect30'];
 $vacationHourValue31 = $_POST['vacationHourSelect31'];
 ?>

	<select name="vacationMinSelect<?php print $rownumber; ?>" <?php print $disable_on_lock; ?> value="<?php print $vacationMinSelect;?>">
   

        <?php
        $mi = array('--'=> 'NO', '00'=> '.00', '15'=>'.25','30'=>'.50','45'=>'.75');
        foreach($mi as $mikey=>$mivalue)
        {
            echo $mikey;
            for ($i = 0; $i <= 31; $i++) {
                if ($rownumber == $i) {
                    echo '
<option value="' . $mivalue . '"';
                    if (isset($_POST['vacationMinSelect' . $i]) && $_POST['vacationMinSelect' . $i] == $mivalue) {
                        echo ' selected="selected"';
                    }
					if (isset($vacationMinSelect) && $vacationMinSelect == $mivalue) {
                         echo ' selected="selected"';
                    }
                    echo ">" . $mikey . "</option>\n";
                }
            }
        }


        ?>
</select>


 <?php 
 $vacationMinValue0 = $_POST['vacationMinSelect0'];
 $vacationMinValue1 = $_POST['vacationMinSelect1'];
 $vacationMinValue2 = $_POST['vacationMinSelect2'];
 $vacationMinValue3 = $_POST['vacationMinSelect3'];
 $vacationMinValue4 = $_POST['vacationMinSelect4'];
 $vacationMinValue5 = $_POST['vacationMinSelect5'];
 $vacationMinValue6 = $_POST['vacationMinSelect6'];
 $vacationMinValue7 = $_POST['vacationMinSelect7'];
 $vacationMinValue8 = $_POST['vacationMinSelect8'];
 $vacationMinValue9 = $_POST['vacationMinSelect9'];
 $vacationMinValue10 = $_POST['vacationMinSelect10'];
 $vacationMinValue11 = $_POST['vacationMinSelect11'];
 $vacationMinValue12 = $_POST['vacationMinSelect12'];
 $vacationMinValue13 = $_POST['vacationMinSelect13'];
 $vacationMinValue14 = $_POST['vacationMinSelect14'];
 $vacationMinValue15 = $_POST['vacationMinSelect15'];
 $vacationMinValue16 = $_POST['vacationMinSelect16'];
 $vacationMinValue17 = $_POST['vacationMinSelect17'];
 $vacationMinValue18 = $_POST['vacationMinSelect18'];
 $vacationMinValue19 = $_POST['vacationMinSelect19'];
 $vacationMinValue20 = $_POST['vacationMinSelect20'];
 $vacationMinValue21 = $_POST['vacationMinSelect21'];
 $vacationMinValue22 = $_POST['vacationMinSelect22'];
 $vacationMinValue23 = $_POST['vacationMinSelect23'];
 $vacationMinValue24 = $_POST['vacationMinSelect24'];
 $vacationMinValue25 = $_POST['vacationMinSelect25'];
 $vacationMinValue26 = $_POST['vacationMinSelect26'];
 $vacationMinValue27 = $_POST['vacationMinSelect27'];
 $vacationMinValue28 = $_POST['vacationMinSelect28'];
 $vacationMinValue29 = $_POST['vacationMinSelect29'];
 $vacationMinValue30 = $_POST['vacationMinSelect30'];
 $vacationMinValue31 = $_POST['vacationMinSelect31'];
 ?>
 
	<select name="vacationAMSelect<?php print $rownumber; ?>" <?php print $disable_on_lock; ?> value="<?php print $vacationAMSelect;?>" >
    <option value="NO" >--</option>

        <?php
        foreach ($ab = array("AM", "PM") as $a) {
            echo $a;
            for ($i = 0; $i <= 31; $i++) {
                if ($rownumber == $i) {
                    echo '
<option value="' . $a . '"';
                    if (isset($_POST['vacationAMSelect' . $i]) && $_POST['vacationAMSelect' . $i] == $a) {
                        echo ' selected="selected"';
                    }
					if (isset($vacationAMSelect) && $vacationAMSelect == $a) {
                        echo ' selected="selected"';
				    }
                    echo ">" . $a . "</option>\n";
                }
            }
        }

        ?>

</select>


 <?php 
 $vacationAMValue0 = $_POST['vacationAMSelect0'];
 $vacationAMValue1 = $_POST['vacationAMSelect1'];
 $vacationAMValue2 = $_POST['vacationAMSelect2'];
 $vacationAMValue3 = $_POST['vacationAMSelect3'];
 $vacationAMValue4 = $_POST['vacationAMSelect4'];
 $vacationAMValue5 = $_POST['vacationAMSelect5'];
 $vacationAMValue6 = $_POST['vacationAMSelect6'];
 $vacationAMValue7 = $_POST['vacationAMSelect7'];
 $vacationAMValue8 = $_POST['vacationAMSelect8'];
 $vacationAMValue9 = $_POST['vacationAMSelect9'];
 $vacationAMValue10 = $_POST['vacationAMSelect10'];
 $vacationAMValue11 = $_POST['vacationAMSelect11'];
 $vacationAMValue12 = $_POST['vacationAMSelect12'];
 $vacationAMValue13 = $_POST['vacationAMSelect13'];
 $vacationAMValue14 = $_POST['vacationAMSelect14'];
 $vacationAMValue15 = $_POST['vacationAMSelect15'];
 $vacationAMValue16 = $_POST['vacationAMSelect16'];
 $vacationAMValue17 = $_POST['vacationAMSelect17'];
 $vacationAMValue18 = $_POST['vacationAMSelect18'];
 $vacationAMValue19 = $_POST['vacationAMSelect19'];
 $vacationAMValue20 = $_POST['vacationAMSelect20'];
 $vacationAMValue21 = $_POST['vacationAMSelect21'];
 $vacationAMValue22 = $_POST['vacationAMSelect22'];
 $vacationAMValue23 = $_POST['vacationAMSelect23'];
 $vacationAMValue24 = $_POST['vacationAMSelect24'];
 $vacationAMValue25 = $_POST['vacationAMSelect25'];
 $vacationAMValue26 = $_POST['vacationAMSelect26'];
 $vacationAMValue27 = $_POST['vacationAMSelect27'];
 $vacationAMValue28 = $_POST['vacationAMSelect28'];
 $vacationAMValue29 = $_POST['vacationAMSelect29'];
 $vacationAMValue30 = $_POST['vacationAMSelect30'];
 $vacationAMValue31 = $_POST['vacationAMSelect31'];
 ?>

</td>

 <?php
 $vacationMilValue0 = $_POST['vacationMilitary0'];
 $vacationMilValue1 = $_POST['vacationMilitary1'];
 $vacationMilValue2 = $_POST['vacationMilitary2'];
 $vacationMilValue3 = $_POST['vacationMilitary3'];
 $vacationMilValue4 = $_POST['vacationMilitary4'];
 $vacationMilValue5 = $_POST['vacationMilitary5'];
 $vacationMilValue6 = $_POST['vacationMilitary6'];
 $vacationMilValue7 = $_POST['vacationMilitary7'];
 $vacationMilValue8 = $_POST['vacationMilitary8'];
 $vacationMilValue9 = $_POST['vacationMilitary9'];
 $vacationMilValue10 = $_POST['vacationMilitary10'];
 $vacationMilValue11 = $_POST['vacationMilitary11'];
 $vacationMilValue12 = $_POST['vacationMilitary12'];
 $vacationMilValue13 = $_POST['vacationMilitary13'];
 $vacationMilValue14 = $_POST['vacationMilitary14'];
 $vacationMilValue15 = $_POST['vacationMilitary15'];
 $vacationMilValue16 = $_POST['vacationMilitary16'];
 $vacationMilValue17 = $_POST['vacationMilitary17'];
 $vacationMilValue18 = $_POST['vacationMilitary18'];
 $vacationMilValue19 = $_POST['vacationMilitary19'];
 $vacationMilValue20 = $_POST['vacationMilitary20'];
 $vacationMilValue21 = $_POST['vacationMilitary21'];
 $vacationMilValue22 = $_POST['vacationMilitary22'];
 $vacationMilValue23 = $_POST['vacationMilitary23'];
 $vacationMilValue24 = $_POST['vacationMilitary24'];
 $vacationMilValue25 = $_POST['vacationMilitary25'];
 $vacationMilValue26 = $_POST['vacationMilitary26'];
 $vacationMilValue27 = $_POST['vacationMilitary27'];
 $vacationMilValue28 = $_POST['vacationMilitary28'];
 $vacationMilValue29 = $_POST['vacationMilitary29'];
 $vacationMilValue30 = $_POST['vacationMilitary30'];
 $vacationMilValue31 = $_POST['vacationMilitary31'];
 ?>
 
<?php 

$vacationMilitary0 = $vacationHourValue0 . $vacationMinValue0;
 if ($vacationAMValue0 == 'PM') {$vacationMilitary0 = $vacationMilitary0 + 12;}
 else if ($vacationHourValue0 == '12') {$vacationMilitary0 = $vacationMilitary0 - 12;}
$vacationMilitary1 = $vacationHourValue1 . $vacationMinValue1;
 if ($vacationAMValue1 == 'PM') {$vacationMilitary1 = $vacationMilitary1 + 12;}
  else if ($vacationHourValue1 == '12') {$vacationMilitary1 = $vacationMilitary1 - 12;}
$vacationMilitary2 = $vacationHourValue2 . $vacationMinValue2;
 if ($vacationAMValue2 == 'PM') {$vacationMilitary2 = $vacationMilitary2 + 12;}
 else if ($vacationHourValue2 == '12') {$vacationMilitary2 = $vacationMilitary2 - 12;}
 $vacationMilitary3 = $vacationHourValue3 . $vacationMinValue3;
 if ($vacationAMValue3 == 'PM') {$vacationMilitary3 = $vacationMilitary3 + 12;}
 else if ($vacationHourValue3 == '12') {$vacationMilitary3 = $vacationMilitary3 - 12;}
 $vacationMilitary4 = $vacationHourValue4 . $vacationMinValue4;
 if ($vacationAMValue4 == 'PM') {$vacationMilitary4 = $vacationMilitary4 + 12;}
 else if ($vacationHourValue4 == '12') {$vacationMilitary4 = $vacationMilitary4 - 12;}
 $vacationMilitary5 = $vacationHourValue5 . $vacationMinValue5;
 if ($vacationAMValue5 == 'PM') {$vacationMilitary5 = $vacationMilitary5 + 12;}
 else if ($vacationHourValue5 == '12') {$vacationMilitary5 = $vacationMilitary5 - 12;}
 $vacationMilitary6 = $vacationHourValue6 . $vacationMinValue6;
 if ($vacationAMValue6 == 'PM') {$vacationMilitary6 = $vacationMilitary6 + 12;}
 else if ($vacationHourValue6 == '12') {$vacationMilitary6 = $vacationMilitary6 - 12;}
 $vacationMilitary7 = $vacationHourValue7 . $vacationMinValue7;
 if ($vacationAMValue7 == 'PM') {$vacationMilitary7 = $vacationMilitary7 + 12;}
 else if ($vacationHourValue7 == '12') {$vacationMilitary7 = $vacationMilitary7 - 12;}
 $vacationMilitary8 = $vacationHourValue8 . $vacationMinValue8;
 if ($vacationAMValue8 == 'PM') {$vacationMilitary8 = $vacationMilitary8 + 12;}
 else if ($vacationHourValue8 == '12') {$vacationMilitary8 = $vacationMilitary8 - 12;}
 $vacationMilitary9 = $vacationHourValue9 . $vacationMinValue9;
 if ($vacationAMValue9 == 'PM') {$vacationMilitary9 = $vacationMilitary9 + 12;}
 else if ($vacationHourValue9 == '12') {$vacationMilitary9 = $vacationMilitary9 - 12;}
 $vacationMilitary10 = $vacationHourValue10 . $vacationMinValue10;
 if ($vacationAMValue10 == 'PM') {$vacationMilitary10 = $vacationMilitary10 + 12;}
 else if ($vacationHourValue10 == '12') {$vacationMilitary10 = $vacationMilitary10 - 12;}
 $vacationMilitary11 = $vacationHourValue11 . $vacationMinValue11;
 if ($vacationAMValue11 == 'PM') {$vacationMilitary11 = $vacationMilitary11 + 12;}
 else if ($vacationHourValue11 == '12') {$vacationMilitary11 = $vacationMilitary11 - 12;}
 $vacationMilitary12 = $vacationHourValue12 . $vacationMinValue12;
 if ($vacationAMValue12 == 'PM') {$vacationMilitary12 = $vacationMilitary12 + 12;}
 else if ($vacationHourValue12 == '12') {$vacationMilitary12 = $vacationMilitary12 - 12;}
 $vacationMilitary13 = $vacationHourValue13 . $vacationMinValue13;
 if ($vacationAMValue13 == 'PM') {$vacationMilitary13 = $vacationMilitary13 + 12;}
  else if ($vacationHourValue13 == '12') {$vacationMilitary13 = $vacationMilitary13 - 12;}
$vacationMilitary14 = $vacationHourValue14 . $vacationMinValue14;
 if ($vacationAMValue14 == 'PM') {$vacationMilitary14 = $vacationMilitary14 + 12;}
  else if ($vacationHourValue14 == '12') {$vacationMilitary14 = $vacationMilitary14 - 12;}
$vacationMilitary15 = $vacationHourValue15 . $vacationMinValue15;
 if ($vacationAMValue15 == 'PM') {$vacationMilitary15 = $vacationMilitary15 + 12;}
 else if ($vacationHourValue15 == '12') {$vacationMilitary15 = $vacationMilitary15 - 12;}
 $vacationMilitary16 = $vacationHourValue16 . $vacationMinValue16;
 if ($vacationAMValue16 == 'PM') {$vacationMilitary16 = $vacationMilitary16 + 12;}
 else if ($vacationHourValue16 == '12') {$vacationMilitary16 = $vacationMilitary16 - 12;}
 $vacationMilitary17 = $vacationHourValue17 . $vacationMinValue17;
 if ($vacationAMValue17 == 'PM') {$vacationMilitary17 = $vacationMilitary17 + 12;}
 else if ($vacationHourValue17 == '12') {$vacationMilitary17 = $vacationMilitary17 - 12;}
 $vacationMilitary18 = $vacationHourValue18 . $vacationMinValue18;
 if ($vacationAMValue18 == 'PM') {$vacationMilitary18 = $vacationMilitary18 + 12;}
 else if ($vacationHourValue18 == '12') {$vacationMilitary18 = $vacationMilitary18 - 12;}
 $vacationMilitary19 = $vacationHourValue19 . $vacationMinValue19;
 if ($vacationAMValue19 == 'PM') {$vacationMilitary19 = $vacationMilitary19 + 12;}
 else if ($vacationHourValue19 == '12') {$vacationMilitary19 = $vacationMilitary19 - 12;}
 $vacationMilitary20 = $vacationHourValue20 . $vacationMinValue20;
 if ($vacationAMValue20 == 'PM') {$vacationMilitary20 = $vacationMilitary20 + 12;}
 else if ($vacationHourValue20 == '12') {$vacationMilitary20 = $vacationMilitary20 - 12;}
 $vacationMilitary21 = $vacationHourValue21 . $vacationMinValue21;
 if ($vacationAMValue21 == 'PM') {$vacationMilitary21 = $vacationMilitary21 + 12;}
 else if ($vacationHourValue21 == '12') {$vacationMilitary21 = $vacationMilitary21 - 12;}
 $vacationMilitary22 = $vacationHourValue22 . $vacationMinValue22;
 if ($vacationAMValue22 == 'PM') {$vacationMilitary22 = $vacationMilitary22 + 12;}
 else if ($vacationHourValue22 == '12') {$vacationMilitary22 = $vacationMilitary22 - 12;}
 $vacationMilitary23 = $vacationHourValue23 . $vacationMinValue23;
 if ($vacationAMValue23 == 'PM') {$vacationMilitary23 = $vacationMilitary23 + 12;}
 else if ($vacationHourValue23 == '12') {$vacationMilitary23 = $vacationMilitary23 - 12;}
 $vacationMilitary24 = $vacationHourValue24 . $vacationMinValue24;
 if ($vacationAMValue24 == 'PM') {$vacationMilitary24 = $vacationMilitary24 + 12;}
 else if ($vacationHourValue24 == '12') {$vacationMilitary24 = $vacationMilitary24 - 12;}
 $vacationMilitary25 = $vacationHourValue25 . $vacationMinValue25;
 if ($vacationAMValue25 == 'PM') {$vacationMilitary25 = $vacationMilitary25 + 12;}
 else if ($vacationHourValue25 == '12') {$vacationMilitary25 = $vacationMilitary25 - 12;}
 $vacationMilitary26 = $vacationHourValue26 . $vacationMinValue26;
 if ($vacationAMValue26 == 'PM') {$vacationMilitary26 = $vacationMilitary26 + 12;}
 else if ($vacationHourValue26 == '12') {$vacationMilitary26 = $vacationMilitary26 - 12;}
 $vacationMilitary27 = $vacationHourValue27 . $vacationMinValue27;
 if ($vacationAMValue27 == 'PM') {$vacationMilitary27 = $vacationMilitary27 + 12;}
 else if ($vacationHourValue27 == '12') {$vacationMilitary27 = $vacationMilitary27 - 12;}
 $vacationMilitary28 = $vacationHourValue28 . $vacationMinValue28;
 if ($vacationAMValue28 == 'PM') {$vacationMilitary28 = $vacationMilitary28 + 12;}
 else if ($vacationHourValue28 == '12') {$vacationMilitary28 = $vacationMilitary28 - 12;}
 $vacationMilitary29 = $vacationHourValue29 . $vacationMinValue29;
 if ($vacationAMValue29 == 'PM') {$vacationMilitary29 = $vacationMilitary29 + 12;}
 else if ($vacationHourValu29 == '12') {$vacationMilitary29 = $vacationMilitary29 - 12;}
 $vacationMilitary30 = $vacationHourValue30 . $vacationMinValue30;
 if ($vacationAMValue30 == 'PM') {$vacationMilitary30 = $vacationMilitary30 + 12;}
 else if ($vacationHourValue30 == '12') {$vacationMilitary30 = $vacationMilitary30 - 12;}
 $vacationMilitary31 = $vacationHourValue31 . $vacationMinValue31;
 if ($vacationAMValue31 == 'PM') {$vacationMilitary31 = $vacationMilitary31 + 12;}
 else if ($vacationHourValue31 == '12') {$vacationMilitary31 = $vacationMilitary31 - 12;}
 ?>


 <input type="hidden" <?php print $disable_on_lock; ?>  size="2" style="<?php print $rejected_style; ?> name="vacationMilitary<?php print $rownumber; ?>" value="<?php $vacationMilitary; ?>"></td>

  <input type="hidden" <?php print $disable_on_lock; ?>  size="2" style="<?php print $rejected_style; ?> name="vacation_time_used<?php print $rownumber; ?>" value="<?php print $vacation_time; ?>"></td>
	
	
	<td class="entryRowMiddleCell" style="<?php print $rejected_style; ?>" onchange="changeflag();">

	<select name="sickHourSelect<?php print $rownumber; ?>" <?php print $disable_on_lock; ?> value="<?php print $sickHourSelect; ?>">
 <option value="NO" >--</option>
  
<?php

        $xhour = array('1'=> '1', '2'=>'2','3'=>'3','4'=>'4', '5'=>'5', '6'=>'6', '7'=>'7', '8'=>'8', '9'=>'9', '10'=>'10', '11'=>'11', '12'=>'00');
       foreach($xhour as $xkey=>$xvalue)
       {
           echo $xkey;
           for ($i = 0; $i <= 31; $i++) {
               if ($rownumber == $i) {
                   echo '
<option value="' . $xvalue . '"';
                   if (isset($_POST['sickHourSelect' . $i]) && $_POST['sickHourSelect' . $i] == $xvalue) {
                       echo ' selected="selected"';
                   }
				   
				   if (isset($sickHourSelect) && $sickHourSelect == $xvalue) {
                         echo ' selected="selected"';
                    }
				   
                   echo ">" . $xkey . "</option>\n";
               }
           }
       }
       ?>
</select>

 <?php 
 $sickHourValue0 = $_POST['sickHourSelect0'];
 $sickHourValue1 = $_POST['sickHourSelect1'];
 $sickHourValue2 = $_POST['sickHourSelect2'];
 $sickHourValue3 = $_POST['sickHourSelect3'];
 $sickHourValue4 = $_POST['sickHourSelect4'];
 $sickHourValue5 = $_POST['sickHourSelect5'];
 $sickHourValue6 = $_POST['sickHourSelect6'];
 $sickHourValue7 = $_POST['sickHourSelect7'];
 $sickHourValue8 = $_POST['sickHourSelect8'];
 $sickHourValue9 = $_POST['sickHourSelect9'];
 $sickHourValue10 = $_POST['sickHourSelect10'];
 $sickHourValue11 = $_POST['sickHourSelect11'];
 $sickHourValue12 = $_POST['sickHourSelect12'];
 $sickHourValue13 = $_POST['sickHourSelect13'];
 $sickHourValue14 = $_POST['sickHourSelect14'];
 $sickHourValue15 = $_POST['sickHourSelect15'];
 $sickHourValue16 = $_POST['sickHourSelect16'];
 $sickHourValue17 = $_POST['sickHourSelect17'];
 $sickHourValue18 = $_POST['sickHourSelect18'];
 $sickHourValue19 = $_POST['sickHourSelect19'];
 $sickHourValue20 = $_POST['sickHourSelect20'];
 $sickHourValue21 = $_POST['sickHourSelect21'];
 $sickHourValue22 = $_POST['sickHourSelect22'];
 $sickHourValue23 = $_POST['sickHourSelect23'];
 $sickHourValue24 = $_POST['sickHourSelect24'];
 $sickHourValue25 = $_POST['sickHourSelect25'];
 $sickHourValue26 = $_POST['sickHourSelect26'];
 $sickHourValue27 = $_POST['sickHourSelect27'];
 $sickHourValue28 = $_POST['sickHourSelect28'];
 $sickHourValue29 = $_POST['sickHourSelect29'];
 $sickHourValue30 = $_POST['sickHourSelect30'];
 $sickHourValue31 = $_POST['sickHourSelect31'];?>

	<select name="sickMinSelect<?php print $rownumber; ?>" <?php print $disable_on_lock; ?> value="<?php print $sickMinSelect;?>">
   

        <?php
        $mi = array('--'=> 'NO', '00'=> '.00', '15'=>'.25','30'=>'.50','45'=>'.75');
        foreach($mi as $mikey=>$mivalue)
        {
            echo $mikey;
            for ($i = 0; $i <= 31; $i++) {
                if ($rownumber == $i) {
                    echo '
<option value="' . $mivalue . '"';
                    if (isset($_POST['sickMinSelect' . $i]) && $_POST['sickMinSelect' . $i] == $mivalue) {
                        echo ' selected="selected"';
                    }
					if (isset($sickMinSelect) && $sickMinSelect == $mivalue) {
                         echo ' selected="selected"';
                    }
                    echo ">" . $mikey . "</option>\n";
                }
            }
        }


        ?>
    </select>

 <?php $sickMinValue0 = $_POST['sickMinSelect0'];
 $sickMinValue1 = $_POST['sickMinSelect1'];
 $sickMinValue2 = $_POST['sickMinSelect2'];
 $sickMinValue3 = $_POST['sickMinSelect3'];
 $sickMinValue4 = $_POST['sickMinSelect4'];
 $sickMinValue5 = $_POST['sickMinSelect5'];
 $sickMinValue6 = $_POST['sickMinSelect6'];
 $sickMinValue7 = $_POST['sickMinSelect7'];
 $sickMinValue8 = $_POST['sickMinSelect8'];
 $sickMinValue9 = $_POST['sickMinSelect9'];
 $sickMinValue10 = $_POST['sickMinSelect10'];
 $sickMinValue11 = $_POST['sickMinSelect11'];
 $sickMinValue12 = $_POST['sickMinSelect12'];
 $sickMinValue13 = $_POST['sickMinSelect13'];
 $sickMinValue14 = $_POST['sickMinSelect14'];
 $sickMinValue15 = $_POST['sickMinSelect15'];
 $sickMinValue16 = $_POST['sickMinSelect16'];
 $sickMinValue17 = $_POST['sickMinSelect17'];
 $sickMinValue18 = $_POST['sickMinSelect18'];
 $sickMinValue19 = $_POST['sickMinSelect19'];
 $sickMinValue20 = $_POST['sickMinSelect20'];
 $sickMinValue21 = $_POST['sickMinSelect21'];
 $sickMinValue22 = $_POST['sickMinSelect22'];
 $sickMinValue23 = $_POST['sickMinSelect23'];
 $sickMinValue24 = $_POST['sickMinSelect24'];
 $sickMinValue25 = $_POST['sickMinSelect25'];
 $sickMinValue26 = $_POST['sickMinSelect26'];
 $sickMinValue27 = $_POST['sickMinSelect27'];
 $sickMinValue28 = $_POST['sickMinSelect28'];
 $sickMinValue29 = $_POST['sickMinSelect29'];
 $sickMinValue30 = $_POST['sickMinSelect30'];
 $sickMinValue31 = $_POST['sickMinSelect31'];
 ?>
 
	<select name="sickAMSelect<?php print $rownumber; ?>" <?php print $disable_on_lock; ?> value="<?php print $sickAMSelect;?>" onchange="saveTimesheet2();">
    <option value="NO" >--</option>

        <?php
        foreach ($ab = array("AM", "PM") as $a) {
            echo $a;
            for ($i = 0; $i <= 31; $i++) {
                if ($rownumber == $i) {
                    echo '
<option value="' . $a . '"';
                    if (isset($_POST['sickAMSelect' . $i]) && $_POST['sickAMSelect' . $i] == $a) {
                        echo ' selected="selected"';
                    }
					if (isset($sickAMSelect) && $sickAMSelect == $a) {
                        echo ' selected="selected"';
				    }
                    echo ">" . $a . "</option>\n";
                }
            }
        }

        ?>
</select>

 <?php 
 $sickAMValue0 = $_POST['sickAMSelect0'];
 $sickAMValue1 = $_POST['sickAMSelect1'];
 $sickAMValue2 = $_POST['sickAMSelect2'];
 $sickAMValue3 = $_POST['sickAMSelect3'];
 $sickAMValue4 = $_POST['sickAMSelect4'];
 $sickAMValue5 = $_POST['sickAMSelect5'];
 $sickAMValue6 = $_POST['sickAMSelect6'];
 $sickAMValue7 = $_POST['sickAMSelect7'];
 $sickAMValue8 = $_POST['sickAMSelect8'];
 $sickAMValue9 = $_POST['sickAMSelect9'];
 $sickAMValue10 = $_POST['sickAMSelect10'];
 $sickAMValue11 = $_POST['sickAMSelect11'];
 $sickAMValue12 = $_POST['sickAMSelect12'];
 $sickAMValue13 = $_POST['sickAMSelect13'];
 $sickAMValue14 = $_POST['sickAMSelect14'];
 $sickAMValue15 = $_POST['sickAMSelect15'];
 $sickAMValue16 = $_POST['sickAMSelect16'];
 $sickAMValue17 = $_POST['sickAMSelect17'];
 $sickAMValue18 = $_POST['sickAMSelect18'];
 $sickAMValue19 = $_POST['sickAMSelect19'];
 $sickAMValue20 = $_POST['sickAMSelect20'];
 $sickAMValue21 = $_POST['sickAMSelect21'];
 $sickAMValue22 = $_POST['sickAMSelect22'];
 $sickAMValue23 = $_POST['sickAMSelect23'];
 $sickAMValue24 = $_POST['sickAMSelect24'];
 $sickAMValue25 = $_POST['sickAMSelect25'];
 $sickAMValue26 = $_POST['sickAMSelect26'];
 $sickAMValue27 = $_POST['sickAMSelect27'];
 $sickAMValue28 = $_POST['sickAMSelect28'];
 $sickAMValue29 = $_POST['sickAMSelect29'];
 $sickAMValue30 = $_POST['sickAMSelect30'];
 $sickAMValue31 = $_POST['sickAMSelect31'];?>
 
</td>
 
 <?php
 $sickMilValue0 = $_POST['sickMilitary0'];
 $sickMilValue1 = $_POST['sickMilitary1'];
 $sickMilValue2 = $_POST['sickMilitary2'];
 $sickMilValue3 = $_POST['sickMilitary3'];
 $sickMilValue4 = $_POST['sickMilitary4'];
 $sickMilValue5 = $_POST['sickMilitary5'];
 $sickMilValue6 = $_POST['sickMilitary6'];
 $sickMilValue7 = $_POST['sickMilitary7'];
 $sickMilValue8 = $_POST['sickMilitary8'];
 $sickMilValue9 = $_POST['sickMilitary9'];
 $sickMilValue10 = $_POST['sickMilitary10'];
 $sickMilValue11 = $_POST['sickMilitary11'];
 $sickMilValue12 = $_POST['sickMilitary12'];
 $sickMilValue13 = $_POST['sickMilitary13'];
 $sickMilValue14 = $_POST['sickMilitary14'];
 $sickMilValue15 = $_POST['sickMilitary15'];
 $sickMilValue16 = $_POST['sickMilitary16'];
 $sickMilValue17 = $_POST['sickMilitary17'];
 $sickMilValue18 = $_POST['sickMilitary18'];
 $sickMilValue19 = $_POST['sickMilitary19'];
 $sickMilValue20 = $_POST['sickMilitary20'];
 $sickMilValue21 = $_POST['sickMilitary21'];
 $sickMilValue22 = $_POST['sickMilitary22'];
 $sickMilValue23 = $_POST['sickMilitary23'];
 $sickMilValue24 = $_POST['sickMilitary24'];
 $sickMilValue25 = $_POST['sickMilitary25'];
 $sickMilValue26 = $_POST['sickMilitary26'];
 $sickMilValue27 = $_POST['sickMilitary27'];
 $sickMilValue28 = $_POST['sickMilitary28'];
 $sickMilValue29 = $_POST['sickMilitary29'];
 $sickMilValue30 = $_POST['sickMilitary30'];
 $sickMilValue31 = $_POST['sickMilitary31'];?>
  
<?php $sickMilitary0 = $sickHourValue0 . $sickMinValue0;
 if ($sickAMValue0 == 'PM') {$sickMilitary0 = $sickMilitary0 + 12;}
  else if ($sickHourValue0 == '12') {$sickMilitary0 = $sickMilitary0 - 12;}
$sickMilitary1 = $sickHourValue1 . $sickMinValue1;
 if ($sickAMValue1 == 'PM') {$sickMilitary1 = $sickMilitary1 + 12;}
  else if ($sickHourValue1 == '12') {$sickMilitary1 = $sickMilitary1 - 12;}
$sickMilitary2 = $sickHourValue2 . $sickMinValue2;
 if ($sickAMValue2 == 'PM') {$sickMilitary2 = $sickMilitary2 + 12;}
  else if ($sickHourValue2 == '12') {$sickMilitary2 = $sickMilitary2 - 12;}
$sickMilitary3 = $sickHourValue3 . $sickMinValue3;
 if ($sickAMValue3 == 'PM') {$sickMilitary3 = $sickMilitary3 + 12;}
  else if ($sickHourValue3 == '12') {$sickMilitary3 = $sickMilitary3 - 12;}
$sickMilitary4 = $sickHourValue4 . $sickMinValue4;
 if ($sickAMValue4 == 'PM') {$sickMilitary4 = $sickMilitary4 + 12;}
  else if ($sickHourValue4 == '12') {$sickMilitary4 = $sickMilitary4 - 12;}
$sickMilitary5 = $sickHourValue5 . $sickMinValue5;
 if ($sickAMValue5 == 'PM') {$sickMilitary5 = $sickMilitary5 + 12;}
 else if ($sickHourValue5 == '12') {$sickMilitary5 = $sickMilitary5 - 12;}
 $sickMilitary6 = $sickHourValue6 . $sickMinValue6;
 if ($sickAMValue6 == 'PM') {$sickMilitary6 = $sickMilitary6 + 12;}
 else if ($sickHourValue6 == '12') {$sickMilitary6 = $sickMilitary6 - 12;}
 $sickMilitary7 = $sickHourValue7 . $sickMinValue7;
 if ($sickAMValue7 == 'PM') {$sickMilitary7 = $sickMilitary7 + 12;}
 else if ($sickHourValue7 == '12') {$sickMilitary7 = $sickMilitary7 - 12;}
 $sickMilitary8 = $sickHourValue8 . $sickMinValue8;
 if ($sickAMValue8 == 'PM') {$sickMilitary8 = $sickMilitary8 + 12;}
 else if ($sickHourValue8 == '12') {$sickMilitary8 = $sickMilitary8 - 12;}
 $sickMilitary9 = $sickHourValue9 . $sickMinValue9;
 if ($sickAMValue9 == 'PM') {$sickMilitary9 = $sickMilitary9 + 12;}
 else if ($sickHourValue9 == '12') {$sickMilitary9 = $sickMilitary9 - 12;}
 $sickMilitary10 = $sickHourValue10 . $sickMinValue10;
 if ($sickAMValue10 == 'PM') {$sickMilitary10 = $sickMilitary10 + 12;}
 else if ($sickHourValue10 == '12') {$sickMilitary10 = $sickMilitary10 - 12;}
 $sickMilitary11 = $sickHourValue11 . $sickMinValue11;
 if ($sickAMValue11 == 'PM') {$sickMilitary11 = $sickMilitary11 + 12;}
 else if ($sickHourValue11 == '12') {$sickMilitary11 = $sickMilitary11 - 12;}
 $sickMilitary12 = $sickHourValue12 . $sickMinValue12;
 if ($sickAMValue12 == 'PM') {$sickMilitary12 = $sickMilitary12 + 12;}
 else if ($sickHourValue12 == '12') {$sickMilitary12 = $sickMilitary12 - 12;}
 $sickMilitary13 = $sickHourValue13 . $sickMinValue13;
 if ($sickAMValue13 == 'PM') {$sickMilitary13 = $sickMilitary13 + 12;}
 else if ($sickHourValue13 == '12') {$sickMilitary13 = $sickMilitary13 - 12;}
 $sickMilitary14 = $sickHourValue14 . $sickMinValue14;
 if ($sickAMValue14 == 'PM') {$sickMilitary14 = $sickMilitary14 + 12;}
 else if ($sickHourValue14 == '12') {$sickMilitary14 = $sickMilitary14 - 12;}
 $sickMilitary15 = $sickHourValue15 . $sickMinValue15;
 if ($sickAMValue15 == 'PM') {$sickMilitary15 = $sickMilitary15 + 12;}
 else if ($sickHourValue15 == '12') {$sickMilitary15 = $sickMilitary15 - 12;}
 $sickMilitary16 = $sickHourValue16 . $sickMinValue16;
 if ($sickAMValue16 == 'PM') {$sickMilitary16 = $sickMilitary16 + 12;}
 else if ($sickHourValue16 == '12') {$sickMilitary16 = $sickMilitary16 - 12;}
 $sickMilitary17 = $sickHourValue17 . $sickMinValue17;
 if ($sickAMValue17 == 'PM') {$sickMilitary17 = $sickMilitary17 + 12;}
 else if ($sickHourValue17 == '12') {$sickMilitary17 = $sickMilitary17 - 12;}
 $sickMilitary18 = $sickHourValue18 . $sickMinValue18;
 if ($sickAMValue18 == 'PM') {$sickMilitary18 = $sickMilitary18 + 12;}
  else if ($sickHourValue18 == '12') {$sickMilitary18 = $sickMilitary18 - 12;}
$sickMilitary19 = $sickHourValue19 . $sickMinValue19;
 if ($sickAMValue19 == 'PM') {$sickMilitary19 = $sickMilitary19 + 12;}
  else if ($sickHourValue19 == '12') {$sickMilitary19 = $sickMilitary19 - 12;}
$sickMilitary20 = $sickHourValue20 . $sickMinValue20;
 if ($sickAMValue20 == 'PM') {$sickMilitary20 = $sickMilitary20 + 12;}
  else if ($sickHourValue20 == '12') {$sickMilitary20 = $sickMilitary20 - 12;}
$sickMilitary21 = $sickHourValue21 . $sickMinValue21;
 if ($sickAMValue21 == 'PM') {$sickMilitary21 = $sickMilitary21 + 12;}
 else if ($sickHourValue21 == '12') {$sickMilitary21 = $sickMilitary21 - 12;}
 $sickMilitary22 = $sickHourValue22 . $sickMinValue22;
 if ($sickAMValue22 == 'PM') {$sickMilitary22 = $sickMilitary22 + 12;}
 else if ($sickHourValue22 == '12') {$sickMilitary22 = $sickMilitary22 - 12;}
 $sickMilitary23 = $sickHourValue23 . $sickMinValue23;
 if ($sickAMValue23 == 'PM') {$sickMilitary23 = $sickMilitary23 + 12;}
 else if ($sickHourValue23 == '12') {$sickMilitary23 = $sickMilitary23 - 12;}
 $sickMilitary24 = $sickHourValue24 . $sickMinValue24;
 if ($sickAMValue24 == 'PM') {$sickMilitary24 = $sickMilitary24 + 12;}
 else if ($sickHourValue24 == '12') {$sickMilitary24 = $sickMilitary24 - 12;}
 $sickMilitary25 = $sickHourValue25 . $sickMinValue25;
 if ($sickAMValue25 == 'PM') {$sickMilitary25 = $sickMilitary25 + 12;}
 else if ($sickHourValue25 == '12') {$sickMilitary25 = $sickMilitary25 - 12;}
 $sickMilitary26 = $sickHourValue26 . $sickMinValue26;
 if ($sickAMValue26 == 'PM') {$sickMilitary26 = $sickMilitary26 + 12;}
 else if ($sickHourValue26 == '12') {$sickMilitary26 = $sickMilitary26 - 12;}
 $sickMilitary27 = $sickHourValue27 . $sickMinValue27;
 if ($sickAMValue27 == 'PM') {$sickMilitary27 = $sickMilitary27 + 12;}
 else if ($sickHourValue27 == '12') {$sickMilitary27 = $sickMilitary27 - 12;}
 $sickMilitary28 = $sickHourValue28 . $sickMinValue28;
 if ($sickAMValue28 == 'PM') {$sickMilitary28 = $sickMilitary28 + 12;}
 else if ($sickHourValue28 == '12') {$sickMilitary28 = $sickMilitary28 - 12;}
 $sickMilitary29 = $sickHourValue29 . $sickMinValue29;
 if ($sickAMValue29 == 'PM') {$sickMilitary29 = $sickMilitary29 + 12;}
 else if ($sickHourValue29 == '12') {$sickMilitary29 = $sickMilitary29 - 12;}
 $sickMilitary30 = $sickHourValue30 . $sickMinValue30;
 if ($sickAMValue30 == 'PM') {$sickMilitary30 = $sickMilitary30 + 12;}
 else if ($sickHourValue30 == '12') {$sickMilitary30 = $sickMilitary30 - 12;}
 $sickMilitary31 = $sickHourValue31 . $sickMinValue31;
 if ($sickAMValue31 == 'PM') {$sickMilitary31 = $sickMilitary31 + 12;}
  else if ($sickHourValue31 == '12') {$sickMilitary31 = $sickMilitary31 - 12;}
  ?>
  
 <input type="hidden" <?php print $disable_on_lock; ?> style="<?php print $rejected_style; ?>" size="2"  name="sickMilitary<?php print $rownumber; ?>" value="<?php $sickMilitary; ?>"></td>

 <?php if ($vacationMilitary0 <= $sickMilitary0) {$AfterLunchHours0 = $sickMilitary0 - $vacationMilitary0;}
else {$AfterLunchHours0 = (24 - $vacationMilitary0) + $sickMilitary0;}
 if ($vacationMilitary1 <= $sickMilitary1) {$AfterLunchHours1 = $sickMilitary1 - $vacationMilitary1;}
else {$AfterLunchHours1 = (24 - $vacationMilitary1) + $sickMilitary1;}
if ($vacationMilitary2 <= $sickMilitary2) {$AfterLunchHours2 = $sickMilitary2 - $vacationMilitary2;}
else {$AfterLunchHours2 = (24 - $vacationMilitary2) + $sickMilitary2;}
if ($vacationMilitary3 <= $sickMilitary3) {$AfterLunchHours3 = $sickMilitary3 - $vacationMilitary3;}
else {$AfterLunchHours3 = (24 - $vacationMilitary3) + $sickMilitary3;}
if ($vacationMilitary4 <= $sickMilitary4) {$AfterLunchHours4 = $sickMilitary4 - $vacationMilitary4;}
else {$AfterLunchHours4 = (24 - $vacationMilitary4) + $sickMilitary4;}
if ($vacationMilitary5 <= $sickMilitary5) {$AfterLunchHours5 = $sickMilitary5 - $vacationMilitary5;}
else {$AfterLunchHours5 = (24 - $vacationMilitary5) + $sickMilitary5;}
if ($vacationMilitary6 <= $sickMilitary6) {$AfterLunchHours6 = $sickMilitary6 - $vacationMilitary6;}
else {$AfterLunchHours6 = (24 - $vacationMilitary6) + $sickMilitary6;}
if ($vacationMilitary7 <= $sickMilitary7) {$AfterLunchHours7 = $sickMilitary7 - $vacationMilitary7;}
else {$AfterLunchHours7 = (24 - $vacationMilitary7) + $sickMilitary7;}
if ($vacationMilitary8 <= $sickMilitary8) {$AfterLunchHours8 = $sickMilitary8 - $vacationMilitary8;}
else {$AfterLunchHours8 = (24 - $vacationMilitary8) + $sickMilitary8;}
if ($vacationMilitary9 <= $sickMilitary9) {$AfterLunchHours9 = $sickMilitary9 - $vacationMilitary9;}
else {$AfterLunchHours9 = (24 - $vacationMilitary9) + $sickMilitary9;}
if ($vacationMilitary10 <= $sickMilitary10) {$AfterLunchHours10 = $sickMilitary10 - $vacationMilitary10;}
else {$AfterLunchHours10 = (24 - $vacationMilitary10) + $sickMilitary10;}
if ($vacationMilitary11 <= $sickMilitary11) {$AfterLunchHours11 = $sickMilitary11 - $vacationMilitary11;}
else {$AfterLunchHours11 = (24 - $vacationMilitary11) + $sickMilitary11;}
if ($vacationMilitary12 <= $sickMilitary12) {$AfterLunchHours12 = $sickMilitary12 - $vacationMilitary12;}
else {$AfterLunchHours12 = (24 - $vacationMilitary12) + $sickMilitary12;}
if ($vacationMilitary13 <= $sickMilitary13) {$AfterLunchHours13 = $sickMilitary13 - $vacationMilitary13;}
else {$AfterLunchHours13 = (24 - $vacationMilitary13) + $sickMilitary13;}
if ($vacationMilitary14 <= $sickMilitary14) {$AfterLunchHours14 = $sickMilitary14 - $vacationMilitary14;}
else {$AfterLunchHours14 = (24 - $vacationMilitary14) + $sickMilitary14;}
if ($vacationMilitary15 <= $sickMilitary15) {$AfterLunchHours15 = $sickMilitary15 - $vacationMilitary15;}
else {$AfterLunchHours15 = (24 - $vacationMilitary15) + $sickMilitary15;}
if ($vacationMilitary16 <= $sickMilitary16) {$AfterLunchHours16 = $sickMilitary16 - $vacationMilitary16;}
else {$AfterLunchHours16 = (24 - $vacationMilitary16) + $sickMilitary16;}
if ($vacationMilitary17 <= $sickMilitary17) {$AfterLunchHours17 = $sickMilitary17 - $vacationMilitary17;}
else {$AfterLunchHours17 = (24 - $vacationMilitary17) + $sickMilitary17;}
if ($vacationMilitary18 <= $sickMilitary18) {$AfterLunchHours18 = $sickMilitary18 - $vacationMilitary18;}
else {$AfterLunchHours18 = (24 - $vacationMilitary18) + $sickMilitary18;}
if ($vacationMilitary19 <= $sickMilitary19) {$AfterLunchHours19 = $sickMilitary19 - $vacationMilitary19;}
else {$AfterLunchHours19 = (24 - $vacationMilitary19) + $sickMilitary19;}
if ($vacationMilitary20 <= $sickMilitary20) {$AfterLunchHours20 = $sickMilitary20 - $vacationMilitary20;}
else {$AfterLunchHours20 = (24 - $vacationMilitary20) + $sickMilitary20;}
if ($vacationMilitary21 <= $sickMilitary21) {$AfterLunchHours21 = $sickMilitary21 - $vacationMilitary21;}
else {$AfterLunchHours21 = (24 - $vacationMilitary21) + $sickMilitary21;}
if ($vacationMilitary22 <= $sickMilitary22) {$AfterLunchHours22 = $sickMilitary22 - $vacationMilitary22;}
else {$AfterLunchHours22 = (24 - $vacationMilitary22) + $sickMilitary22;}
if ($vacationMilitary23 <= $sickMilitary23) {$AfterLunchHours23 = $sickMilitary23 - $vacationMilitary23;}
else {$AfterLunchHours23 = (24 - $vacationMilitary23) + $sickMilitary23;}
if ($vacationMilitary24 <= $sickMilitary24) {$AfterLunchHours24 = $sickMilitary24 - $vacationMilitary24;}
else {$AfterLunchHours24 = (24 - $vacationMilitary24) + $sickMilitary24;}
if ($vacationMilitary25 <= $sickMilitary25) {$AfterLunchHours25 = $sickMilitary25 - $vacationMilitary25;}
else {$AfterLunchHours25 = (24 - $vacationMilitary25) + $sickMilitary25;}
if ($vacationMilitary26 <= $sickMilitary26) {$AfterLunchHours26 = $sickMilitary26 - $vacationMilitary26;}
else {$AfterLunchHours26 = (24 - $vacationMilitary26) + $sickMilitary26;}
if ($vacationMilitary27 <= $sickMilitary27) {$AfterLunchHours27 = $sickMilitary27 - $vacationMilitary27;}
else {$AfterLunchHours27 = (24 - $vacationMilitary27) + $sickMilitary27;}
if ($vacationMilitary28 <= $sickMilitary28) {$AfterLunchHours28 = $sickMilitary28 - $vacationMilitary28;}
else {$AfterLunchHours28 = (24 - $vacationMilitary28) + $sickMilitary28;}
if ($vacationMilitary29 <= $sickMilitary29) {$AfterLunchHours29 = $sickMilitary29 - $vacationMilitary29;}
else {$AfterLunchHours29 = (24 - $vacationMilitary29) + $sickMilitary29;}
if ($vacationMilitary30 <= $sickMilitary30) {$AfterLunchHours30 = $sickMilitary30 - $vacationMilitary30;}
else {$AfterLunchHours30 = (24 - $vacationMilitary30) + $sickMilitary30;}
if ($vacationMilitary31 <= $sickMilitary31) {$AfterLunchHours31 = $sickMilitary31 - $vacationMilitary31;}
else {$AfterLunchHours31 = (24 - $vacationMilitary31) + $sickMilitary31;}?>

<?php
 $sick_time0 = $AfterLunchHours0;
 $sick_time1 = $AfterLunchHours1;
 $sick_time2 = $AfterLunchHours2;
 $sick_time3 = $AfterLunchHours3;
 $sick_time4 = $AfterLunchHours4;
 $sick_time5 = $AfterLunchHours5;
 $sick_time6 = $AfterLunchHours6;
 $sick_time7 = $AfterLunchHours7;
 $sick_time8 = $AfterLunchHours8;
 $sick_time9 = $AfterLunchHours9;
 $sick_time10 = $AfterLunchHours10;
 $sick_time11 = $AfterLunchHours11;
 $sick_time12 = $AfterLunchHours12;
 $sick_time13 = $AfterLunchHours13;
 $sick_time14 = $AfterLunchHours14;
 $sick_time15 = $AfterLunchHours15;
 $sick_time16 = $AfterLunchHours16;
 $sick_time17 = $AfterLunchHours17;
 $sick_time18 = $AfterLunchHours18;
 $sick_time19 = $AfterLunchHours19;
 $sick_time20 = $AfterLunchHours20;
 $sick_time21 = $AfterLunchHours21;
 $sick_time22 = $AfterLunchHours22;
 $sick_time23 = $AfterLunchHours23;
 $sick_time24 = $AfterLunchHours24;
 $sick_time25 = $AfterLunchHours25;
 $sick_time26 = $AfterLunchHours26;
 $sick_time27 = $AfterLunchHours27;
 $sick_time28 = $AfterLunchHours28;
 $sick_time29 = $AfterLunchHours29;
 $sick_time30 = $AfterLunchHours30;
 $sick_time31 = $AfterLunchHours31;
?>

  <input type="hidden"  <?php print $disable_on_lock; ?> style="<?php print $rejected_style; ?>" size="2"  name="sick_time<?php print $rownumber; ?>" value="<?php 
  
if ($rownumber == '0') {print($sick_time0);}
else if ($rownumber == '1') {print($sick_time1);}
else if ($rownumber == '2') {print($sick_time2);}
else if ($rownumber == '3') {print($sick_time3);}
else if ($rownumber == '4') {print($sick_time4);}
else if ($rownumber == '5') {print($sick_time5);}
else if ($rownumber == '6') {print($sick_time6);}
else if ($rownumber == '7') {print($sick_time7);}
else if ($rownumber == '8') {print($sick_time8);}
else if ($rownumber == '9') {print($sick_time9);}
else if ($rownumber == '10') {print($sick_time10);}
else if ($rownumber == '11') {print($sick_time11);}
else if ($rownumber == '12') {print($sick_time12);}
else if ($rownumber == '13') {print($sick_time13);}
else if ($rownumber == '14') {print($sick_time14);}
else if ($rownumber == '15') {print($sick_time15);}
else if ($rownumber == '16') {print($sick_time16);}
else if ($rownumber == '17') {print($sick_time17);}
else if ($rownumber == '18') {print($sick_time18);}
else if ($rownumber == '19') {print($sick_time19);}
else if ($rownumber == '20') {print($sick_time20);}
else if ($rownumber == '21') {print($sick_time21);}
else if ($rownumber == '22') {print($sick_time22);}
else if ($rownumber == '23') {print($sick_time23);}
else if ($rownumber == '24') {print($sick_time24);}
else if ($rownumber == '25') {print($sick_time25);}
else if ($rownumber == '26') {print($sick_time26);}
else if ($rownumber == '27') {print($sick_time27);}
else if ($rownumber == '28') {print($sick_time28);}
else if ($rownumber == '29') {print($sick_time29);}
else if ($rownumber == '30') {print($sick_time30);}
else if ($rownumber == '31') {print($sick_time31);}
?>"></td>
 
  <td class="entryRowMiddleCell" style="<?php print $rejected_style; ?>"><input type="checkbox" <?php print $disable_on_lock; ?> onchange="changeflag();" size="2"  name="evening_hours<?php print $rownumber; ?>" <?php if (isset($evening_hours) && $evening_hours=="1") echo "checked";?>  value="<?php print 1; ?>">
	</td>
    <td class="entryRowMiddleCell" style="<?php print $rejected_style; ?>"><input type="checkbox" <?php print $disable_on_lock; ?> onchange="changeflag();" size="2"  name="other<?php print $rownumber; ?>" <?php if (isset($other) && $other=="1") echo "checked";?> value="<?php print 1; ?>">

	
	</td>
    <td class="entryRowMiddleCell" style="<?php print $rejected_style; ?>"><input type="text"  onchange="changeflag()" size="10" name="comment<?php print $rownumber; ?>" id="comment<?php print $rownumber; ?>" value="<?php print $comment; ?>" readonly onclick="commentPanel(this);"></td>
    <td class="entryRowMiddleCell" style="<?php print $rejected_style; ?>"><input type="text" readonly size="2"  name="totalhrs<?php print $rownumber; ?>" value="<?php print $totalhrs; ?>"></td>

    <td class="entryRowMiddleCell" style="<?php print $rejected_style; ?><?php print $enable_on_lock; ?>"><input type="text" onchange="changeflag();roundVal(this);" size="2"  name="adjustment<?php print $rownumber; ?>" value="<?php print $adjustment; ?>" style="<?php print $enable_on_lock; ?>"></td>
    <td style="display:none"><input type="text" <?php print $disable_on_lock; ?> size="2"  name="rejected<?php print $rownumber; ?>" id="rejected<?php print $rownumber; ?>" value="<?php print $rejected; ?>"></td>
    <td style="display:none"><input type="text" <?php print $disable_on_lock; ?> size="2"  name="locked<?php print $rownumber; ?>" id="locked<?php print $rownumber; ?>" value="<?php print $locked; ?>"></td>
    <td style="display:none"><input type="text" <?php print $disable_on_lock; ?> size="2"  name="approved<?php print $rownumber; ?>" id="approved<?php print $rownumber; ?>" value="<?php print $approved; ?>"></td>
    <td class="entryRowMiddleCell" style="<?php print $rejected_style; ?>;display:<?php print $display_for_approval; ?>"><input type="checkbox" name="chkapproval<?php print $rownumber; ?>" id="chkapproval<?php print $rownumber; ?>" value="1" <?php print $chkapproval_checked; ?> onclick="doApprovalPageApprove('chkapproval<?php print $rownumber; ?>','chkreject<?php print $rownumber; ?>','<?php print $rownumber; ?>','<?php print $id; ?>');"></td>
    <td class="entryRowMiddleCell" style="<?php print $rejected_style; ?>;display:<?php print $display_for_approval; ?>"><input type="checkbox" name="chkreject<?php print $rownumber; ?>" id="chkreject<?php print $rownumber; ?>" value="1" <?php print $chkreject_checked; ?> onclick="doApprovalPageReject('chkapproval<?php print $rownumber; ?>','chkreject<?php print $rownumber; ?>','<?php print $rownumber; ?>','<?php print $id; ?>');"></td>
    <td class="entryRowMiddleCell" style="<?php print $rejected_style; ?>;display:<?php print $display_for_entry_rejection; ?>"><input type="button"  id="btnCheckReject<?php print $rownumber; ?>" value="Reason" onclick="showReason('<?php print $id; ?>')" style="font-size:7pt"><input type="button" id="btnClearReject<?php print $rownumber; ?>" value="Clear" onclick="clearReject('<?php print $id; ?>','<?php print $uid; ?>')" style="font-size:7pt"></td>

	

</tr>
	
	