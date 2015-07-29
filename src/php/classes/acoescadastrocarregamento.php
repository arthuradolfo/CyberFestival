<?php
	/**
	 * Interface para ações de cadastro e carregamento de dados do site, como usuários, bandas, arquivos...
	 * @category Sistema
	 * @package Interfaces
	 * @author Arthur Adolfo <arthur_adolfo@hotmail.com>
	 * @version 1.0
	 * @copyright CyberFestival 2015
	 */

	interface AcoesCadstroCarregamento {
		/**
	     * Define informações 
	     * @param array dados da ser definido
	     * @throws InvalidArgumentException Uso de argumentos inválidos
	     */
		private function setDados($dados);
		
		/**
		 * Salva dados no banco de dados
	     * @throws Exception Ocorreu erro
		 */
		public function salvaDados();

		/**
	     * Retorna informações para cadastrar no DB
	     * @return array dados
	     */
		public function getDadosBanco();

		/**
	     * Retorna informações
	     * @return array dados
	     */
		public function getDados();

		/**
	     * Valida dados
	     * @throws Exception caso ocorra erro
	     */
		private function validaDados();
	}