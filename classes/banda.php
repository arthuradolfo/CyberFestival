<?php
	include('define.php');
	class Cadastro {
		function __construct($nome,$estilo,$membros[$n_membros]) {
			$dbh = new PDO('mysql:dbname='.DB_NAME.';host='.HOST_NAME.';charset=utf8', HOST_USR, HOST_PASS);//recebe info do website do mesmo modo que o cadastro do user
			$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			$query_new_user_insert = $dbh->prepare("INSERT INTO bandas (id, nome, estilo) VALUES (NULL, :nome, :estilo)") or die();
	                $query_new_user_insert->bindValue(':nome', $nome, PDO::PARAM_STR);
            	    	$query_new_user_insert->bindValue(':estilo', $estilo, PDO::PARAM_STR);
			$query_new_user_insert->execute();
			$rs = $con->query("SELEC id FROM banda WHERE nome = '$nome'");
			
			$row = $rs->fetch(PDO::FETCH_OBJ);
			$id_banda = $row->id ;
			for($i=0;$i<$n_membros;$i++){
				$rs = $con->query("SELEC id FROM usuarios WHERE nome = '$username[$i]'");//adiciona o usuario com user name forncido a banda
				$row = $rs->fetch(PDO::FETCH_OBJ);
				$id_usuarios = $row->id ;		
				$query_new_user_insert = $dbh->prepare("INSERT INTO bandasRusuario ( id,id_banda, id_usuario) VALUES (NULL, :id_banda, :id_usuario)") or die();
				$query_new_user_insert->bindValue(':id_usuario', $id_usuario, PDO::PARAM_STR);//relaciona o usuario a banda na DB de relacoes
				$query_new_user_insert->bindValue(':id_banda', $id_banda, PDO::PARAM_STR);
			}
			
			$query_new_user_insert->execute();
		}
	}
?>