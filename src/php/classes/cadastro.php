<?php
	/**
	 * Classe cadastro, que comanda todas as ações de cadstro de usuário, de bandas, de bares, entre outros
	 * @package Cadastro
	 * @category Usuário
	 * @author Arthur Adolfo <arthur_adolfo@hotmail.com>
	 * @version 1.0
	 * @copyright CyberFestival 2015
	 */
	class Cadastro {
		function __construct() {
			
		}

		/**
		 * Insere os dados de um novo usuário no banco
		 * @param array dados do usuário
		 * @return int id do usuário
		 */

		public insereDadosBancoDeDados($dados) {
			if(!is_array($dados) || is_null($dados)) {
				throw new InvalidArgumentException("Erro! Dados inválidos! ".Utilidades::debugBacktrace(), E_USER_ERROR);
			}
			$query = new MysqliDB;
			$id = $query->insert(TABELA_USUARIOS, $dados);
			if(!$id) {
				throw new Exception("Erro ao cadastrar usuário! ".$query->getLastError().Utilidades::debugBacktrace());
			}
			return $id;
		}

		/**
		 * Atualiza os dados de um novo usuário no banco
		 * @param array dados do usuário
		 */

		public atualizaDadosBancoDeDados($dados) {
			if(!is_array($dados) || is_null($dados)) {
				throw new InvalidArgumentException("Erro! Dados inválidos! ".Utilidades::debugBacktrace(), E_USER_ERROR);
			}
			$query = new MysqliDB;
			$query->where('id', $dados['id']);
			if(!$query->update(TABELA_USUARIOS, $dados)) {
				throw new Exception("Erro ao cadastrar usuário! ".$query->getLastError().Utilidades::debugBacktrace());
			}
		}
	}
?>