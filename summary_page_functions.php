<?php
require_once 'class_data_operations.php';

class syed_api_hits extends data_operations {

    public function __construct($connection) {
        // Use parent constructor to set up the basic properties
        parent::__construct('syed_final_api', 'api_logs_access_key', true, ['api_logs_id', 'api_logs_timestamp', 'api_logs_ip', 'api_logs_query'], $connection);
    }

    
    public function getTotalHits($userId) {
        $this->load($userId, 'api_logs_access_key');
        return $this->id_value;
    }

    
    public function getHitsByQueryType($userId, $queryType) {
        $this->load($userId, 'api_logs_access_key');
        return $this->values["api_logs_$queryType"];
    }

    // Method to get the most recent hit date for a user
    public function getMostRecentHitDate($userId) {
        $this->load($userId, 'api_logs_access_key');
        return $this->values['api_logs_timestamp'];
    }

    
    public function getRecentHits($userId, $limit) {
        $this->load_table('api_logs_timestamp', 'DESC');

        $recentHits = [];
        foreach ($this->records as $record) {
            $recentHits[] = [
                'api_logs_access_key' => $record['api_logs_id'],
                'api_logs_timestamp' => $record['api_logs_timestamp'],
                'api_logs_ip' => $record['api_logs_ip'],
                'api_logs_query' => $record['api_logs_query'],
            ];

            if (count($recentHits) >= $limit) {
                break;
            }
        }

        return $recentHits;
    }
}
?>
