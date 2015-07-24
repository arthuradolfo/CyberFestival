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
		 * id do fã clube da banda
		 * @var int id do fã clube
		 */
		private $fanClube;

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

		function __construct($id = NULL) {
			if(!is_null($id)) {
				if(!is_int($id)) {
					throw new InvalidArgumentException("Erro! Id inválido!".Utilidades::debugBacktrace(), E_USER_ERROR);
				}
				else {
					validaId();
					carregaDados(array('id' => $id));
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
	            	$this->setFanClube($dados['fanClube']);
	            	$this->setEventos($this->carregaEventos($this->getId()));
	            	$this->setFans($this->carregaFans($this->getId()));
	            	$this->setIntegrantes($this->carregaIntegrantes($this->getId()));

			    }
			    catch(Exception $e) {
			    	trigger_error($e->getMessage(), $e->getCode())
			    }
	        }
	        else {
	        	throw new InvalidArgumentException("Ocorreu um erro! Esperava receber um array. Recebeu ".gettype($dados).Utilidades::debugBacktrace(), E_USER_ERROR);
	        }
		}

	    /**
	     * Retorna informações da banda
	     * @return array dados da banda
	     */
		private function getDados() {
			try {
				$dados = array( "id" => $this->getId()
		        				);
			}
			catch(Exception $e) {
				trigger_error("Ocorreu algum erro!".Utilidades::debugBacktrace(), E_USER_ERROR);
			}
			return $dados;
		}

		/**
		 * Valida o id da banda
		 */
		private function validaId() {
			$query = new MysqliDB;
			$query->where('id', $this->getId());
			if(!$query->getOne(TABELA_BANDAS)) {
				trigger_error("Banda não existe no banco de dados! ".Utilidades::debugBacktrace(), E_USER_ERROR);
			}
		}

		/**
		 * Define o id da banda
		 * @param int id da banda
		 */
		private function setId($id) {
			if(!is_int($id)) {
				throw new InvalidArgumentException("Erro ao definir id da banda. Esperava um inteiro, recebeu um ".gettype($id).Utilidades::debugBacktrace(), E_USER_ERROR);				
			}
			else if(is_null($id)) {
				throw new InvalidArgumentException("Erro ao definir id da banda. Inteiro nulo!".Utilidades::debugBacktrace(), E_USER_ERROR);				
			}
			else {
				$this->id = $id;
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
		private function setNome($nome) {
			if(!is_string($nome)) {
				throw new InvalidArgumentException("Erro ao definir nome da banda. Esperava uma string, recebeu um ".gettype($nome).Utilidades::debugBacktrace(), E_USER_ERROR);				
			}
			else if(is_null($nome)) {
				throw new InvalidArgumentException("Erro ao definir nome da banda. String nula!".Utilidades::debugBacktrace(), E_USER_ERROR);				
			}
			else {
				$this->nome = $nome;
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
		private function setEmail($email) {
			if(!is_string($email)) {
				throw new InvalidArgumentException("Erro ao definir email da banda. Esperava uma string, recebeu um ".gettype($email).Utilidades::debugBacktrace(), E_USER_ERROR);				
			}
			else if(is_null($email)) {
				throw new InvalidArgumentException("Erro ao definir email da banda. String nula!".Utilidades::debugBacktrace(), E_USER_ERROR);				
			}
			else {
				$this->email = $email;
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
		private function setEstilo($estilo) {
			if(!is_string($estilo)) {
				throw new InvalidArgumentException("Erro ao definir estilo da banda. Esperava uma string, recebeu um ".gettype($estilo).Utilidades::debugBacktrace(), E_USER_ERROR);				
			}
			else if(is_null($estilo)) {
				throw new InvalidArgumentException("Erro ao definir estilo da banda. String nula!".Utilidades::debugBacktrace(), E_USER_ERROR);				
			}
			else {
				$this->estilo = $estilo;
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
		private function setCidade($cidade) {
			if(!is_string($cidade)) {
				throw new InvalidArgumentException("Erro ao definir cidade da banda. Esperava uma string, recebeu um ".gettype($cidade).Utilidades::debugBacktrace(), E_USER_ERROR);				
			}
			else if(is_null($cidade)) {
				throw new InvalidArgumentException("Erro ao definir cidade da banda. String nula!".Utilidades::debugBacktrace(), E_USER_ERROR);				
			}
			else {
				$this->cidade = $cidade;
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
		private function setEventos($eventos) {
			if(!is_array($eventos)) {
				throw new InvalidArgumentException("Erro ao definir eventos da banda. Esperava um array, recebeu um ".gettype($eventos).Utilidades::debugBacktrace(), E_USER_ERROR);				
			}
			else if(is_null($eventos)) {
				throw new InvalidArgumentException("Erro ao definir eventos da banda. Array nulo!".Utilidades::debugBacktrace(), E_USER_ERROR);				
			}
			else {
				$this->eventos = $eventos;
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
		private function setFans($fans) {
			if(!is_array($fans)) {
				throw new InvalidArgumentException("Erro ao definir fãs da banda. Esperava um array, recebeu um ".gettype($fans).Utilidades::debugBacktrace(), E_USER_ERROR);				
			}
			else if(is_null($fans)) {
				throw new InvalidArgumentException("Erro ao definir fãs da banda. Array nulo!".Utilidades::debugBacktrace(), E_USER_ERROR);				
			}
			else {
				$this->fans = $fans;
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
		 * Define o fã clube da banda
		 * @param string fã clube da banda
		 */
		private function setFanClube($fanClube) {
			if(!is_string($fanClube)) {
				throw new InvalidArgumentException("Erro ao definir fã clube da banda. Esperava uma string, recebeu um ".gettype($fanClube).Utilidades::debugBacktrace(), E_USER_ERROR);				
			}
			else if(is_null($fanClube)) {
				throw new InvalidArgumentException("Erro ao definir fã clube da banda. String nula!".Utilidades::debugBacktrace(), E_USER_ERROR);				
			}
			else {
				$this->fanClube = $fanClube;
			}
		}

		/**
		 * Retorna o fã clube da banda
		 * @return string fã clube da banda
		 */
		public function getFanClube() {
			return $this->fanClube;
		}

		/**
		 * Define integrantes da banda
		 * @param array integrantes da banda
		 */
		private function setIntegrantes($integrantes) {
			if(!is_array($integrantes)) {
				throw new InvalidArgumentException("Erro ao definir integrantes da banda. Esperava um array, recebeu um ".gettype($integrantes).Utilidades::debugBacktrace(), E_USER_ERROR);				
			}
			else if(is_null($integrantes)) {
				throw new InvalidArgumentException("Erro ao definir integrantes da banda. Array nulo!".Utilidades::debugBacktrace(), E_USER_ERROR);				
			}
			else {
				$this->integrantes = $integrantes;
			}
		}

		/**
		 * Retorna os integrantes da banda
		 * @return array integrantes da banda
		 */
		public function getIntegrantes() {
			return $this->integrantes;
		}
	}
?>