<?php
	/**
	 * Classe que contem informacoes e metodos para o manipulação do banco de dados
	 * Ela instancia objetos de cadastro e carregamento para realizar tais operações
	 * @category Ações
	 * @package  Banco de Dados
	 * @author Arthur Adolfo <arthur_adolfo@hotmail.com>
	 * @version 1.0
	 * @copyright CyberFestival 2015
	 */
	class AcoesDBUsuario {
		/**
		 * Objeto de cadastro apra realizar operações de cadastro
		 * @var object cadastro
		 */
		private $cadastro;

		/**
		 * Objeto de carregamento apra realizar operações de carregamento de usuário no banco de dados
		 * @var object carregamento
		 */
		private $carregamento;

		/**
		 * Construtor da classe que instancia os objetos de cadastro e carregamento
		 */
		function __construct() {
			try {
				$this->setCadastro();
				$this->setCarregamento();
			}
			catch(Exception $e) {
				trigger_error("Erro ao instanciar os objetos de cadastro e carregamento.".$e->getMessage().Utilidades::debugBacktrace(), E_USER_ERROR);
			}
		}

		/**
		 * @return object cadastro
		 */
		public function getCadastro() {
			return $this->cadastro;
		}

		public function setCadastro() {
			$this->cadastro = new CadastroUsuario;
		}

		/**
		 * @return object cadastro
		 */
		public function getCarregamento() {
			return $this->carregamento;
		}

		public function setCarregamento() {
			$this->carregamento = new Carregamento;
		}
	}
?>