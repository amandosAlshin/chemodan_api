<?php
include 'model.php';
class Comments extends model    
{
  
  function comment_add(){
      if(!isset($_POST['email']) || $_POST['email'] == ''){
        echo json_encode(array('type' => 'error','msg'=>'Вы не заполнили поле E-Mail'));
        die;
      }else{
        if(!$this->check_length_input_var($_POST['email'],45,3)){
          echo json_encode(array('type' => 'error','msg'=>'Email минимум 3 символа, максимум 45 символов'));
          die;
        }
      }
      if(!isset($_POST['subject']) || $_POST['subject'] == ''){
        echo json_encode(array('type' => 'error','msg'=>"Вы не заполнили поле Тема"));
        die;
      }else{
        if(!$this->check_length_input_var($_POST['subject'],45,3)){
          echo json_encode(array('type' => 'error','msg'=>'Тема минимум 3 символа, максимум 45 символов'));
          die;
        }
      }
      if(!isset($_POST['comment']) || $_POST['comment'] == ''){
        echo json_encode(array('type' => 'error','msg'=>"Вы не заполнили поле коментария"));
        die;
      }else{
        if(!$this->check_length_input_var($_POST['comment'],355,1)){
          echo json_encode(array('type' => 'error','msg'=>'Коментария минимум 1 символ, максимум 355 символов'));
          die;
        }
      }
      if(!isset($_SERVER['REMOTE_ADDR']) || $_SERVER['REMOTE_ADDR'] == ''){
        echo json_encode(array('type' => 'error','msg'=>'Не получен ipclient'));
        die;
      }
      $check_again_data = array(
        'email'=>$this->trimPostData($_POST['email'])
      );
      $check_again = model::check_comment_user_again($check_again_data);
      
      if($check_again){
        $data = array(
          'email'=>$this->trimPostData($_POST['email']),
          'subject'=>$this->trimPostData($_POST['subject']),
          'comment'=>$this->trimPostData($_POST['comment']),
          'ipclient'=>$_SERVER['REMOTE_ADDR']
        );
        $comment_add = model::comment_add_model($data);
        if($comment_add){
          echo json_encode(array('type' => 'ok','msg'=>"Комментария добавлена"));
          die;
        }  
      }
      
    }
  function comments_list(){
      $comments_list = model::comments_list_model();
      echo json_encode(array('type'=>'ok','data'=>$comments_list));
      die;
  }
  function check_length_input_var($input,$max,$min){
    if((strlen($input) > $max) || (strlen($input) < $min)){
      return false;
    }else{
      return true;
    }
  }
  function trimPostData($data){
     $data = stripslashes($data);
     $data = htmlspecialchars($data);
     $data = trim($data);
     return $data;
  }
}
$Comments = new Comments;
if(!empty($_POST['method'])){
    $get = $_POST['method'];
    if(method_exists($Comments,$get)){
        $Comments->$get();
    }else{
        echo json_encode(array('type' => 'error','sush'));
        die;
    }
}else{
  echo json_encode(array('type' => 'error','get jok demek zapros bolgan jok'));
  die;      //$_POST['method'] jok demek zapros bolgan jok
}
?>
