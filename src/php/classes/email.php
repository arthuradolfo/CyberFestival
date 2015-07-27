<?php
	/**
	 * Classe email, wrapper do phpmailer
	 * @package Email
	 * @category Utilidades
	 * @author Arthur Adolfo <arthur_adolfo@hotmail.com>
	 * @version 1.0
	 * @copyright CVyberFestival 2015
	 */

	class Email extends PHPMailer {

		/**
		 * Construtor que configura as informações do email
		 */

		function __construct() {
			try {
				$this->carregaInformacoesEmail();
			}
			catch(Exception $e) {
				trigger_error("Erro inesperado! Não foi possível criar email! ".$e->getMessage().Utilidades::debugBacktrace(), E_USER_ERROR);
			}
		}

		/**
		 * Carrega info do email
		 */

		private function carregaInformacoesEmail() {
	        $this->setLanguage('pt');
	        $this->IsSMTP();                          // Ativar SMTP
	        $this->SMTPDebug = (MODO_DEV) ? 1 : 0;    // Debugar: 1 = erros e mensagens, 2 = mensagens apenas
	        $this->SMTPAuth   = true;                 
	        $this->SMTPSecure = "tls";      
	        $this->SMTPKeepAlive = true;    
	        $this->Host       = HOST_EMAIL_SISTEMA;     
	        $this->Port       = 587;                
	        $this->Username   = EMAIL_SISTEMA;       // email remetente
	        $this->Password   = SENHA_EMAIL_SISTEMA; // senha remetente
	        $this->SetFrom(EMAIL_SISTEMA, NOME_SISTEMA); 
	        $this->IsHTML(true);
	        $this->CharSet = 'UTF-8'; 
		}

		/**
		 * Define titulo do email
		 * @param string titulo do email
		 */

		public function setTitulo($titulo) {
			if(!is_string($titulo) || is_null($titulo)) {
				throw new Exception("Erro ao criar titulo para email! Esperava receber string não nula! Recebeu ".gettype($titulo).Utilidaes::debugBacktrace(), E_USER_ERROR);
				
			}
			else {
	        	$this->Subject = $titulo; 
	        }
		}

		/**
		 * @return string titulo do email
		 */

		public function getTitulo() {
	        return $this->Subject; 
		}

		/**
		 * Define mensagem do email
		 * @param string mensagem do email
		 */

		public function setMensagem($mensagem) {
			if(!is_string($mensagem) || is_null($mensagem)) {
				throw new Exception("Erro ao criar mensagem para email! Esperava receber string não nula! Recebeu ".gettype($mensagem).Utilidaes::debugBacktrace(), E_USER_ERROR);
				
			}
			else {
	        	$this->Body = $mensagem; 
	        }
		}

		/**
		 * @return string mensagem do email
		 */

		public function getMensagem() {
	        return $this->Body; 
		}

		/**
		 * Define anexos do email
		 * @param string caminho para o arquivo
		 */

		public function setAnexo($anexo) {
			if(!is_string($anexo) || is_null($anexo)) {
				throw new Exception("Erro ao criar anexo para email! Esperava receber string não nula! Recebeu ".gettype($anexo).Utilidaes::debugBacktrace(), E_USER_ERROR);
				
			}
			else {
	        	$this->AddAttachment($anexo); 
	        }
		}

		/**
		 * Define destinátario do email
		 * @param string email do destinatario
		 * @param string nome do destinatario
		 */

		public function setDestinatario($email, $nome) {
			if(!is_string($email) || is_null($email)) {
				throw new Exception("Erro ao definir email do destinatário! Esperava receber string não nula! Recebeu ".gettype($email).Utilidaes::debugBacktrace(), E_USER_ERROR);
				
			}
			else if(!is_string($nome) || is_null($nome)) {
				throw new Exception("Erro ao definir nome do destinatário! Esperava receber string não nula! Recebeu ".gettype($nome).Utilidaes::debugBacktrace(), E_USER_ERROR);
				
			}
			else {
	       		$this->AddAddress($email, $nome); 
	       	}
		}

		/**
		 * Define cópia
		 * @param string email do destinatario
		 * @param string nome do destinatario
		 */

		public function setCopia($email, $nome) {
			if(!is_string($email) || is_null($email)) {
				throw new Exception("Erro ao definir email do destinatário! Esperava receber string não nula! Recebeu ".gettype($email).Utilidaes::debugBacktrace(), E_USER_ERROR);
				
			}
			else if(!is_string($nome) || is_null($nome)) {
				throw new Exception("Erro ao definir nome do destinatário! Esperava receber string não nula! Recebeu ".gettype($nome).Utilidaes::debugBacktrace(), E_USER_ERROR);
				
			}
			else {
	        	$this->AddCC($email, $nome); 
	        }
		}

		/**
		 * Define cópia oculta
		 * @param string email do destinatario
		 * @param string nome do destinatario
		 */

		public function setCopiaOculta($email, $nome) {
			if(!is_string($email) || is_null($email)) {
				throw new Exception("Erro ao definir email do destinatário! Esperava receber string não nula! Recebeu ".gettype($email).Utilidaes::debugBacktrace(), E_USER_ERROR);
				
			}
			else if(!is_string($nome) || is_null($nome)) {
				throw new Exception("Erro ao definir nome do destinatário! Esperava receber string não nula! Recebeu ".gettype($nome).Utilidaes::debugBacktrace(), E_USER_ERROR);
				
			}
			else {
	        	$this->AddBCC($email, $nome); 
	        }
		}

		/**
		 * Envia o email
		 */
		public function sendEmail() {        
	        if(!$this->Send()){
	            throw new Exception("Erro ao enviar email! Tente mais tarde!".Utilidades::debugBacktrace(), E_USER_WARNING);
	        }
		}
	}
?>