<?php
	/**
	 *
	 * Define constantes importantes para o funcionamento do sistema
	 *
	 */

	/**
	 * Link de ativação da conta
	 * @var string  LINK_ATIVACAO
	 */
	define("LINK_ATIVACAO", URL_SISTEMA."/ativacao.php"); 
	 
	/**
	 * Nome do sistema
	 * @var string NOME_SISTEMA
	 */
	define("NOME_SISTEMA","CyberFestival");

	/**
	 * O número que identifica como administrador
	 * @var int  USER_TYPE_ADMIN
	 */
	define("USER_TYPE_ADMIN", 0);

	/**
	 * O número que identifica como artista
	 * @var int  USER_TYPE_ARTIST
	 */
	define("USER_TYPE_ARTIST", 1);

	/**
	 * O número que identifica como fã
	 * @var int  USER_TYPE_FAN
	 */
	define("USER_TYPE_FAN", 2);

	/**
	 * O número que identifica como produtor
	 * @var int  USER_TYPE_PRODUTOR
	 */
	define("USER_TYPE_PRODUTOR", 3);

	/**
	 * O tamanho mínimo para a senha
	 * @var int  TAMANHO_MINIMO_SENHA
	 */
	define("TAMANHO_MINIMO_SENHA", 8);

	/**
	 * O número que identifica usuário como inativo
	 * @var int  USUARIO_INATIVO
	 */
	define("USUARIO_INATIVO", 0);

	/**
	 * O número que identifica usuário como ativo
	 * @var int  USUARIO_ATIVO
	 */
	define("USUARIO_ATIVO", 1); 

	/**
	 * O número que identifica usuário como esperando a confirmação por email
	 * @var int  USUARIO_ESPERANDO_CONFIRMACAO_EMAIL
	 */
	define("USUARIO_ESPERANDO_CONFIRMACAO_EMAIL", 2);

	/**
	 * O número que identifica usuário como recuperando a senha
	 * @var int  USUARIO_RECUPERANDO_SENHA
	 */
	define("USUARIO_RECUPERANDO_SENHA", 3);
 
	/**
	 * O número que identifica usuário como trocando o perfil
	 * @var int  USUARIO_TROCANDO_PERFIL
	 */
	define("USUARIO_TROCANDO_PERFIL", 4);

	/**
	 * O tamanho máximo para upload de arquivos
	 * @var int  TAMANHO_MAXIMO_ARQUIVO
	 */
	define("TAMANHO_MAXIMO_ARQUIVO", 2786432000);

	/**
	 * O largura máxima para upload de arquivos
	 * @var int  TAMANHO_MAXIMA_FOTO_PERFIL
	 */
	define("LARGURA_MAXIMA_FOTO_PERFIL", 2056);

	/**
	 * O altura máxima para upload de arquivos
	 * @var int  ALTURA_MAXIMA_FOTO_PERFIL
	 */
	define("ALTURA_MAXIMA_FOTO_PERFIL", 2056);
?>