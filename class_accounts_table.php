<?php

require_once 'class_data_operations.php';
require_once 'auth_functions.php';

class people extends data_operations {

    function people() {
        $table = 'syed_accounts';
        $id_field = 'id';
        $id_field_is_ai = true;
        $fields = array(
            'name',
            'email',
            'password',
            'time',
            'ip',
            'api_key' 
        );

        parent::data_operations($table, $id_field, $id_field_is_ai, $fields);
    }

   

    function authenticate_login($email, $password, $rememberMe) {
        $this->load($email, 'email');

        if (!empty($this->values['email']) && hash('sha256', $password) === $this->values['password']) {
            $logonToken = generate_logon_token($email);
            $logon = new logon_records();
            $logon->values['logon_token'] = $logonToken;
            $logon->values['profile_id'] = $this->get_id_value();
            $logon->values['created_at'] = date('Y-m-d H:i:s');
            $logon->values['last_modified_at'] = $logon->values['created_at'];
            $logon->values['ip_address'] = $_SERVER['REMOTE_ADDR'];
            $logon->save();

            $_SESSION['user_id'] = $this->get_id_value();

            if ($rememberMe) {
                set_remember_me_cookie($email);
            }

            header("Location: thank_you.php");
            exit;
        } else {
            $loginError = "Invalid email/password combination. Please try again.";
            return $loginError;
        }
    }
}

?>
