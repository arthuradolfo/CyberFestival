<?php
/**
* Classe que contem informacoes e metodos para o usuário
*
* @category Usuário
* @package  Usuário
* @author Arthur Adolfo <arthur_adolfo@hotmail.com>
* @version 1.0
* @copyright CyberFestival
*/

class Usuario {
	/**
	 * ID do usuário no banco de dados 
	 * Caso o usuário exista seu id será diferente de NULO
	 * @var int id do usuário
	 */
	private $id;

	/**
	 * @var string nome do usuário
	 */
	private $nome;

	/**
	 * @var string email do usuário
	 */
	private $email;

	/**
	 * @var string nacionalidade do usuário
	 */
	private $nacionalidade;

	/**
	 * Existem três tipos de usuário principais, além do administrador
	 * Tipo Artista - USER_TYPE_ARTIST, Tipo Fã - USER_TYPE_FAN, Tipo Produtor - USER_TYPE_PRODUTOR
	 * @var int tipo do usuário
	 */
	private $tipo;

	/**
	 * Senha do usuário 
	 * @var string senha do usuário
	 */
	private $senha;

	/**
	 * @var string hash da senha do usuário
	 */
	private $senhaHash;

	/**
	 * @var string foto de perfil do usuário
	 */
	private $fotoPerfil;

	/**
	 * @var string estilo principal de música do usuário
	 */
	private $estilo;

	/**
	 * @var array interesse musical do usuário
	 */
	private $interesseMusical;

	/**
	 * @var array instrumentos tocados pelo usuário
	 */
	private $instrumentos;

	/**
	 * @var array bandas do usuário
	 */
	private $bandas;

    /**
     * Se o id do usuário não for nulo, carrega os dados do banco de dados
	 * @param int id do usuário
	 * @throws InvalidArgumentException Uso de arguimentos inválidos
	 */
	public function __construct($id = NULL) {
		if(!is_null($id)) {
			if(!is_int($id)) {
				throw new InvalidArgumentException("Erro ao definir o id do usuário. Esperava um inteiro, recebeu ".gettype($id), E_USER_ERROR);
			}
			else {
				$this->carregaDados(array('id' => $id));
			}
		}
	}



    /**
     * Carrega dados do usuário do banco de dados pelo id
     * @param array campos do usuário a ser definido
     */
	private function carregaDados($campos) {
		if(!is_array($campos)){
            throw new InvalidArgumentException("Erro ao definar os campos, esperava um array de campos. Recebeu ".gettype($campos).Utilidades::debugBacktrace(), E_USER_ERROR);
        }

        $query = new MysqliDb();

		foreach ($campos as $coluna => $valor) {
            $query->where($coluna, $valor);
        }

		$this->setDados($query->getOne(TABELA_USUARIOS));
	}

    /**
     * Define informações do usuário pelo usuário
     * @param array dados do usuário a ser definido
     */
	private function setDados($dados) {
		if(is_array($dados)){
            $this->id = $dados['id'];
            $this->nome = $dados['nome'];
            $this->nacionalidade = $dados['nacionalidade'];
            $this->email = $dados['email'];
            $this->senhaHash = $dados['senha'];
            $this->tipo = $dados['tipo'];
            $this->estilo = $dados['estilo'];
            $this->fotoPerfil = $this->carregaFotoPerfil($this->getId());
            $this->interesseMusical = $this->carregaInteresseMusical($this->getId());
            $this->instrumentos = $this->carregaInstrumentos($this->getId());
            $this->bandas = $this->carregaBandas($this->getId());
        }
	}

	/**
     * Carrega foto de perfil do usuário
     * @param int id do usuário
     * @return string url foto de perfil do usuário
     */

	private function carregaFotoPerfil($id) {
		if(!is_int($id)) {
			throw new InvalidArgumentException("Erro, Espera receber um inteiro, recebeu ". gettype($id), E_USER_ERROR);
		}
		else {
			$query = new MysqliDb;
			$query->where('id', $id);
			$dados = $query->getOne(TABELA_USUARIOS);
			setFotoPerfil($dados['fotoPerfil']);
		}
	}

    /**
     * Define id do usuário
     * @param int id do usuário a ser definido
     * @throws InvalidArgumentException Uso de argumentos inválidos
     */
	private function setId($id) {
		if(!is_int($id)) {
			throw new InvalidArgumentException("Erro ao definir o id do usuário. Esperava um inteiro, recebeu ".gettype($id), E_USER_ERROR);
		}
		else {
			$this->id = $id;
		}
	}

    /**
     * Define nome do usuário
     * @param string nome do usuário a ser definido
     * @throws InvalidArgumentException Uso de argumentos inválidos
     */
	private function setNome($nome) {
		if(!is_string($nome)) {
			throw new InvalidArgumentException("Erro ao definir o nome do usuário. Esperava uma string, recebeu ".gettype($nome), E_USER_ERROR);
		}
		else if(is_null($nome)) {
			throw new InvalidArgumentException("Erro ao definir nome do usuário. String nula.", E_USER_ERROR);
		}
		else {
			$this->nome = $nome;
		}
	}

