<?php 
    session_start(); 
	include "db.php";

    $comments=array('password'=>'', 'password1'=>'', 'password2'=>'');
    if($_POST && isset($_POST['send'])){
        $error=false;
        foreach ($_POST as $key => $value) {
			${$key}=stripslashes(htmlspecialchars(trim($value)));
        }
        if(empty($password)){
            $error=true;
            $comments['password']='Введите текущий пароль';
        }
        else{
            $stmt=$pdo->prepare('SELECT * FROM users Where email=? LIMIT 1');
            $stmt->execute(array($_SESSION['email']));
            $user=$stmt->fetch();
            $password=md5($password);
            echo $password;
            if($user['password']!=$password){
                $error=true;
                $comments['password']='Текущий пароль не верный';
            }
        }
        if(empty($password1)){
            $error=true;
            $comments['password1']='Введите новый пароль';
        }
        if(empty($password2)){
            $error=true;
            $comments['password2']='Повторите новый пароль';
        }
        else if($password1!=$password2){
            $error=true;
            $comments['password2']='Пароли не совпадают';
        }
        
        if(!$error){
            $password1=md5($password1);
            $stmt=$pdo->prepare('UPDATE users SET password=?,date_change_password=? WHERE email=?');
            $stmt->execute([$password1,date('Y-m-d H:i:s'),$_SESSION['email']]);
            
            $stmt=$pdo->prepare('SELECT * FROM users Where email=? LIMIT 1');
            $stmt->execute([$_SESSION['email']]);
            $user=$stmt->fetch();
            $_SESSION['login']=$user['login'];
            header('Location: http://'.$_SERVER['HTTP_HOST'].'/index.php');
        }
    }
?>


<!DOCTYPE html>
<html lang="ru">
    <head>
        <meta charset="UTF-8"/>
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="description" content="Сборники. Регистрация" >
	<title>Сборники. Изменение пароля</title>
        <link type="text/css" href="styles/style.css" rel="stylesheet">
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
        <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
	<script src="scripts/footer.js"></script>
    </head>
    <body>
        <nav class="navbar navbar-expand-sm bg-primary navbar-dark justify-content-between">
            <a class="navbar-brand" href="index.php"><h2>Сборники</h2></a>
            <ul class="nav navbar-nav">
                <?php if(isset($_SESSION['login'])){?>
                <li><?=$_SESSION['login']?></li>
                <?php } else{ ?>
                <li><a href="index.php">Вход</a></li>
                <li><a href="register.php">Регистрация</a></li>
                <?php } ?>
            </ul>
        </nav>
        
        <div class="container">
            
            <form id="password-form" method="post">
              <h4>Изменение пароля</h4>
              <div class="form-group">
                  <p class="p-error"><?=$comments['password']?></p>
                  <label for="password">Текущий пароль:</label>
                  <input type="password" class="form-control" name="password" value="<?php echo (isset($_POST['password'])) ? $_POST['password'] : ""; ?>" id="password" required>
              </div>
              <div class="form-group">
                  <p class="p-error"><?=$comments['password1']?></p>
                  <label for="password1">Новый пароль:</label>
                  <input type="password" class="form-control" name="password1" value="<?php echo (isset($_POST['password1'])) ? $_POST['password1'] : ""; ?>" id="password1" required>
              </div>
              <div class="form-group">
                  <p class="p-error"><?=$comments['password2']?></p>
                  <label for="password2">Повторите пароль:</label>
                  <input type="password" class="form-control" name="password2" value="<?php echo (isset($_POST['password2'])) ? $_POST['password2'] : ""; ?>" id="password2" required>
              </div>
              <button type="submit" name="send" class="btn btn-primary">Сохранить пароль</button>
            </form>
            
        </div>

	<?php include "_footer.php"; ?>

    </body>
</html>