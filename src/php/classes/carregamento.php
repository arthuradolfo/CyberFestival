<?php
	/**
	 * Classe cadastro, que comanda todas as ações de cadstro de usuário, de bandas, de bares, entre outros
	 * Terá classes herdeiras (Usuário, Banda, Produtor)
	 * @package Carregamento
	 * @category Banco de Dados
	 * @author Arthur Adolfo <arthur_adolfo@hotmail.com>
	 * @version 1.0
	 * @copyright CyberFestival 2015
	 */
	class Carregamento {
		/**
         * Método para verificar ser o(s) valores existem em um mesmo registro da tabela
         * @param  mixed[] $campos uma array indexada da seguinte maneira
         *                         $campos['coluna'] = valor dela
         *                  
         * @param  string $tabela tabela usada na query
         * @throws InvalidArgumentException Uso de argumentos inválidos
         * @return mixed[]|null registro(s) caso seja(o) encontrado(s) ou NULL
         */ 
        static public function valoresExistenteDB($campos, $tabela){
            if(!is_array($campos)){
                throw new InvalidArgumentException("Erro ao definar os campos, esperava uma array de campos. Recebeu ".gettype($campos), E_USER_ERROR);
            }

            if(!is_string($tabela)){
                throw new InvalidArgumentException("Erro ao definar a tabela, esperava uma string. Recebeu ".gettype($tabela), E_USER_ERROR);
            }

        	$query = new MysqliDb();

            foreach ($campos as $coluna => $valor) {
                  $query->where($coluna, $valor);
            }

        	// o método getOne retorna uma array caso encontre alguma linha
    		return $query->getOne($tabela);
        }

       /**
         * Carrega dados da banda do banco de dados pelo id
         * @param array campos da banda a ser definido
         * @param string nome da tabela a ser acessada
         */
        public function carregaDados($campos, $tabela) {
            if(!is_array($campos)){
                throw new InvalidArgumentException("Erro ao definar os campos, esperava um array de campos. Recebeu ".gettype($campos).Utilidades::debugBacktrace(), E_USER_ERROR);
            }

            if(!is_string($tabela)){
                throw new InvalidArgumentException("Erro ao definar a tabela, esperava uma string. Recebeu ".gettype($tabela), E_USER_ERROR);
            }
            
            $query = new MysqliDb();

            foreach ($campos as $coluna => $valor) {
                $query->where($coluna, $valor);
            }

            return $query->getOne($tabela);
        }
	}
?>