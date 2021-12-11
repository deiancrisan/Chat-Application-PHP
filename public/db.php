<?php

$connect = new PDO("mysql:host=localhost; dbname=chatapp", "root", "");

// date_default_timezone_set('Asia/Kolkata');

function fetch_user_last_activity($user_id, $connect)
{
 $query = "SELECT * FROM login_details 
 WHERE user_id = '$user_id' 
 ORDER BY last_activity DESC 
 LIMIT 1
 ";
 $statement = $connect->prepare($query);
 $statement->execute();
 $result = $statement->fetchAll();
 foreach($result as $row)
 {
  return $row['last_activity'];
 }
}

// This function will fetch particular user last_activity datetime data from login_details table. So, by using this function we can get particular user last activity datetime, so we can check user is online or not in Live Chat App using Ajax with PHP. For this things we have some code at fetch_user.php file. 

function fetch_user_chat_history($from_user_id, $to_user_id, $connect){
    $query="SELECT * FROM chat_message WHERE (from_user_id = '".$from_user_id."' AND to_user_id ='".$to_user_id."') OR (from_user_id = '".$to_user_id."' AND to_user_id = '".$from_user_id."') ORDER BY timestamp DESC";

    $statement = $connect->prepare($query);
    $statement->execute();
    $result = $statement->fetchAll();
    $output = '<ul class="list-unstyled">';
    foreach($result as $row){
        $user_name='';
        if($row["from_user_id"] == $from_user_id)
        {
            $user_name = '<b class="text-success">You</b>';
        }
        else
        {
            $user_name = '<b class="text-danger">'.get_user_name($row['from_user_id'], $connect).'</b>';
        }
        $output .='
        <li style="border-bottom:1px dotted #ccc">
        <p>'.$user_name.' - '.$row["chat_message"].'
        <div align="right">
        - <small><em>'.$row['timestamp'].'</em></small>
        </div>
        </p>
        </li>';
    }
    $output .= '</ul>';
    return $output;
}

function get_user_name($user_id, $connect){
    $query = "SELECT username FROM login WHERE user_id = '$user_id'";
    $statement = $connect -> prepare($query);
    $statement->execute();
    $result = $statement->fetchAll();
    foreach($result as $row)
    {
        return $row['username'];
    }
}

// For fetch chat conversation we have make fetch_user_chat_history() function in database_connection.php file. This function has fetch latest chat message from mysql database and return data in html format. Here we have make one another function get_user_name() this function has return username of particular user based on value of user_id. So, this two function has use for return chat message in html format and this data has been display under chat dialog box using Ajax. So, this way we have insert chat message into mysql database and then after display in chat dialog box using Ajax with PHP.