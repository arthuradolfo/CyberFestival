<?php
	include_once("C:\wamp\www\CyberFestival\src\php\configs\base.php");
	$banda = new Banda(8);
	//$banda->setNome("Esmeralda Villalobos");
	//$banda->setEmail("arthur_adolfo@hotmail.com");
	//$banda->setEstilo("Rock");
	//$banda->setCidade("Porto Alegre");
	//$banda->setDataCadastro(date("y-m-d"));
	$integrantes = array(array("id_banda" => null, "id_usuario" => "13", "funcao" => "baterista"),
						 array("id_banda" => null, "id_usuario" => "12", "funcao" => "guitarrista"), 
						 array("id_banda" => null, "id_usuario" => "4", "funcao" => "cantor")
						 );
	$banda->cadastraIntegrantes($integrantes);
	//$banda->salvaDados();
?>