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
	        chmod("C:\wamp\www\CyberFestival\uploads", 0777);
		}

		/**
		 * Define titulo do email
		 * @param string titulo do email
		 */

		public function setTitulo($titulo) {
			TratamentoErros::validaString($titulo, "titulo do email");
			TratamentoErros::validaNulo($titulo, "titulo do email");
	        $this->Subject = $titulo; 
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
			TratamentoErros::validaString($mensagem, "mensagem do email");
			TratamentoErros::validaNulo($mensagem, "mensagem do email");
	        $this->Body = $mensagem;
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
			TratamentoErros::validaString($anexo, "anexo do email");
			TratamentoErros::validaNulo($anexo, "anexo do email");
	        $this->AddAttachment($anexo); 
		}

		/**
		 * Define destinátario do email
		 * @param string email do destinatario
		 * @param string nome do destinatario
		 */

		public function setDestinatario($email, $nome) {
			TratamentoErros::validaString($email, "destinatario do email");
			TratamentoErros::validaNulo($email, "destinatario do email");
			TratamentoErros::validaString($nome, "nome do destinatario do email");
			TratamentoErros::validaNulo($nome, "nome do destinatario do email");
	       	$this->AddAddress($email, $nome);
		}

		/**
		 * Define cópia
		 * @param string email do destinatario
		 * @param string nome do destinatario
		 */

		public function setCopia($email, $nome) {
			TratamentoErros::validaString($email, "destinatario do email");
			TratamentoErros::validaNulo($email, "destinatario do email");
			TratamentoErros::validaString($nome, "nome do destinatario do email");
			TratamentoErros::validaNulo($nome, "nome do destinatario do email");
	        $this->AddCC($email, $nome);
		}

		/**
		 * Define cópia oculta
		 * @param string email do destinatario
		 * @param string nome do destinatario
		 */

		public function setCopiaOculta($email, $nome) {
			TratamentoErros::validaString($email, "destinatario do email");
			TratamentoErros::validaNulo($email, "destinatario do email");
			TratamentoErros::validaString($nome, "nome do destinatario do email");
			TratamentoErros::validaNulo($nome, "nome do destinatario do email");
	        $this->AddBCC($email, $nome);
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