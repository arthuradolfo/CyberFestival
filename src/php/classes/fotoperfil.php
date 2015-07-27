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
		 * Arquivo da foto a fazer upload
		 * @var file dados do arquivo da foto
		 */
		private $arquivo;

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
	     * @return array dados da foto
	     */
		public function getDados() {
			try {
				$dados = array( "id" => $this->getId(),
	        					"id_usuario" => $this->usuario->getId(),
	        					"nome" => $this->getNome(),
		        				"caminho" => $this->getCaminho(),
		        				"arquivo" => $this->getArquivo()
		        				);
			}
			catch(Exception $e) {
				trigger_error("Ocorreu algum erro!".Utilidades::debugBacktrace(), E_USER_ERROR);
			}
			return $dados;
		}


		/**
		 * Salva dados no banco de dados (atualiza cadastro com a foto de perfil)
	     * @throws Exception Ocorreu erro
		 */

		public function salvaDados() {
			$this->validaDados();
			try {
				parent::insereDadosBancoDeDados($this->getDados(), TABELA_FOTOS_PERFIL);
				parent::uploadArquivo($this->getDados());
			}
			catch(Exception $e) {
				trigger_error("Ocorreu um erro ao tentar salvar dados da foto no DB! ".$e->getMessage().Utildiades::debugBacktrace(), $e->getCode());
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
			$this->getUsuario()->setFotoPerfil($nome); //Atualiza o caminho da foto de perfil nos dados do usuário para atualizar o DB
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
	     * Define objeto usuario da foto
	     * @param object usuário
	     */
		public function setUsuario($usuario) {
			$this->validaUsuario($usuario);
			$this->usuario = $usuario;
		}

		/**
		 * Valida objeto usuário
	     * @throws InvalidArgumentException Uso de argumentos inválidos
		 */
		private function validaUsuario($usuario) {
			if(!$usuario instanceof Usuario) {
				throw new InvalidArgumentException("Erro ao definir o objeto usuário. Esperava um objeto, recebeu ".gettype($usuario).Utilidades::debugBacktrace(), E_USER_ERROR);
			}
		}

		/**
	     * Retorna objeto usuário
	     * @return object usuário
	     */
		private function getUsuario() {
			return $this->usuario;
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
	}
?>