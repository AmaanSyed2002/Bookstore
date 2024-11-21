<?php
require_once 'class_data_operations.php';

class logon_records extends data_operations {
    function logon_records() {
        $table = 'syed_final_logins';
        $id_field = 'logins_token';
        $id_field_is_ai = false;
        $fields = array(
            'logins_user_id',
            'logins_created',
            'logins_modified',
            'logins_ip'
        );

        parent::data_operations($table, $id_field, $id_field_is_ai, $fields);
    }
}
?>
