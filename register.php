<!DOCTYPE html>

<?php 
	include "db.php";
	session_start(); 

    $comments=array('login'=>'','password'=>'','password1'=>'','email'=>'');
    if($_POST && isset($_POST['send'])){
        $error=false;
        foreach ($_POST as $key => $value) {
			${$key}=stripslashes(htmlspecialchars(trim($value)));
        }
        if(empty($login)){
            $error=true;
            $comments['login']="Введите логин";
        }
        else{
            $q=$pdo->prepare("SELECT * FROM users WHERE login=?");
            $q->execute([$login]);
            if($q->fetchColumn()!=0){
                $error=true;
                $comments['login']="Такой логин есть в системе";
            }
        }
        if(empty($password)){
            $error=true;
            $comments['password']="Введите пароль";
        }
        if(empty($password1)){
            $error=true;
            $comments['password1']="Повторите пароль";
        }
        else if($password!=$password1){
            $error=true;
            $comments['password1']="Пароли не равны";
        }
        if(empty($email)){
            $error=true;
            $comments['email']="Введите email";
        }
        else{
            $q=$pdo->prepare("SELECT * FROM users WHERE email=?");
            $q->execute([$email]);
            if($q->fetchColumn()!=0){
                $error=true;
                $comments['email']="Такой email есть в системе";
            }
        }
        if(!$error){
            $activation=md5($email.time());
            $password=md5($password);
            $sql="INSERT INTO users(`login`,`password`,`email`,`id_role`,`date_change_password`,`activation`) VALUES ('".$login."','".$password."','".$email."','2','".date('Y-m-d H:i:s')."','".$activation."')";
            $result=$pdo->exec($sql);
            $_SESSION['email']=$email;

            $to = $email;
            $subject = "Подтверждение email";
            $charset = "utf-8";
            $headers =  'MIME-Version: 1.0' . "\r\n"; 
            $headers .= 'From: Сборники <sborniki@unibel.by>' . "\r\n";
            $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n"; 
            $msg = htmlspecialchars('Для подтверждения регистрации в системе "Сборники" перейдите по ссылке http://sborniki.unibel.by/activation.php?code='.$activation.'.');
            $send=mail($to, $subject, $msg, $headers);
            if($send){
                header('Location: http://'.$_SERVER['HTTP_HOST'].'/send-message.php');
            }
        }
    }

?>


<html lang="ru">
    <head>
        <meta charset="UTF-8"/>
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="description" content="Сборники. Регистрация" >
	<title>Сборники. Регистрация</title>
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
                <?php } ?>
            </ul>
        </nav>
        
        <div class="container">
            
            <form id="reg-form" method="post">
              <h4>Регистрация</h4>
              <div class="form-group">
                <p class="p-error"><?=$comments['login']?></p>
                <label for="login">Логин:</label>
                <input class="form-control" id="login" name="login" value="<?php echo (isset($_POST['login'])) ? $_POST['login'] : ""; ?>" required>
              </div>
              <div class="form-group">
                <p class="p-error"><?=$comments['password']?></p>
                <label for="password">Пароль:</label>
                <input type="password" class="form-control" name="password" value="<?php echo (isset($_POST['password'])) ? $_POST['password'] : ""; ?>" id="password" required>
              </div>
              <div class="form-group">
                <p class="p-error"><?=$comments['password1']?></p>
                <label for="password1">Повторите пароль:</label>
                <input type="password" class="form-control" name="password1" value="<?php echo (isset($_POST['password1'])) ? $_POST['password1'] : ""; ?>" id="password1" required>
              </div>
              <div class="form-group">
                <p class="p-error"><?=$comments['email']?></p>
                <label for="email">Email:</label>
                <input type="email" class="form-control" name="email" value="<?php echo (isset($_POST['email'])) ? $_POST['email'] : ""; ?>" id="email" required>
              </div>  
              <button type="submit" name="send" class="btn btn-primary">Зарегистрироваться</button>
            </form>
            
        </div>

	<?php include "_footer.php"; ?>

    </body>
</html>