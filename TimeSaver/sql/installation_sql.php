<?php
// $Id
/**
 * @file
 * listkeeper installation sql file.
 *
 * The Timesaver installation routine calls this file during installation to populate the required listkeeper lists,
 * fields and default values.
 */
$sql ="INSERT INTO {listkeeper} (`module`, `category`, `name`, `description`, `listfields`, `active`) VALUES ";
$sql .="('all', '', 'Supervisor to Employee', 'Aligns Supervisors to Employees', '', 1)";
$res=db_query($sql);
$sup_and_emp=db_last_insert_id('listkeeper','id');
variable_set('timesaver_list_id_supervisor_to_employee', $sup_and_emp);

$sql ="INSERT INTO {listkeeper} (`module`, `category`, `name`, `description`, `listfields`, `active`) VALUES ";
$sql .="('all', '', 'Activities', 'Activities', '', 1)";
$res=db_query($sql);
$activity_id=db_last_insert_id('listkeeper','id');
variable_set('timesaver_list_id_activities', $activity_id);

$sql ="INSERT INTO {listkeeper} (`module`, `category`, `name`, `description`, `listfields`, `active`) VALUES ";
$sql .="('all', '', 'Tasks', 'Tasks', '', 1)";
$res=db_query($sql);
$tasks_id=db_last_insert_id('listkeeper','id');
variable_set('timesaver_list_id_tasks', $tasks_id);

$sql ="INSERT INTO {listkeeper} (`module`, `category`, `name`, `description`, `listfields`, `active`) VALUES ";
$sql .="('all', '', 'Projects', 'Projects', '', 1)";
$res=db_query($sql);
$projects_id=db_last_insert_id('listkeeper','id');
variable_set('timesaver_list_id_projects', $projects_id);

$sql ="INSERT INTO {listkeeper} (`module`, `category`, `name`, `description`, `listfields`, `active`) VALUES ";
$sql .="('all', '', 'Delegates', 'Links people to their delegated entry person', '', 1)";
$res=db_query($sql);
$delegates_id=db_last_insert_id('listkeeper','id');
variable_set('timesaver_list_id_delegates', $delegates_id);

$sql ="INSERT INTO {listkeeper_fields} (`lid`, `fieldname`, `value_by_function`, `width`, `predefined_function`) VALUES ";
$sql .="( %d, 'Supervisor', 'listkeeper_get_users', 0, 1)";
$res=db_query($sql,$sup_and_emp);

$sql ="INSERT INTO {listkeeper_fields} (`lid`, `fieldname`, `value_by_function`, `width`, `predefined_function`) VALUES ";
$sql .="( %d, 'Employee', 'listkeeper_get_users', 0, 1)";
$res=db_query($sql,$sup_and_emp);


$sql ="INSERT INTO {listkeeper_fields} (`lid`, `fieldname`, `value_by_function`, `width`, `predefined_function`) VALUES ";
$sql .="( %d, 'Activity', '', 0, 0)";
$res=db_query($sql,$activity_id);

$sql ="INSERT INTO {listkeeper_fields} (`lid`, `fieldname`, `value_by_function`, `width`, `predefined_function`) VALUES ";
$sql .="( %d, 'Activity', '[list:2,0]', 0, 1)";
$res=db_query($sql,$tasks_id);

$sql ="INSERT INTO {listkeeper_fields} (`lid`, `fieldname`, `value_by_function`, `width`, `predefined_function`) VALUES ";
$sql .="( %d, 'Task Name', '', 0, 0)";
$res=db_query($sql,$tasks_id);

$sql ="INSERT INTO {listkeeper_fields} (`lid`, `fieldname`, `value_by_function`, `width`, `predefined_function`) VALUES ";
$sql .="( %d, 'Activity', '[list:2,0]', 0, 1)";
$res=db_query($sql,$projects_id);


