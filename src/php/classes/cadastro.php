<?php
	//"AQUELE QUE NUNCA ERROU NUNCA TENTOU UMA COISA NOVO" ALBERT EINSTEN
	class Cadastro {
		function __construct($nome,$email,$nacionalidade,$senha,$tipo) {
			$query = new MysqliDb;
			$query->query("INSERT INTO usuarios (id, nome, email, nacionalidade, senha, tipo) VALUES (NULL, '".$nome."', '".$email."', '".$nacionalidade."', 'SHA1(".$senha.")', '".$tipo."')");
		}
	}
?>