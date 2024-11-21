<?php
require_once 'class_data_operations.php';

class syed_final_api extends data_operations {

    function syed_final_api() {
        $table = 'syed_final_api';
        $id_field = 'api_logs_id';
        $id_field_is_ai = true;
        $fields = array(
            'api_logs_access_key', 
            'api_logs_timestamp',
            'api_logs_ip',
            'api_logs_query'
        );

        
        parent::data_operations($table, $id_field, $id_field_is_ai, $fields);
    }

    function verifyToken($token) {
        
        $this->load($token, 'api_logs_access_key');
    
        
        return !empty($this->values);
    }
    
    
    function logApiHit($userId, $ip, $query) {
        // Check if a record with the same access key already exists
        $existingRecord = new syed_final_api();
        $existingRecord->load($userId, 'api_logs_access_key');
    
        if (empty($existingRecord->values)) {
            // If the record doesn't exist, proceed with insertion
            $this->values['api_logs_access_key'] = $userId;
            $this->values['api_logs_timestamp'] = date('Y-m-d H:i:s');
            $this->values['api_logs_ip'] = $ip;
            $this->values['api_logs_query'] = $query;
            
            
            if ($this->save()) {
                echo "API Hit logged successfully.";
            } else {
                echo "Error: Unable to save the API Hit record.";
            }
        } else {
            
            echo "Error: Record with access key $userId already exists.";
        }
    }
    
}    
?>
