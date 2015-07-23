<?php
	include("define.php");
	class Login {
		function __construct($nome, $senha) {
			$pdo = new PDO("mysql:host=".HOST_NAME.";dbname=".DB_NAME.";charset=utf8", HOST_USR, HOST_PASS);
			$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			$query_login_user = $pdo->prepare("SELECT * FROM usuarios WHERE nome LIKE :nome AND senha LIKE SHA1(:senha)");
	                $query_login_user->bindValue(':nome', $nome, PDO::PARAM_STR);
	               	$query_login_user->bindValue(':senha', $senha, PDO::PARAM_STR);
			$query_login_user->execute();
			$resultado = $query_login_user->fetchAll(PDO::FETCH_ASSOC);
			if ($query_login_user->rowCount() == 1) {
				session_start();
				$_SESSION['nome'] = $nome;
				$_SESSION['email'] = $resultado[0]['email'];
				$_SESSION['nacionalidade'] = $resultado[0]['nacionalidade'];
			}
			else {
				echo "Usuario inexistente.";
			}
		}
	}
	$login = new Login('arthur1', '030197');
	print_r($_SESSION);
?>