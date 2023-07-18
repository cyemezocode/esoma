<?php
header("Content-type:application/json");
require 'config.php';
session_start();
$response = array();
if(isset($_POST['desc'])){
	if(isset($_SESSION['user'])){
		$sql="UPDATE word set w_details=? where w_string=?";
		$book3=$pdo->prepare($sql);
		$book3->bindValue(1,$_POST['desc']);
		$book3->bindValue(2,$_SESSION['t']);
		$book3->execute();
		$response['title']=$_SESSION['alltext'];
		$response['details']=$_POST['desc'];
	}else{
		$response['none']=true;
	}
	echo json_encode($response);
}
if(isset($_POST['findData'])){
	$sql="SELECT * from word where w_string=?";
	$book3=$pdo->prepare($sql);
	$book3->bindValue(1,$_SESSION['t']);
	$book3->execute();
	$data = $book3->fetch();
	$response['title']=$data['w_string'];
	$response['details']=$data['w_details'];
	echo json_encode($response);
}
if(isset($_POST['userLog'])){
	$sql = "SELECT * from users where user_id=?";
	$book3=$pdo->prepare($sql);
	$book3->bindValue(1,$_POST['id']);
	$book3->execute();
    $user = $book3->fetch();
    if($user){
		$sql="UPDATE users set user_email=?,user_name=?,user_image=? where user_id=?";
		$book3=$pdo->prepare($sql);
		$book3->bindValue(1,$_POST['email']);
		$book3->bindValue(2,$_POST['name']);
		$book3->bindValue(3,$_POST['image']);
		$book3->bindValue(4,$_POST['id']);
		$book3->execute();
		$_SESSION['userId']=$_POST['id'];
    }else{
		$sql="INSERT into users set user_id=?,user_email=?,user_name=?,user_image=?,usertype=?";
		$book3=$pdo->prepare($sql);
		$book3->bindValue(1,$_POST['id']);
		$book3->bindValue(2,$_POST['email']);
		$book3->bindValue(3,$_POST['name']);
		$book3->bindValue(4,$_POST['image']);
		$book3->bindValue(5,'publisher');
		$book3->execute();
		$_SESSION['userId']=$_POST['id'];
    }

	$sql = "SELECT * from users where user_id=?";
	$book3=$pdo->prepare($sql);
	$book3->bindValue(1,$_POST['id']);
	$book3->execute();
    $user = $book3->fetch();

    $_SESSION['userID'] = $user['u_id'];
}
if(isset($_POST['logout'])){
	unset($_SESSION['userId']);
	echo json_encode('done');
}
if(isset($_POST['saveData'])){
	$sql = "UPDATE book set b_content=? where b_code=?";
	$book = $pdo->prepare($sql);
	$book->bindValue(1,$_POST['saveData']);
	$book->bindValue(2,$_SESSION['code']);
	$book->execute();
	// echo $_SESSION['userId'];
}

if(isset($_POST['book'])){
	$sql="SELECT * from book where b_id=?";
	$book3=$pdo->prepare($sql);
	$book3->bindValue(1,$_POST['book']);
	$book3->execute();
	$data = $book3->fetch();
	$response['book']=$data['b_content'];
	echo json_encode($response);
} 
if(isset($_GET['download'])){
    $sql="SELECT * from books where b_id=?";
    $book3=$pdo->prepare($sql);
    $book3->bindValue(1,$_GET['download']);
    $book3->execute();
    $data = $book3->fetch();

    $dir = '../'.$data['b_src'];
    $zip_file = $data['b_name'].'.zip';
    header("Content-type: application/zip"); 
    header("Content-Disposition: attachment; filename=$zip_file");
    header("Content-length: " . filesize($dir));
    header("Pragma: no-cache"); 
    header("Expires: 0"); 
    readfile("$dir");
    }
