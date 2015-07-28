<?php

	/**
	 * Classe banda, que comanda todas as ações de banda
	 * @package Banda
	 * @category Banda
	 * @author Arthur Adolfo <arthur_adolfo@hotmail.com>
	 * @version 1.0
	 * @copyright CyberFestival 2015
	 */
	class Banda extends Cadastro{

		/**
		 * ID da banda no banco de dados 
		 * Caso a banda exista seu id será diferente de NULO
		 * @var int id da banda
		 */
		private $id;

		/**
		 * @var string nome da banda
		 */
		private $nome;

		/**
		 * @var string email da banda
		 */
		private $email;

		/**
		 * @var string estilo da banda
		 */
		private $estilo;

		/**
		 * @var string cidade da banda
		 */
		private $cidade;

		/**
		 * @var array eventos da banda
		 */
		private $eventos;

		/**
		 * Array de fãs da banda array("fan1" => idFan1,
		 *                             "fan2" => idFan2,
		 *                             );
		 * @var array fans da banda
		 */
		private $fans;

		/**
		 * Array de fãs da banda array("integrante1" => array("id"     => idIntegrante1,
		 *													  "funcao" => funcaoIntegrante1
		 *													  ),
		 *                             "integrante2" => array("id"     => idIntegrante2,
		 *													  "funcao" => funcaoIntegrante2
		 *													  )
		 *                             );
		 * @var array integrantes da banda
		 */
		private $integrantes;

		/**
		 * @var string data de cadastro
		 */
		private $dataCadastro;

		function __construct($id = NULL) {
			if(!is_null($id)) {
				if(!is_int($id)) {
					throw new InvalidArgumentException("Erro! Id inválido!".Utilidades::debugBacktrace(), E_USER_ERROR);
				}
				else {
					if(Utilidades::valoresExistenteDB(array('id' => $id), TABELA_BANDAS)) {
						$this->carregaDados(array('id' => $id));
					}
					else {
						throw new Exception("Erro! Banda inexistente no banco de dados!".Utilidades::debugBacktrace(), E_USER_ERROR);
					}
				}
			}
		}

	    /**
	     * Carrega dados da banda do banco de dados pelo id
	     * @param array campos da banda a ser definido
	     */
		private function carregaDados($campos) {
			if(!is_array($campos)){
	            throw new InvalidArgumentException("Erro ao definar os campos, esperava um array de campos. Recebeu ".gettype($campos).Utilidades::debugBacktrace(), E_USER_ERROR);
	        }

	        $query = new MysqliDb();

			foreach ($campos as $coluna => $valor) {
	            $query->where($coluna, $valor);
	        }

			$this->setDados($query->getOne(TABELA_BANDAS));
		}

	    /**
	     * Define informações da banda
	     * @param array dados do usuário a ser definido
	     */
		private function setDados($dados) {
			if(is_array($dados)){
	            try {
	            	$this->setId($dados['id']);
	            	$this->setNome($dados['nome']);
	            	$this->setEmail($dados['email']);
	            	$this->setEstilo($dados['estilo']);
	            	$this->setCidade($dados['cidade']);
	            	$this->setDataCadastro($dados['data']);
	            	//$this->setEventos($this->carregaEventos($this->getId()));
	            	//$this->setFans($this->carregaFans($this->getId()));
	            	//$this->setIntegrantes($this->carregaIntegrantes($this->getId()));

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
					$id = parent::insereDadosBancoDeDados($this->getDados(), TABELA_BANDAS);
					$this->setId($id);
					if(is_null($this->getId())) {
						throw new Exception("Erro ao cadastrar banda! ".Utilidades::debugBacktrace(), E_USER_ERROR);
					}
					$this->carregaDados(array('id' => $id));
					$this->cadastraIntegrantes($this->getIntegrantes());
				}
				catch(Exception $e) {
					trigger_error("Ocorreu um erro ao tentar salvar dados da banda no DB! ".$e->getMessage().Utilidades::debugBacktrace(), E_USER_ERROR);
				}
			}
			else {
				try {
					parent::atualizaDadosBancoDeDados($this->getDados(), TABELA_BANDAS);

				}
				catch(Exception $e) {
					trigger_error("Ocorreu um erro ao tentar salvar dados da banda no DB! ".$e->getMessage().Utilidades::debugBacktrace(), E_USER_ERROR);
				}
			}
		}

	    /**
	     * Retorna informações da banda
	     * @return array dados da banda
	     */
		public function getDados() {
			try {
				$dados = array( "id"     => $this->getId(),
		        				"nome"   => $this->getNome(),
		        				"email"  => $this->getEmail(),
		        				"estilo" => $this->getEstilo(),
		        				"cidade" => $this->getCidade(),
		        				"data"   => $this->getDataCadastro()
		        				);
			}
			catch(Exception $e) {
				trigger_error("Ocorreu algum erro!".Utilidades::debugBacktrace(), E_USER_ERROR);
			}
			return $dados;
		}

		/**
		 * Classe que cadastra os integrantes na banda
		 * @param array informacoes integrantes
	     * @throws Exception Ocorreu erro
		 */

		public function cadastraIntegrantes($integrantes) {
			if(!is_array($integrantes)) {
				throw new InvalidArgumentException("Erro! Esperava array, recebeu ".gettype($integrantes).Utilidades::debugBacktrace(), E_USER_ERROR);
			}
			else {
				foreach($integrantes as $integrante) {
					try {
						$integrante['id_banda'] = $this->getId();
						if(!Utilidades::valoresExistenteDB(array('id_banda' => $integrante['id_banda'], 'id_usuario' => $integrante['id_usuario'], 'funcao' => $integrante['funcao']), TABELA_INTEGRANTES_BANDA)) {
							parent::insereDadosBancoDeDados($integrante, TABELA_INTEGRANTES_BANDA);
						}
						else {
							throw new Exception("Erro ao cadastrar integrante! Já faz parte da banda nessa função!".Utilidades::debugBacktrace(), E_USER_ERROR);
							
						}
					}
					catch(Exception $e){
						trigger_error("Erro! Não foi possível cadastrar integrante!".Utilidades::debugBacktrace().$e->getMessage(), E_USER_ERROR);
					}
				}
			}
		}

		/**
	     * Valida dados do usuário
	     * @throws Exception caso ocorra erro
	     */

		private function validaDados() {
			if(is_null($this->getNome())) {
				throw new InvalidArgumentException("Erro! Nome inválido!".Utilidades::debugBacktrace(), E_USER_ERROR);
			}
			if(is_null($this->getEmail())) {
				throw new InvalidArgumentException("Erro! Email inválido!".Utilidades::debugBacktrace(), E_USER_ERROR);
			}
			if(is_null($this->getEstilo())) {
				throw new InvalidArgumentException("Erro! Estilo inválido!".Utilidades::debugBacktrace(), E_USER_ERROR);
			}
			if(is_null($this->getCidade())) {
				throw new InvalidArgumentException("Erro! Cidade inválida!".Utilidades::debugBacktrace(), E_USER_ERROR);
			}
			if(is_null($this->getDataCadastro())) {
				throw new InvalidArgumentException("Erro! Data de cadastro inválido!".Utilidades::debugBacktrace(), E_USER_ERROR);
			}
		}

	    /**
	     * Retorna informações da banda do usuário
	     * @param int id do usuário
	     * @return array dados das bandas
	     */
		public function getBandasUsuario($id) {
			$this->validaId($id);
			if(Utilidades::valoresExistenteDB(array('id' => $id), TABELA_USUARIOS)) {
				$query = new MysqliDb;
				$query->where("id_usuario", $id);
				$bandas = $query->get(TABELA_INTEGRANTES_BANDA);
				foreach($bandas as $banda) {
					$query->where("id", $banda['id_banda']);
					$bandasUsuario[] = $query->getOne(TABELA_BANDAS);
				}
				return $bandasUsuario;
			}
			else {
				throw new Exception("Erro! Banda inexistente no banco de dados!".Utilidades::debugBacktrace(), E_USER_ERROR);
			}
		}
		/**
		 * Define o id da banda
		 * @param int id da banda
		 */
		public function setId($id) {
			$this->validaId($id);
			$this->id = $id;
		}

		/**
		 * Valida o id da banda
		 */
		private function validaId($id) {
			if(!is_int($id)) {
				throw new InvalidArgumentException("Erro ao definir id da banda. Esperava um inteiro, recebeu um ".gettype($id).Utilidades::debugBacktrace(), E_USER_ERROR);				
			}
			else if(is_null($id)) {
				throw new InvalidArgumentException("Erro ao definir id da banda. Inteiro nulo!".Utilidades::debugBacktrace(), E_USER_ERROR);				
			}
		}

		/**
		 * Retorna o id da banda
		 * @return int id da banda
		 */
		public function getId() {
			return $this->id;
		}

		/**
		 * Define o nome da banda
		 * @param string nome da banda
		 */
		public function setNome($nome) {
			$this->validaNome($nome);
			$this->nome = $nome;
		}

		/**
		 * Valida o nome da banda
		 */
		private function validaNome($nome) {
			if(!is_string($nome)) {
				throw new InvalidArgumentException("Erro ao definir nome da banda. Esperava uma string, recebeu um ".gettype($nome).Utilidades::debugBacktrace(), E_USER_ERROR);				
			}
			else if(is_null($nome)) {
				throw new InvalidArgumentException("Erro ao definir nome da banda. String nula!".Utilidades::debugBacktrace(), E_USER_ERROR);				
			}
		}

		/**
		 * Retorna o nome da banda
		 * @return string nome da banda
		 */
		public function getNome() {
			return $this->nome;
		}

		/**
		 * Define o email da banda
		 * @param string email da banda
		 */
		public function setEmail($email) {
			$this->validaEmail($email);
			$this->email = $email;
		}

		/**
		 * Valida o email da banda
		 */
		private function validaEmail($email) {
			if(!is_string($email)) {
				throw new InvalidArgumentException("Erro ao definir email da banda. Esperava uma string, recebeu um ".gettype($email).Utilidades::debugBacktrace(), E_USER_ERROR);				
			}
			else if(is_null($email)) {
				throw new InvalidArgumentException("Erro ao definir email da banda. String nula!".Utilidades::debugBacktrace(), E_USER_ERROR);				
			}
		}

		/**
		 * Retorna o email da banda
		 * @return string email da banda
		 */
		public function getEmail() {
			return $this->email;
		}

		/**
		 * Define o estilo da banda
		 * @param string estilo da banda
		 */
		public function setEstilo($estilo) {
			$this->validaEstilo($estilo);
			$this->estilo = $estilo;
		}

		/**
		 * Valida o estilo da banda
		 */
		private function validaEstilo($estilo) {
			if(!is_string($estilo)) {
				throw new InvalidArgumentException("Erro ao definir estilo da banda. Esperava uma string, recebeu um ".gettype($estilo).Utilidades::debugBacktrace(), E_USER_ERROR);				
			}
			else if(is_null($estilo)) {
				throw new InvalidArgumentException("Erro ao definir estilo da banda. String nula!".Utilidades::debugBacktrace(), E_USER_ERROR);				
			}
		}

		/**
		 * Retorna o estilo da banda
		 * @return string estilo da banda
		 */
		public function getEstilo() {
			return $this->estilo;
		}

		/**
		 * Define a cidade da banda
		 * @param string cidade da banda
		 */
		public function setCidade($cidade) {
			$this->validaCidade($cidade);
			$this->cidade = $cidade;
		}

		/**
		 * Valida o estilo da banda
		 */
		private function validaCidade($cidade) {
			if(!is_string($cidade)) {
				throw new InvalidArgumentException("Erro ao definir cidade da banda. Esperava uma string, recebeu um ".gettype($cidade).Utilidades::debugBacktrace(), E_USER_ERROR);				
			}
			else if(is_null($cidade)) {
				throw new InvalidArgumentException("Erro ao definir cidade da banda. String nula!".Utilidades::debugBacktrace(), E_USER_ERROR);				
			}
		}

		/**
		 * Retorna a cidade da banda
		 * @return string cidade da banda
		 */
		public function getCidade() {
			return $this->cidade;
		}

		/**
		 * Define eventos da banda
		 * @param array eventos da banda
		 */
		public function setEventos($eventos) {
			$this->validaEventos($eventos);
			$this->eventos = $eventos;
		}

		/**
		 * Valida o estilo da banda
		 */
		private function validaEventos($eventos) {
			if(!is_array($eventos)) {
				throw new InvalidArgumentException("Erro ao definir eventos da banda. Esperava um array, recebeu um ".gettype($eventos).Utilidades::debugBacktrace(), E_USER_ERROR);				
			}
			else if(is_null($eventos)) {
				throw new InvalidArgumentException("Erro ao definir eventos da banda. Array nulo!".Utilidades::debugBacktrace(), E_USER_ERROR);				
			}
		}

		/**
		 * Retorna os eventos da banda
		 * @return array eventos da banda
		 */
		public function getEventos() {
			return $this->eventos;
		}

		/**
		 * Define fãs da banda
		 * @param array fãs da banda
		 */
		public function setFans($fans) {
			$this->validaFans($fans);
			$this->fans = $fans;
		}

		/**
		 * Valida os fãs da banda
		 */
		private function validaFans($fans) {
			if(!is_array($fans)) {
				throw new InvalidArgumentException("Erro ao definir fãs da banda. Esperava um array, recebeu um ".gettype($fans).Utilidades::debugBacktrace(), E_USER_ERROR);				
			}
			else if(is_null($fans)) {
				throw new InvalidArgumentException("Erro ao definir fãs da banda. Array nulo!".Utilidades::debugBacktrace(), E_USER_ERROR);				
			}
		}

		/**
		 * Retorna os fãs da banda
		 * @return array fãs da banda
		 */
		public function getFans() {
			return $this->fans;
		}

		/**
		 * Define integrantes da banda
		 * @param array integrantes da banda
		 */
		public function setIntegrantes($integrantes) {
			$this->validaIntegrantes($integrantes);
			$this->integrantes = $integrantes;
		}

		/**
		 * Valida os integrantes da banda
		 */
		private function validaIntegrantes($integrantes) {
			if(!is_array($integrantes)) {
				throw new InvalidArgumentException("Erro ao definir integrantes da banda. Esperava um array, recebeu um ".gettype($integrantes).Utilidades::debugBacktrace(), E_USER_ERROR);				
			}
			else if(is_null($integrantes)) {
				throw new InvalidArgumentException("Erro ao definir integrantes da banda. Array nulo!".Utilidades::debugBacktrace(), E_USER_ERROR);				
			}
		}

		/**
		 * Retorna os integrantes da banda
		 * @return array integrantes da banda
		 */
		public function getIntegrantes() {
			return $this->integrantes;
		}

	    /**
	     * Define a data de cadastro da banda
	     * @param string data de cadastro da banda a ser definido
	     */
		public function setDataCadastro($dataCadastro) {
			$this->validaDataCadastro($dataCadastro);
			$this->dataCadastro = $dataCadastro;
		}

		/**
		 * Valida a data de cadastro banda
	     * @throws InvalidArgumentException Uso de argumentos inválidos
		 */
		private function validaDataCadastro($dataCadastro) {
			if(!is_string($dataCadastro)) {
				throw new InvalidArgumentException("Erro ao definir a data de cadastro da banda. Esperava uma string, recebeu ".gettype($dataCadastro).Utilidades::debugBacktrace(), E_USER_ERROR);
			}
			else if(is_null($dataCadastro)) {
				throw new InvalidArgumentException("Erro ao definir o status da banda. Inteiro nulo".Utilidades::debugBacktrace(), E_USER_ERROR);
			}
		}

	    /**
	     * Return data de cadastro da banda
	     * @return string data de cadastro da banda
	     */
		public function getDataCadastro() {
			return $this->dataCadastro;
		}
	}
?>