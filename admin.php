<?php 
    session_start(); 
	include "db.php";
    if(!isset($_SESSION['login'])){
       header('Location: http://'.$_SERVER['HTTP_HOST'].'/login.php');
    }
    if($_SESSION['id_role']!=1){
        header('Location: http://'.$_SERVER['HTTP_HOST'].'/index.php');
    }

    $pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, FALSE); 

    $all_count=$pdo->query('SELECT COUNT(*) FROM users')->fetchColumn(); //all users count
    $page=1;
    if($_GET && isset($_GET['page'])){
        $p=trim(htmlspecialchars(stripslashes($_GET['page'])));
        if(is_numeric($p) && $p<=$all_count && $p>0){
            $page=$p;
        }
    } 
    $row_count=10; //number of documents on page

    $sth=$pdo->prepare('SELECT * FROM users LIMIT ?, ?');
    $start=($page-1)*$row_count;
    $sth->execute([$start,$row_count]);
    $docs=$sth->fetchAll();

    $number_pages=(($all_count % $row_count) >0) ? (floor($all_count/$row_count)+1) : floor($all_count/$row_count); //number pages
?>

<!DOCTYPE html>
<html lang="ru">
    <head>
        <meta charset="UTF-8"/>
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="description" content="Сборники" >
	<title>Список пользователей</title>
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
        <link type="text/css" href="styles/style.css" rel="stylesheet">
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
            
            <div class="row">
                <div class="col-1">
                    <b>№</b>
                </div>   
                <div class="col-3">
                    <b>Логин</b>
                </div> 
                <div class="col-3">
                    <b>Email</b>
                </div> 
                <div class="col-3">
                    <b>Дата изменения пароля</b>
                </div>
                <div class="col-2">
                    <b>Статус</b>
                </div> 
            </div>
            <hr>
            <?php 
                $i=$start+1;
                foreach($docs as $key=>$value){
            ?>
            <div class="row row-user-info">
                <div class="col-1">
                    <span><?=$i?></span>
                </div>   
                <div class="col-3">
                    <h5><?=$value['login']?></h5>
                </div> 
                <div class="col-3">
                    <h5><?=$value['email']?></h5>
                </div> 
                <div class="col-3">
                    <h5><?=$value['date_change_password']?></h5>
                </div>
                <div class="col-2">
                    <h5><?=$value['status']?></h5>
                </div> 
            </div>
            <hr>
            <?php ++$i; } ?>
            
             
            <div class="pagination d-flex align-items-center justify-content-center">
                <div class="pagination-buttons">
                <?php if($number_pages>1){ ?>
                
                    <a class="btn btn-sm <?php if($page==1){echo 'a-pg-block';} else{ echo 'btn-primary'; } ?>" <?php if($page>1){ echo "href='index.php?page=".($page-1)."'";} ?> >&lt;</a>
                    
                    <?php if(($page-2)>1){  ?>
                        <a class="btn btn-sm btn-primary" href="index.php?page=1">1</a>
                    <?php } if(($page-3)>1){ ?>
                        <span>...</span>
                    <?php } ?>
                    
                    <?php if(($page-2)>0){  ?>
                        <a class="btn btn-sm btn-primary" href="index.php?page=<?=($page-2)?>"><?=($page-2)?></a>
                    <?php } if(($page-1)>0){ ?>
                        <a class="btn btn-sm btn-primary" href="index.php?page=<?=($page-1)?>"><?=($page-1)?></a>
                    <?php } ?>
                
                    <a class="btn btn-sm a-pg-active" href="#"><?=$page?></a>
                
                    <?php if(($page+1)<=$number_pages){  ?>
                        <a class="btn btn-sm btn-primary" href="index.php?page=<?=($page+1)?>"><?=($page+1)?></a>
                    <?php } if(($page+2)<=$number_pages){ ?>
                        <a class="btn btn-sm btn-primary" href="index.php?page=<?=($page+2)?>"><?=($page+2)?></a>
                    <?php } ?>
                    
                    <?php if(($page+3)<$number_pages){ ?>
                        <span>...</span>
                    <?php } if(($page+2)<$number_pages){  ?>
                        <a class="btn btn-sm btn-primary" href="index.php?page=<?=$number_pages?>"><?=$number_pages?></a>
                    <?php } ?>
                
                    <a class="btn btn-sm <?php if($page==$number_pages){echo 'a-pg-block';} else{ echo 'btn-primary'; } ?>" <?php if($page<$number_pages){ echo "href='index.php?page=".($page+1)."'";} ?> >&gt;</a>
                
                <?php }?>
                </div>
            </div>
            
            
        </div>

	<?php include "_footer.php"; ?>

    </body>
</html>