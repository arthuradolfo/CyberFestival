<?php
	/**
	 * Classe que contem informacoes e metodos para o upload de fotos de perfil
	 * DEVE SER CHAMADA COM UM ID VÁLIDO! Ex. $foto = new FotoPerfil($id)
	 *
	 * @category Fotos
	 * @package  Usuário
	 * @author Arthur Adolfo <arthur_adolfo@hotmail.com>
	 * @version 1.0
	 * @copyright CyberFestival 2015
	 */

	class FotoPerfil extends Arquivo {
		/**
		 * Id do usuário
		 * @var id do usuário
		 */
		private $id;

		/**
		 * Id do usuário
		 * @var id do usuário
		 */
		private $id;

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
		 * Nome de usuário para a foto
		 * @var string nome do usuário da foto
		 */
		private $nomeUsuario;


		/**
		 * Caminho para a foto
		 * @var string caminho da foto
		 */
		private $caminho;

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
				parent::__construct(TIPO_ARQUIVO_FOTO_PERFIL);
				if(!is_int($idUsuario)) {
					throw new InvalidArgumentException("Erro. Id inválido. Esperava um inteiro, recebeu ".gettype($idUsuario).Utilidades::debugBacktrace(), E_USER_ERROR);
				}
				else {
					if(Utilidades::valoresExistenteDB(array('id_usuario' => $idUsuario), TABELA_USUARIOS)) {
						$this->carregaDados(array('id_usuario' => $idUsuario));
					}
					else {
						$usuario = new Usuario($idUsuario);
						$this->setIdUsuario($idUsuario);
					}
				}
			}
		}

		/**
		 * Carrega dados do arquivo
		 * @param array[] dados para buscar no banco de dados
	     */
		private function carregaDados($campos) {
			if(!is_array($campos)){
	            throw new InvalidArgumentException("Erro ao definar os campos, esperava um array de campos. Recebeu ".gettype($campos).Utilidades::debugBacktrace(), E_USER_ERROR);
	        }

	        $query = new MysqliDb();

			foreach ($campos as $coluna => $valor) {
	            $query->where($coluna, $valor);
	        }

			$this->setDados($query->getOne(TABELA_FOTOS_PERFIL));
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
	     * Retorna informações da foto
	     * @param boolean Se for true, carrega o arquivo nos dados
	     * @return array dados da foto
	     */
		public function getDados($arquivo = false) {
			try {
				if($arquivo) {
					$dados = array( "id" => $this->getId(),
		        					"id_usuario" => $this->usuario->getId(),
		        					"nome" => $this->getNome(),
			        				"caminho" => $this->getCaminho(),
			        				"arquivo" => $this->getArquivo()
			        				);
				}
				else {
					$dados = array( "id" => $this->getId(),
		        					"id_usuario" => $this->usuario->getId(),
		        					"nome" => $this->getNome(),
			        				"caminho" => $this->getCaminho()
			        				);
				}
			}
			catch(Exception $e) {
				trigger_error("Ocorreu algum erro!".Utilidades::debugBacktrace(), E_USER_ERROR);
			}
			return $dados;
		}


		/**
		 * Salva dados no banco de dados (atualiza cadastro com a foto de perfil)
		 * Se id for nulo, cadastra nova foto de perfil no banco de dados
		 * Se não for nulo, atualiza banco de dados
	     * @throws Exception Ocorreu erro
		 */

		public function salvaDados() {
			$this->validaDados();
			$this->validaExtensao();
			if(is_null($this->getId())) {
				try {
					$id = parent::insereDadosBancoDeDados($this->getDados(), TABELA_FOTOS_PERFIL);
					$this->setId($id);
					if(is_null($this->getId())) {
						throw new Exception("Erro ao cadastrar foto! ".Utilidades::debugBacktrace(), E_USER_ERROR);
					}
					parent::uploadArquivo($this->getDados(true));
				}
				catch(Exception $e) {
					trigger_error("Ocorreu um erro ao tentar salvar dados da foto no DB! ".$e->getMessage().Utildiades::debugBacktrace(), $e->getCode());
				}
			}
			else {
				try {
					parent::atualizaDadosBancoDeDados($this->getDados(), TABELA_FOTOS_PERFIL);
					parent::uploadArquivo($this->getDados(true));
				}
				catch(Exception $e) {
					trigger_error("Ocorreu um erro ao tentar salvar dados da foto no DB! ".$e->getMessage().Utildiades::debugBacktrace(), $e->getCode());
				}
			}
		}

		/**
	     * Valida dados da foto de eprfil
	     * @throws Exception caso ocorra erro
	     */

		private function validaDados() {
			if(is_null($this->getId())) {
				throw new InvalidArgumentException("Erro! Id inválido!".Utilidades::debugBacktrace(), E_USER_ERROR);
			}
			if(is_null($this->getIdUsuario())) {
				throw new InvalidArgumentException("Erro! Id de usuário inválido!".Utilidades::debugBacktrace(), E_USER_ERROR);
			}
			if(is_null($this->getNome())) {
				throw new InvalidArgumentException("Erro! Nome inválido!".Utilidades::debugBacktrace(), E_USER_ERROR);
			}
			if(is_null($this->getCaminho())) {
				throw new InvalidArgumentException("Erro! Caminho inválido!".Utilidades::debugBacktrace(), E_USER_ERROR);
			}
			if(is_null($this->getArquivo())) {
				throw new InvalidArgumentException("Erro! Arquivo inválido!".Utilidades::debugBacktrace(), E_USER_ERROR);
			}
		}

		/**
		 * Valida o id do usuário
	     * @throws InvalidArgumentException Uso de argumentos inválidos
		 */
		private function validaExtensao() {
			$tipoPermitido = false;
			foreach($this->getTiposPermitidos as $tipo){
	            if(strtolower($this-getExtensao()) == strtolower($tipo)){
	                $tipoPermitido = true;
	            }
	        }
	        if(!$tipoPermitido){
	            throw new Exception("Erro! Tipo não é permitido! envie outro arquivo!".Utilidade::debugBacktrace(), E_USER_ERROR);
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
		private function getNome() {
			return $this->nome;
		}

	    /**
	     * Define nome do usuário da foto
	     * @param string nome do usuário da foto
	     */
		public function setNomeUsuario($nomeUsuario) {
			$this->validaNomeUsuario($nomeUsuario);
			$this->nomeUsuario = $nomeUsuario;
		}

		/**
		 * Valida nome do usuário da foto
	     * @throws InvalidArgumentException Uso de argumentos inválidos
		 */
		private function validaNomeUsuario($nomeUsuario) {
			if(!is_string($nomeUsuario)) {
				throw new InvalidArgumentException("Erro ao definir o url da foto. Esperava uma string, recebeu ".gettype($nomeUsuario).Utilidades::debugBacktrace(), E_USER_ERROR);
			}
		}

		/**
	     * Retorna nome do usuário da foto
	     * @return string nome do usuário da foto
	     */
		private function getNomeUsuario() {
			return $this->nomeUsuario;
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
		private function getCaminho() {
			return $this->caminho;
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
			if(!is_file($arquivo)) {
				throw new InvalidArgumentException("Erro ao definir o arquivo da foto. Esperava um arquivo, recebeu ".gettype($arquivo).Utilidades::debugBacktrace(), E_USER_ERROR);
			}
		}

		/**
	     * Retorna arquivo foto
	     * @return file foto
	     */
		private function getArquivo() {
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
			if(!is_file($tiposPermitidos)) {
				throw new InvalidArgumentException("Erro ao definir os tipos permitidos. Esperava um array, recebeu ".gettype($tiposPermitidos).Utilidades::debugBacktrace(), E_USER_ERROR);
			}
		}

		/**
	     * Retorna tiposPermitidos
	     * @return file
	     */
		private function getTiposPermitidos() {
			return $this->tiposPermitidos;
		}
	}
?>