    /**
     * Define email do usuário
     * @param string email do usuário a ser definido
     * @throws InvalidArgumentException Uso de argumentos inválidos
     */
	private function setEmail($email) {
		if(!is_string($email)) {
			throw new InvalidArgumentException("Erro ao definir o email do usuário. Esperava uma string, recebeu ".gettype($nome), E_USER_ERROR);
		}
		else if(is_null($email)) {
			throw new InvalidArgumentException("Erro ao definir email do usuário. String nula.", E_USER_ERROR);
		}
		else {
			$this->email = $email;
		}
	}

    /**
     * Define nacionalidade do usuário
     * @param string nacionalidade do usuário a ser definido
     * @throws InvalidArgumentException Uso de argumentos inválidos
     */
	private function setNacionalidade($nacionalidade) {
		if(!is_string($nacionalidade)) {
			throw new InvalidArgumentException("Erro ao definir a nacionalidade do usuário. Esperava uma string, recebeu ".gettype($nacionalidade), E_USER_ERROR);
		}
		else if(is_null($nacionalidade)) {
			throw new InvalidArgumentException("Erro ao definir a nacionalidade do usuário. String nula.", E_USER_ERROR);
		}
		else {
			$this->nacionalidade = $nacionalidade;
		}
	}

    /**
     * Define tipo do usuário
     * @param int tipo do usuário a ser definido
     * @throws InvalidArgumentException Uso de argumentos inválidos
     */
	private function setTipo($tipo) {
		if(!is_int($tipo)) {
			throw new InvalidArgumentException("Erro ao definir o nome do usuário. Esperava uma string, recebeu ".gettype($tipo), E_USER_ERROR);
		}
		else if(is_null($tipo)) {
			throw new InvalidArgumentException("Erro ao definir tipo do usuário. Inteiro nulo.", E_USER_ERROR);
		}
		else {
			$this->tipo = $tipo;
		}
	}

    /**
     * Define senha do usuário
     * @param string senha do usuário a ser definido
     * @throws InvalidArgumentException Uso de argumentos inválidos
     */
	private function setSenha($senha) {
		if(!is_string($senha)) {
			throw new InvalidArgumentException("Erro ao definir a senha do usuário. Esperava uma string, recebeu ".gettype($senha), E_USER_ERROR);
		}
		else if(is_null($senha)) {
			throw new InvalidArgumentException("Erro ao definir a senha do usuário. String nula", E_USER_ERROR);
		}
		else {
			$this->senha = $senha;
		}
	}

    /**
     * Define a hash da senha do usuário
     * @param string hash da senha do usuário a ser definido
     * @throws InvalidArgumentException Uso de argumentos inválidos
     */
	private function setSenhaHash($senhaHash) {
		if(!is_string($senhaHash)) {
			throw new InvalidArgumentException("Erro ao definir a hash da senha do usuário. Esperava uma string, recebeu ".gettype($senhaHash), E_USER_ERROR);
		}
		else if(is_null($senhaHash)) {
			throw new InvalidArgumentException("Erro ao definir a hash da senha do usuário. String nula", E_USER_ERROR);
		}
		else {
			$this->senhaHash = $senhaHash;
		}
	}

    /**
     * Define a foto de perfil do usuário
     * @param string url da foto de perfil do usuário a ser definido
     * @throws InvalidArgumentException Uso de argumentos inválidos
     */
	private function setFotoPerfil($fotoPefil) {
		if(!is_string($fotoPerfil)) {
			throw new InvalidArgumentException("Erro ao definir a foto de perfil do usuário. Esperava uma string, recebeu ".gettype($fotoPerfil), E_USER_ERROR);
		}
		else if(is_null($fotoPerfil)) {
			throw new InvalidArgumentException("Erro ao definir a foto de perfil do usuário. String nula", E_USER_ERROR);
		}
		else {
			$this->fotoPerfil = $fotoPerfil;
		}
	}

    /**
     * Define o estilo principal de perfil do usuário
     * @param string estilo principal do usuário a ser definido
     * @throws InvalidArgumentException Uso de argumentos inválidos
     */
	private function setEstilo($estilo) {
		if(!is_string($estilo)) {
			throw new InvalidArgumentException("Erro ao definir o estilo de perfil do usuário. Esperava uma string, recebeu ".gettype($estilo), E_USER_ERROR);
		}
		else if(is_null($estilo)) {
			throw new InvalidArgumentException("Erro ao definir o estilo de perfil do usuário. String nula", E_USER_ERROR);
		}
		else {
			$this->estilo = $estilo;
		}
	}

