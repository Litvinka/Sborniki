<?php

include "db.php";
if($_POST && isset($_POST['id'])){
    $q=$pdo->prepare("SELECT path from documents where id=?");
    $q->execute([$_POST['id']]);
    $file=$q->fetch()['path'];
    header('Content-Description: File Transfer');
    header('Content-Type: application/octet-stream');
    header('Content-Disposition: attachment; filename=' . basename($file));
    header('Content-Transfer-Encoding: binary');
    header('Expires: 0');
    header('Cache-Control: must-revalidate');
    header('Pragma: public');
    header('Content-Length: ' . filesize($file));
    readfile($file);
    exit;
}

?>