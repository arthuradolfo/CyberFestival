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

	class Usuario extends Cadastro {
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
			if(!is_null($id)) {
				if(!is_int($id)) {
					throw new InvalidArgumentException("Erro ao definir o id do usuário. Esperava um inteiro, recebeu ".gettype($id).Utilidades::debugBacktrace(), E_USER_ERROR);
				}
				else {
					if(Utilidades::valoresExistenteDB(array('id' => $id), TABELA_USUARIOS)) {
						$this->carregaDados(array('id' => $id));
					}
					else {	
						throw new InvalidArgumentException("Id não existe no banco de dados.".Utilidades::debugBacktrace(), E_USER_ERROR);
					}
				}
			}
		}

	    /**
	     * Carrega dados do usuário do banco de dados pelo id
	     * @param array campos do usuário a ser definido
	     */
		private function carregaDados($campos) {
			if(!is_array($campos)){
	            throw new InvalidArgumentException("Erro ao definar os campos, esperava um array de campos. Recebeu ".gettype($campos).Utilidades::debugBacktrace(), E_USER_ERROR);
	        }

	        $query = new MysqliDb();

			foreach ($campos as $coluna => $valor) {
	            $query->where($coluna, $valor);
	        }

			$this->setDados($query->getOne(TABELA_USUARIOS));
		}

	    /**
	     * Define informações do usuário
	     * @param array dados do usuário a ser definido
	     * @throws InvalidArgumentException Uso de argumentos inválidos
	     */
		private function setDados($dados) {
			if(is_array($dados)){
	            try {
	            	$this->setId($dados['id']);
			        $this->setNome($dados['nome']);
			        $this->setNacionalidade($dados['nacionalidade']);
			        $this->setEmail($dados['email']);
			        $this->setSenhaHash($dados['senha'], false);
			        $this->setTipo($dados['tipo']);
			        $this->setEstilo($dados['estilo']);
			        $this->setStatus($dados['status']);
			        $this->setDataCadastro($dados['dataCadastro']);
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
	        else {
	        	throw new InvalidArgumentException("Ocorreu um erro! Esperava receber um array. Recebeu ".gettype($dados).Utilidades::debugBacktrace(), E_USER_ERROR);
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
					$id = parent::insereDadosBancoDeDados($this->getDados(), TABELA_USUARIOS);
					$this->setId($id);
					if(is_null($this->getId())) {
						throw new Exception("Erro ao cadastrar usuário! ".Utilidades::debugBacktrace(), E_USER_ERROR);
					}
					$this->carregaDados(array('id' => $id));
					$dados = array( "id" => $id,
									"nome" => $this->getNome(),
									"email" => $this->getEmail()
									);
					parent::enviaEmailConfirmacao($dados);
					$this->carregaDados(array('id' => $id));
				}
				catch(Exception $e) {
					trigger_error("Ocorreu um erro ao tentar salvar dados do usuário no DB! ".$e->getMessage().Utildiades::debugBacktrace(), $e->getCode());
				}
			}
			else {
				try {
					parent::atualizaDadosBancoDeDados($this->getDados(), TABELA_USUARIOS);
				}
				catch(Exception $e) {
					trigger_error("Ocorreu um erro ao tentar salvar dados do usuário no DB! ".$e->getMessage().Utildiades::debugBacktrace(), $e->getCode());
				}
			}
		}

	    /**
	     * Retorna informações do usuário
	     * @return array dados do usuário
	     */
		public function getDados() {
			try {
				$dados = array( "id" => $this->getId(),
	        					"nome" => $this->getNome(),
	        					"nacionalidade" => $this->getNacionalidade(),
		        				"email" => $this->getEmail(),
		        				"senha" => $this->getSenhaHash(),
		        				"tipo" => $this->getTipo(),
		        				"estilo" => $this->getEstilo(),
		        				"status" => $this->getStatus(),
		        				"dataCadastro" => $this->getDataCadastro(),
		        				"codigoVerificacao" => NULL
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

		private function validaDados() {
			if(is_null($this->getNome())) {
				throw new InvalidArgumentException("Erro! Nome inválido!".Utilidades::debugBacktrace(), E_USER_ERROR);
			}
			if(is_null($this->getNacionalidade())) {
				throw new InvalidArgumentException("Erro! Nacionalidade inválida!".Utilidades::debugBacktrace(), E_USER_ERROR);
			}
			if(is_null($this->getEmail())) {
				throw new InvalidArgumentException("Erro! Email inválido!".Utilidades::debugBacktrace(), E_USER_ERROR);
			}
			if(is_null($this->getSenhaHash())) {
				throw new InvalidArgumentException("Erro! Senha Hash inválido!".Utilidades::debugBacktrace(), E_USER_ERROR);
			}
			if(is_null($this->getTipo())) {
				throw new InvalidArgumentException("Erro! Tipo inválido!".Utilidades::debugBacktrace(), E_USER_ERROR);
			}
			if(is_null($this->getEstilo())) {
				throw new InvalidArgumentException("Erro! Estilo inválido!".Utilidades::debugBacktrace(), E_USER_ERROR);
			}
			if(is_null($this->getStatus())) {
				throw new InvalidArgumentException("Erro! Status inválido!".Utilidades::debugBacktrace(), E_USER_ERROR);
			}
			if(is_null($this->getDataCadastro())) {
				throw new InvalidArgumentException("Erro! Data de cadastro inválido!".Utilidades::debugBacktrace(), E_USER_ERROR);
			}
		}

		/**
	     * Carrega foto de perfil do usuário
	     * @param int id do usuário
	     * @return array url foto de perfil do usuário
	     * @throws InvalidArgumentException caso ocorra erro
	     */

		private function carregaFotoPerfil($id) {
			if(!is_int($id)) {
				throw new InvalidArgumentException("Erro, Espera receber um inteiro, recebeu ". gettype($id).Utilidades::debugBacktrace(), E_USER_ERROR);
			}
			else {
				$fotoPerfil = new FotoPerfil($this->getId());
				return $fotoPerfil->getDados();
			}
		}

		public function uploadFotoPerfil($arquivo) {
			try {
				$fotoPerfil = new FotoPerfil($this->getId());
				$fotoPerfil->setArquivo($arquivo);
				$fotoPerfil->setNome($this->getNome().$fotoPefil->getExtensao($this->getArquivo()));
				$fotoPerfil->setCaminho(CAMINHO_USUARIOS_FOTO_PERFIL.$fotoPerfil->getNome());
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
			if(!is_int($id)) {
				throw new InvalidArgumentException("Erro, Espera receber um inteiro, recebeu ". gettype($id).Utilidades::debugBacktrace(), E_USER_ERROR);
			}
			else {
				$interesseMusical = new InteresseMusical($this->getId());
				return $interesseMusical->getInteresseMuscial();
			}
		}


		/**
	     * Carrega instrumentos do usuário
	     * @param int id do usuário
	     * @return array instrumentos do usuário
	     * @throws InvalidArgumentException caso ocorra erro
	     */

		private function carregaInstrumentos($id) {
			if(!is_int($id)) {
				throw new InvalidArgumentException("Erro, Espera receber um inteiro, recebeu ". gettype($id).Utilidades::debugBacktrace(), E_USER_ERROR);
			}
			else {
				$instrumentos = new Instrumentos($this->getId());
				return $instrumentos->getInstrumentos();
			}
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
			$this->validaId($id);
			$this->id = $id;
		}

		/**
		 * Valida o id do usuário
	     * @throws InvalidArgumentException Uso de argumentos inválidos
		 */
		private function validaId($id) {
			if(!is_int($id)) {
				throw new InvalidArgumentException("Erro ao definir o id do usuário. Esperava um inteiro, recebeu ".gettype($id).Utilidades::debugBacktrace(), E_USER_ERROR);
			}
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
			$this->validaNome($nome);
			$this->nome = $nome;
		}

		/**
		 * Valida o nome do usuário
	     * @throws InvalidArgumentException Uso de argumentos inválidos
		 */
		private function validaNome($nome) {
			if(!is_string($nome)) {
				throw new InvalidArgumentException("Erro ao definir o nome do usuário. Esperava uma string, recebeu ".gettype($nome).Utilidades::debugBacktrace(), E_USER_ERROR);
			}
			else if(is_null($nome)) {
				throw new InvalidArgumentException("Erro ao definir nome do usuário. String nula.".Utilidades::debugBacktrace(), E_USER_ERROR);
			}
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
			echo $email;
			$this->validaEmail($email);
			//Se estiver trocando o email
			if($troca) {
	            if(Utilidades::valoresExistenteDB(array('email' => $email), TABELA_USUARIOS)){
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
		 * Valida o email do usuário
	     * @throws InvalidArgumentException Uso de argumentos inválidos
		 */
		private function validaEmail($email) {
			if(!is_string($email)) {
				throw new InvalidArgumentException("Erro ao definir o email do usuário. Esperava uma string, recebeu ".gettype($email).Utilidades::debugBacktrace(), E_USER_ERROR);
			}
			else if(is_null($email)) {
				throw new InvalidArgumentException("Erro ao definir email do usuário. String nula.".Utilidades::debugBacktrace(), E_USER_ERROR);
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
			$this->validaNacionalidade($nacionalidade);
			$this->nacionalidade = $nacionalidade;
		}

		/**
		 * Valida a nacionalidade do usuário
	     * @throws InvalidArgumentException Uso de argumentos inválidos
		 */
		private function validaNacionalidade($nacionalidade) {
			if(!is_string($nacionalidade)) {
				throw new InvalidArgumentException("Erro ao definir a nacionalidade do usuário. Esperava uma string, recebeu ".gettype($nacionalidade).Utilidades::debugBacktrace(), E_USER_ERROR);
			}
			else if(is_null($nacionalidade)) {
				throw new InvalidArgumentException("Erro ao definir a nacionalidade do usuário. String nula.".Utilidades::debugBacktrace(), E_USER_ERROR);
			}
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
			$this->validaTipo($tipo);
			$this->tipo = $tipo;
		}

		/**
		 * Valida o tipo do usuário
	     * @throws InvalidArgumentException Uso de argumentos inválidos
		 */
		private function validaTipo($tipo) {
			if(!is_int($tipo)) {
				throw new InvalidArgumentException("Erro ao definir o tipo do usuário. Esperava um inteiro, recebeu ".gettype($tipo).Utilidades::debugBacktrace(), E_USER_ERROR);
			}
			else if(is_null($tipo)) {
				throw new InvalidArgumentException("Erro ao definir tipo do usuário. Inteiro nulo.".Utilidades::debugBacktrace(), E_USER_ERROR);
			}
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
			$this->validaSenha($senha);
			$this->senhaHash = ($hash) ? Utilidades::geraHashSenha($senha) : $senha;
		}

		/**
		 * Valida a senha do usuário
	     * @throws InvalidArgumentException Uso de argumentos inválidos
		 */
		private function validaSenha($senha) {
			if(!is_string($senha)) {
				throw new InvalidArgumentException("Erro ao definir a hash da senha do usuário. Esperava uma string, recebeu ".gettype($senha).Utilidades::debugBacktrace(), E_USER_ERROR);
			}
			else if(is_null($senha)) {
				throw new InvalidArgumentException("Erro ao definir a hash da senha do usuário. String nula".Utilidades::debugBacktrace(), E_USER_ERROR);
			}
			else if($this->senhaTamanhoInvalido($senha)) {
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
			$this->validaFotoPerfil($fotoPerfil);
			$this->fotoPerfil = $fotoPerfil;
		}

		/**
		 * Valida a foto de perfil do usuário
	     * @throws InvalidArgumentException Uso de argumentos inválidos
		 */
		private function validaFotoPerfil($fotoPerfil) {
			if(!is_array($fotoPerfil)) {
				throw new InvalidArgumentException("Erro ao definir a foto de perfil do usuário. Esperava um array, recebeu ".gettype($fotoPerfil).Utilidades::debugBacktrace(), E_USER_ERROR);
			}
			else if(is_null($fotoPerfil)) {
				throw new InvalidArgumentException("Erro ao definir a foto de perfil do usuário. String nula".Utilidades::debugBacktrace(), E_USER_ERROR);
			}
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
			$this->validaEstilo($estilo);
			$this->estilo = $estilo;
		}

		/**
		 * Valida o estilo do usuário
	     * @throws InvalidArgumentException Uso de argumentos inválidos
		 */
		private function validaEstilo($estilo) {
			if(!is_string($estilo)) {
				throw new InvalidArgumentException("Erro ao definir o estilo de perfil do usuário. Esperava uma string, recebeu ".gettype($estilo).Utilidades::debugBacktrace(), E_USER_ERROR);
			}
			else if(is_null($estilo)) {
				throw new InvalidArgumentException("Erro ao definir o estilo de perfil do usuário. String nula".Utilidades::debugBacktrace(), E_USER_ERROR);
			}
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
			$this->validaStatus($status);
			$this->status = $status;
		}

		/**
		 * Valida o status do usuário
	     * @throws InvalidArgumentException Uso de argumentos inválidos
		 */
		private function validaStatus($status) {
			if(!is_int($status)) {
				throw new InvalidArgumentException("Erro ao definir o status do usuário. Esperava um inteiro, recebeu ".gettype($status).Utilidades::debugBacktrace(), E_USER_ERROR);
			}
			else if(is_null($status)) {
				throw new InvalidArgumentException("Erro ao definir o status do usuário. Inteiro nulo".Utilidades::debugBacktrace(), E_USER_ERROR);
			}
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
			$this->validaDataCadastro($dataCadastro);
			$this->dataCadastro = $dataCadastro;
		}

		/**
		 * Valida a data de cadastro do usuário
	     * @throws InvalidArgumentException Uso de argumentos inválidos
		 */
		private function validaDataCadastro($dataCadastro) {
			if(!is_string($dataCadastro)) {
				throw new InvalidArgumentException("Erro ao definir a data de cadastro do usuário. Esperava uma string, recebeu ".gettype($dataCadastro).Utilidades::debugBacktrace(), E_USER_ERROR);
			}
			else if(is_null($dataCadastro)) {
				throw new InvalidArgumentException("Erro ao definir o status do usuário. Inteiro nulo".Utilidades::debugBacktrace(), E_USER_ERROR);
			}
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
			$this->validaInteresseMusical($interesseMusical);
			$this->interesseMusical = $interesseMusical;
		}

		/**
		 * Valida o interesse musical do usuário
	     * @throws InvalidArgumentException Uso de argumentos inválidos
		 */
		private function validaInteresseMusical($interesseMusical) {
			if(!is_array($interesseMusical)) {
				throw new InvalidArgumentException("Erro ao definir o interesse musical do usuário. Esperava um array, recebeu ".gettype($interesseMusical).Utilidades::debugBacktrace(), E_USER_ERROR);
			}
			else if(!is_string($interesseMusical)) {
				throw new InvalidArgumentException("Erro ao definir o interesse musical do usuário. Esperava uma string, recebeu ".gettype($interesseMusical).Utilidades::debugBacktrace(), E_USER_ERROR);
			}
			else if(is_null($interesseMusical)) {
				throw new InvalidArgumentException("Erro ao definir o interesse musical do usuário. String nula".Utilidades::debugBacktrace(), E_USER_ERROR);
			}
		}

	    /**
	     * Retorna o interesse musical do usuário
	     * @return array interesse musical do usuário
	     */
		private function getInteresseMuscial() {
			return $this->interesseMusical;
		}

	    /**
	     * Define os instrumentos tocados pelo usuário
	     * @param array instrumentos tocados pelo usuário a ser definido
	     */
		public function setInstrumentos($instrumentos) {
			$this->validaInstrumentos($instrumentos);
			$this->instrumentos = $instrumentos;
		}

		/**
		 * Valida instrumentos do usuário
	     * @throws InvalidArgumentException Uso de argumentos inválidos
		 */
		private function validaInstrumentos($instrumentos) {
			if(!is_array($instrumentos)) {
				throw new InvalidArgumentException("Erro ao definir os instrumentos do usuário. Esperava um array, recebeu ".gettype($instrumentos).Utilidades::debugBacktrace(), E_USER_ERROR);
			}
			else if(!is_string($instrumentos)) {
				throw new InvalidArgumentException("Erro ao definir os intrumentos do usuário. Esperava uma string, recebeu".gettype($instrumentos).Utilidades::debugBacktrace(), E_USER_ERROR);
			}
			else if($this->isArtistUser()) {
				if(is_null($instrumentos)) {
					throw new Exception("Erro ao definir os instrumentos do usuário. String nula.".Utilidades::debugBacktrace(), E_USER_ERROR);
				}
			}
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
			$this->validaBandas($bandas);
			$this->bandas = $bandas;
		}

		/**
		 * Valida as bandas do usuário
	     * @throws InvalidArgumentException Uso de argumentos inválidos
		 */
		private function validaBandas($bandas) {
			if(!is_array($bandas)) {
				throw new InvalidArgumentException("Erro ao definir as bandas do usuário. Esperava um array, recebeu ".gettype($instrumentos).Utilidades::debugBacktrace(), E_USER_ERROR);
			}
			else if(!is_string($bandas)) {
				throw new InvalidArgumentException("Erro ao definir as bandas do usuário. Esperava uma string, recebeu ".gettype($instrumentos).Utilidades::debugBacktrace(), E_USER_ERROR);
			}
			else if(!$this->isArtistUser()) {
				throw new Exception("Erro ao definir as bandas do usuário. Apenas artista pode ter banda.".Utilidades::debugBacktrace(), E_USER_ERROR);
			}
		}

	    /**
	     * Return as bandas tocadas pelo usuário
	     * @return array bandas tocadas pelo usuário
	     */
		private function getBandas() {
			return $this->bandas;
		}
	}
	//Apenas para testes, pode excluir se desnecessário.
	/*$usuario = new Usuario;
	$usuario->setNome("cristian");
	$usuario->setEmail("arthur_adolfo@hotmail.com");
	$usuario->setNacionalidade("Brasileiro");
	$usuario->setTipo(1);
	$usuario->setSenhaHash("030197999");
	$usuario->setEstilo("rock");
	$usuario->setStatus(1);
	$usuario->setDataCadastro("25/07/2015");
	$usuario->salvaDados();*/
?>