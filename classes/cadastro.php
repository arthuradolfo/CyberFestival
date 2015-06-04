<?php
	//"AQUEK QUE NUNCA ERROU NUNCA TENTOU UMA COISA NOVO" ALBERT EINSTEN
	include('define.php');
	class Cadastro {
		function __construct($tipo,$nome,$nacionalidade,$senha,$estilo,$membros[$n_membros]) {
		if($tipo = 'u'){//se o tipo de cadastro for user
				$dbh = new PDO('mysql:dbname='.DB_NAME.';host='.HOST_NAME.';charset=utf8', HOST_USR, HOST_PASS);//recebe info do website do mesmo modo que o cadastro do user
				$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
				$query_new_user_insert = $dbh->prepare("INSERT INTO bandas (id, nome, estilo) VALUES (NULL, :nome, :estilo)") or die();
						$query_new_user_insert->bindValue(':nome', $nome, PDO::PARAM_STR);
							$query_new_user_insert->bindValue(':estilo', $estilo, PDO::PARAM_STR);
				$query_new_user_insert->execute();
				$rs = $con->query(“SELEC id FROM banda ”);
				
				$row = $rs->fetch(PDO::FETCH_OBJ);
				$id_banda = $row->id ;
				for($i=0;$i<$n_membros;$i++){
					$rs = $con->query(“SELEC id FROM usuarios WHERE nome = '$username[$i]'”);//adiciona o usuario com user name forncido a banda
					$row = $rs->fetch(PDO::FETCH_OBJ);
					$id_usuarios = $row->id ;		
					$query_new_user_insert = $dbh->prepare("INSERT INTO bandasRusuario ( id,id_banda, id_usuario) VALUES (NULL, :id_banda, :id_usuario)") or die();
					$query_new_user_insert->bindValue(':id_usuario', $id_usuario, PDO::PARAM_STR);//relaciona o usuario a banda na DB de relacoes
					$query_new_user_insert->bindValue(':id_banda', $id_banda, PDO::PARAM_STR);
				}
				
				//fim user
			if($tipo = 'b'){//se o tipo de cadastro for banda
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
			
			
			
			
			}//fim banda
		
		}
	}
?>