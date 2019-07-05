<?php

include "db.php";

$result="";
if($_GET && $_GET['code']){
    $code=trim(htmlspecialchars(stripslashes($_GET['code'])));
    $sth=$pdo->prepare('SELECT * from users where activation=?');
    $sth->execute([$code]);
    $user=$sth->fetch();
    if($user){
	$sth=$pdo->prepare("UPDATE users set status='1' where activation=?");
        $sth->execute([$code]);
	$result="Ваш email подтвержден. Можете войти в систему";
    }
    else{
	$result="Такого пользователя нет в базе данных";
    }
}
?>

<html lang="ru">
    <head>
        <meta charset="UTF-8"/>
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="description" content="Сборники" >
	<title>Сборники. Активация учетной записи</title>
        <link type="text/css" href="styles/style.css" rel="stylesheet">
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
        <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
	<script src="scripts/footer.js"></script>
    </head>
    <body>
        <nav class="navbar navbar-expand-sm bg-primary navbar-dark justify-content-between">
            <a class="navbar-brand" href="login.php"><h2>Сборники</h2></a>
            
                <ul class="nav navbar-nav nav-right">
                    <li><a href="login.php">Вход</a></li>
                    <li><a href="register.php">Регистрация</a></li>
                </ul>
            
        </nav>
        
        <div class="container">
            
		<p><?=$result?></p>
            
        </div>

	<?php include "_footer.php"; ?>

    </body>
</html>