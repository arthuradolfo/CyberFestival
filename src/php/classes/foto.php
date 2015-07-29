<?php
	/**
	 * Classe que contem informacoes e metodos para o upload de fotos
	 * DEVE SER CHAMADA COM UM ID VÁLIDO! Ex. $foto = new Foto($id)
	 *
	 * @category Fotos
	 * @package  Usuário
	 * @author Arthur Adolfo <arthur_adolfo@hotmail.com>
	 * @version 1.0
	 * @copyright CyberFestival 2015
	 */

	class Foto extends Arquivo implements AcoesCadastroCarregamento {
		/**
		 * Id da foto
		 * @var id da foto
		 */
		private $id;

		/**
		 * Id do usuário
		 * @var id do usuário
		 */
		private $idUsuario;

		/**
		 * Arquivo da foto a fazer upload
		 * @var file dados do arquivo da foto
		 */
		private $arquivo;

		/**
		 * Nome para a foto
		 * @var string nome da foto
		 */
		private $nome;

		/**
		 * Caminho para a foto
		 * @var string caminho da foto
		 */
		private $caminho;

		/**
		 * Descrição para a foto
		 * @var string descrição da foto
		 */
		private $descricao;

		/**
		 * Tipo de foto (Perfil, etc)
		 * @var string tipo de foto
		 */
		private $tipo;

		/**
		 * @var string data de cadastro
		 */
		private $dataCadastro;

		/**
		 * Tipos permitidos
		 * @var array tipos permitidos
		 */
		private $tiposPermitidos = array("bmp", "gif", "jpeg", "jpg", "png");

		/**
		 * Construtor da classe, se id não for nulo, carrega dados da foto de perfil
		 * @param int id do usuário que pertence a foto
	     * @throws InvalidArgumentException Uso de argumentos inválidos
	     * @throws Exception Erro ocorrido
		 */
		function __construct($idUsuario = NULL) {
			if(!is_null($idUsuario)) {
				parent::__construct();
				if(!is_int($idUsuario)) {
					throw new InvalidArgumentException("Erro. Id inválido. Esperava um inteiro, recebeu ".gettype($idUsuario).Utilidades::debugBacktrace(), E_USER_ERROR);
				}
				else {
					if(Utilidades::valoresExistenteDB(array('id_usuario' => $idUsuario), TABELA_FOTOS)) {
						Utilidades::carregaDados(array('id_usuario' => $idUsuario), TABELA_FOTOS);
					}
					else {
						$this->setIdUsuario($idUsuario);
					}
				}
			}
		}

	    /**
	     * Define informações da foto de perfil
	     * @param array dados da foto de perfil a ser definida
	     * @throws InvalidArgumentException Uso de argumentos inválidos
	     */
		private function setDados($dados) {
			if(is_array($dados)){
	            try {
	            	$this->setId($dados['id']);
			        $this->setIdUsuario($dados['id_usuario']);
			        $this->setNome($dados['nome']);
			        $this->setCaminho($dados['caminho']);
			        $this->setDescricao($dados['descricao']);
			        $this->setTipo($dados['tipo']);
			        $this->setDataCadastro($dados['data']);
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
	     * Retorna informações da foto para inserir no DB
	     * @return array dados da foto
	     */
		public function getDadosBanco() {
			$dados = array( "id"         => $this->getId(),
        					"id_usuario" => $this->getIdUsuario(),
        					"nome"       => $this->getNome(),
	        				"caminho"    => $this->getCaminho(),
	        				"descricao"  => $this->getDescricao(),
	        				"tipo"       => $this->getTipo(),
	        				"data"       => $this->getDataCadastro()
	        				);
			return $dados;
		}

	    /**
	     * Retorna informações da foto
	     * @return array dados da foto
	     */
		public function getDados($arquivo = false) {
			$dados = array( "id"         => $this->getId(),
        					"id_usuario" => $this->getIdUsuario(),
        					"nome"       => $this->getNome(),
	        				"caminho"    => $this->getCaminho(),
	        				"descricao"  => $this->getDescricao(),
	        				"tipo"       => $this->getTipo(),
	        				"data"       => $this->getDataCadastro()
	        				);
			return $dados;
		}

		/**
		 * Salva dados no banco de dados (atualiza cadastro com a foto)
		 * Se id for nulo, cadastra nova foto no banco de dados
		 * Se não for nulo, atualiza banco de dados
	     * @throws Exception Ocorreu erro
		 */

		public function salvaDados() {
			$this->validaDados();
			$this->validaExtensao();
			if(is_null($this->getId())) {
				try {
					$id = parent::insereDadosBancoDeDados($this->getDadosBanco(), TABELA_FOTOS);
					$this->setId($id);
					if(is_null($this->getId())) {
						throw new Exception("Erro ao cadastrar foto! ".Utilidades::debugBacktrace(), E_USER_ERROR);
					}
					$this->carregaDados(array('id' => $id));
					parent::uploadArquivo($this->getDados());
				}
				catch(Exception $e) {
					trigger_error("Ocorreu um erro ao tentar salvar dados da foto no DB! ".$e->getMessage().Utilidades::debugBacktrace(), E_USER_ERROR);
				}
			}
			else {
				try {
					parent::atualizaDadosBancoDeDados($this->getDadosBanco(), TABELA_FOTOS);
					parent::uploadArquivo($this->getDados());
				}
				catch(Exception $e) {
					trigger_error("Ocorreu um erro ao tentar salvar dados da foto no DB! ".$e->getMessage().Utilidades::debugBacktrace(), $e->getCode());
				}
			}
		}

		/**
		 * Salva dados da foto de perfil no banco de dados fotos
		 * @throws Exception em caso de erro
		 */

		public function salvaDadosFotoPerfil() {
			$this->validaDados();
			$this->validaExtensao();
			if(is_null($this->getId())) {
				try {
					$id = parent::insereDadosBancoDeDados($this->getDadosBanco(), TABELA_FOTOS);
					$this->setId($id);
					if(is_null($this->getId())) {
						throw new Exception("Erro ao cadastrar foto! ".Utilidades::debugBacktrace(), E_USER_ERROR);
					}
					$this->carregaDados(array('id' => $id));
				}
				catch(Exception $e) {
					trigger_error("Ocorreu um erro ao tentar salvar dados da foto no DB! ".$e->getMessage().Utilidades::debugBacktrace(), E_USER_ERROR);
				}
			}
		} 

		/**
	     * Valida dados da foto de eprfil
	     * @throws Exception caso ocorra erro
	     */

		private function validaDados() {
			if(is_null($this->getIdUsuario())) {
				throw new InvalidArgumentException("Erro! Id de usuário inválido!".Utilidades::debugBacktrace(), E_USER_ERROR);
			}
			if(is_null($this->getNome())) {
				throw new InvalidArgumentException("Erro! Nome inválido!".Utilidades::debugBacktrace(), E_USER_ERROR);
			}
			if(is_null($this->getCaminho())) {
				throw new InvalidArgumentException("Erro! Caminho inválido!".Utilidades::debugBacktrace(), E_USER_ERROR);
			}
			if(is_null($this->getDescricao())) {
				throw new InvalidArgumentException("Erro! Descrição inválida!".Utilidades::debugBacktrace(), E_USER_ERROR);
			}
			if(is_null($this->getTipo())) {
				throw new InvalidArgumentException("Erro! Tipo de foto inválido!".Utilidades::debugBacktrace(), E_USER_ERROR);
			}
			if(is_null($this->getArquivo())) {
				throw new InvalidArgumentException("Erro! Arquivo inválido!".Utilidades::debugBacktrace(), E_USER_ERROR);
			}
			if(is_null($this->getDataCadastro())) {
				throw new InvalidArgumentException("Erro! Data de cadastro inválido!".Utilidades::debugBacktrace(), E_USER_ERROR);
			}
		}

		/**
		 * Valida o id do usuário
	     * @throws InvalidArgumentException Uso de argumentos inválidos
		 */
		private function validaExtensao() {
			$tipoPermitido = false;
			foreach($this->getTiposPermitidos() as $tipo){
	            if(strtolower($this->getExtensao($this->getArquivo())) == strtolower($tipo)){
	                $tipoPermitido = true;
	            }
	        }
	        if(!$tipoPermitido){
	            throw new Exception("Erro! Tipo não é permitido! envie outro arquivo!".Utilidades::debugBacktrace(), E_USER_ERROR);
	        }
		}

	    /**
	     * Retorna informações da foto
	     * @return array dados da foto
	     */
		public function getExtensao($arquivo) {
			$this->validaArquivo($arquivo);
			$tipos = explode(".", $arquivo["name"]); //se arquivo.ext tipos[0] = "arquivo" e tipos[1] = "ext"
			$extensao = $tipos[count($tipos) - 1];
			return $extensao;
		}

	    /**
	     * Define id da foto
	     * @param string id da foto
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
		public function getId() {
			return $this->id;
		}

	    /**
	     * Define nome do usuário da foto
	     * @param string nome do usuário da foto
	     */
		public function setIdUsuario($idUsuario) {
			$this->validaIdUsuario($idUsuario);
			$this->idUsuario = $idUsuario;
		}

		/**
		 * Valida id do usuário da foto
	     * @throws InvalidArgumentException Uso de argumentos inválidos
		 */
		private function validaIdUsuario($idUsuario) {
			if(!is_int($idUsuario)) {
				throw new InvalidArgumentException("Erro ao definir o id do usuário. Esperava um inteiro, recebeu ".gettype($idUsuario).Utilidades::debugBacktrace(), E_USER_ERROR);
			}
		}

		/**
	     * Retorna id do usuário da foto
	     * @return string id do usuário da foto
	     */
		private function getIdUsuario() {
			return $this->idUsuario;
		}

	    /**
	     * Define nome da foto
	     * @param string nome da foto
	     */
		public function setNome($nome) {
			$this->validaNome($nome);
			$this->nome = $nome;
		}

		/**
		 * Valida nome da foto
	     * @throws InvalidArgumentException Uso de argumentos inválidos
		 */
		private function validaNome($nome) {
			if(!is_string($nome)) {
				throw new InvalidArgumentException("Erro ao definir o url da foto. Esperava uma string, recebeu ".gettype($nome).Utilidades::debugBacktrace(), E_USER_ERROR);
			}
		}

		/**
	     * Retorna nome da foto
	     * @return string nome da foto
	     */
		public function getNome() {
			return $this->nome;
		}

	    /**
	     * Define caminho da foto
	     * @param string caminho da foto
	     */
		public function setCaminho($caminho) {
			$this->validaCaminho($caminho);
			$this->caminho = $caminho;
		}

		/**
		 * Valida caminho da foto
	     * @throws InvalidArgumentException Uso de argumentos inválidos
		 */
		private function validaCaminho($caminho) {
			if(!is_string($caminho)) {
				throw new InvalidArgumentException("Erro ao definir o caminho da foto. Esperava uma string, recebeu ".gettype($caminho).Utilidades::debugBacktrace(), E_USER_ERROR);
			}
		}

		/**
	     * Retorna caminho da foto
	     * @return string caminho da foto
	     */
		public function getCaminho() {
			return $this->caminho;
		}

	    /**
	     * Define descrição da foto
	     * @param string descrição da foto
	     */
		public function setDescricao($descricao) {
			$this->validaDescricao($descricao);
			$this->descricao = $descricao;
		}

		/**
		 * Valida descrição da foto
	     * @throws InvalidArgumentException Uso de argumentos inválidos
		 */
		private function validaDescricao($descricao) {
			if(!is_string($descricao)) {
				throw new InvalidArgumentException("Erro ao definir a definição da foto. Esperava uma string, recebeu ".gettype($descricao).Utilidades::debugBacktrace(), E_USER_ERROR);
			}
		}

		/**
	     * Retorna descrição da foto
	     * @return string descrição da foto
	     */
		public function getDescricao() {
			return $this->descricao;
		}

	    /**
	     * Define tipo de foto
	     * @param string tipo de foto
	     */
		public function setTipo($tipo) {
			$this->validaTipo($tipo);
			$this->tipo = $tipo;
		}

		/**
		 * Valida tipo de foto
	     * @throws InvalidArgumentException Uso de argumentos inválidos
		 */
		private function validaTipo($tipo) {
			if(!is_int($tipo)) {
				throw new InvalidArgumentException("Erro ao definir o tipo de foto. Esperava um inteiro, recebeu ".gettype($tipo).Utilidades::debugBacktrace(), E_USER_ERROR);
			}
		}

		/**
	     * Retorna tipo de foto
	     * @return string tipo de foto
	     */
		public function getTipo() {
			return $this->tipo;
		}

	    /**
	     * Define o arquivo da foto
	     * @param file foto
	     */
		public function setArquivo($arquivo) {
			$this->validaArquivo($arquivo);
			$this->arquivo = $arquivo;
		}

		/**
		 * Valida arquivo da foto
	     * @throws InvalidArgumentException Uso de argumentos inválidos
		 */
		private function validaArquivo($arquivo) {
			if(!isset($arquivo)) {
				throw new InvalidArgumentException("Erro ao definir o arquivo da foto. Esperava um arquivo, recebeu ".gettype($arquivo).Utilidades::debugBacktrace(), E_USER_ERROR);
			}
		}

		/**
	     * Retorna arquivo foto
	     * @return file foto
	     */
		public function getArquivo() {
			return $this->arquivo;
		}

	    /**
	     * Define os tipos permitidos
	     * @param array tipos permitidos
	     */
		public function setTiposPermitidos($tipos) {
			$this->validaTiposPermitidos($tipos);
			$this->tiposPermitidos = $tiposPermitidos;
		}

		/**
		 * Valida o tipos permitidos
	     * @throws InvalidArgumentException Uso de argumentos inválidos
		 */
		private function validaTiposPermitidos($tiposPermitidos) {
			if(!is_array($tiposPermitidos)) {
				throw new InvalidArgumentException("Erro ao definir os tipos permitidos. Esperava um array, recebeu ".gettype($tiposPermitidos).Utilidades::debugBacktrace(), E_USER_ERROR);
			}
		}

		/**
	     * Retorna tiposPermitidos
	     * @return file
	     */
		public function getTiposPermitidos() {
			return $this->tiposPermitidos;
		}

	    /**
	     * Define a data de cadastro da foto
	     * @param string data de cadastro da foto a ser definido
	     */
		public function setDataCadastro($dataCadastro) {
			$this->validaDataCadastro($dataCadastro);
			$this->dataCadastro = $dataCadastro;
		}

		/**
		 * Valida a data de cadastro da foto de perfil
	     * @throws InvalidArgumentException Uso de argumentos inválidos
		 */
		private function validaDataCadastro($dataCadastro) {
			if(!is_string($dataCadastro)) {
				throw new InvalidArgumentException("Erro ao definir a data de cadastro da foto. Esperava uma string, recebeu ".gettype($dataCadastro).Utilidades::debugBacktrace(), E_USER_ERROR);
			}
			else if(is_null($dataCadastro)) {
				throw new InvalidArgumentException("Erro ao definir a data de cadastro da foto. Inteiro nulo".Utilidades::debugBacktrace(), E_USER_ERROR);
			}
		}

	    /**
	     * Return data de cadastro da foto de perfil
	     * @return string data de cadastro da foto de perfil
	     */
		private function getDataCadastro() {
			return $this->dataCadastro;
		}
	}
?>