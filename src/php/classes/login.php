<?php
	/**
	 * Classe que contem informacoes e metodos para a validação do login
	 *
	 * @category Login
	 * @package  Usuário
	 * @author Arthur Adolfo <arthur_adolfo@hotmail.com>
	 * @version 1.0
	 * @copyright CyberFestival 2015
	 */

	class Login {
		/**
		 * Dados do usuário a ser logado
		 * @var object usuário
		 */
		private $usuario;

		/**
		 * Senha do usuário a ser logado
		 * @var string senha
		 */
		private $senha;

		public function setUsuario($email) {
			$usuario = new Usuario;
			$usuario->carregaUsuarioPorEmail();

		    if(is_null($usuario->getId())){
            	$this->erroLogin();
        	}

			$this->usuario = $usuario;
		}

		public function getUsuario() {
			return $this->usuario;
		}

	    /**
	     * Verifica se o usuário é válido
	     * @throws   Exception caso o usuário for inválido
	     */
	    private function validaUsuario(){
	        if(is_null($this->getUsuario())){
	            throw new Exception("Usuário não setado!".Utilidades::debugBacktrace(), E_USER_WARNING);
	        }
	    }

		public function setSenha($senha) {
			$this->validaUsuario();

			$this->validaString($senha, "senha do usuário");

			$this->senha = $senha;
		}

	    /**
	     * Verifica a senha passada com o hash salvo na DB
	     * @param  string $senha senha a ser testada
	     */
	    private function verificaSenha(){
	        $this->validaUsuario();

	        if(!password_verify($this->getSenha(), $this->getUsuario()->getSenhaHash())){
	            $this->erroLogin();
	        }
	    }

		public function getSenha() {
			return $this->senha;
		}

	    /**
	     * Valida a senha e se o usuário está apto a logar
	     */
	    public function validaLogin(){
	        $this->validaUsuario();
	        $this->verificaContaAtivada();
	        $this->verificaSenha();
	    }

	    /**
	     * Verifica se a conta do usuário foi ativada via email
	     */
	    private function verificaContaAtivada(){
	        $this->validaUsuario();

	        if($this->getUsuario()->getStatus() === USUARIO_ESPERANDO_CONFIRMACAO_EMAIL){
	            $this->erroLogin("Sua conta ainda não foi ativada via email!");
	        }
	    }

	    /**
	     * Como login foi sucesso, remover flags como esqueci a senha e outras coisas
	     */
	    public function sucesso(){
	        $this->validaUsuario();

	        // remove flags
	        $this->preparaUsuario();
	        // prepara sessao
	        $this->preparaSessao();
	    }

	    /**
	     * Remove flags do usuário como inatividade, senha em recuperação..
	     */
	    private function preparaUsuario(){
	        $this->validaUsuario();
	        
	         // status de usuário não inativo/bloqueado
	        $this->getUsuario()->setStatus(CONTA_ATIVA);

	        // senha mudada pelo responsável
	        $this->getUsuario()->removeResponsavelTrocaDeSenha();
	        
	        $this->getUsuario()->salvaDados();
	    }

	     /**
	     * Prepara a sessao do usuário logado
	     */
	    private function preparaSessao(){
	        $this->validaUsuario();

	        $sessao = new Sessao;
	        $sessao->setUsuario($this->getUsuario()->getCodigo());
	        $sessao->registraLogin();
	    }

	    /**
	     * Declara erro no processo de login
	     * @throws Exception erro no login
	     */
	    private function erroLogin($mensagem = "Informações incorretas!"){
	        throw new Exception($mensagem.Utilidades::debugBacktrace(), E_USER_WARNING);
	    }
	}
?>