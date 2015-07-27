<?php

	/**
	 * Classe que contem informacoes e metodos para o upload de arquivos no sistema
	 *
	 * @category Arquivo
	 * @package  Uploads
	 * @author Arthur Adolfo <arthur_adolfo@hotmail.com>
	 * @version 1.0
	 * @copyright CyberFestival 2015
	 */

	class Arquivo extends Cadastro {
		/**
		 * ID do arquivo no banco de dados 
		 * Caso o arquivo exista seu id será diferente de NULO
		 * @var int id do arquivo
		 */
		private $id;

		/**
		 * Configuração do arquivo
		 * Array com informações importantes sobre o arquivo $arquivo = array("tamanho" => TAMANHO_MAXIMO,
		 *																	  "largura" => LARGURA_MAXIMA_FOTO_PERFIL,
		 *																	  "altura" => ALTURA_MAXIMA_FOTO_PERFIL
		 *																	  )
		 * @var array informações configuração arquivo
		 */
		private $configuracao;

		/**
		 * Informações do arquivo
		 * @var array informações do arquivo
		 */
		private $arquivo;

		/**
		 * Tipos permitidos
		 * @var array tipos permitidos
		 */
		private $tiposPermitidos = array("mp4", "mp3", "wma", "aac", "ogc", "ac3", "wav", "avi", "mpeg", "mov", "rmvb", "mkv", "bmp", "gif", "jpeg", "jpg", "png", "zip", "rar", "7z", "txt", "pdf");

		function __construct($tipoArquivo = null) {
			if(!is_null($tipoArquivo)) {
				if(!is_int($tipoArquivo)) {
					throw new InvalidArgumentException("Erro, Espera receber um inteiro, recebeu ". gettype($tipoArquivo).Utilidades::debugBacktrace(), E_USER_ERROR);
				}
				else {
					if($tipoArquivo == TIPO_ARQUIVO_FOTO_PERFIL) {
						$configuracao = array(	"tamanho" => TAMANHO_MAXIMO_ARQUIVO,
												"largura" => LARGURA_MAXIMA_FOTO_PERFIL,
												"altura" => ALTURA_MAXIMA_FOTO_PERFIL
												);
						$this->setConfiguracao($configuracao);
					}
					else {
						$configuracao = array(	"tamanho" => TAMANHO_MAXIMO_ARQUIVO
												);
						$this->setConfiguracao($configuracao);
					}
				}
			}
		}

	    /**
	     * Faz upload de arquivo no sistema (É necessário possuir o arquivo e o caminho do arquivo)
	     * @param array informações do arquivo
	     * @throws InvalidArgumentException em caso de argumento inválido
	     * @throws Exception em caso de erro
	     */
	    private function uploadArquivo($dados) {
	    	if(!is_array($dados)) {
	    		throw new InvalidArgumentException("Erro ao fazer upload de arquivo! Espera um array, recebeu ".gettype($dados).Utilidades::debugBacktrace(), E_USER_ERROR);	    		
	    	}
	    	else {
	    		if(!is_null($dados['arquivo']) && !is_null($dados['caminho'])) {
	    			$this->validaTipo($this->informaçõesTipoArquivo($dados['arquivo']));
	    			$this->validaTamanho($dados['arquivo']['size']);
	    			if($this->getConfiguracao()['largura']) {
	    				validaAltura();
	    				validaLargura();
	    			}
		        	move_uploaded_file($dados['arquivo']['tmp_name'], $dados['caminho']);
	    		}
	    		else {
	    			throw new Exception("Erro ao fazer upload de arquivo! Arquivo ou caminho nulos!".Utilidades::debugBacktrace(), E_USER_ERROR);	    		
	    		}
	    	}
	    }

	    /**
		 * Retorna o tipo do arquivo
	     * @param file
	     * @return array extensão do arquivo no último índice do array
	     * @throws InvalidArgumentException Uso de argumentos inválidos
		 */
		private function informacoesTipoArquivo($arquivo) {
			$this->validaArquivo($arquivo);
			$tipos = explode(".", $arquivo["name"]); //se arquivo.ext tipos[0] = "arquivo" e tipos[1] = "ext"
			return $tipos;
		}

		/**
		 * Valida o tipo do arquivo
	     * @param array tipo do arquivo
	     * @throws Exception caso ocorra erro
		 */
		private function validaTipo($tipos) {
			$tipoPermitido = false;
			$tipoArquivo = $tipos[count($tipos) - 1];//se arquivo.ext tipos[0] = "arquivo" e tipos[1] = "ext" pega ultimo indice
	        foreach($this->getTiposPermitidos as $tipo){
	            if(strtolower($tipoArquivo) == strtolower($tipo)){
	                $tipoPermitido = true;
	            }
	        }
	        if(!$tipoPermitido){
	            throw new Exception("Erro! Tipo não é permitido! envie outro arquivo!".Utilidade::debugBacktrace(), E_USER_ERROR);
	            
	        }
		}

	    /**
	     * Define id do arquivo
	     * @param int id do arquivo a ser definido
	     */
		public function setId($id) {
			$this->validaId($id);
			$this->id = $id;
		}

		/**
		 * Valida o id do arquivo
	     * @throws InvalidArgumentException Uso de argumentos inválidos
		 */
		private function validaId($id) {
			if(!is_int($id)) {
				throw new InvalidArgumentException("Erro ao definir o id do usuário. Esperava um inteiro, recebeu ".gettype($id).Utilidades::debugBacktrace(), E_USER_ERROR);
			}
		}

		/**
	     * Retorna id do arquivo
	     * @return int id do arquivo
	     */
		private function getId() {
			return $this->id;
		}

	    /**
	     * Define configuracao do arquivo
	     * @param int configuracao do arquivo a ser definido
	     */
		public function setConfiguracao($configuracao) {
			$this->validaConfiguracao($configuracao);
			$this->configuracao = $configuracao;
		}

		/**
		 * Valida a configuracao do arquivo
	     * @throws InvalidArgumentException Uso de argumentos inválidos
		 */
		private function validaConfiguracao($configuracao) {
			if(!is_array($configuracao)) {
				throw new InvalidArgumentException("Erro ao definir a configuracao do usuário. Esperava um array, recebeu ".gettype($configuracao).Utilidades::debugBacktrace(), E_USER_ERROR);
			}
		}

		/**
	     * Retorna configuracao do arquivo
	     * @return int configuracao do arquivo
	     */
		private function getConfiguracao() {
			return $this->configuracao;
		}

	    /**
	     * Define o arquivo
	     * @param file foto
	     */
		public function setArquivo($arquivo) {
			$this->validaArquivo($arquivo);
			$this->arquivo = $arquivo;
		}

		/**
		 * Valida o arquivo
	     * @throws InvalidArgumentException Uso de argumentos inválidos
		 */
		private function validaArquivo($arquivo) {
			if(!is_file($arquivo)) {
				throw new InvalidArgumentException("Erro ao definir o arquivo. Esperava um arquivo, recebeu ".gettype($arquivo).Utilidades::debugBacktrace(), E_USER_ERROR);
			}
		}

		/**
	     * Retorna arquivo
	     * @return file
	     */
		private function getArquivo() {
			return $this->arquivo;
		}

	    /**
	     * Define os tipos permitidos
	     * @param array tipos permitidos
	     */
		public function setTiposPermitidos($tipos) {
			$this->validaTiposPermitidos($tipos);
			$this->tiposPermitidos = $tiposPermitidos;
		}

		/**
		 * Valida o tipos permitidos
	     * @throws InvalidArgumentException Uso de argumentos inválidos
		 */
		private function validaTiposPermitidos($tiposPermitidos) {
			if(!is_file($tiposPermitidos)) {
				throw new InvalidArgumentException("Erro ao definir os tipos permitidos. Esperava um array, recebeu ".gettype($tiposPermitidos).Utilidades::debugBacktrace(), E_USER_ERROR);
			}
		}

		/**
	     * Retorna tiposPermitidos
	     * @return file
	     */
		private function getTiposPermitidos() {
			return $this->tiposPermitidos;
		}

		$erro = $config = array();

		// Prepara a variável do arquivo
		$arquivo = isset($_FILES["foto"]) ? $_FILES["foto"] : FALSE;

		// Tamanho máximo do arquivo (em bytes)
		$config["tamanho"] = 500000000;
		// Largura máxima (pixels)
		$config["largura"] = 540;
		// Altura máxima (pixels)
		$config["altura"]  = 540;

		// Formulário postado... executa as ações
		if($arquivo)
		{  
		    // Verifica se o mime-type do arquivo é de imagem
		    if(!eregi("^image\/(pjpeg|jpeg|png|gif|bmp)$", $arquivo["type"]))
		    {
		        $erro[] = "Arquivo em formato inválido! A imagem deve ser jpg, jpeg, 
					bmp, gif ou png. Envie outro arquivo";
		    }
		    else
		    {
		        // Verifica tamanho do arquivo
		        if($arquivo["size"] > $config["tamanho"])
		        {
		            $erro[] = "Arquivo em tamanho muito grande! 
				A imagem deve ser de no máximo " . $config["tamanho"] . " bytes. 
				Envie outro arquivo";
		        }
		        
		        // Para verificar as dimensões da imagem
		        $tamanhos = getimagesize($arquivo["tmp_name"]);
		        
		        // Verifica largura
		        if($tamanhos[0] > $config["largura"])
		        {
		            $erro[] = "Largura da imagem não deve 
						ultrapassar " . $config["largura"] . " pixels";
		        }

		        // Verifica altura
		        if($tamanhos[1] > $config["altura"])
		        {
		            $erro[] = "Altura da imagem não deve 
						ultrapassar " . $config["altura"] . " pixels";
		        }
		    }
		    
		    // Imprime as mensagens de erro
		    if(sizeof($erro))
		    {
		        foreach($erro as $err)
		        {
		            echo " - " . $err . "<BR>";
		        }

		        echo "<a href=\"foto.html\">Fazer Upload de Outra Imagem</a>";
		    }

		    // Verificação de dados OK, nenhum erro ocorrido, executa então o upload...
		    else
		    {
		        // Pega extensão do arquivo
		        preg_match("/\.(gif|bmp|png|jpg|jpeg){1}$/i", $arquivo["name"], $ext);

		        // Gera um nome único para a imagem
		        $imagem_nome = md5(uniqid(time())) . "." . $ext[1];

		        // Caminho de onde a imagem ficará
		        $imagem_dir = "fotos/" . $imagem_nome;

		        // Faz o upload da imagem
		        move_uploaded_file($arquivo["tmp_name"], $imagem_dir);

		        echo "Sua foto foi enviada com sucesso!";
		    }
		}
	}
?>