<?php
	/**
	 * Classe cadastro, que comanda todas as ações específicas de cadstro de usuário
	 * Terá classes herdeiras (Usuário, Banda, Produtor)
	 * @package Cadastro
	 * @category Banco de Dados
	 * @author Arthur Adolfo <arthur_adolfo@hotmail.com>
	 * @version 1.0
	 * @copyright CyberFestival 2015
	 */
	class CadastroUsuario extends Cadastro {
		/**
		 * Codigo para ativação de conta
		 * @var string 
		 */

		private $codigoAtivacao;

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
			$dados = parent::getDadosInseridos();
			var_dump($dados);
			if(!$query->update(TABELA_USUARIOS, $dados)) {
				throw new Exception("Erro ao atualizar! ".$query->getLastError().Utilidades::debugBacktrace());
			}
			parent::setLastQuery($query);
			parent::setDadosAtualizados($dados);
		}

		/**
		 * Insere o código de ativação nos dados inseridos
		 * @param string código de ativação
		 */
		private function insereCodigoAtivacaoEmDadosInseridos($codigo) {
			$dados = parent::getDadosInseridos();
			$dados['codigoVerificacao'] = $codigo;
			parent::setDadosInseridos($dados);
		}

		/**
		 * Define o codigo de ativação
		 * @param string codigo de ativação
		 */

		public function setCodigoAtivacao($codigo) {
			$this->validaCodigoAtivacao($codigo);
			$this->codigoAtivacao = $codigo;
		}

		/**
		 * Valida codigo de ativacao
		 */
		public function validaCodigoAtivacao($codigo) {
			if(is_null($codigo)) {
				throw new Exception("Erro ao armazenar codigo de ativação! ".Utilidades::debugBacktrace(), E_USER_ERROR);
			}
		}

		/**
		 * Retorna o codigo de ativação
		 * @return int o código de ativação
		 */

		public function getCodigoAtivacao() {
			return $this->codigoAtivacao;
		}
	}
?>