$sql ="INSERT INTO {listkeeper_fields} (`lid`, `fieldname`, `value_by_function`, `width`, `predefined_function`) VALUES ";
$sql .="( %d, 'Project', '', 0, 0)";
$res=db_query($sql,$projects_id);

$sql ="INSERT INTO {listkeeper_fields} (`lid`, `fieldname`, `value_by_function`, `width`, `predefined_function`) VALUES ";
$sql .="(%d, 'Delegated User', 'listkeeper_get_users', 0, 1)";
$res=db_query($sql,$delegates_id);

$sql ="INSERT INTO {listkeeper_fields} (`lid`, `fieldname`, `value_by_function`, `width`, `predefined_function`) VALUES ";
$sql .="(%d, 'Employee', 'listkeeper_get_users', 0, 1)";
$res=db_query($sql,$delegates_id);


$sql ="INSERT INTO {listkeeper_items} (`lid`, `value`, `itemorder`, `active`) VALUES ";
$sql .="( %d, '1,1', 20, 1)";
$res=db_query($sql,$sup_and_emp);

$sql ="INSERT INTO {listkeeper_items} (`lid`, `value`, `itemorder`, `active`) VALUES ";
$sql .="( %d, 'Activity #1', 10, 1)";
$res=db_query($sql,$activity_id);
$activity_1=db_last_insert_id('listkeeper_items','id');

$sql ="INSERT INTO {listkeeper_items} (`lid`, `value`, `itemorder`, `active`) VALUES ";
$sql .="( %d, 'Activity #2', 20, 1)";
$res=db_query($sql,$activity_id);
$activity_2=db_last_insert_id('listkeeper_items','id');

$sql ="INSERT INTO {listkeeper_items} (`lid`, `value`, `itemorder`, `active`) VALUES ";
$sql .="( %d, '%d,Task #1.1', 10, 1)";
$res=db_query($sql,$tasks_id, $activity_1);

$sql ="INSERT INTO {listkeeper_items} (`lid`, `value`, `itemorder`, `active`) VALUES ";
$sql .="( %d, '%d,Task #2.1', 20, 1)";
$res=db_query($sql,$tasks_id, $activity_2);

$sql ="INSERT INTO {listkeeper_items} (`lid`, `value`, `itemorder`, `active`) VALUES ";
$sql .="( %d, '%d,Task #1.2', 30, 1)";
$res=db_query($sql,$tasks_id, $activity_1);

$sql ="INSERT INTO {listkeeper_items} (`lid`, `value`, `itemorder`, `active`) VALUES ";
$sql .="( %d, '%d,Task #2.2', 40, 1)";
$res=db_query($sql,$tasks_id, $activity_2);


$sql ="INSERT INTO {listkeeper_items} (`lid`, `value`, `itemorder`, `active`) VALUES ";
$sql .="( %d, '%d,Project #1.1', 10, 1)";
$res=db_query($sql,$projects_id, $activity_1);

$sql ="INSERT INTO {listkeeper_items} (`lid`, `value`, `itemorder`, `active`) VALUES ";
$sql .="( %d, '%d,Project #2.1', 20, 1)";
$res=db_query($sql,$projects_id, $activity_2);

$sql ="INSERT INTO {listkeeper_items} (`lid`, `value`, `itemorder`, `active`) VALUES ";
$sql .="( %d, '%d,Project #1.2', 30, 1)";
$res=db_query($sql,$projects_id, $activity_1);

$sql ="INSERT INTO {listkeeper_items} (`lid`, `value`, `itemorder`, `active`) VALUES ";
$sql .="( %d, '%d,Project #2.2', 40, 1)";
$res=db_query($sql,$projects_id, $activity_2);

$sql ="INSERT INTO {listkeeper_items} (`lid`, `value`, `itemorder`, `active`) VALUES ";
$sql .="( 5, '1,1', 10, 1)";
$res=db_query($sql,$delegates_id);

