<?php
	/**
	 * Classe cadastro, que comanda todas as ações de cadstro de usuário, de bandas, de bares, entre outros
	 * Terá classes herdeiras (Usuário, Banda, Produtor)
	 * @package Cadastro
	 * @category Banco de Dados
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
		 * Insere os dados de um novo usuário no banco
		 * @param array dados do usuário
		 * @return int id do usuário
		 */

		public function insereDadosBancoDeDados($dados, $tabela) {
			TratamentoErros::validaArray($dados, "dados");
			TratamentoErros::validaNulo($dados, "dados");
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
			TratamentoErros::validaArray($dados, "dados");
			TratamentoErros::validaNulo($dados, "dados");
			TratamentoErros::validaString($tabela, "tabela");
			TratamentoErros::validaNulo($tabela, "tabela");
			$query = new MysqliDB;
			$query->where('id', $dados['id']);
			if(!$query->update($tabela, $dados)) {
				throw new Exception("Erro ao atualizar! ".$query->getLastError().Utilidades::debugBacktrace());
			}
			$this->setLastQuery($query);
			$this->setDadosAtualizados($dados);
		}

		/**
		 * Define a ultima query realizada
		 * @param query
		 */

		public function setLastQuery($query) {
			TratamentoErros::validaNulo($query, "última query");
			$this->lastQuery = $query;
		}

		/**
		 * Retorna a ultima query realizada
		 * @return query
		 */

		public function getLastQuery() {
			return $this->lastQuery;
		}


		/**
		 * Define os ultimos dados inseridos
		 * @param array
		 */

		public function setDadosInseridos($dados) {
			TratamentoErros::validaArray($dados, "últimos dados inseridos");
			TratamentoErros::validaNulo($dados, "últimos dados inseridos");
			$this->dadosInseridos = $dados;
		}

		/**
		 * Retorna os ultimos dados inseridos
		 * @return array
		 */

		public function getDadosInseridos() {
			return $this->dadosInseridos;
		}

		/**
		 * Define os ultimos dados atualizados
		 * @param array
		 */

		public function setDadosAtualizados($dados) {
			TratamentoErros::validaArray($dados, "últimos dados atualizados");
			TratamentoErros::validaNulo($dados, "últimos dados atualizados");
			$this->dadosAtualizados = $dados;
		}

		/**
		 * Retorna os ultimos dados atualizados
		 * @return array
		 */

		public function getDadosAtualizados() {
			return $this->dadosAtualizados;
		}
	}
?>