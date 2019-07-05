<!DOCTYPE html>
<?php 
    session_start();
	include "db.php";
    if(isset($_SESSION['login'])){
       header('Location: http://'.$_SERVER['HTTP_HOST'].'/index.php');
    }

    $comment_error=array('login'=>'','password'=>'');
    if($_POST && isset($_POST['send_login'])){
        
        $error=false;
        $login=trim(stripslashes(htmlspecialchars($_POST['login'])));
        $password=trim(stripslashes(htmlspecialchars($_POST['password'])));
        if(empty($login)){
            $error=true;
            $comment_error['login']="Введите логин";
        }
        if(empty($password)){
            $error=true;
            $comment_error['password']="Введите пароль";
        }
        if(!$error){
            $password=md5($password);
            $sth=$pdo->prepare('SELECT * FROM users WHERE login=? and password=?');
            $sth->execute(array($login,$password));
            $user=$sth->fetch();
            if($user && $user['status']==1){
                $date=date('Y-m-d', strtotime($user['date_change_password']." +1 month"));
                $date_now=date('Y-m-d');
                if($date_now>=$date){
                    $_SESSION['email']=$user['email'];
                    header('Location: http://'.$_SERVER['HTTP_HOST'].'/change-password.php');
                }
                else {
                    $_SESSION['login']=$user['login'];
                    $_SESSION['id_role']=$user['id_role'];
                    header('Location: http://'.$_SERVER['HTTP_HOST'].'/index.php');
                } 
            }
	    else if($user && $user['status']==0){
		$comment_error['login']="Ваша учетная запись не активирована. Для активации Вам было выслано письмо на email <b>".$user['email']."</b>";
	    }
            else{
                $comment_error['login']="Логин или пароль неверный";
            }
        }
    }

?>

<html lang="ru">
    <head>
        <meta charset="UTF-8"/>
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="description" content="Сборники" >
	<title>Сборники. Вход</title>
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
            
                <ul class="nav navbar-nav">
                    <li><a href="register.php">Регистрация</a></li>
                </ul>
            
        </nav>
        
        <div class="container">
            
            <form id="login-form" method="post">
              <h4>Вход</h4>
                
              <div class="form-group">
                <p class="p-error"><?=$comment_error['login']?></p>
                <label for="login">Логин:</label>
                <input class="form-control" id="login" name="login" value="<?php echo (isset($_POST['login'])) ? $_POST['login'] : ""; ?>"  required>
              </div>
                
              <div class="form-group">
                <p class="p-error"><?=$comment_error['password']?></p>
                <label for="password">Пароль:</label>
                <input type="password" class="form-control" name="password" value="<?php echo (isset($_POST['password'])) ? $_POST['password'] : ""; ?>"  id="password" required>
              </div>
               
                <p class="d-flex justify-content-center">
                    <a href="recovery-password.php">Забыли пароль?</a>
                </p>
                
              <input type="submit" name="send_login" class="btn btn-primary" value="Войти">
            </form>
            
        </div>

	<?php include "_footer.php"; ?>

    </body>
</html>