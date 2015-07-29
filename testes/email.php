<?php
	include_once("C:\wamp\www\CyberFestival\src\php\configs\base.php");
	/**
	 * Classe de testes para a classe Email
	 * @category Email
	 * @package Testes
	 * @author Arthur Adolfo <arthur_adolfo@hotmail.com>
	 * @version 1.0
	 * @copyright 2015
	 */
	class TesteEmail {

		/**
		 * Variavel booleana que diz se houve erro ao enviar email
		 * por padrão é false
		 * @var boolean erroEnviaEmail
		 */
		private $erroEnviaEmail;

		/**
		 * Variavel que retorna mensagens de erro
		 * @var array mensagemErroEnviaEmail
		 */
		private $mensagemErroEnviaEmail;

		/**
		 * Variavel booleana que diz se houve erro ao enviar email com anexo
		 * por padrão é false
		 * @var boolean erroEnviaEmailComAnexo
		 */
		private $erroEnviaEmailComAnexo;

		/**
		 * Variavel que retorna mensagens de erro
		 * @var array mensagemErroEnviaEmailComAnexo
		 */
		private $mensagemErroEnviaEmailComAnexo;

		/**
		 * Variavel booleana que diz se houve erro ao enviar email com cópia
		 * por padrão é false
		 * @var boolean erroEnviaEmailComCopia
		 */
		private $erroEnviaEmailComCopia;

		/**
		 * Variavel que retorna mensagens de erro
		 * @var array mensagemErroEnviaEmailComCopia
		 */
		private $mensagemErroEnviaEmailComCopia;

		/**
		 * Variavel booleana que diz se houve erro ao enviar email com cópia oculta
		 * por padrão é false
		 * @var boolean erroEnviaEmailComCopiaOculta
		 */
		private $erroEnviaEmailComCopiaOculta;

		/**
		 * Variavel que retorna mensagens de erro
		 * @var array mensagemErroEnviaEmailComAnexo
		 */
		private $mensagemErroEnviaEmailComCopiaOculta;

		function __construct() {
			$this->testaEnviaEmail();
			$this->testaEnviaEmailComAnexo();
			$this->testaEnviaEmailComCopia();
			$this->testaEnviaEmailComCopiaOculta();
		}

		/**
		 * Método que testa o envio de email
		 */
		public function testaEnviaEmail() {
			try {
				$email = new Email;
				$email->setTitulo("Email teste");
				$email->setMensagem("Este email é só um teste!");
				$email->setDestinatario("arthur_adolfo@hotmail.com","arthur");
				$email->sendEmail();
			}
			catch(Exception $e) {
				$this->setErroEnviaEmail(true);
				$this->setMensagemErroEnviaEmail($e->getMessage());
			}
		}

		/**
		 * Método que testa o envio de email com anexo
		 */
		public function testaEnviaEmailComAnexo($arquivo) {
			try {
				$email = new Email;
				$email->setTitulo("Email teste");
				$email->setMensagem("Este email é só um teste!");
				$email->setDestinatario("arthur_adolfo@hotmail.com","arthur");
				$email->setAnexo($arquivo['tmp_name']);
				$email->sendEmail();
			}
			catch(Exception $e) {
				$this->setErroEnviaEmail(true);
				$this->setMensagemErroEnviaEmail($e->getMessage());
			}
		}

		/**
		 * @param boolean
		 */
		public function setErroEnviaEmail($erroEnviaEmaill) {
			$this->erroEnviaEmail = $erroEnviaEmail;
		}

		/**
		 * @return boolean
		 */
		public function getErroEnviaEmail() {
			return $this->erroEnviaEmail;
		}

		/**
		 * @param string
		 */
		public function setMensagemErroEnviaEmail($mensagemErroEnviaEmail) {
			$this->mensagemErroEnviaEmail[] = $mensagemErroEnviaEmail;
		}

		/**
		 * @return string
		 */
		public function getMensagemErroEnviaEmail() {
			return $this->mensagemErroEnviaEmail;
		}

		/**
		 * @param boolean
		 */
		public function setErroEnviaEmailComAnexo($erroEnviaEmaillComAnexo) {
			$this->erroEnviaEmailComAnexo = $erroEnviaEmailComAnexo;
		}

		/**
		 * @return boolean
		 */
		public function getErroEnviaEmailComAnexo() {
			return $this->erroEnviaEmailComAnexo;
		}

		/**
		 * @param string
		 */
		public function setMensagemErroEnviaEmailComAnexo($mensagemErroEnviaEmailComAnexo) {
			$this->mensagemErroEnviaEmailComAnexo[] = $mensagemErroEnviaEmailComAnexo;
		}

		/**
		 * @return string
		 */
		public function getMensagemErroEnviaEmailComAnexo() {
			return $this->mensagemErroEnviaEmailComAnexo;
		}

		/**
		 * @param boolean
		 */
		public function setErroEnviaEmailComCopia($erroEnviaEmaillComCopia) {
			$this->erroEnviaEmailComCopia = $erroEnviaEmailComCopia;
		}

		/**
		 * @return boolean
		 */
		public function getErroEnviaEmailComCopia() {
			return $this->erroEnviaEmailComCopia;
		}

		/**
		 * @param string
		 */
		public function setMensagemErroEnviaEmailComCopia($mensagemErroEnviaEmailComCopia) {
			$this->mensagemErroEnviaEmailComCopia[] = $mensagemErroEnviaEmailComCopia;
		}

		/**
		 * @return string
		 */
		public function getMensagemErroEnviaEmailComCopia() {
			return $this->mensagemErroEnviaEmailComCopia;
		}

		/**
		 * @param boolean
		 */
		public function setErroEnviaEmailComCopiaOculta($erroEnviaEmaillComCopiaOculta) {
			$this->erroEnviaEmailComCopiaOculta = $erroEnviaEmailComCopiaOculta;
		}

		/**
		 * @return boolean
		 */
		public function getErroEnviaEmailComCopiaOculta() {
			return $this->erroEnviaEmailComCopiaOculta;
		}

		/**
		 * @param string
		 */
		public function setMensagemErroEnviaEmailComCopiaOculta($mensagemErroEnviaEmailComCopiaOculta) {
			$this->mensagemErroEnviaEmailComCopiaOculta[] = $mensagemErroEnviaEmailComCopiaOculta;
		}

		/**
		 * @return string
		 */
		public function getMensagemErroEnviaEmailComCopiaOculta() {
			return $this->mensagemErroEnviaEmailComCopiaOculta;
		}
	}
?>
<form action="usuario.php" method="post">
	<input type="file" name="arquivo">
	<input type="submit">
</form>