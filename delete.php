<?php
session_start();
require('./dbconnect.php');
if (isset($_SESSION['id'])) {
    $id = $_REQUEST['id'];
    // inspection message
    $messages = $db->prepare('SELECT * FROM posts WHERE id=?');
    $messages->execute(array($id));
    $message = $messages->fetch();
    // print($id);
    // print($message['member_id']);
    /*
    if ($message['member_id'] == $_SESSION['$id']) {
        // delete
        $del = $db->prepare('DELETE FROM posts WHERE id=?');
        $del->execute(array($id));
    }
    */
    $del = $db->prepare('DELETE FROM posts WHERE id=?');
    $del->execute(array($id));

    header('Location: index.php'); exit();
}

header('Loation: index.php'); exit();
?>