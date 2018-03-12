<?php
// $Id:
?>
<tr style="<?php print $hide_weekly_totals; ?>text-align:center">
    <td colspan=11 style="text-align:right;font-weight:bold"><?php print $weekly_totals; ?>&nbsp;&nbsp;&nbsp;&nbsp;</td><?php print $weekly_numbers; ?>
</tr>
<tr><td colspan=30><br /></td></tr>
<tr style="<?php print $hide_grand_totals; ?>;text-align:center">
    <td colspan=11 style="text-align:right;font-weight:bold"><?php print $grand_totals; ?>&nbsp;&nbsp;&nbsp;&nbsp;</td><?php print $grand_numbers; ?>
</tr>
</table>
<div style="width:100%;text-align:center;padding-top:5px;display:<?php print $disabled_style; ?>"><input type="button" value="Save" onclick="saveTimesheet();" style="display:<?php print $disabled_style; ?>"></div>



