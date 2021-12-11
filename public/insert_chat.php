<?php

include('db.php');

session_start();

$data = array(
    ':to_user_id' => $_POST['to_user_id'],
    ':from_user_id' => $_SESSION['user_id'],
    ':chat_message' => $_POST['chat_message'],
    ':status' => '1'
);

$query = "INSERT INTO chat_message (to_user_id, from_user_id, chat_message, status) VALUES (:to_user_id, :from_user_id, :chat_message, :status)";

$statement = $connect -> prepare($query);

if($statement -> execute($data)){
    echo fetch_user_chat_history($_SESSION['user_id'], $_POST['to_user_id'], $connect);
}

// Insert chat message into Mysql table. After insert into database we want to display all chat message conversation in chat dialog box. So we have to fetch chat data from database.