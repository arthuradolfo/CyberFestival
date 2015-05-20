<?php
	include('define.php');
	class Cadastro {
		function __construct($nome, $email, $senha, $nacionalidade) {
			$dbh = new PDO('mysql:dbname='.DB_NAME.';host='.HOST_NAME.';charset=utf8', HOST_USR, HOST_PASS);
			$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			$query_new_user_insert = $dbh->prepare("INSERT INTO usuarios (id, nome, email, senha, nacionalidade) VALUES (NULL, :nome, :email, SHA1(:senha), :nacionalidade)") or die();
	                $query_new_user_insert->bindValue(':nome', $nome, PDO::PARAM_STR);
            	    	$query_new_user_insert->bindValue(':email', $email, PDO::PARAM_STR);
             	  	$query_new_user_insert->bindValue(':senha', $senha, PDO::PARAM_STR);
               		$query_new_user_insert->bindValue(':nacionalidade', $nacionalidade, PDO::PARAM_STR);
			$query_new_user_insert->execute();
		}
	}
?>