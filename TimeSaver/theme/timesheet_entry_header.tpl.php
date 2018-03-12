<?php
// $Id:
?>
<br>

<table style="margin-left:15px;border-collapse:collapse">
<tr>
    <th style="display:none">id</th>
    <th style="display:none">uid</th>
    <th style="display:none">date</th>
    <th >Date</th>
    <th>&nbsp;</th>
    <th><img  src="<?php print $images_dir; ?>/del.png"  border =0 onclick="<?php print $onclick; ?>" title="Delete Entry"></th>
    <th class="verticalText"><?php print $timesaver_activity_id; ?></th>
    <th class="verticalText"><?php print $project_id; ?></th>
    <th class="verticalText"><?php print $regular_time; ?></th>
    <th class="verticalText"><?php print $stat_time; ?></th>
    <th class="verticalText"><?php print $vacation_time_used; ?></th>
    <th class="verticalText"><?php print $sick_time; ?></th>
    <th class="verticalText"><?php print $evening_hours; ?></th>
    <th class="verticalText"><?php print $other; ?></th>
    <th class="verticalText"><?php print $comment; ?></th>
    <th class="verticalText"><?php print $totalhrs; ?></th>
    <th class="verticalText" style="<?php print $enable_on_lock; ?>"><?php print $adjustment; ?></th>

<?php print $header_item_for_approval; ?>
<?php print $header_item_for_reject; ?>

</tr>
