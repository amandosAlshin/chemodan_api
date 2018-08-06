<?php
    include 'config.php';
    class model
    {
        function comment_add_model($data){
          $connect = $this->connect();
          $query = "INSERT INTO `comments`
          (
            `email`,
            `subject`,
            `comment`,
            `ipclient`
          )
          VALUES (
            '{$data['email']}',
            '{$data['subject']}',
            '{$data['comment']}',
            '{$data['ipclient']}'
          )";
          $result = mysqli_query($connect,$query);
          if(!$connect->error){
            return true;
          }else{
            echo json_encode(array('type'=>'error','msg'=>'system error comment add model'));
            die;
          }
        }
        function comments_list_model(){
          $connect = $this->connect();
          $query = "
            SELECT
              c.id,
              c.email,
              c.comment,
              c.subject,
              c.ipclient
            FROM comments c ORDER BY id DESC
          ";
          $result = mysqli_query($connect,$query);
          if(!$connect->error){
              $myrows = $this->arrayResult($result);
              return $myrows;
          }else{
            echo json_encode(array('type'=>'error','msg'=>'system error comments list model'));
            die;
          }
        }
        function check_comment_user_again($data){
          $connect = $this->connect();
          $query="SELECT c.id,c.ipclient,c.email
                  FROM comments c
                  WHERE email = '{$data['email']}'";
          $result = mysqli_query($connect,$query);
          if(!$connect->error){
            $num_rows = mysqli_num_rows($result);
            if($num_rows>0){
              echo json_encode(array('type'=>'error','msg'=>'вы уже комментировали'));
              die;
            }else{
              return true;
            }
          }else{
            echo json_encode(array('type'=>'error','msg'=>'system error check comment user again'));
            die;
          }
        }
        function arrayResult($data){
           $result = array();
           while($row = mysqli_fetch_assoc($data)){
               $result[] = $row;
           }
           return $result;
        }
        function connect(){
          $connection = new mysqli(db_server, db_user, db_key, db_name);
          if (mysqli_connect_errno()) {
               mysqli_connect_error();
              die;
          }
          mysqli_set_charset($connection, 'utf8');
          return $connection;
       }
    }
?>
