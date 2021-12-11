

<?php

include('db.php');

session_start();

if(!isset($_SESSION['user_id'])){

    header("location:login.php");
}


?>






<html>  
    <head>  
        <title>Chat Application using PHP Ajax Jquery</title>  
  <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
  <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    </head>  
    <body>  
        <div class="container">
   <br />
   
   <h3 align="center">Chat Application using PHP Ajax Jquery</a></h3><br />
   <br />
   
   <div class="table-responsive">
    <h4 align="center">Online User</h4>
    <p align="right">Hi - <?php echo $_SESSION['username']; ?> - <a href="logout.php">Logout</a></p>
    <div id="user_details"></div>
    <div id="user_model_details"></div>
   </div>
  </div>
    </body>  
</html>  


<script>  
$(document).ready(function(){

 fetch_user();

 setInterval(function(){
     update_last_activity();
     fetch_user();
     update_chat_history_data();
 },5000);

 function fetch_user()
 {
  $.ajax({
   url:"fetch_user.php",
   method:"POST",
   success:function(data){
    $('#user_details').html(data);
   }
  })
 }

 function update_last_activity(){
     $.ajax({
         url:"update_last_activity.php",
         success:function()
         {

         }
     })
 }

 // Here we can see make_chat_dialog_box() function. This function will generate chat dialog box for each user based on value of to_user_id and to_user_name argument. So, based on value of these both arguments, this function will make dynamic chat dialog box for every user. Now when this function will be called, this function will be called when we have click on start chat button. So here we can see jquery code in which we can see start chat button class .start_chat which is selector with click event. So when we have click on this button it will fetch value from data-touserid and data-tousername attribute and store in variable. After this it has called make_chat_dialog_box(to_user_id, to_user_name) function and it will make dynamic chat dialog box for particular user of which we have click on start chat button. So, this way we can make dynamic chat dialog box in our online chat application using PHP. 

 function make_chat_dialog_box(to_user_id, to_user_name){
     var modal_content = '<div id="user_dialog_'+to_user_id+'" class="user_dialog" title="You have chat with '+to_user_name+'">';
     modal_content += '<div style="height:400px; border:1px solid #ccc; overflow-y: scroll; margin-bottom:24px; padding:1px;" class="chat_history" data-touserid="'+to_user_id+'" id="chat_history_'+to_user_id+'">';
     modal_content += fetch_user_chat_history(to_user_id);
    modal_content += '</div>';
    modal_content += '<div class="form-group">';
    modal_content += '<textarea name="chat_message_'+to_user_id+'" id="chat_message_'+to_user_id+'" class="form-control"></textarea>';
    modal_content += '</div><div class="form-group" alin="right">';
    modal_content += '<button type="button" name="send_chat" id="'+to_user_id+'" class="btn btn-info send_chat">Send</button></div></div>';
    $('#user_model_details').html(modal_content);
 }

 $(document).on('click', '.start_chat', function(){
     var to_user_id = $(this).data('touserid');
     var to_user_name = $(this).data('tousername');
     make_chat_dialog_box(to_user_id, to_user_name);
     $("#user_dialog_"+to_user_id).dialog({
         autoOpen:false,
         width:400
     });
     $('#user_dialog_'+to_user_id).dialog('open');
 });

 // wrote jquery script on send button which class is .send_chat as selector. In this script we have fetch value of id attribute of this send button in which we have store user id to whom we have send message. After this we have fetch value of textarea in which we have write chat message. After this we have send ajax request to insert_chat.php page for insert chat message into mysql database. 
 $(document).on('click', '.send_chat', function(){
     var to_user_id = $(this).attr('id');
     var chat_message = $('#chat_message_'+to_user_id).val();
     $.ajax({
         url:"insert_chat.php",
         method:"POST", // send data to server
         data:{to_user_id:to_user_id, chat_message:chat_message}, // what value we want to send to server
         success:function(data){
             $('#chat_message_'+to_user_id).val('');
             $('#chat_history_'+to_user_id).html(data);
         }
     })
 });

 function fetch_user_chat_history(to_user_id){
     $.ajax({
         url: "fetch_user_chat_history.php",
         method: "POST",
         data:{to_user_id:to_user_id},
         success:function(data){
             $('#chat_history_'+to_user_id).html(data);
         }
     })
 }

 // Will fetch chat data from mysql and display under chat history div tag of particular user dialog box. After this we have called this function into make_chat_dialog_box(). So when user click on start chat button then it will called make_chat_dialog_box() function for make dynamic chat dialog box and under this function will called fetch_user_chat_history() which fetch chat message of sender and receiver and display under chat dialog box history div tag. 

 function update_chat_history_data(){
     $('.chat_history').each(function(){
         var to_user_id = $(this).data('touserid');
         fetch_user_chat_history(to_user_id);
     })
 }

 $(document).on('click', '.ui-button-icon', function(){
    $('.user_dialog').dialog('destroy').remove();
 })
 
});  
</script>
