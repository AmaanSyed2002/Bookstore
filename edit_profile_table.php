<?php
// edit_profile_table.php
require_once 'class_data_operations.php';

class edit_profile_table extends data_operations {
    function __construct() {
        $table = 'syed_accounts';
        $id_field = 'id';
        $id_field_is_ai = true;
        $fields = array(
            'email',
            'password' 
        );

        parent::__construct($table, $id_field, $id_field_is_ai, $fields);
    }
    
    
}
?>
