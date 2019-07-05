﻿<!DOCTYPE html>
<?php 
    session_start();
	include "db.php";
    if(isset($_SESSION['login'])){
       header('Location: http://'.$_SERVER['HTTP_HOST'].'/index.php');
    }

    $comment_error=array('email'=>'','password1'=>'', 'password2'=>'');
    if($_POST && isset($_POST['send'])){
        $error=false;
        $email=trim(stripslashes(htmlspecialchars($_POST['email'])));
        if(empty($email)){
            $error=true;
            $comment_error['email']="Введите email";
        }
        $sth=$pdo->prepare('Select * from users where email=?');
        $sth->execute([$email]);
        $user=$sth->fetch();
        if(!$user){
            $error=true;
            $comment_error['email']="Пользователя с таким email нет в базе данных";
        }
        if(!$error){
            $_SESSION['email']=$user['email'];
        }
    }
    else if($_POST && isset($_POST['send2'])){
        $error=false;
        $password1=trim(stripslashes(htmlspecialchars($_POST['password1'])));
        $password2=trim(stripslashes(htmlspecialchars($_POST['password2'])));
        if(empty($password1)){
            $error=true;
            $comment_error['password1']='Введите новый пароль';
        }
        if(empty($password2)){
            $error=true;
            $comment_error['password2']='Повторите новый пароль';
        }
        else if($password1!=$password2){
            $error=true;
            $comment_error['password2']='Пароли не совпадают';
        }
        if(!$error){
            $password1=md5($password1);
            $sth=$pdo->prepare('UPDATE users set password=? where email=?');
            $sth->execute([$password1,$_SESSION['email']]);
            header('Location: http://'.$_SERVER['HTTP_HOST'].'/login.php');
        }
    }

?>

<html lang="ru">
    <head>
        <meta charset="UTF-8"/>
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="description" content="Сборники" >
	<title>Сборники. Восстановление пароля</title>
        <link type="text/css" href="styles/style.css" rel="stylesheet">
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
        <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
	<script src="scripts/footer.js"></script>
    </head>
    <body>
        <nav class="navbar navbar-expand-sm bg-primary navbar-dark justify-content-between">
            <a class="navbar-brand" href="#"><h2>Сборники</h2></a>
            
                <ul class="nav navbar-nav nav-right">
                    <li><a href="login.php">Вход</a></li>
                    <li><a href="register.php">Регистрация</a></li>
                </ul>
            
        </nav>
        
        <div class="container">
            
            <?php if(!isset($_SESSION['email'])) { ?>
            
            <form  method="post">
              <h4>Восстановление пароля (Шаг 1)</h4>
              <div class="form-group">
                <p class="p-error"><?=$comment_error['email']?></p>
                <label for="login">Введите email:</label>
                <input class="form-control" id="email" name="email" type="email" value="<?php echo (isset($_POST['email'])) ? $_POST['email'] : ""; ?>"  required>
              </div>
              <input type="submit" name="send" class="btn btn-primary" value="Восстановить пароль">
            </form>
            
            <?php } else { ?>
            
            <form method="post">
                <h4>Восстановление пароля (Шаг 2)</h4>
                <div class="form-group">
                    <p class="p-error"><?=$comment_error['password1']?></p>
                    <label for="login">Введите пароль:</label>
                    <input class="form-control" id="password1" name="password1" type="password" value="<?php echo (isset($_POST['password1'])) ? $_POST['password1'] : ""; ?>"  required>
                </div>
                <div class="form-group">
                    <p class="p-error"><?=$comment_error['password2']?></p>
                    <label for="login">Повторите пароль:</label>
                    <input class="form-control" id="password2" name="password2" type="password" value="<?php echo (isset($_POST['password2'])) ? $_POST['password2'] : ""; ?>"  required>
                </div>
                <input type="submit" name="send2" class="btn btn-primary" value="Сохранить пароль">
            </form>
            
            <?php } ?>
                     
        </div>

	<?php include "_footer.php"; ?>

    </body>
</html>