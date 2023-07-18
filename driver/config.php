<?php 
class DB{
	public static function connect(){
		try {
			$pdo = new PDO('mysql:host=localhost;dbname=tospeech','root','');
			return $pdo;
		} catch (PDOException $e) {
			echo $e->getMessage();
		}
	}
}
$pdo=DB::connect();
?>