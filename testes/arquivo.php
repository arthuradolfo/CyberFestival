<?php
	include_once("C:\wamp\www\CyberFestival\src\php\configs\base.php");
	/**
	 * Classe de testes para a classe Usuario
	 * @category Usuário
	 * @package Testes
	 * @author Arthur Adolfo <arthur_adolfo@hotmail.com>
	 * @version 1.0
	 * @copyright 2015
	 */
	class TesteArquivo {

		/**
		 * Variavel booleana que diz se houve erro ao fazer upload de foto
		 * por padrão é false
		 * @var boolean erroUploadArquivo
		 */
		private $erroUploadArquivo = false;

		/**
		 * Variavel que retorna mensagens de erro
		 * @var array mensagemErroUploadArquivo
		 */
		private $mensagemErroUploadArquivo;
		
		function __construct($arquivo) {
			$this->testaUploadArquivo($arquivo);
			$this->printaResultado();
		}

		/**
		 * Método que testa o upload de arquivos
		 */
		public function testaUploadArquivo($arquivo) {
			try {
				$arquivoUpload = new Arquivo;
				$dados = array( "arquivo" => $arquivo,
								"caminho" => CAMINHO_UPLOAD_TESTES."/".$arquivo["name"]
								);
				$arquivoUpload->uploadArquivo($dados);
			}
			catch(Exception $e) {
				$this->setErroUploadArquivo(true);
				$this->setMensagemErroUploadArquivo($e->getMessage());
			}
		}

		/**
		 * Printa resultado dos testes
		 */
		public function printaResultado() {
			if($this->getErroUploadArquivo()) {
				echo "Erro! Teste de upload de arquivo falhou!<br>";
				var_dump($this->getMensagemErroCadastraUsuario());
			}
			else {
				echo "Teste de upload de arquivo bem sucedido!<br>";
			}
		}

		/**
		 * @param boolean
		 */
		public function setErroUploadArquivo($erroUploadArquivo) {
			$this->erroUploadArquivo = $erroUploadArquivo;
		}

		/**
		 * @return boolean
		 */
		public function getErroUploadArquivo() {
			return $this->erroUploadArquivo;
		}

		/**
		 * @param string
		 */
		public function setMensagemErroUploadArquivo($mensagemErroUploadArquivo) {
			$this->mensagemErroUploadArquivo[] = $mensagemErroUploadArquivo;
		}

		/**
		 * @return string
		 */
		public function getMensagemErroUploadArquivo() {
			return $this->mensagemErroUploadArquivo;
		}
	}

	if($_FILES) {
		var_dump($_FILES['arquivo']);
		$arquivoTeste = new TesteArquivo($_FILES['arquivo']);
	}
?>
<form action="arquivo.php" method="post" enctype="multipart/form-data">
	<input type="file" name="arquivo">
	<input type="submit">
</form>