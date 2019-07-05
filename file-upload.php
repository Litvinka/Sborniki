<?php 
    session_start(); 
	include "db.php";
    if(!isset($_SESSION['login'])){
       header('Location: http://'.$_SERVER['HTTP_HOST'].'/login.php');
    }
    if($_SESSION['id_role']!=1){
        header('Location: http://'.$_SERVER['HTTP_HOST'].'/index.php');
    }
    
    $comments=['name'=>'','file'=>''];
    if($_POST && isset($_POST['send'])){
        $error=false;
        $name=trim(htmlspecialchars(stripslashes($_POST['name'])));
        $file=$_FILES['file'];
        if(empty($name)){
            $error=true;
            $comments['name']='Введите название файла';
        }
        if(empty($file)){
            $error=true;
            $comments['file']='Загрузите файл';
        }
        else if($file['size']>(20*1024*1024)){
            $error=true;
            $comments['file']='Файл превышает размер 20 МБ';
        }
        else{
            $ext = pathinfo($file["name"], PATHINFO_EXTENSION);
            if($ext!='pdf'){
                $error=true;
                $comments['file']='Файл должен быть в формате .pdf';
            }
        }
        if(!$error){
            $stmt=$pdo->prepare('SELECT id from users where login=?');
            $stmt->execute([$_SESSION['login']]);
            $user=$stmt->fetch();
            $id_user=$user['id'];
            
            $uploaddir = 'documents/';
            $temp = explode(".", $file["name"]);
            $newfilename = date('dmYHis').'_'.str_replace(" ", "", basename($file["name"]));
            $uploadfile = $uploaddir . $newfilename;

            if (move_uploaded_file($file['tmp_name'], $uploadfile)) {
                $stmt=$pdo->prepare('INSERT INTO documents (name,date_download,path,id_user) VALUES(?, ?, ?, ?)');
                $stmt->execute([$name, date('Y-m-d H:i:s'), $uploadfile, $id_user]);
                header('Location: http://'.$_SERVER['HTTP_HOST'].'/index.php');
            }      
        }
    }

?>

<!DOCTYPE html>
<html lang="ru">
    <head>
        <meta charset="UTF-8"/>
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="description" content="Сборники" >
	<title>Сборники. Загрузка сборника</title>
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
            <div class="btn-group">
                <button type="button" class="btn user-btn btn-default dropdown-toggle dropdown-toggle-split" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <?=$_SESSION['login']?>
                </button>
                <div class="dropdown-menu">
                    <a class="dropdown-item" href="file-upload.php">Загрузка сборников</a>
                    <a class="dropdown-item" href="admin.php">Список пользователей</a>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item" href="logout.php">Выйти</a>
                </div>
            </div>   
        </nav>
        
        <div class="container">
            
            <div class="col-md-12">
                <form id="upload_form" method="post" enctype="multipart/form-data">
                    <h4>Загрузка сборника</h4>
                    <p><i>Для загрузки принимаются файлы в формате .pdf и размером не более 20 MB</i></p>
                    <div class="form-group">
                        <p class="p-error"><?=$comments['name']?></p>
                        <label for="name">Название файла:</label>
                        <input type="name" name="name" class="form-control" value="<?php echo (isset($_POST['name'])) ? $_POST['name'] : ""; ?>" id="name" required>
                    </div>
                    <div class="form-group">
                        <p class="p-error"><?=$comments['file']?></p>
                        <input type="file" name="file" accept="application/pdf" id="file" required>
                    </div>
                    <button type="submit" name="send" class="btn btn-primary">Сохранить</button>
                </form>
            </div>
            
        </div>

	<?php include "_footer.php"; ?>

    </body>
</html>