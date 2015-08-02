<?php
	include_once("C:\wamp\www\CyberFestival\src\php\configs\base.php");
	echo CAMINHO_SISTEMA;
	if($_FILES) {
		var_dump($_FILES['arquivo']);
		$usuario = new Usuario(13);
		$usuario->uploadFotoPerfil($_FILES['arquivo']);
	}
?>
<form action="uploadArquivo.php" method="post" enctype="multipart/form-data">
Carregue a foto de perfil<input type="file" name="arquivo">
<input type="submit">
</form>