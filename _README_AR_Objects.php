<?php
echo "This example is not meant to be executed. Read the contents of the file.";
exit;
/*
This file shows how to implement the ORM/AR framework to read and write records to a Database table.

Example Database Table:
  db_pk    (auto-increment primary key field)
  db_col1  (other fields of varying types)
  db_col2
  db_col3

The corresponding table-specific class has the following format.
*/
class db_table extends data_operations {
  // Constructor - must have same name as class.
  function db_table() {
    $table = EXAMPLE_DB_TABLE;  // Constant defined in init.php
    $id_field = 'db_pk';        // Primary Key field
    $id_field_is_ai = true;     // Is Primary Key Auto Increment?
    $fields = array(            // Array of all non-PK fields
      'db_col1',
      'db_col2',
      'db_col3'
    );
    // Parent class constructor - populates a $values associative array whose keys are the above $fields
    parent::data_operations($table, $id_field, $fields);
  }
}

// The examples below show how to use object instances from a table-specific class.

//////////////////////////////////////////////////////////////////////////////////////////////////////////////////
// Manually populate an AR object
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////
$obj = new db_table();
$obj->values['db_col1'] = "hello";
$obj->values['db_col2'] = 15;
$obj->values['db_col3'] = implode('-' , $some_array);


//////////////////////////////////////////////////////////////////////////////////////////////////////////////////
// Populate an AR object from an HTML Form submit
// IMPORTANT - Only loads form elements whose names EXACTLY match the object's fields
//             Other $obj->values[] can be populated manually as above.
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////
$obj = new db_table();
$obj->load_from_form_submit();

// After loading
//  $obj->values[] are set with form element values whose names match the object's fields
//  $obj->id_value is set ONLY IF the Primary Key value is hidden in the form


//////////////////////////////////////////////////////////////////////////////////////////////////////////////////
// Populate an AR object with database record from its primary key value
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////
$obj = new db_table();
$obj->load(1);      // 1 is a primary key value for a row

// After loading
//  $obj->values[] are populated with the db field values from that row
//  $obj->id_value contains 1

//////////////////////////////////////////////////////////////////////////////////////////////////////////////////
// Populate an AR object with database record from any database field containing unique values
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////
$obj = new db_table();
$obj->load('field_name','unique value');    // 'unique value' from a database field with unique values

// After loading
//  $obj->values[] are populated with the db field values from that row
//  $obj->id_value contains the value of the primary key field for that row


//////////////////////////////////////////////////////////////////////////////////////////////////////////////////
// AUTO-INCREMENT Primary Key - Save a populated AR object to the database
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////
$obj->save();

// For a new record:
//   The AR object will not yet have an Auto-Increment primary key value.
//   Thus, save() knows to do a mySQL INSERT to create a new DB record and mySql automatically creates the AI PK.
//   After saving, you can retrieve the newly generated Auto-Increment value
$ai_pk = $obj->get_id_value();

// For an existing DB record loaded straight from DB or from an HTML form re-populated from DB:
//    The AR object contains the existing PK value, which you can retrieve using get_id_value() as shown above.
//    Since the AR object already knows the AI PK, save() knows to do an SQL IUPDATE on the existing database record.

//////////////////////////////////////////////////////////////////////////////////////////////////////////////////
// NON AUTO-INCREMENT Primary Key - Save a populated AR object to the database
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////

// For a new record:
//   FIRST: Prior to saving, manually set the unique value that will become the PK.
//   The new PK value goes into a TEMP variable in the AR object, so that save() knows to do a mySQL INSERT.
$obj->set_id_value('UHiougSD87Hst976');  // Some unique value
$obj->save();

// For an existing DB record loaded straight from DB or from an HTML form re-populated from DB:
//    The AR object contains the existing PK value, which you can retrieve using get_id_value().


?>