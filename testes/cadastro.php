<?php
	include_once("base.php");
	/**
	 * Classe de testes para a classe Usuario
	 * @category Usuário
	 * @package Testes
	 * @author Arthur Adolfo <arthur_adolfo@hotmail.com>
	 * @version 1.0
	 * @copyright 2015
	 */
	class TesteCadastro {

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

		private $id;
		
		function __construct() {
			$this->testaInsereBanco();
			$this->testaAtualizaBanco();
			$this->printaResultado();
		}

		/**
		 * Método que testa o cadastro de dados com todos os erros possíveis e da forma correta
		 */
		public function testaInsereBanco() {
			try {
				$dados = array( "id"            => NULL,
								"nome"          => "teste",
								"email"         => "email.teste@testehost.com",
								"nacionalidade" => "Brasileiro",
								"tipo"          => USER_TYPE_ARTIST,
								"senha"         => "senhaTeste",
								"estilo"        => "Rock",
								"data"          => date("y-m-d")
								);
				$cadastro = new Cadastro;
				$this->id = $cadastro->insereDadosBancoDeDados($dados, TABELA_USUARIOS);
			}
			catch(Exception $e) {
				$this->setErroInsereBanco(true);
				$this->setMensagemErroInsereBanco($e->getMessage());
			}
		}

		/**
		 * Método que testa a atualização de dados com todos os erros possíveis e da forma correta
		 */
		public function testaAtualizaBanco() {
			try {
				$dados = array( "id"            => $this->id,
								"nome"          => "teste123",
								"email"         => "email.teste@testehost.com",
								"nacionalidade" => "Brasileiro",
								"tipo"          => USER_TYPE_ARTIST,
								"senha"         => "senhaTeste",
								"estilo"        => "Rock",
								"data"          => date("y-m-d")
								);
				$cadastro = new Cadastro;
				$cadastro->atualizaDadosBancoDeDados($dados, TABELA_USUARIOS);
			}
			catch(Exception $e) {
				$this->setErroAtualizaBanco(true);
				$this->setMensagemErroAtualizaBanco($e->getMessage());
			}
		}

		/**
		 * Printa resultado dos testes
		 */
		public function printaResultado() {
			if($this->getErroAtualizaBanco()) {
				echo "Erro! Teste de cadastramento no banco de dados falhou!<br>";
				var_dump($this->getMensagemErroAtualizaBanco());
			}
			else {
				echo "Teste de cadastramento no banco de dados bem sucedido!<br>";
			}

			if($this->getErroAtualizaBanco()) {
				echo "Erro! Teste de atualização do banco de dados falhou!<br>";
				var_dump($this->getMensagemErroAtualizaBanco());
			}
			else {
				echo "Teste de atualização do banco de dados bem sucedido!<br>";
			}
		}

		/**
		 * @param boolean
		 */
		public function setErroInsereBanco($erroInsereBanco) {
			$this->erroInsereBanco = $erroInsereBanco;
		}

		/**
		 * @return boolean
		 */
		public function getErroInsereBanco() {
			return $this->erroInsereBanco;
		}

		/**
		 * @param string
		 */
		public function setMensagemErroInsereBanco($mensagemErroInsereBanco) {
			$this->mensagemErroInsereBanco[] = $mensagemErroInsereBanco;
		}

		/**
		 * @return string
		 */
		public function getMensagemErroInsereBanco() {
			return $this->mensagemErroInsereBanco;
		}

		/**
		 * @param boolean
		 */
		public function setErroAtualizaBanco($erroAtualizaBanco) {
			$this->erroAtualizaBanco = $erroAtualizaBanco;
		}

		/**
		 * @return boolean
		 */
		public function getErroAtualizaBanco() {
			return $this->erroAtualizaBanco;
		}

		/**
		 * @param string
		 */
		public function setMensagemErroAtualizaBanco($mensagemErroAtualizaBanco) {
			$this->mensagemErroAtualizaBanco[] = $mensagemErroAtualizaBanco;
		}

		/**
		 * @return string
		 */
		public function getMensagemErroAtualizaBanco() {
			return $this->mensagemErroAtualizaBanco;
		}
	}
	$cadastroTeste = new TesteCadastro;
?>