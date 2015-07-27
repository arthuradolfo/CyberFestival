<?php
	include_once("src/php/configs/base.php");
	$usuario = new Usuario;
	$usuario->setNome($_POST['nome']);
	$usuario->setEmail($_POST['email']);
	$usuario->setNacionalidade($_POST['nacionalidade']);
	$usuario->setTipo($_POST['tipo']);
	$usuario->setSenhaHash($_POST['senha']);
	$usuario->setEstilo($_POST['estilo']);
	$usuario->setStatus(3);
	$usuario->setDataCadastro($_POST['dataCadstro']);
	$usuario->salvaDados();
?>