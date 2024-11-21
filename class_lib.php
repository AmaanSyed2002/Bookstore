<?php
/* 
This class is just a wrapper for some useful php utility functions.

All the methods are static, so you don't have to instantiate a lib object to call them.

Simply do

lib::function_name()

*/

class lib {

    /******************************************************************************************
    Function for database queries with built in SQL error reporting
    ******************************************************************************************/
    
    static function db_query($query) {
        // this is a wrapper for the PHP $mysqli->query() function with built-in error reporting
        // $mysqli is the global DB connection defined BEFORE this lib is loaded
        
        global $mysqli;  // from init file
        
        $result = $mysqli->query($query) or trigger_error("Query Failed! SQL: $query - Error: ".mysqli_error($mysqli), E_USER_ERROR);
        return $result;
    }


    /******************************************************************************************
    Functions to help with HTML forms
    ******************************************************************************************/

    static function textarea_to_html($text) {
        $r = str_replace("\r\n", '<br>', $text);
        $r = str_replace("\n", '<br>', $r);
        return $r;
    }
    
    static function menu_from_assoc_array($name, &$array, $dummy_option='', $selected='', $multiple='', $event_handler='') {
  
        // The $array keys become the menu's hidden values.  The $array values become the visible text on the menu.
        
        // The id doesn't need the [] but the menu name needs them for multiple selection menus.
        $id = str_replace(array('[',']'),'',$name); 
        
        $menu = "<select name=\"$name\" id=\"$id\" $multiple $event_handler >\n";
        
        if ($dummy_option) {
            // Option such as "Choose Item" for first item of single selection menu
            $menu .= "<option value=\"\" ";
            if ($default == '' ) {
                $menu .= "selected='yes'";
            }
            $menu .= ">$dummy_option</option>\n";
        }
        
         foreach ($array as $key=>$value) {
              $menu .= "<option value=\"$key\" ";
              if ( (!is_array($selected) && $key==$selected) || (is_array($selected) && in_array($key,$selected)) ) {
                  $menu .= "selected='yes'";
              }
              $menu .= ">$value</option>\n";
         }
         $menu .= "</select>\n";
         return $menu;
     } 
     
     
    /******************************************************************************************
    Functions for Date formatting
    ******************************************************************************************/

    static function nice_date ($timestamp, $format) {
        if ($timestamp == '' || $timestamp == '0000-00-00 00:00:00') return '';
        
        if ($timestamp == 'now') {
            // UNIX Timestamp
            $timestamp = time();
        } 
        else {
            // UNIX Timestamp from string
            $timestamp = strtotime($timestamp);
            // Examples
            // strtotime("now")  same as time()
            // strtotime("10 September 2000")
            // strtotime("+1 day")   from now
            // strtotime("+1 year")  from now
        }

        switch ($format) {
            case 'date' :
                // 1/1/06
                $f = 'n/j/y';
                break;
            case 'datetime' :
                // 1/1/06 01:59:59 PM
                $f = 'n/j/y h:i:s A';
                break;
            case 'shortdatetime' :
                // 1/1/06 13:59:59
                $f = 'n/j/y H:i:s';
                break;
            case 'time' :
                // 01:59 PM
                $f = 'h:i A';
                break;
            case 'mysql_timestamp' :
                // sortable date format
                // 2006-01-01 13:01:01 
                $f = 'Y-m-d H:i:s';
                break;
            case 'mysql_datestamp' :
                // sortable date format
                // 2006-01-01
                $f = 'Y-m-d';
                break;
        }
        return date($f,$timestamp);
    } // end nice_date
    
}//end class lib
?>