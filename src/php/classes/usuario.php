<?php
	/**
	 * Classe que contem informacoes e metodos para o usuário
	 *
	 * @category Usuário
	 * @package  Usuário
	 * @author Arthur Adolfo <arthur_adolfo@hotmail.com>
	 * @version 1.0
	 * @copyright CyberFestival 2015
	 */

	class Usuario extends AcoesDBUsuario implements AcoesCadastroCarregamento {
		/**
		 * ID do usuário no banco de dados 
		 * Caso o usuário exista seu id será diferente de NULO
		 * @var int id do usuário
		 */
		private $id;

		/**
		 * @var string nome do usuário
		 */
		private $nome;

		/**
		 * @var string email do usuário
		 */
		private $email;

		/**
		 * @var string nacionalidade do usuário
		 */
		private $nacionalidade;

		/**
		 * Existem três tipos de usuário principais, além do administrador
		 * Tipo Artista - USER_TYPE_ARTIST, Tipo Fã - USER_TYPE_FAN, Tipo Produtor - USER_TYPE_PRODUTOR
		 * @var int tipo do usuário
		 */
		private $tipo;

		/**
		 * @var string hash da senha do usuário
		 */
		private $senhaHash;

		/**
		 * Ex: $fotoPerifl['id'], $fotoPerifl['id_usuario'], $fotoPerifl['nome'], $fotoPerfi['caminho']
		 * @var array informações da foto de perfil.
		 */
		private $fotoPerfil;

		/**
		 * @var string estilo principal de música do usuário
		 */
		private $estilo;

		/**
		 * @var array interesse musical do usuário
		 */
		private $interesseMusical;

		/**
		 * @var array instrumentos tocados pelo usuário
		 */
		private $instrumentos;

		/**
		 * @var array bandas do usuário
		 */
		private $bandas;

		/**
		 * 0 - desativado
		 * 1 - ativo
		 * 2 - esperando verificação de email
		 * 3 - redefinição de senha
		 * 4 - troca de perfil
		 * @var int sinaliza se usuário está ativo
		 */
		private $status;

		/**
		 * @var string data de cadastro
		 */
		private $dataCadastro;

	    /**
	     * Se o id do usuário não for nulo, carrega os dados do banco de dados
		 * @param int id do usuário
		 * @throws InvalidArgumentException Uso de arguimentos inválidos
		 */
		public function __construct($id = NULL) {
			parent::__construct();
			if(!is_null($id)) {
				TratamentoErros::validaInteiro($id, "id do usuário");
				if(parent::getCarregamento()->valoresExistenteDB(array('id' => $id), TABELA_USUARIOS)) {
					$this->carregaInformacao(array('id' => $id));
				}
				else {	
					throw new InvalidArgumentException("Id não existe no banco de dados.".Utilidades::debugBacktrace(), E_USER_ERROR);
				}
			}
		}

		/**
		 * Carrega informações do usuário no banco de dados para preencher o objeto
		 * @param array dados do usuário para procurar na tabela
		 */
		public function carregaInformacao($dados) {
			$this->setDados(parent::getCarregamento()->carregaDados($dados, TABELA_USUARIOS));
		}

	    /**
	     * Define informações do usuário
	     * @param array dados do usuário a ser definido
	     * @throws InvalidArgumentException Uso de argumentos inválidos
	     */
		public function setDados($dados) {
			TratamentoErros::validaArray($dados, "informações do usuário");
            try {
            	$this->setId($dados['id']);
		        $this->setNome($dados['nome']);
		        $this->setNacionalidade($dados['nacionalidade']);
		        $this->setEmail($dados['email']);
		        $this->setSenhaHash($dados['senha'], false);
		        $this->setTipo($dados['tipo']);
		        $this->setEstilo($dados['estilo']);
		        $this->setStatus($dados['status']);
		        $this->setDataCadastro($dados['data']);
		        $this->setFotoPerfil($this->carregaFotoPerfil($this->getId()));
		        //$this->setInteresseMusical($this->carregaInteresseMusical($this->getId()));
		        if($this->isArtistUser()) {
		        	//$this->setInstrumentos($this->carregaInstrumentos($this->getId()));
		        }
		        if($this->isArtistUser()) {
		        	//$this->setBandas($this->carregaBandas($this->getId()));
		        }
		    }
		    catch(Exception $e) {
		    	trigger_error($e->getMessage(), $e->getCode());
		    }
		}

		/**
		 * Salva dados no banco de dados
		 * Se id for nulo, cadastra novo usuário no banco de dados
		 * Se não for nulo, atualiza banco de dados
	     * @throws Exception Ocorreu erro
		 */
		public function salvaDados() {
			$this->validaDados();
			if(is_null($this->getId())) {
				try {
					$id = parent::getCadastro()->insereDadosBancoDeDados($this->getDadosBanco(), TABELA_USUARIOS);
					$this->setId($id); //Carrega o id fornecido ao usuário no cadastramento
					TratamentoErros::validaNulo($this->getId(), "id do usuário");
					//Carrega os dados para enviar email de confirmação
					$dados = array( "id" => $id,
									"nome" => $this->getNome(),
									"email" => $this->getEmail()
									);
					parent::getCadastro()->enviaEmailConfirmacao($dados);
					$this->carregaInformacao(array('id' => $id)); //Carrega o codigo de verificação no objeto
				}
				catch(Exception $e) {
					trigger_error("Ocorreu um erro ao tentar salvar dados do usuário no DB! ".$e->getMessage().Utilidades::debugBacktrace(), E_USER_ERROR);
				}
			}
			else {
				try {
					parent::getCadastro()->atualizaDadosBancoDeDados($this->getDadosBanco(), TABELA_USUARIOS);
				}
				catch(Exception $e) {
					trigger_error("Ocorreu um erro ao tentar salvar dados do usuário no DB! ".$e->getMessage().Utildiades::debugBacktrace(), $e->getCode());
				}
			}
		}

	    /**
	     * Retorna informações do usuário para cadastrar no DB
	     * @return array dados do usuário
	     */
		public function getDadosBanco() {
			try {
				$dados = array( "id"                => $this->getId(),
	        					"nome"              => $this->getNome(),
	        					"nacionalidade"     => $this->getNacionalidade(),
		        				"email"             => $this->getEmail(),
		        				"senha"             => $this->getSenhaHash(),
		        				"tipo"              => $this->getTipo(),
		        				"estilo"            => $this->getEstilo(),
		        				"status"            => $this->getStatus(),
		        				"data"              => $this->getDataCadastro(),
		        				"codigoVerificacao" => NULL
		        				);
			}
			catch(Exception $e) {
				trigger_error("Ocorreu algum erro!".Utilidades::debugBacktrace(), E_USER_ERROR);
			}
			return $dados;
		}

	    /**
	     * Retorna informações do usuário
	     * @return array dados do usuário
	     */
		public function getDados() {
			try {
				$dados = array( "id"               => $this->getId(),
	        					"nome"             => $this->getNome(),
	        					"nacionalidade"    => $this->getNacionalidade(),
		        				"email"            => $this->getEmail(),
		        				"senha"            => $this->getSenhaHash(),
		        				"tipo"             => $this->getTipo(),
		        				"estilo"           => $this->getEstilo(),
		        				"status"           => $this->getStatus(),
		        				"data"             => $this->getDataCadastro(),
		        				"fotoPerfil"       => $this->getFotoPerfil(),
		        				"instrumentos"     => $this->getInstrumentos(),
		        				"interesseMusical" => $this->getInteresseMusical(),
		        				"bandas"           => $this->getBandas()
		        				);
			}
			catch(Exception $e) {
				trigger_error("Ocorreu algum erro!".Utilidades::debugBacktrace(), E_USER_ERROR);
			}
			return $dados;
		}

		/**
	     * Valida dados do usuário
	     * @throws Exception caso ocorra erro
	     */
		public function validaDados() {
			TratamentoErros::validaNulo($this->getNome(), "nome do usuário");
			TratamentoErros::validaNulo($this->getNacionalidade(), "nacionalidade do usuário");
			TratamentoErros::validaNulo($this->getNome(), "nome do usuário");
			TratamentoErros::validaNulo($this->getEmail(), "email do usuário");
			TratamentoErros::validaNulo($this->getSenhaHash(), "senha hash do usuário");
			TratamentoErros::validaNulo($this->getTipo(), "tipo do usuário");
			TratamentoErros::validaNulo($this->getEstilo(), "estilo do usuário");
			TratamentoErros::validaNulo($this->getStatus(), "status do usuário");
			TratamentoErros::validaNulo($this->getDataCadastro(), "data de cadastro do usuário");
		}

		/**
		 * Carrega informações do usuário pelo Id
		 * @param int id do usuário
		 */
		public function carregaUsuarioPorId($id) {
			TratamentoErros::validaInteiro($id, "id do usuário");
			$this->carregaInformacao(array('id' => $id));
		}

		/**
		 * Carrega informações do usuário pelo nome
		 * @param string nome do usuário
		 */
		public function carregaUsuarioPorNome($nome) {
			TratamentoErros::validaString($nome, "nome do usuário");
			$this->carregaInformacao(array('nome' => $nome));
		}

		/**
		 * Carrega informações do usuário pelo email
		 * @param string email do usuário
		 */
		public function carregaUsuarioPorEmail($email) {
			TratamentoErros::validaString($email, "email do usuário");
			$this->carregaInformacao(array('email' => $email));
		}

		/**
	     * Carrega foto de perfil do usuário
	     * @param int id do usuário
	     * @return array url foto de perfil do usuário
	     * @throws InvalidArgumentException caso ocorra erro
	     */

		private function carregaFotoPerfil($id) {
			TratamentoErros::validaInteiro($id, "id do usuário");
			$fotoPerfil = new FotoPerfil($this->getId());
			return $fotoPerfil->getDados();
		}

		public function uploadFotoPerfil($arquivo) {
			try {
				$fotoPerfil = new FotoPerfil($this->getId());
				$fotoPerfil->setArquivo($arquivo);
				$fotoPerfil->setNome($this->getNome().".".$fotoPerfil->getExtensao($arquivo));
				$fotoPerfil->setCaminho(CAMINHO_USUARIOS_FOTO_PERFIL."/".$fotoPerfil->getNome());
				$fotoPerfil->setDataCadastro(date("y-m-d"));
				$fotoPerfil->salvaDados();
			}
			catch(Exception $e) {
				trigger_error("Ocorreu um erro inesperado!".$e->getMessage().Utilidades::debugBacktrace(), E_USER_ERROR);
			}
		}


		/**
	     * Carrega interesse musical do usuário
	     * @param int id do usuário
	     * @return array interesse musical do usuário
	     * @throws InvalidArgumentException caso ocorra erro
	     */

		private function carregaInteresseMusical($id) {
			TratamentoErros::validaInteiro($id, "id do usuário");
			$interesseMusical = new InteresseMusical($this->getId());
			return $interesseMusical->getInteresseMuscial();
		}


		/**
	     * Carrega instrumentos do usuário
	     * @param int id do usuário
	     * @return array instrumentos do usuário
	     * @throws InvalidArgumentException caso ocorra erro
	     */

		private function carregaInstrumentos($id) {
			TratamentoErros::validaInteiro($id, "id do usuário");
			$instrumentos = new Instrumentos($this->getId());
			return $instrumentos->getInstrumentos();
		}

		/**
	     * Carrega instrumentos do usuário
	     * @param int id do usuário
	     * @return array instrumentos do usuário
	     * @throws InvalidArgumentException caso ocorra erro
	     */

		private function carregaBandas($id) {
			TratamentoErros::validaInteiro($id, "id do usuário");
			$bandas = new Banda;
			return $bandas->getBandasUsuario();
		}

		/**
	     * Verifica se usuário é administrador
	     * @return boolean se for administrador é true, se não, false
	     */

		private function isAdminUser() {
			if($this->tipo == USER_TYPE_ADMIN) {
				return true;
			}
			else {
				return false;
			}
		}

		/**
	     * Verifica se usuário é produtor
	     * @return boolean se for produtor é true, se não, false
	     */

		private function isProdutorUser() {
			if($this->tipo == USER_TYPE_PRODUTOR) {
				return true;
			}
			else {
				return false;
			}
		}

		/**
	     * Verifica se usuário é artista
	     * @return boolean se for artista é true, se não, false
	     */

		private function isArtistUser() {
			if($this->tipo == USER_TYPE_ARTIST) {
				return true;
			}
			else {
				return false;
			}
		}

		/**
	     * Verifica se usuário é fã
	     * @return boolean se for fan é true, se não, false
	     */

		private function isFanUser() {
			if($this->tipo == USER_TYPE_FAN) {
				return true;
			}
			else {
				return false;
			}
		}

	    /**
	     * Define id do usuário
	     * @param int id do usuário a ser definido
	     */
		public function setId($id) {
			TratamentoErros::validaInteiro($id, "id do usuário");
			$this->id = $id;
		}

		/**
	     * Retorna id do usuário
	     * @return int id do usuário
	     */
		private function getId() {
			return $this->id;
		}

	    /**
	     * Define nome do usuário
	     * @param string nome do usuário a ser definido
	     */
		public function setNome($nome) {
			TratamentoErros::validaString($nome, "nome do usuário");
			TratamentoErros::validaNulo($nome);
			$this->nome = $nome;
		}

	    /**
	     * Retorna nome do usuário
	     * @return string nome do usuário
	     */
		private function getNome() {
			return $this->nome;
		}

	    /**
	     * Define email do usuário
	     * @param string email do usuário a ser definido
	     * @param string email do usuário a ser definido
	     */
		public function setEmail($email, $troca = false) {
			TratamentoErros::validaString($email, "email do usuário");
			TratamentoErros::validaNulo($email);
			//Se estiver trocando o email
			if($troca) {
	            if(parent::getCarregamento()->valoresExistenteDB(array('email' => $email), TABELA_USUARIOS)){
	                throw new Exception("Esse email já está em uso!".Utilidades::debugBacktrace(), E_USER_WARNING);
	            }
			}
			if(Utilidades::validaEmail($email)){
				$this->email = $email;
			}
			else {
	             throw new Exception("Esse email é inválido!".Utilidades::debugBacktrace(), E_USER_WARNING);
			}
		}

	    /**
	     * Retorna email do usuário
	     * @return string email do usuário
	     */
		private function getEmail() {
			return $this->email;
		}


	    /**
	     * Define nacionalidade do usuário
	     * @param string nacionalidade do usuário a ser definido
	     */
		public function setNacionalidade($nacionalidade) {
			TratamentoErros::validaString($nacionalidade, "nacionalidade do usuário");
			TratamentoErros::validaNulo($nacionalidade);
			$this->nacionalidade = $nacionalidade;
		}

	    /**
	     * Retorna nacionalidade do usuário
	     * @return string nacionalidade do usuário
	     */
		private function getNacionalidade() {
			return $this->nacionalidade;
		}

	    /**
	     * Define tipo do usuário
	     * @param int tipo do usuário a ser definido
	     */
		public function setTipo($tipo) {
			TratamentoErros::validaInteiro($tipo, "tipo do usuário");
			TratamentoErros::validaNulo($tipo);
			$this->tipo = $tipo;
		}

	    /**
	     * Retorna tipo do usuário
	     * @return int tipo do usuário
	     */
		private function getTipo() {
			return $this->tipo;
		}

	    /**
	     * Define a hash da senha do usuário
	     * @param string hash da senha ou senha do usuário a ser definido
	     */
		public function setSenhaHash($senha, $hash = true) {
			TratamentoErros::validaString($senha, "senha do usuário");
			TratamentoErros::validaNulo($senha);
			$this->validaTamanhoSenha($senha);
			$this->senhaHash = ($hash) ? Utilidades::geraHashSenha($senha) : $senha;
		}

		/**
		 * Valida a senha do usuário
	     * @throws InvalidArgumentException Uso de argumentos inválidos
		 */
		private function validaTamanhoSenha($senha) {
			if($this->senhaTamanhoInvalido($senha)) {
				throw new InvalidArgumentException("Erro ao definir a hash da senha do usuário. Senha muito pequena".Utilidades::debugBacktrace(), E_USER_ERROR);
			}
		}

	    /**
	     * Return a hash da senha do usuário
	     * @return string hash da senha do usuário
	     */
		private function getSenhaHash() {
			return $this->senhaHash;
		}

		/**
		 * Verifica tamanho da senha
		 * @return boolean
		 */

		public function senhaTamanhoInvalido($senha) {
			if(strlen($senha) < TAMANHO_MINIMO_SENHA) {
				return true;
			}
			else {
				return false;
			}
		}

	    /**
	     * Define a foto de perfil do usuário
	     * @param array informarções foto de perfil
	     */
		public function setFotoPerfil($fotoPerfil) {
			TratamentoErros::validaArray($fotoPerfil, "foto de perfil do usuário");
			TratamentoErros::validaNulo($fotoPerfil);
			$this->fotoPerfil = $fotoPerfil;
		}

	    /**
	     * Return a foto de perfil do usuário
	     * @return string url da foto de perfil do usuário
	     */
		private function getFotoPerfil() {
			return $this->fotoPerfil;
		}

	    /**
	     * Define o estilo principal de perfil do usuário
	     * @param string estilo principal do usuário a ser definido
	     */
		public function setEstilo($estilo) {
			TratamentoErros::validaString($estilo, "estilo do usuário");
			TratamentoErros::validaNulo($estilo);
			$this->estilo = $estilo;
		}

	    /**
	     * Return o estilo principal de perfil do usuário
	     * @return string estilo principal do usuário
	     */
		private function getEstilo() {
			return $this->estilo;
		}

	    /**
	     * Define o status do usuário
	     * @param int status do usuário a ser definido
	     */
		public function setStatus($status) {
			TratamentoErros::validaInteiro($status, "status do usuário");
			TratamentoErros::validaNulo($status);
			$this->status = $status;
		}

	    /**
	     * Return o status do usuário
	     * @return int status do usuário
	     */
		private function getStatus() {
			return $this->status;
		}

	    /**
	     * Define a data de cadastro do usuário
	     * @param string data de cadastro do usuário a ser definido
	     */
		public function setDataCadastro($dataCadastro) {
			TratamentoErros::validaString($dataCadastro, "data de cadastro do usuário");
			TratamentoErros::validaNulo($dataCadastro);
			$this->dataCadastro = $dataCadastro;
		}

	    /**
	     * Return data de cadastro do usuário usuário
	     * @return string data de cadastro do usuário
	     */
		private function getDataCadastro() {
			return $this->dataCadastro;
		}

	    /**
	     * Define o interesse musical de perfil do usuário
	     * @param array interesse musical do usuário a ser definido
	     */
		public function setInteresseMuscial($interesseMusical) {
			TratamentoErros::validaArray($interesseMusical, "interesse musical do usuário");
			$this->interesseMusical = $interesseMusical;
		}

	    /**
	     * Retorna o interesse musical do usuário
	     * @return array interesse musical do usuário
	     */
		private function getInteresseMusical() {
			return $this->interesseMusical;
		}

	    /**
	     * Define os instrumentos tocados pelo usuário
	     * @param array instrumentos tocados pelo usuário a ser definido
	     */
		public function setInstrumentos($instrumentos) {
			TratamentoErros::validaString($instrumentos, "instrumentos do usuário");
			$this->instrumentos = $instrumentos;
		}

	    /**
	     * Retorna os instrumentos tocados pelo usuário
	     * @return array instrumentos tocados pelo usuário
	     */
		private function getInstrumentos() {
			return $this->instrumentos;
		}

	    /**
	     * Define as bandas tocadas pelo usuário
	     * @param array bandas tocadas pelo usuário a ser definido
	     */
		public function setBandas($bandas) {
			TratamentoErros::validaArray($bandas, "bandas do usuário");
			$this->bandas = $bandas;
		}

	    /**
	     * Return as bandas tocadas pelo usuário
	     * @return array bandas tocadas pelo usuário
	     */
		private function getBandas() {
			return $this->bandas;
		}
	}
?>