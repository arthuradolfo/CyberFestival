<?php
	/**
	 *
	 * Define constantes importantes para o funcionamento do sistema
	 *
	 */

	/**
	 * O número que identifica como administrador
	 * @var string  USER_TYPE_ADMIN
	 */
	define("USER_TYPE_ADMIN", 0);

	/**
	 * O número que identifica como artista
	 * @var string  USER_TYPE_ARTIST
	 */
	define("USER_TYPE_ARTIST", 1);

	/**
	 * O número que identifica como fã
	 * @var string  USER_TYPE_FAN
	 */
	define("USER_TYPE_FAN", 2);

	/**
	 * O número que identifica como produtor
	 * @var string  USER_TYPE_PRODUTOR
	 */
	define("USER_TYPE_PRODUTOR", 3);

	/**
	 * O tamanho mínimo para a senha
	 * @var string  TAMANHO_MINIMO_SENHA
	 */
	define("TAMANHO_MINIMO_SENHA", 8);

	/**
	 * O número que identifica usuário como inativo
	 * @var string  USUARIO_INATIVO
	 */
	define("USUARIO_INATIVO", 0);

	/**
	 * O número que identifica usuário como ativo
	 * @var string  USUARIO_ATIVO
	 */
	define("USUARIO_ATIVO", 1); 

	/**
	 * O número que identifica usuário como esperando a confirmação por email
	 * @var string  USUARIO_ESPERANDO_CONFIRMACAO_EMAIL
	 */
	define("USUARIO_ESPERANDO_CONFIRMACAO_EMAIL", 2);

	/**
	 * O número que identifica usuário como recuperando a senha
	 * @var string  USUARIO_RECUPERANDO_SENHA
	 */
	define("USUARIO_RECUPERANDO_SENHA", 3);
 
	/**
	 * O número que identifica usuário como trocando o perfil
	 * @var string  USUARIO_TROCANDO_PERFIL
	 */
	define("USUARIO_TROCANDO_PERFIL", 4);
?>