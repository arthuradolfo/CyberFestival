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
	class TesteUsuario {

		/**
		 * Variavel booleana que diz se houve erro ao inserir dado no banco de dados
		 * por padrão é false
		 * @var boolean erroInsereBanco
		 */
		private $erroInsereBanco = false;

		/**
		 * Variavel que retorna mensagens de erro
		 * @var array mensagemErroInsereBanco
		 */
		private $mensagemErroInsereBanco;

		/**
		 * Variavel booleana que diz se houve erro ao atualiza dado no banco de dados
		 * por padrão é false
		 * @var boolean erroInsereBanco
		 */
		private $erroAtualizaBanco = false;

		/**
		 * Variavel que retorna mensagens de erro
		 * @var array mensagemErroAtualizaBanco
		 */
		private $mensagemErroAtualizaBanco;
		
		function __construct($arquivo) {
			$this->testaInsereBanco();
			$this->testaAtualizaBanco();
			$this->printaResultado();
		}

		/**
		 * Método que testa o cadastro de usuário com todos os erros possíveis e da forma correta
		 */
		public function testaCadastraUsuario() {
			try {
				$dados = array( "nome"          => "teste",
								"email"         => "email.teste@testehost.com",
								"nacionalidade" => "Brasileiro",
								"tipo"          => USER_TYPE_ARTIST,
								"senha"         => "senhaTeste",
								"estilo"        => "Rock",
								"data"  => date("y-m-d")
								);
				$this->cadastraUsuario($dados);
			}
			catch(Exception $e) {
				$this->setErroCadastraUsuario(true);
				$this->setMensagemErroCadastraUsuario($e->getMessage());
			}
		}

		/**
		 * Método que faz o teste de retorno de informações do usuário
		 */
		public function testaCarregaUsuario() {
			try {
				$usuario = new Usuario($this->getUsuario()['id']);
				var_dump($usuario->getDados());
				if(!$this->validaUsuario($usuario->getDados())["erro"]) {
					$this->setMensagemErroCarregaUsuario($this->validaUsuario($usuario->getDados())["mensagemErro"]);
					$this->setErroCarregaUsuario(true);
				}
			}
			catch(Exception $e) {
				$this->setErroCarregaUsuario(true);
				$this->setMensagemErroCarregaUsuario($e->getMessage());
			}
		}

		/**
		 * Printa resultado dos testes
		 */
		public function printaResultado() {
			if($this->getErroCadastraUsuario()) {
				echo "Erro! Teste de cadastramento falhou!<br>";
				var_dump($this->getMensagemErroCadastraUsuario());
			}
			else {
				echo "Teste de cadastramento bem sucedido!<br>";
			}

			if($this->getErroCarregaUsuario()) {
				echo "Erro! Teste de carregamento falhou!<br>";
				var_dump($this->getMensagemErroCarregaUsuario());
			}
			else {
				echo "Teste de carregamento bem sucedido!<br>";
			}
		}

		/**
		 * @param boolean
		 */
		public function setErroCadastraUsuario($erroCadastraUsuario) {
			$this->erroCadastraUsuario = $erroCadastraUsuario;
		}

		/**
		 * @return boolean
		 */
		public function getErroCadastraUsuario() {
			return $this->erroCadastraUsuario;
		}

		/**
		 * @param string
		 */
		public function setMensagemErroCadastraUsuario($mensagemErroCadastraUsuario) {
			$this->mensagemErroCadastraUsuario[] = $mensagemErroCadastraUsuario;
		}

		/**
		 * @return string
		 */
		public function getMensagemErroCadastraUsuario() {
			return $this->mensagemErroCadastraUsuario;
		}

		/**
		 * @param boolean
		 */
		public function setErroCarregaUsuario($erroCarregaUsuario) {
			$this->erroCarregaUsuario = $erroCarregaUsuario;
		}

		/**
		 * @return boolean
		 */
		public function getErroCarregaUsuario() {
			return $this->erroCarregaUsuario;
		}

		/**
		 * @param string
		 */
		public function setMensagemErroCarregaUsuario($mensagemErroCarregaUsuario) {
			$this->mensagemErroCarregaUsuario[] = $mensagemErroCarregaUsuario;
		}

		/**
		 * @return string
		 */
		public function getMensagemErroCarregaUsuario() {
			return $this->mensagemErroCarregaUsuario;
		}
	}
	$cadastroTeste = new TesteUsuario($_FILES['arquivo']);
?>
<form action="usuario.php" method="post" enctype="multipart/form-data">
	<input type="file" name="arquivo">
	<input type="submit">
</form>