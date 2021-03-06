<?php

	/**
	 * Classe banda, que comanda todas as ações de banda
	 * @package Banda
	 * @category Banda
	 * @author Arthur Adolfo <arthur_adolfo@hotmail.com>
	 * @version 1.0
	 * @copyright CyberFestival 2015
	 */
	class Banda implements AcoesCadastroCarregamento {

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
				TratamentoErros::validaInteiro($id, "id da banda");
				$carregamento = new Carregamento;
				if($carregamento->valoresExistenteDB(array('id' => $id), TABELA_BANDAS)) {
					$this->carregaInformacao(array('id' => $id));
				}
				else {
					throw new Exception("Erro! Banda inexistente no banco de dados!".Utilidades::debugBacktrace(), E_USER_ERROR);
				}
			}
		}

		/**
		 * Carrega informações da banda no banco de dados para preencher o objeto
		 * @param array dados do usuário para procurar na tabela
		 */
		public function carregaInformacao($dados) {
			$carregamento = new Carregamento;
			$this->setDados($carregamento->carregaDados($dados, TABELA_BANDAS));
		}

	    /**
	     * Define informações da banda
	     * @param array dados do usuário a ser definido
	     */
		public function setDados($dados) {
			TratamentoErros::validaArray($dados, "dados da banda");
            try {
            	$this->setId($dados['id']);
            	$this->setNome($dados['nome']);
            	$this->setEmail($dados['email']);
            	$this->setEstilo($dados['estilo']);
            	$this->setCidade($dados['cidade']);
            	$this->setDataCadastro($dados['data']);
            	//$this->setEventos($this->carregaEventos($this->getId()));
            	//$this->setFans($this->carregaFans($this->getId()));
            	$this->setIntegrantes($this->carregaIntegrantes($this->getId()));

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
					$cadastro = new Cadastro;
					$id = $cadastro->insereDadosBancoDeDados($this->getDadosBanco(), TABELA_BANDAS);
					$this->setId($id);
					TratamentoErros::validaNulo($this->getId(), "id da banda");
					$this->cadastraIntegrantes($this->getIntegrantes());
					$this->carregaInformacao(array('id' => $id));
				}
				catch(Exception $e) {
					trigger_error("Ocorreu um erro ao tentar salvar dados da banda no DB! ".$e->getMessage().Utilidades::debugBacktrace(), E_USER_ERROR);
				}
			}
			else {
				try {
					$cadastro = new Cadastro;
					$cadastro->atualizaDadosBancoDeDados($this->getDadosBanco(), TABELA_BANDAS);

				}
				catch(Exception $e) {
					trigger_error("Ocorreu um erro ao tentar salvar dados da banda no DB! ".$e->getMessage().Utilidades::debugBacktrace(), E_USER_ERROR);
				}
			}
		}

	    /**
	     * Retorna informações da banda para inserir no DB
	     * @return array dados da banda
	     */
		public function getDadosBanco() {
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
	     * Retorna informações da banda
	     * @return array dados da banda
	     */
		public function getDados() {
			try {
				$dados = array( "id"          => $this->getId(),
		        				"nome"        => $this->getNome(),
		        				"email"       => $this->getEmail(),
		        				"estilo"      => $this->getEstilo(),
		        				"cidade"      => $this->getCidade(),
		        				"eventos"     => $this->getEventos(),
		        				"fans"        => $this->getFans(),
		        				"integrantes" => $this->getIntegrantes(),
		        				"data"        => $this->getDataCadastro()
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
			TratamentoErros::validaArray($integrantes, "integrantes da banda");
			foreach($integrantes as $integrante) {
				try {
					$integrante['id_banda'] = $this->getId();
					$carregamento = new Carregamento;
					$cadastro = new Cadastro;
					if(!$carregamento->valoresExistenteDB(array('id_banda' => $integrante['id_banda'], 'id_usuario' => $integrante['id_usuario'], 'funcao' => $integrante['funcao']), TABELA_INTEGRANTES_BANDA)) {
						$cadastro->insereDadosBancoDeDados($integrante, TABELA_INTEGRANTES_BANDA);
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

		/**
	     * Valida dados do usuário
	     * @throws Exception caso ocorra erro
	     */

		public function validaDados() {
			TratamentoErros::validaNulo($this->getNome(), "nome da banda");
			TratamentoErros::validaNulo($this->getEmail(), "email da banda");
			TratamentoErros::validaNulo($this->getEstilo(), "estilo da banda");
			TratamentoErros::validaNulo($this->getCidade(), "cidade da banda");
			TratamentoErros::validaNulo($this->getDataCadastro(), "data de cadastro da banda");
		}

		/**
		 * Carrega informações da banda pelo Id
		 * @param int id da banda
		 */
		public function carregaBandaPorId($id) {
			TratamentoErros::validaInteiro($id, "id da banda");
			$this->carregaInformacao(array('id' => $id));
		}

		/**
		 * Carrega informações da banda pelo nome
		 * @param string nome da banda
		 */
		public function carregaBandaPorNome($nome) {
			TratamentoErros::validaString($nome, "nome da banda");
			$this->carregaInformacao(array('nome' => $nome));
		}

		/**
		 * Carrega integrantes da banda pelo Id
		 * @param int id da banda
		 */
		public function carregaIntegrantes($id) {
			TratamentoErros::validaInteiro($id, "id da banda");
			$query = new MysqliDb;
			$query->where("id_banda", $id);
			return $query->get(TABELA_INTEGRANTES_BANDA);
		}

	    /**
	     * Retorna bandas do usuário
	     * @param int id do usuário
	     * @return array dados das bandas
	     */
		public function getBandasUsuario($id) {
			TratamentoErros::validaInteiro($id, "id do usuario");
			$carregamento = new Carregamento;
			if($carregamento->valoresExistenteDB(array('id' => $id), TABELA_USUARIOS)) {
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
				throw new Exception("Erro! Usuario inexistente no banco de dados!".Utilidades::debugBacktrace(), E_USER_ERROR);
			}
		}

	    /**
	     * Retorna bandas de tal estilo
	     * @param int id do usuário
	     * @return array dados das bandas
	     */
		public function getBandasEstilo($estilo) {
			TratamentoErros::validaString($estilo, "estilo da banda");
			$carregamento = new Carregamento;
			if($carregamento->valoresExistenteDB(array('nome' => $estilo), TABELA_ESTILOS_MUSICAIS)) {
				$query = new MysqliDb;
				$query->where("estilo", $estilo);
				$bandas = $query->get(TABELA_BANDAS);
				return $bandas;
			}
			else {
				throw new Exception("Erro! Estilo inexistente no banco de dados!".Utilidades::debugBacktrace(), E_USER_ERROR);
			}
		}

	    /**
	     * Retorna bandas de tal estilo
	     * @param int id do usuário
	     * @return array dados das bandas
	     */
		public function getBandasCidade($cidade) {
			TratamentoErros::validaString($cidade, "cidade da banda");
			$query = new MysqliDb;
			$query->where("cidade", $cidade);
			$bandas = $query->get(TABELA_BANDAS);
			return $bandas;
		}

		/**
		 * Define o id da banda
		 * @param int id da banda
		 */
		public function setId($id) {
			TratamentoErros::validaInteiro($id, "id da banda");
			TratamentoErros::validaNulo($id, "id da banda");
			$this->id = $id;
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
			TratamentoErros::validaString($nome, "nome da banda");
			TratamentoErros::validaNulo($nome, "nome da banda");
			$this->nome = $nome;
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
			TratamentoErros::validaString($email, "email da banda");
			TratamentoErros::validaNulo($email, "email da banda");
			$this->email = $email;
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
			TratamentoErros::validaString($estilo, "estilo da banda");
			TratamentoErros::validaNulo($estilo, "estilo da banda");
			$this->estilo = $estilo;
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
			TratamentoErros::validaString($cidade, "cidade da banda");
			TratamentoErros::validaNulo($cidade, "cidade da banda");
			$this->cidade = $cidade;
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
			TratamentoErros::validaArray($eventos, "eventos da banda");
			TratamentoErros::validaNulo($eventos, "eventos da banda");
			$this->eventos = $eventos;
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
			TratamentoErros::validaArray($fans, "fans da banda");
			TratamentoErros::validaNulo($fans, "fans da banda");
			$this->fans = $fans;
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
			TratamentoErros::validaArray($integrantes, "integrantes da banda");
			TratamentoErros::validaNulo($integrantes, "integrantes da banda");
			$this->integrantes = $integrantes;
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
			TratamentoErros::validaString($dataCadastro, "data de cadastro da banda");
			TratamentoErros::validaNulo($dataCadastro, "data de cadastro da banda");
			$this->dataCadastro = $dataCadastro;
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