    /**
     * Define o interesse musical de perfil do usuário
     * @param array interesse musical do usuário a ser definido
     * @throws InvalidArgumentException Uso de argumentos inválidos
     */
	private function setInteresseMuscial($interesseMusical) {
		if(!is_array($interesseMusical)) {
			throw new InvalidArgumentException("Erro ao definir o interesse musical do usuário. Esperava um array, recebeu ".gettype($interesseMusical), E_USER_ERROR);
		}
		else if(!is_string($interesseMusical)) {
			throw new InvalidArgumentException("Erro ao definir o interesse musical do usuário. Esperava uma string, recebeu ".gettype($interesseMusical), E_USER_ERROR);
		}
		else if(is_null($interesseMusical)) {
			throw new InvalidArgumentException("Erro ao definir o interesse musical do usuário. String nula", E_USER_ERROR);
		}
		else {
			$this->interesseMusical = $interesseMusical;
		}
	}

    /**
     * Define os instrumentos tocados pelo usuário
     * @param array instrumentos tocados pelo usuário a ser definido
     * @throws InvalidArgumentException Uso de argumentos inválidos
     */
	private function setInstrumentos($instrumentos) {
		if(!is_array($instrumentos)) {
			throw new InvalidArgumentException("Erro ao definir os instrumentos do usuário. Esperava um array, recebeu ".gettype($instrumentos), E_USER_ERROR);
		}
		else if(!is_string($instrumentos)) {
			throw new InvalidArgumentException("Erro ao definir os intrumentos do usuário. Esperava uma string, recebeu".gettype($instrumentos), E_USER_ERROR);
		}
		else if(is_null($instrumentos)) {
			throw new InvalidArgumentException("Erro ao definir os instrumentos do usuário. String nula.", E_USER_ERROR);
		}
		else {
			$this->instrumentos = $instrumentos;
		}
	}

    /**
     * Define as bandas tocadas pelo usuário
     * @param array bandas tocadas pelo usuário a ser definido
     * @throws InvalidArgumentException Uso de argumentos inválidos
     */
	private function setBandas($bandas) {
		if(!is_array($bandas)) {
			throw new InvalidArgumentException("Erro ao definir os instrumentos do usuário. Esperava um array, recebeu ".gettype($instrumentos), E_USER_ERROR);
		}
		else if(!is_string($bandas)) {
			throw new InvalidArgumentException("Erro ao definir os intrumentos do usuário. Esperava uma string, recebeu ".gettype($instrumentos), E_USER_ERROR);
		}
		else if(is_null($bandas)) {
			throw new InvalidArgumentException("Erro ao definir os instrumentos do usuário. String nula.", E_USER_ERROR);
		}
		else if(isUserFan()) {
			throw new InvalidArgumentException("Erro ao definir as bandas do usuário. Fã não pode ter banda.", E_USER_ERROR);
		}
		else {
			$this->bandas = $bandas;
		}
	}

	/**
     * Retorna id do usuário
     * @return int id do usuário a ser definido
     */
	private function getId() {
		return $this->id;
	}

    /**
     * Retorna nome do usuário
     * @return string nome do usuário a ser definido
     */
	private function getNome() {
		return $this->nome;
	}

    /**
     * Retorna email do usuário
     * @return string email do usuário a ser definido
     */
	private function getEmail() {
		return $this->email;
	}

    /**
     * Retorna nacionalidade do usuário
     * @return string nacionalidade do usuário a ser definido
     */
	private function getNacionalidade() {
		return $this->nacionalidade;
	}

    /**
     * Retorna tipo do usuário
     * @return int tipo do usuário a ser definido
     */
	private function getTipo() {
		return $this->tipo;
	}

    /**
     * Return senha do usuário
     * @return string senha do usuário a ser definido
     */
	private function getSenha() {
		return $this->senha;
	}

    /**
     * Return a hash da senha do usuário
     * @return string hash da senha do usuário a ser definido
     */
	private function getSenhaHash() {
		return $this->senhaHash;
	}

    /**
     * Return a foto de perfil do usuário
     * @return string url da foto de perfil do usuário a ser definido
     */
	private function getFotoPerfil() {
		return $this->fotoPerfil;
	}

    /**
     * Return o estilo principal de perfil do usuário
     * @return string estilo principal do usuário a ser definido
     */
	private function getEstilo() {
		return $this->estilo;
	}

    /**
     * Retorna o interesse musical de perfil do usuário
     * @return array interesse musical do usuário a ser definido
     */
	private function getInteresseMuscial() {
		return $this->interesseMusical
	}

    /**
     * Retorna os instrumentos tocados pelo usuário
     * @return array instrumentos tocados pelo usuário a ser definido
     */
	private function getInstrumentos() {
		return $this->instrumentos;
	}

    /**
     * Return as bandas tocadas pelo usuário
     * @return array bandas tocadas pelo usuário a ser definido
     */
	private function getBandas() {
		return $this->bandas;
	}
}
?>