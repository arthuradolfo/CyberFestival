<?php
	/**
	 * Classe cadastro, que comanda todas as ações de cadstro de usuário, de bandas, de bares, entre outros
	 * Terá classes herdeiras (Usuário, Banda, Produtor)
	 * @package Cadastro
	 * @category Usuário
	 * @author Arthur Adolfo <arthur_adolfo@hotmail.com>
	 * @version 1.0
	 * @copyright CyberFestival 2015
	 */
	class Cadastro {

		/**
		 * Guarda ultima query realizada
		 * @var query 
		 */

		private $lastQuery;

		/**
		 * Guarda ultimos dados inseridos no banco de dados e em qual tabela
		 * @var array 
		 */

		private $dadosInseridos;

		/**
		 * Guarda ultimos dados atualizados no banco de dados e em qual tabela
		 * @var array 
		 */

		private $dadosAtualizados;

		/**
		 * Codigo para ativação de conta
		 * @var string 
		 */

		private $codigoAtivacao;

		function __construct() {
			
		}

		/**
		 * Insere os dados de um novo usuário no banco
		 * @param array dados do usuário
		 * @return int id do usuário
		 */

		public function insereDadosBancoDeDados($dados, $tabela) {
			if(!is_array($dados) || is_null($dados)) {
				throw new InvalidArgumentException("Erro! Dados inválidos! ".Utilidades::debugBacktrace(), E_USER_ERROR);
			}
			$query = new MysqliDB;
			$id = $query->insert($tabela, $dados);
			if(!$id) {
				throw new Exception("Erro ao cadastrar! ".$query->getLastError().Utilidades::debugBacktrace());
			}
			$dados['id'] = $id; //seta o id para o novo id do usuario (MUITO IMPORTANTE)
			$this->setLastQuery($query);
			$this->setDadosInseridos($dados);
			var_dump($this->getDadosInseridos());
			return $id;
		}

		/**
		 * Atualiza os dados de um novo usuário no banco
		 * @param array dados do usuário
		 */

		public function atualizaDadosBancoDeDados($dados, $tabela) {
			if(!is_array($dados) || is_null($dados)) {
				throw new InvalidArgumentException("Erro! Dados inválidos! ".Utilidades::debugBacktrace(), E_USER_ERROR);
			}
			$query = new MysqliDB;
			$query->where('id', $dados['id']);
			if(!$query->update($tabela, $dados)) {
				throw new Exception("Erro ao atualizar! ".$query->getLastError().Utilidades::debugBacktrace());
			}
			$this->setLastQuery($query);
			$this->setDadosAtualizados($dados);
		}

		/**
		 * Envia email de confirmcao para destinatário
		 * @param array array('id', 'nome', 'email')
		 */

		public function enviaEmailConfirmacao($dados) {
			try {
				$this->setCodigoAtivacao(Utilidades::geraCodigoAtivacao($dados['email']));
				$email = new Email;
				$email->setTitulo(EMAIL_TITULO_CONFIRMACAO);
				$email->setMensagem(EMAIL_MENSAGEM_CONFIRMACAO."<a href='".LINK_ATIVACAO."?codigoAtivacao=".$this->getCodigoAtivacao()."'>Clique aqui para ativar!</a>");
				$this->insereCodigoAtivacaoDB($dados['id']);
				$email->setDestinatario($dados['email'], $dados['nome']);
				$email->sendEmail();
			}
			catch(Exception $e) {
				trigger_error("Ocorreu um erro! ".Utilidades::debugBacktrace(), $e->getMessage());
			}
		}

		/**
		 * Envia email para destinatário
		 * @param array array('id', 'nome', 'email', 'titulo', 'mensagem', 'anexos')
		 */

		public function enviaEmail($dados) {
			try {
				$email = new Email;
				$email->setTitulo($dados['titulo']);
				$email->setMensagem($dados['mensagem']);
				if(!is_null($dados['anexos'])) {
					foreach($dados['anexos'] as $anexo) {
						$email->setAnexo($anexo);
					}
				}
				$email->setDestinatario($dados['email'], $dados['nome']);
				$email->sendEmail();
			}
			catch(Exception $e) {
				trigger_error("Ocorreu um erro! ".Utilidaes::debugBacktrace(), $e->getMessage(), $e->getCode);
			}
		}

		private function insereCodigoAtivacaoDB($id) {
			if(is_null($id)) {
				throw new Exception("Erro! Id de usuário nulo! ".Utildiades::debugBacktrace());
			}
			echo $id;
			$query = new MysqliDB;
			$query->where('id', $id);
			$this->insereCodigoAtivacaoEmDadosInseridos($this->getCodigoAtivacao());
			echo "codigo ativacao".$this->getCodigoAtivacao();
			$dados = $this->getDadosInseridos();
			var_dump($dados);
			if(!$query->update(TABELA_USUARIOS, $dados)) {
				throw new Exception("Erro ao atualizar! ".$query->getLastError().Utilidades::debugBacktrace());
			}
			$this->setLastQuery($query);
			$this->setDadosAtualizados($dados);
		}

		/**
		 * Insere o código de ativação nos dados inseridos
		 * @param string código de ativação
		 */
		private function insereCodigoAtivacaoEmDadosInseridos($codigo) {
			$dados = $this->getDadosInseridos();
			$dados['codigoVerificacao'] = $codigo;
			$this->setDadosInseridos($dados);
		}

		/**
		 * Define a ultima query realizada
		 * @param query
		 */

		private function setLastQuery($query) {
			$this->validaLastQuery($query);
			$this->lastQuery = $query;
		}

		/**
		 * Valida lastQuery
		 */
		private function validaLastQuery($query) {
			if(is_null($query)) {
				throw new Exception("Erro ao armazenar última query! ".Utilidades::debugBacktrace(), E_USER_ERROR);
			}
		}

		/**
		 * Retorna a ultima query realizada
		 * @return query
		 */

		private function getLastQuery() {
			return $this->lastQuery;
		}

		/**
		 * Define o codigo de ativação
		 * @param string codigo de ativação
		 */

		private function setCodigoAtivacao($codigo) {
			$this->validaCodigoAtivacao($codigo);
			$this->codigoAtivacao = $codigo;
		}

		/**
		 * Valida codigo de ativacao
		 */
		private function validaCodigoAtivacao($codigo) {
			if(is_null($codigo)) {
				throw new Exception("Erro ao armazenar codigo de ativação! ".Utilidades::debugBacktrace(), E_USER_ERROR);
			}
		}

		/**
		 * Retorna o codigo de ativação
		 * @return int o código de ativação
		 */

		private function getCodigoAtivacao() {
			return $this->codigoAtivacao;
		}

		/**
		 * Define os ultimos dados inseridos
		 * @param array
		 */

		private function setDadosInseridos($dados) {
			$this->validaDadosInseridos($dados);
			$this->dadosInseridos = $dados;
		}

		/**
		 * Valida dados inseridos
		 */
		private function validaDadosInseridos($dados) {
			if(is_null($this->getLastQuery())) {
				if(is_null($dados) || !is_array($dados)) {
					throw new Exception("Erro ao armazenar últimos dados inseridos! ".Utilidades::debugBacktrace(), E_USER_ERROR);
				}
			}
		}

		/**
		 * Retorna os ultimos dados inseridos
		 * @return array
		 */

		private function getDadosInseridos() {
			return $this->dadosInseridos;
		}

		/**
		 * Define os ultimos dados atualizados
		 * @param array
		 */

		private function setDadosAtualizados($dados) {
			$this->validaDadosAtualizados($dados);
			$this->dadosAtualizados = $dados;
		}

		/**
		 * Valida dados atualizados
		 */
		private function validaDadosAtualizados($dados) {
			if(is_null($this->getLastQuery())) {
				if(is_null($dados) || !is_array($dados)) {
					throw new Exception("Erro ao armazenar últimos dados atualizados! ".Utilidades::debugBacktrace(), E_USER_ERROR);
				}
			}
		}

		/**
		 * Retorna os ultimos dados atualizados
		 * @return array
		 */

		private function getDadosAtualizados() {
			return $this->dadosAtualizados;
		}
	}
?>