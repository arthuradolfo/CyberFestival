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
	class TesteAcoesDBUsuario {

		/**
		 * Variavel booleana que diz se houve erro ao carregar classe Cadastro
		 * por padrão é false
		 * @var boolean erroCadastro
		 */
		private $erroCadastro = false;

		/**
		 * Variavel que retorna mensagens de erro
		 * @var array mensagemErroCadastro
		 */
		private $mensagemErroCadastro;

		/**
		 * Variavel booleana que diz se houve erro ao carregar classe Carregamento
		 * por padrão é false
		 * @var boolean erroCarregamento
		 */
		private $erroCarregamento = false;

		/**
		 * Variavel que retorna mensagens de erro
		 * @var array mensagemErroCarregamento
		 */
		private $mensagemErroCarregamento;
		
		function __construct() {
			$this->testaCadastro();
			$this->testaCarregamento();
			$this->printaResultado();
		}

		/**
		 * Método que testa o cadastro de usuário com todos os erros possíveis e da forma correta
		 */
		public function testaCadastro() {
			try {
				$acoesDB = new AcoesDBUsuario;
				$acoesDB->getCadastro();
			}
			catch(Exception $e) {
				$this->setErroCadastro(true);
				$this->setMensagemErroCadastro($e->getMessage());
			}
		}

		/**
		 * Método que testa o cadastro de usuário com todos os erros possíveis e da forma correta
		 */
		public function testaCarregamento() {
			try {
				$acoesDB = new AcoesDBUsuario;
				$acoesDB->getCarregamento();
			}
			catch(Exception $e) {
				$this->setErroCarregamento(true);
				$this->setMensagemErroCarregamento($e->getMessage());
			}
		}

		/**
		 * Printa resultado dos testes
		 */
		public function printaResultado() {
			if($this->getErroCadastro()) {
				echo "Erro! Teste de criar classe cadastroUsuario falhou!<br>";
				var_dump($this->getMensagemErroCadastro());
			}
			else {
				echo "Teste de criar classe cadastroUsuario bem sucedido!<br>";
			}

			if($this->getErroCarregamento()) {
				echo "Erro! Teste de criar classe carregamento falhou!<br>";
				var_dump($this->getMensagemErroCarregamento());
			}
			else {
				echo "Teste de criar classe carregamento bem sucedido!<br>";
			}
		}

		/**
		 * @param boolean
		 */
		public function setErroCadastro($erroCadastro) {
			$this->erroCadastro = $erroCadastro;
		}

		/**
		 * @return boolean
		 */
		public function getErroCadastro() {
			return $this->erroCadastro;
		}

		/**
		 * @param string
		 */
		public function setMensagemErroCadastro($mensagemErroCadastro) {
			$this->mensagemErroCadastro[] = $mensagemErroCadastro;
		}

		/**
		 * @return string
		 */
		public function getMensagemErroCadastro() {
			return $this->mensagemErroCadastro;
		}

		/**
		 * @param boolean
		 */
		public function setErroCarregamento($erroCarregamento) {
			$this->erroCarregamento = $erroCarregamento;
		}

		/**
		 * @return boolean
		 */
		public function getErroCarregamento() {
			return $this->erroCarregamento;
		}

		/**
		 * @param string
		 */
		public function setMensagemErroCarregamento($mensagemErroCarregamento) {
			$this->mensagemErroCarregamento[] = $mensagemErroCarregamento;
		}

		/**
		 * @return string
		 */
		public function getMensagemErroCarregamento() {
			return $this->mensagemErroCarregamento;
		}
	}

	$acoesDBTeste = new TesteAcoesDBUsuario;
?>