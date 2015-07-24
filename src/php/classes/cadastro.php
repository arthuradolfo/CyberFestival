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

		$lastQuery;

		/**
		 * Guarda ultimos dados inseridos no banco de dados e em qual tabela
		 * @var array 
		 */

		$dadosInseridos;

		/**
		 * Guarda ultimos dados atualizados no banco de dados e em qual tabela
		 * @var array 
		 */

		$dadosAtualizados;

		function __construct() {
			
		}

		/**
		 * Insere os dados de um novo usuário no banco
		 * @param array dados do usuário
		 * @return int id do usuário
		 */

		public insereDadosBancoDeDados($dados, $tabela) {
			if(!is_array($dados) || is_null($dados)) {
				throw new InvalidArgumentException("Erro! Dados inválidos! ".Utilidades::debugBacktrace(), E_USER_ERROR);
			}
			$query = new MysqliDB;
			$id = $query->insert($tabela, $dados);
			if(!$id) {
				throw new Exception("Erro ao cadastrar! ".$query->getLastError().Utilidades::debugBacktrace());
			}
			$this->setLastQuery($query);
			$this->setDadosInseridos($dados);
			return $id;
		}

		/**
		 * Atualiza os dados de um novo usuário no banco
		 * @param array dados do usuário
		 */

		public atualizaDadosBancoDeDados($dados, $tabela) {
			if(!is_array($dados) || is_null($dados)) {
				throw new InvalidArgumentException("Erro! Dados inválidos! ".Utilidades::debugBacktrace(), E_USER_ERROR);
			}
			$query = new MysqliDB;
			$query->where('id', $dados['id']);
			if(!$query->update(TABELA_USUARIOS, $dados)) {
				throw new Exception("Erro ao atualizar! ".$query->getLastError().Utilidades::debugBacktrace());
			}
			$this->setLastQuery($query);
			$this->setDadosAtualizados($dados);
		}

		/**
		 * Envia email de confirmcao para destinatário
		 * @param array array('id', 'nome', 'email')
		 */

		public enviaEmailConfirmacao($dados) {
			try {
				$email =  new Email;
				$email->setTitulo(EMAIL_TITULO_CONFIRMACAO);
				$email->setMensagem(EMAIL_MENSAGEM_CONFIRMACAO."<a href='".CAMINHO_URL_CONFIRMACAO."?codigoAtivacao=".Utilidades::geraCodigoAtivacao()."'>Clique aqui para ativar!</a>");
				$this->insereCodigoAtivacaoDB($dados['id']);
				$email->setDestinatario($dados['email'], $dados['nome']);
				$email->sendEmail();
			catch(Exception $e) {
				trigger_error("Ocorreu um erro! ".Utilidaes::debugBacktrace(), $e->getMessage(), $e->getCode);
			}
		}

		/**
		 * Envia email para destinatário
		 * @param array array('id', 'nome', 'email', 'titulo', 'mensagem', 'anexos')
		 */

		public enviaEmail($dados) {
			try {
				$email =  new Email;
				$email->setTitulo($dados['titulo']);
				$email->setMensagem($dados['mensagem']);
				if(!is_null($dados['anexos'])) {
					foreach($dados['anexos'] as $anexo) {
						$email->setAnexo($anexo);
					}
				}
				$email->setDestinatario($dados['email'], $dados['nome']);
				$email->sendEmail();
			catch(Exception $e) {
				trigger_error("Ocorreu um erro! ".Utilidaes::debugBacktrace(), $e->getMessage(), $e->getCode);
			}
		}

		/**
		 * Define a ultima query realizada
		 * @param query
		 */

		private function setLastQuery($query) {
			if(is_null($query)) {
				throw new Exception("Erro ao armazenar última query! ".Utilidades::debugBacktrace(), E_USER_ERROR);
			}
			$this->lastQuery = $query;
		}

		/**
		 * Retorna a ultima query realizada
		 * @return query
		 */

		private function getLastQuery() {
			return $this->lastQuery;
		}

		/**
		 * Define os ultimos dados inseridos
		 * @param query
		 */

		private function setDadosInseridos($dados) {
			if(is_null($this->getLastQuery())) {
				if(is_null($dados) || !is_array($dados)) {
					throw new Exception("Erro ao armazenar últimos dados inseridos! ".Utilidades::debugBacktrace(), E_USER_ERROR);
				}
			}
			$this->dadosInseridos = $dados;
		}

		/**
		 * Retorna os ultimos dados inseridos
		 * @return query
		 */

		private function getDadosInseridos() {
			return $this->dadosInseridos;
		}

		/**
		 * Define os ultimos dados atualizados
		 * @param query
		 */

		private function setDadosAtualizados($dados) {
			if(is_null($this->getLastQuery())) {
				if(is_null($dados) || !is_array($dados)) {
					throw new Exception("Erro ao armazenar últimos dados atualizados! ".Utilidades::debugBacktrace(), E_USER_ERROR);
				}
			}
			$this->dadosAtualizados = $dados;
		}

		/**
		 * Retorna os ultimos dados atualizados
		 * @return query
		 */

		private function getDadosAtualizados() {
			return $this->dadosAtualizados;
		}
	}
?>