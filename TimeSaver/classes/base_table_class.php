<?php
// $Id: base_table_class.php,v 1.9 Exp $
/**
 * @file
 * Base table class definition file.
 *
 * This is the base class that each table class extends.
 */
class TABLE{
  var $fulltablename;     // table name
  var $tablename;         // alias name
  var $fieldlist;         // list of fields in this table
  var $fieldvals;         //list of values for fields
  var $number_of_columns;   //holds the number of columns dynamically determined in this variable
  var $fetched_data;        //holds the last DB_fetchArray data

  function clear_column_values($set_value_to=NULL) {
    $this->fieldvals=array();
    for ($cntr=0; $cntr < $this->number_of_columns; $cntr++) {
      $this->fieldvals[$this->fieldlist[$cntr]] = $set_value_to;
    }
  }

  function set_column_value($col, $set_value_to=NULL) {
    $this->fieldvals[$col]=$set_value_to;
  }

  function generate_table_header($skip_fetch=false, $already_fetched_data=NULL) {
    if (isset($already_fetched_data)) { //if we're passing in an array of fetched data, set our current table's fetched data to it
      $this->fetched_data=$already_fetched_data;
    }
    if (isset($this->result_set) || isset($already_fetched_data)) {  //essentially if we've got an explicitly set fetched data set, enter into the loop
      if (!$skip_fetch) {  //we'll skip the fetch if we've forced in some data
        $this->fetched_data=db_fetch_array($this->result_set);
      }
      $this->fieldvals=array();
      for ($cntr=0;$cntr<$this->number_of_columns;$cntr++) {
        $this->fieldvals[$this->fieldlist[$cntr]]=$this->fetched_data[$this->fieldlist[$cntr]];
      }
    }
  }

  function populate_next_fetched_data_array() {
    $this->fetched_data=db_fetch_array($this->result_set);
  }

  function return_columns() {
    $retval='';
    for ($cntr=0;$cntr<$this->number_of_columns;$cntr++) {
      if ($retval!='') $retval .=",";
      $retval.=$this->fieldlist[$cntr];
    }
    return $retval;
  }

}

?>