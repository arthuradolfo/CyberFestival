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
			try {
				$this->testaEnviaEmail();
				$this->testaEnviaEmailComAnexo();
				$this->testaEnviaEmailComCopia();
				$this->testaEnviaEmailComCopiaOculta();
				$this->printaResultado();
			}
			catch(Exception $e) {
				trigger_error("Erro ao testar classe!".$e->getMessage().Utilidades::debugBacktrace(), E_USER_ERROR);
			}
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
				$this->setMensagemErroEnviaEmail($e->getMessage().Utilidades::debugBacktrace());
			}
		}

		/**
		 * Método que testa o envio de email com anexo
		 */
		public function testaEnviaEmailComAnexo() {
			try {
				$email = new Email;
				$email->setTitulo("Email teste");
				$email->setMensagem("Este email é só um teste!");
				$email->setDestinatario("arthur_adolfo@hotmail.com","arthur");
				$email->setAnexo("C:\wamp\www\CyberFestival\uploads\usuarios\\fotosperfil\cristian.jpg");
				$email->sendEmail();
			}
			catch(Exception $e) {
				$this->setErroEnviaEmailComAnexo(true);
				$this->setMensagemErroEnviaEmailComAnexo($e->getMessage().Utilidades::debugBacktrace());
			}
		}

		/**
		 * Método que testa o envio de email com copia
		 */
		public function testaEnviaEmailComCopia() {
			try {
				$email = new Email;
				$email->setTitulo("Email teste");
				$email->setMensagem("Este email é só um teste!");
				$email->setDestinatario("arthur_adolfo@hotmail.com","arthur");
				$email->setCopia("arthur03011997@gmail.com","arthur");
				$email->sendEmail();
			}
			catch(Exception $e) {
				$this->setErroEnviaEmailComCopia(true);
				$this->setMensagemErroEnviaEmailComCopia($e->getMessage().Utilidades::debugBacktrace());
			}
		}

		/**
		 * Método que testa o envio de email com copia oculta
		 */
		public function testaEnviaEmailComCopiaOculta() {
			try {
				$email = new Email;
				$email->setTitulo("Email teste");
				$email->setMensagem("Este email é só um teste!");
				$email->setDestinatario("arthur_adolfo@hotmail.com","arthur");
				$email->setCopiaOculta("arthur03011997@gmail.com","arthur");
				$email->sendEmail();
			}
			catch(Exception $e) {
				$this->setErroEnviaEmailComCopiaOculta(true);
				$this->setMensagemErroEnviaEmailComCopiaOculta($e->getMessage().Utilidades::debugBacktrace());
			}
		}

		/**
		 * Printa resultado dos testes
		 */

		public function printaResultado() {
			if($this->getErroEnviaEmail()) {
				echo "Erro! Teste de envio de email falhou!<br>";
				var_dump($this->getMensagemErroEnviaEmail);
			}
			else {
				echo "Teste de envio de email bem sucedido!<br>";
			}

			if($this->getErroEnviaEmailComAnexo()) {
				echo "Erro! Teste de envio de email com anexo falhou!<br>";
				var_dump($this->getMensagemErroEnviaEmailComAnexo);
			}
			else {
				echo "Teste de envio de email com anexo bem sucedido!<br>";
			}

			if($this->getErroEnviaEmailComCopia()) {
				echo "Erro! Teste de envio de email com copia falhou!<br>";
				var_dump($this->getMensagemErroEnviaEmailComCopia);
			}
			else {
				echo "Teste de envio de email com copia bem sucedido!<br>";
			}

			if($this->getErroEnviaEmailComCopiaOculta()) {
				echo "Erro! Teste de envio de email com copia oculta falhou!<br>";
				var_dump($this->getMensagemErroEnviaEmailComCopiaOculta);
			}
			else {
				echo "Teste de envio de email com copia oculta bem sucedido!<br>";
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
	
	$emailTeste = new TesteEmail();
?>