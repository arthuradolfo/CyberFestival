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
		 * Info do usuário cadastrado
		 * @var array usuario
		 */
		private $usuario;

		/**
		 * Variavel booleana que diz se houve erro ao cadastrar usuário
		 * por padrão é false
		 * @var boolean erroCadastraUsuario
		 */
		private $erroCadastraUsuario = false;

		/**
		 * Variavel que retorna mensagens de erro
		 * @var array mensagemErroCadastraUsuario
		 */
		private $mensagemErroCadastraUsuario;

		/**
		 * Variavel booleana que diz se houve erro ao carregar usuário
		 * por padrão é false
		 * @var boolean erroCarregaUsuario
		 */
		private $erroCarregaUsuario = false;

		/**
		 * Variavel que retorna mensagens de erro
		 * @var array mensagemErroCarregaUsuario
		 */
		private $mensagemErroCarregaUsuario;

		/**
		 * Variavel booleana que diz se houve erro ao carregar usuário por id
		 * por padrão é false
		 * @var boolean erroCarregaUsuarioPorId
		 */
		private $erroCarregaUsuarioPorId = false;

		/**
		 * Variavel que retorna mensagens de erro
		 * @var array mensagemErroCarregaUsuarioPorId
		 */
		private $mensagemErroCarregaUsuarioPorId;

		/**
		 * Variavel booleana que diz se houve erro ao carregar usuário por nome
		 * por padrão é false
		 * @var boolean erroCarregaUsuarioPorNome
		 */
		private $erroCarregaUsuarioPorNome = false;

		/**
		 * Variavel que retorna mensagens de erro
		 * @var array mensagemErroCarregaUsuarioPorNome
		 */
		private $mensagemErroCarregaUsuarioPorNome;

		/**
		 * Variavel booleana que diz se houve erro ao carregar usuário por email
		 * por padrão é false
		 * @var boolean erroCarregaUsuarioPorEmail
		 */
		private $erroCarregaUsuarioPorEmail = false;

		/**
		 * Variavel que retorna mensagens de erro
		 * @var array mensagemErroCarregaUsuarioPorEmail
		 */
		private $mensagemErroCarregaUsuarioPorEmail;
		

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
				$this->setMensagemErroCadastraUsuario($e->getMessageErroCadastraUsuario());
			}
			if($this->getErroCadastraUsuario()) {
				echo "Erro! Teste falhou! ";
				var_dump($this->getMensagemErroCadastraUsuario());
			}
			else {
				echo "Teste bem sucedido!";
			}
		}

		/**
		 * Método que lança informações à classe usuários
		 * @param array info do usuário
		 */
		private function cadastraUsuario($dados) {
			$usuario = new Usuario;
			$usuario->setNome($dados['nome']);
			$usuario->setEmail($dados['email']);
			$usuario->setNacionalidade($dados['nacionalidade']);
			$usuario->setTipo($dados['tipo']);
			$usuario->setSenhaHash($dados['senha']);
			$usuario->setEstilo($dados['estilo']);
			$usuario->setStatus(3);
			$usuario->setDataCadastro($dados['data']);
			$usuario->salvaDados();
			$this->setUsuario($usuario->getDados());
		}

		/**
		 * Método que faz o teste de retorno de informações do usuário
		 */
		public function testaCarregaUsuario() {
			try {
				$usuario = new Usuario($this->getUsuario()['id']);
				var_dump($usuario->getDados());
				if(!$this->validaUsuario($usuario->getDados(), $this->getMensagemErroCarregaUsuario())) {
					$this->setErroCarregaUsuario(true);
				}
			}
			catch(Exception $e) {
				$this->setErroCarregaUsuario(true);
				$this->setMensagemErroCarregaUsuario($e->getMessage());
			}
			if($this->getErroCarregaUsuario()) {
				echo "Erro! Teste falhou! ";
				var_dump($this->getMensagemErroCarregaUsuario());
			}
			else {
				echo "Teste bem sucedido!";
			}
		}

		/**
		 * Método que faz o teste de retorno de informações do usuário por id
		 */
		public function testaCarregaUsuarioPorId() {
			try {
				$usuario = new Usuario;
				$usuario->carregaUsuarioPorId($this->getUsuario()['id']);
				var_dump($usuario->getDados());
				if(!$this->validaUsuario($usuario->getDados(), $this->getMensagemErroCarregaUsuarioPorId())) {
					$this->setErroCarregaUsuarioPorId(true);
				}
			}
			catch(Exception $e) {
				$this->setErroCarregaUsuario(true);
				$this->setMensagemErroCarregaUsuarioPorId($e->getMessage());
			}
			if($this->getErroCarregaUsuarioPorId()) {
				echo "Erro! Teste falhou! ";
				var_dump($this->getMensagemErroCarregaUsuarioPorId());
			}
			else {
				echo "Teste bem sucedido!";
			}
		}

		/**
		 * Método que faz o teste de retorno de informações do usuário por nome
		 */
		public function testaCarregaUsuarioPorNome() {
			try {
				$usuario = new Usuario;
				$usuario->carregaUsuarioPorNome($this->getUsuario()['nome']);
				var_dump($usuario->getDados());
				if(!$this->validaUsuario($usuario->getDados(), $this->getMensagemErroCarregaUsuarioPorNome())) {
					$this->setErroCarregaUsuarioPorNome(true);
				}
			}
			catch(Exception $e) {
				$this->setErroCarregaUsuarioPorNome(true);
				$this->setMensagemErroCarregaUsuarioPorNome($e->getMessage());
			}
			if($this->getErroCarregaUsuario()) {
				echo "Erro! Teste falhou! ";
				var_dump($this->getMensagemErroCarregaUsuarioPorNome());
			}
			else {
				echo "Teste bem sucedido!";
			}
		}

		/**
		 * Método que faz o teste de retorno de informações do usuário por email
		 */
		public function testaCarregaUsuarioPorEmail() {
			try {
				$usuario = new Usuario;
				$usuario->carregaUsuarioPorEmail($this->getUsuario()['email']);
				var_dump($usuario->getDados());
				if(!$this->validaUsuario($usuario->getDados(), $this->getMensagemErroCarregaUsuarioPorEmail())) {
					$this->setErroCarregaUsuarioPorEmail(true);
				}
			}
			catch(Exception $e) {
				$this->setErroCarregaUsuarioPorEmail(true);
				$this->setMensagemErroCarregaUsuarioPorEmail($e->getMessage());
			}
			if($this->getErroCarregaUsuarioPorEmail()) {
				echo "Erro! Teste falhou! ";
				var_dump($this->getMensagemErroCarregaUsuarioPorEmail());
			}
			else {
				echo "Teste bem sucedido!";
			}
		}

		/**
		 * Valida  os dados do usuário
		 * @param array info do usuario
		 * @param array mensagem de erro
		 */

		public function validaUsuario($usuario, $mensagemErro) {
			$erro = true;
			if(is_null($usuario['id'])) {
				$mensagemErro[] = "Erro! Id nulo!";
				$erro = false;
			}
			if(is_null($usuario['nome'])) {
				$mensagemErro[] = "Erro! Nome nulo!";
				$erro = false;
			}
			if(is_null($usuario['nacionalidade'])) {
				$mensagemErro[] = "Erro! Nacionalidade nula!";
				$erro = false;
			}
			if(is_null($usuario['tipo'])) {
				$mensagemErro[] = "Erro! Tipo nulo!";
				$erro = false;
			}
			if(is_null($usuario['senha'])) {
				$mensagemErro[] = "Erro! Senha nula!";
				$erro = false;
			}
			if(is_null($usuario['estilo'])) {
				$mensagemErro[] = "Erro! Estilo nulo!";
				$erro = false;
			}
			if(is_null($usuario['status'])) {
				$mensagemErro[] = "Erro! Status nulo!";
				$erro = false;
			}
			if(is_null($usuario['data'])) {
				$mensagemErro[] = "Erro! Data nulo!";
				$erro = false;
			}
			return $erro;
		}

		/**
		 * @param array info usurio
		 */
		public function setUsuario($usuario) {
			$this->usuario = $usuario;
		}

		/**
		 * @return array info usuario
		 */
		public function getUsuario() {
			return $this->usuario;
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

		/**
		 * @param boolean
		 */
		public function setErroCarregaUsuarioPorId($erroCarregaUsuarioPorId) {
			$this->erroCarregaUsuarioPorId = $erroCarregaUsuarioPorId;
		}

		/**
		 * @return boolean
		 */
		public function getErroCarregaUsuarioPorId() {
			return $this->erroCarregaUsuarioPorId;
		}

		/**
		 * @param string
		 */
		public function setMensagemErroCarregaUsuarioPorId($mensagemErroCarregaUsuarioPorId) {
			$this->mensagemErroCarregaUsuarioPorId[] = $mensagemErroCarregaUsuarioPorId;
		}

		/**
		 * @return string
		 */
		public function getMensagemErroCarregaUsuarioPorId() {
			return $this->mensagemErroCarregaUsuarioPorId;
		}

		/**
		 * @param boolean
		 */
		public function setErroCarregaUsuarioPorNome($erroCarregaUsuarioPorNome) {
			$this->erroCarregaUsuarioPorNome = $erroCarregaUsuarioPorNome;
		}

		/**
		 * @return boolean
		 */
		public function getErroCarregaUsuarioPorNome() {
			return $this->erroCarregaUsuarioPorNome;
		}

		/**
		 * @param string
		 */
		public function setMensagemErroCarregaUsuarioPorNome($mensagemErroCarregaUsuarioPorNome) {
			$this->mensagemErroCarregaUsuarioPorNome[] = $mensagemErroCarregaUsuarioPorNome;
		}

		/**
		 * @return string
		 */
		public function getMensagemErroCarregaUsuarioPorNome() {
			return $this->mensagemErroCarregaUsuarioPorNome;
		}

		/**
		 * @param boolean
		 */
		public function setErroCarregaUsuarioPorEmail($erroCarregaUsuarioPorEmail) {
			$this->erroCarregaUsuarioPorEmail = $erroCarregaUsuarioPorEmail;
		}

		/**
		 * @return boolean
		 */
		public function getErroCarregaUsuarioPorEmail() {
			return $this->erroCarregaUsuarioPorEmail;
		}

		/**
		 * @param string
		 */
		public function setMensagemErroCarregaUsuarioPorEmail($mensagemErroCarregaUsuarioPorEmail) {
			$this->mensagemErroCarregaUsuarioPorEmail[] = $mensagemErroCarregaUsuarioPorEmail;
		}

		/**
		 * @return string
		 */
		public function getMensagemErroCarregaUsuarioPorEmail() {
			return $this->mensagemErroCarregaUsuarioPorEmail;
		}
	}

	$teste = new TesteUsuario;
	$teste->testaCadastraUsuario();
	$teste->testaCarregaUsuario();
	$teste->testaCarregaUsuarioPorId();
	$teste->testaCarregaUsuarioPorNome();
	$teste->testaCarregaUsuarioPorEmail();
?>