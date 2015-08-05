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
	class TesteBanda {

		/**
		 * Info da banda cadastrada
		 * @var array usuario
		 */
		private $banda;

		/**
		 * Variavel booleana que diz se houve erro ao cadastrar banda
		 * por padrão é false
		 * @var boolean erroCadastraBanda
		 */
		private $erroCadastraBanda = false;

		/**
		 * Variavel que retorna mensagens de erro
		 * @var array mensagemErroCadastraBanda
		 */
		private $mensagemErroCadastraBanda;

		/**
		 * Variavel booleana que diz se houve erro ao cadastrar integrantes da banda
		 * por padrão é false
		 * @var boolean erroCadastraIntegrantesBanda
		 */
		private $erroCadastraIntegrantesBanda = false;

		/**
		 * Variavel que retorna mensagens de erro
		 * @var array mensagemErroCadastraIntegrantesBanda
		 */
		private $mensagemErroCadastraIntegrantesBanda;

		/**
		 * Variavel booleana que diz se houve erro ao carregar Banda
		 * por padrão é false
		 * @var boolean erroCarregaBanda
		 */
		private $erroCarregaBanda = false;

		/**
		 * Variavel que retorna mensagens de erro
		 * @var array mensagemErroCarregaBanda
		 */
		private $mensagemErroCarregaBanda;

		/**
		 * Variavel booleana que diz se houve erro ao carregar Banda por id
		 * por padrão é false
		 * @var boolean erroCarregaBandaPorId
		 */
		private $erroCarregaBandaPorId = false;

		/**
		 * Variavel que retorna mensagens de erro
		 * @var array mensagemErroCarregaBandaPorId
		 */
		private $mensagemErroCarregaBandaPorId;

		/**
		 * Variavel booleana que diz se houve erro ao carregar Banda por nome
		 * por padrão é false
		 * @var boolean erroCarregaBandaPorNome
		 */
		private $erroCarregaBandaPorNome = false;

		/**
		 * Variavel que retorna mensagens de erro
		 * @var array mensagemErroCarregaBandaPorNome
		 */
		private $mensagemErroCarregaBandaPorNome;

		/**
		 * Variavel booleana que diz se houve erro ao carregar Bandas de usuario
		 * por padrão é false
		 * @var boolean erroGetBandasUsuario
		 */
		private $erroGetBandasUsuario = false;

		/**
		 * Variavel que retorna mensagens de erro
		 * @var array mensagemErroGetBandasUsuario
		 */
		private $mensagemErroGetBandasUsuario;

		/**
		 * Variavel booleana que diz se houve erro ao carregar Bandas do estilo
		 * por padrão é false
		 * @var boolean erroGetBandasEstilo
		 */
		private $erroGetBandasEstilo = false;

		/**
		 * Variavel que retorna mensagens de erro
		 * @var array mensagemErroGetBandasEstilo
		 */
		private $mensagemErroGetBandasEstilo;

		/**
		 * Variavel booleana que diz se houve erro ao carregar Bandas da cidade
		 * por padrão é false
		 * @var boolean erroGetBandasCidade
		 */
		private $erroGetBandasCidade = false;

		/**
		 * Variavel que retorna mensagens de erro
		 * @var array mensagemErroGetBandasCidade
		 */
		private $mensagemErroGetBandasCidade;
		
		function __construct() {
			$this->testaCadastraBanda();
			$this->testaCadastraIntegrantesBanda();
			$this->testaCarregaBanda();
			$this->testaCarregaBandaPorId();
			$this->testaCarregaBandaPorNome();
			$this->testaGetBandasUsuario();
			$this->testaGetBandasEstilo();
			$this->testaGetBandasCidade();
			$this->printaResultado();
		}

		/**
		 * Método que testa o cadastro de banda com todos os erros possíveis e da forma correta
		 */
		public function testaCadastraBanda() {
			try {
				$dados = array( "nome"          => "Tequila Baby",
								"email"         => "arthur_adolfo@hotmail.com",
								"estilo"        => "Rock",
								"cidade"        => "Porto Alegre",
								"data"          => date("y-m-d"),
								"integrante"    => array(array("id_banda" => null, "id_usuario" => "13", "funcao" => "baterista"))
								);
				$this->cadastraBanda($dados);
			}
			catch(Exception $e) {
				$this->setErroCadastraBanda(true);
				$this->setMensagemErroCadastraBanda($e->getMessage());
			}
		}

		/**
		 * Método que lança informações à classe banda
		 * @param array info do usuário
		 */
		private function cadastraBanda($dados) {
			$banda = new Banda;
			$banda->setNome($dados['nome']);
			$banda->setEmail($dados['email']);
			$banda->setEstilo($dados['estilo']);
			$banda->setCidade($dados['cidade']);
			$banda->setDataCadastro($dados['data']);
			$banda->setIntegrantes($dados['integrante']);
			$banda->salvaDados();
			$this->setBanda($banda->getDados());
		}

		/**
		 * Método que testa o cadastro de integrantes da banda com todos os erros possíveis e da forma correta
		 */
		public function testaCadastraIntegrantesBanda() {
			try {
				$banda = new Banda($this->getBanda()["id"]);
				$integrantes = array(array("id_banda" => null, "id_usuario" => "68", "funcao" => "guitarrista"), 
									 array("id_banda" => null, "id_usuario" => "2", "funcao" => "cantor")
									 );
				$banda->cadastraIntegrantes($integrantes);
				$this->setBanda($banda->getDados());
			}
			catch(Exception $e) {
				$this->setErroCadastraIntegrantesBanda(true);
				$this->setMensagemErroCadastraIntegrantesBanda($e->getMessage());
			}
		}

		/**
		 * Método que faz o teste de retorno de informações da banda
		 */
		public function testaCarregaBanda() {
			try {
				$banda = new Banda($this->getBanda()['id']);
				var_dump($banda->getDados());
				if(!$this->validaBanda($banda->getDados())["erro"]) {
					$this->setMensagemErroCarregaBanda($this->validaBanda($banda->getDados())["mensagemErro"]);
					$this->setErroCarregaBanda(true);
				}
			}
			catch(Exception $e) {
				$this->setErroCarregaBanda(true);
				$this->setMensagemErroCarregaBanda($e->getMessage());
			}
		}

		/**
		 * Método que faz o teste de retorno de informações da banda por id
		 */
		public function testaCarregaBandaPorId() {
			try {
				$banda = new Banda;
				$banda->carregaBandaPorId($this->getBanda()['id']);
				var_dump($banda->getDados());
				if(!$this->validaBanda($banda->getDados())["erro"]) {
					$this->setMensagemErroCarregaBandaPorId($this->validaBanda($banda->getDados())["mensagemErro"]);
					$this->setErroCarregaBandaPorId(true);
				}
			}
			catch(Exception $e) {
				$this->setErroCarregaBanda(true);
				$this->setMensagemErroCarregaBandaPorId($e->getMessage());
			}
		}

		/**
		 * Método que faz o teste de retorno de informações da banda por nome
		 */
		public function testaCarregaBandaPorNome() {
			try {
				$banda= new Banda;
				$banda->carregaBandaPorNome($this->getBanda()['nome']);
				var_dump($banda->getDados());
				if(!$this->validaBanda($banda->getDados())["erro"]) {
					$this->setMensagemErroCarregaBandaPorNome($this->validaBanda($banda->getDados())["mensagemErro"]);
					$this->setErroCarregaBandaPorNome(true);
				}
			}
			catch(Exception $e) {
				$this->setErroCarregaBandaPorNome(true);
				$this->setMensagemErroCarregaBandaPorNome($e->getMessage());
			}
		}

		/**
		 * Método que faz o teste de retorno de bandas de usuário
		 */
		public function testaGetBandasUsuario() {
			try {
				$banda = new Banda;
				var_dump($banda->getBandasUsuario(13));
			}
			catch(Exception $e) {
				$this->setErroGetBandasUsuario(true);
				$this->setMensagemErroGetBandasUsuario($e->getMessage());
			}
		}

		/**
		 * Método que faz o teste de retorno de bandas de usuário
		 */
		public function testaGetBandasEstilo() {
			try {
				$banda = new Banda;
				var_dump($banda->getBandasEstilo($this->getBanda()['estilo']));
			}
			catch(Exception $e) {
				$this->setErroGetBandasEstilo(true);
				$this->setMensagemErroGetBandasEstilo($e->getMessage());
			}
		}

		/**
		 * Método que faz o teste de retorno de bandas de usuário
		 */
		public function testaGetBandasCidade() {
			try {
				$banda = new Banda;
				var_dump($banda->getBandasCidade($this->getBanda()['cidade']));
			}
			catch(Exception $e) {
				$this->setErroGetBandasCidade(true);
				$this->setMensagemErroGetBandasCidade($e->getMessage());
			}
		}

		/**
		 * Valida  os dados da banda
		 * @param array info da banda
		 * @param array mensagem de erro
		 */

		public function validaBanda($banda) {
			$erro = true;
			if(is_null($banda['id'])) {
				$mensagemErro[] = "Erro! Id nulo!";
				$erro = false;
			}
			if(is_null($banda['nome'])) {
				$mensagemErro[] = "Erro! Nome nulo!";
				$erro = false;
			}
			if(is_null($banda['estilo'])) {
				$mensagemErro[] = "Erro! Estilo nulo!";
				$erro = false;
			}
			if(is_null($banda['cidade'])) {
				$mensagemErro[] = "Erro! Cidade nulo!";
				$erro = false;
			}
			if(is_null($banda['data'])) {
				$mensagemErro[] = "Erro! Data nulo!";
				$erro = false;
			}
			$erroArray["erro"] = $erro;
			$erroArray["mensagemErro"] = (isset($mensagemErro)) ? $mensagemErro : null;
			return $erroArray;
		}

		/**
		 * Printa resultado dos testes
		 */
		public function printaResultado() {
			if($this->getErroCadastraBanda()) {
				echo "Erro! Teste de cadastramento falhou!<br>";
				var_dump($this->getMensagemErroCadastraBanda());
			}
			else {
				echo "Teste de cadastramento bem sucedido!<br>";
			}

			if($this->getErroCadastraIntegrantesBanda()) {
				echo "Erro! Teste de cadastramento de integrantes falhou!<br>";
				var_dump($this->getMensagemErroCadastraIntegrantesBanda());
			}
			else {
				echo "Teste de cadastramento de integrantes bem sucedido!<br>";
			}

			if($this->getErroCarregaBanda()) {
				echo "Erro! Teste de carregamento falhou!<br>";
				var_dump($this->getMensagemErroCarregaBanda());
			}
			else {
				echo "Teste de carregamento bem sucedido!<br>";
			}
			
			if($this->getErroCarregaBandaPorId()) {
				echo "Erro! Teste de carregamento por id falhou!<br>";
				var_dump($this->getMensagemErroCarregaBandaPorId());
			}
			else {
				echo "Teste de carregamento por id bem sucedido!<br>";
			}
			
			if($this->getErroCarregaBandaPorNome()) {
				echo "Erro! Teste de carregamento por nome falhou!<br>";
				var_dump($this->getMensagemErroCarregaBandaPorNome());
			}
			else {
				echo "Teste de carregamento por nome bem sucedido!<br>";
			}
			
			if($this->getErroGetBandasUsuario()) {
				echo "Erro! Teste de carregamento de bandas do usario falhou!<br>";
				var_dump($this->getMensagemErroGetBandasUsuario());
			}
			else {
				echo "Teste de carregamento de bandas do usario bem sucedido!<br>";
			}
			
			if($this->getErroGetBandasEstilo()) {
				echo "Erro! Teste de carregamento de bandas do estilo falhou!<br>";
				var_dump($this->getMensagemErroGetBandasEstilo());
			}
			else {
				echo "Teste de carregamento de bandas do estilo bem sucedido!<br>";
			}
			
			if($this->getErroGetBandasCidade()) {
				echo "Erro! Teste de carregamento de bandas da cidade falhou!<br>";
				var_dump($this->getMensagemErroGetBandasCidade());
			}
			else {
				echo "Teste de carregamento de bandas da cidade bem sucedido!<br>";
			}
		}

		/**
		 * @param array info banda
		 */
		public function setBanda($banda) {
			$this->banda = $banda;
		}

		/**
		 * @return array info usuario
		 */
		public function getBanda() {
			return $this->banda;
		}

		/**
		 * @param boolean
		 */
		public function setErroCadastraBanda($erroCadastraBanda) {
			$this->erroCadastraBanda = $erroCadastraBanda;
		}

		/**
		 * @return boolean
		 */
		public function getErroCadastraBanda() {
			return $this->erroCadastraBanda;
		}

		/**
		 * @param string
		 */
		public function setMensagemErroCadastraBanda($mensagemErroCadastraBanda) {
			$this->mensagemErroCadastraBanda[] = $mensagemErroCadastraBanda;
		}

		/**
		 * @return string
		 */
		public function getMensagemErroCadastraBanda() {
			return $this->mensagemErroCadastraBanda;
		}

		/**
		 * @param boolean
		 */
		public function setErroCadastraIntegrantesBanda($erroCadastraIntegrantesBanda) {
			$this->erroCadastraIntegrantesBanda = $erroCadastraIntegrantesBanda;
		}

		/**
		 * @return boolean
		 */
		public function getErroCadastraIntegrantesBanda() {
			return $this->erroCadastraIntegrantesBanda;
		}

		/**
		 * @param string
		 */
		public function setMensagemErroCadastraIntegrantesBanda($mensagemErroCadastraIntegrantesBanda) {
			$this->mensagemErroCadastraIntegrantesBanda[] = $mensagemErroCadastraIntegrantesBanda;
		}

		/**
		 * @return string
		 */
		public function getMensagemErroCadastraIntegrantesBanda() {
			return $this->mensagemErroCadastraIntegrantesBanda;
		}

		/**
		 * @param boolean
		 */
		public function setErroCarregaBanda($erroCarregaBanda) {
			$this->erroCarregaBanda = $erroCarregaBanda;
		}

		/**
		 * @return boolean
		 */
		public function getErroCarregaBanda() {
			return $this->erroCarregaBanda;
		}

		/**
		 * @param string
		 */
		public function setMensagemErroCarregaBanda($mensagemErroCarregaBanda) {
			$this->mensagemErroCarregaBanda[] = $mensagemErroCarregaBanda;
		}

		/**
		 * @return string
		 */
		public function getMensagemErroCarregaBanda() {
			return $this->mensagemErroCarregaBanda;
		}

		/**
		 * @param boolean
		 */
		public function setErroCarregaBandaPorId($erroCarregaBandaPorId) {
			$this->erroCarregaBandaPorId = $erroCarregaBandaPorId;
		}

		/**
		 * @return boolean
		 */
		public function getErroCarregaBandaPorId() {
			return $this->erroCarregaBandaPorId;
		}

		/**
		 * @param string
		 */
		public function setMensagemErroCarregaBandaPorId($mensagemErroCarregaBandaPorId) {
			$this->mensagemErroCarregaBandaPorId[] = $mensagemErroCarregaBandaPorId;
		}

		/**
		 * @return string
		 */
		public function getMensagemErroCarregaBandaPorId() {
			return $this->mensagemErroCarregaBandaPorId;
		}

		/**
		 * @param boolean
		 */
		public function setErroCarregaBandaPorNome($erroCarregaBandaPorNome) {
			$this->erroCarregaBandaPorNome = $erroCarregaBandaPorNome;
		}

		/**
		 * @return boolean
		 */
		public function getErroCarregaBandaPorNome() {
			return $this->erroCarregaBandaPorNome;
		}

		/**
		 * @param string
		 */
		public function setMensagemErroCarregaBandaPorNome($mensagemErroCarregaBandaPorNome) {
			$this->mensagemErroCarregaBandaPorNome[] = $mensagemErroCarregaBandaPorNome;
		}

		/**
		 * @return string
		 */
		public function getMensagemErroCarregaBandaPorNome() {
			return $this->mensagemErroCarregaBandaPorNome;
		}

		/**
		 * @param boolean
		 */
		public function setErroCarregaBandaPorEmail($erroCarregaBandaPorEmail) {
			$this->erroCarregaBandaPorEmail = $erroCarregaBandaPorEmail;
		}

		/**
		 * @return boolean
		 */
		public function getErroCarregaBandaPorEmail() {
			return $this->erroCarregaBandaPorEmail;
		}

		/**
		 * @param string
		 */
		public function setMensagemErroCarregaBandaPorEmail($mensagemErroCarregaBandaPorEmail) {
			$this->mensagemErroCarregaBandaPorEmail[] = $mensagemErroCarregaBandaPorEmail;
		}

		/**
		 * @return string
		 */
		public function getMensagemErroCarregaBandaPorEmail() {
			return $this->mensagemErroCarregaBandaPorEmail;
		}

		/**
		 * @param boolean
		 */
		public function setErroGetBandasUsuario($erroGetBandasUsuario) {
			$this->erroGetBandasUsuario = $erroGetBandasUsuario;
		}

		/**
		 * @return boolean
		 */
		public function getErroGetBandasUsuario() {
			return $this->erroGetBandasUsuario;
		}

		/**
		 * @param string
		 */
		public function setMensagemErroGetBandasUsuario($mensagemErroGetBandasUsuario) {
			$this->mensagemErroGetBandasUsuario[] = $mensagemErroGetBandasUsuario;
		}

		/**
		 * @return string
		 */
		public function getMensagemErroGetBandasUsuario() {
			return $this->mensagemErroGetBandasUsuario;
		}

		/**
		 * @param boolean
		 */
		public function setErroGetBandasEstilo($erroGetBandasEstilo) {
			$this->erroGetBandasEstilo = $erroGetBandasEstilo;
		}

		/**
		 * @return boolean
		 */
		public function getErroGetBandasEstilo() {
			return $this->erroGetBandasEstilo;
		}

		/**
		 * @param string
		 */
		public function setMensagemErroGetBandasEstilo($mensagemErroGetBandasEstilo) {
			$this->mensagemErroGetBandasEstilo[] = $mensagemErroGetBandasEstilo;
		}

		/**
		 * @return string
		 */
		public function getMensagemErroGetBandasEstilo() {
			return $this->mensagemErroGetBandasEstilo;
		}

		/**
		 * @param boolean
		 */
		public function setErroGetBandasCidade($erroGetBandasCidade) {
			$this->erroGetBandasCidade = $erroGetBandasCidade;
		}

		/**
		 * @return boolean
		 */
		public function getErroGetBandasCidade() {
			return $this->erroGetBandasCidade;
		}

		/**
		 * @param string
		 */
		public function setMensagemErroGetBandasCidade($mensagemErroGetBandasCidade) {
			$this->mensagemErroGetBandasCidade[] = $mensagemErroGetBandasCidade;
		}

		/**
		 * @return string
		 */
		public function getMensagemErroGetBandasCidade() {
			return $this->mensagemErroGetBandasCidade;
		}
	}

	$bandaTeste = new TesteBanda();
?>