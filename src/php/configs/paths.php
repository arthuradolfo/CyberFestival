<?php 
 /**
 * Define de todas constantes de ENDEREÇO
 */

 /**
 * definições do ambiente
 * @var string PASTA_RAIZ
 */
define("PASTA_RAIZ","CyberFestival");

 /**
 * A url do sistema 
 * @var string URL_SISTEMA
 */
define("URL_SISTEMA", "http://".$_SERVER['HTTP_HOST'].PASTA_RAIZ);

 /**
 * A url da página inicial 
 * @var string URL_PORTAL
 */
define("URL_PORTAL", URL_SISTEMA.'/index.php');

 /**
 * O caminho nível-servidor dos arquivos
 * @var string  CAMINHO_SISTEMA
 */
define("CAMINHO_SISTEMA", $_SERVER["DOCUMENT_ROOT"].PASTA_RAIZ);
 
 /**
 * A localização dos scripts php
 * @var string  CAMINHO_PHP
 */
define("CAMINHO_PHP", CAMINHO_SISTEMA."/src/php");
 

 /**
 * A localização das configuracoes usada no sistema
 * @var string  CAMINHO_CONFIGS
 */
define("CAMINHO_CONFIGS", CAMINHO_PHP."/configs");

 /**
 * A localização dos includes 
 * @var string  CAMINHO_INCLUDES
 */
define("CAMINHO_INCLUDES", CAMINHO_PHP."/includes");

 /**
 * A localização dos includes da bliblioteca
 * @var string  CAMINHO_BIBLIOTECAS
 */
define("CAMINHO_BIBLIOTECAS", CAMINHO_PHP."/bibliotecas");

/**
 * O caminho nível-servidor dos uploads
 * @var string CAMINHO_UPLOAD
 */
define("CAMINHO_UPLOAD", CAMINHO_SISTEMA."/uploads");

 /**
 * O caminho nível-servidor dos arquivos enviado pelos usuários
 * @var string  CAMINHO_UPLOAD_USUARIOS
 */
define("CAMINHO_UPLOAD_USUARIOS", CAMINHO_UPLOAD."/usuarios");

 /**
 * O caminho nível-servidor das fotos enviado pelos usuários
 * @var string  CAMINHO_USUARIOS_FOTOS
 */
define("CAMINHO_USUARIOS_FOTOS", CAMINHO_UPLOAD_USUARIOS."/fotos"); 

 /**
 * O caminho nível-servidor das fotos de perfil enviado pelos usuários
 * @var string  CAMINHO_USUARIOS_FOTO_PERFIL
 */
define("CAMINHO_USUARIOS_FOTO_PERFIL", CAMINHO_UPLOAD_USUARIOS."/fotosperfil"); 
 
 /**
 * O caminho nível-servidor dos videos enviados pelos usuários
 * @var string  CAMINHO_USUARIOS_VIDEO
 */
define("CAMINHO_USUARIOS_VIDEO", CAMINHO_UPLOAD_USUARIOS."/videos"); 
 
 
 /**
 * O caminho nível-servidor das musicas enviados pelos usuários
 * @var string  CAMINHO_USUARIOS_MUSICAS
 */
define("CAMINHO_USUARIOS_MUSICAS", CAMINHO_UPLOAD_USUARIOS."/musicas"); 

 /**
 * O caminho nível-servidor dos arquivos enviado pelas bandas
 * @var string  CAMINHO_UPLOAD_BANDAS
 */
define("CAMINHO_UPLOAD_BANDAS", CAMINHO_UPLOAD."/bandas");

 /**
 * O caminho nível-servidor das fotos enviado pelas bandas
 * @var string  CAMINHO_BANDAS_FOTOS
 */
define("CAMINHO_BANDAS_FOTOS", CAMINHO_UPLOAD_BANDAS."/fotos"); 
 
 /**
 * O caminho nível-servidor dos videos enviados pelas bandas
 * @var string  CAMINHO_BANDAS_VIDEO
 */
define("CAMINHO_BANDAS_VIDEO", CAMINHO_UPLOAD_BANDAS."/videos"); 
 
 
 /**
 * O caminho nível-servidor das musicas enviados pelas bandas
 * @var string  CAMINHO_BANDAS_MUSICAS
 */
define("CAMINHO_BANDAS_MUSICAS", CAMINHO_UPLOAD_BANDAS."/musicas"); 
 

 /**
 * O caminho dos arquivos ajax
 * @var string  CAMINHO_AJAX
 */
define("CAMINHO_AJAX", URL_SISTEMA."/sources/ajax"); 

 /**
 * A localização dos arquivos css
 * @var string  CAMINHO_CSS
 */
define("CAMINHO_CSS", URL_SISTEMA."/sources/css");

 /**
 * A localização das imagens
 * @var string  CAMINHO_IMAGEM
 */
define("CAMINHO_IMAGEM", URL_SISTEMA."/sources/img");

 /**
 * O caminho url das fotos enviado pelos usuários
 * @var string  CAMINHO_URL_FOTO
 */
define("CAMINHO_URL_FOTO", URL_SISTEMA."/uploads/fotos");  

 /**
 * O caminho url dos videos enviado pelos usuários
 * @var string  CAMINHO_URL_VIDEO
 */
define("CAMINHO_URL_VIDEO", URL_SISTEMA."/uploads/videos"); 

 /**
 * O caminho url dos audios enviado pelos usuários
 * @var string  CAMINHO_URL_AUDIO
 */
define("CAMINHO_URL_AUDIO", URL_SISTEMA."/uploads/audios"); 

 /**
 * A localização dos scripts js
 * @var string  CAMINHO_JS
 */
define("CAMINHO_JS", URL_SISTEMA."/sources/js");

 /**
 * A localização dos action dos formulários <form>
 * @var string  CAMINHO_FORM_ACTION
 */
define("CAMINHO_FORM_ACTION", URL_SISTEMA."/sources/php/actions");

 /**
 * O caminho url para ativação de conta
 * @var string  CAMINHO_URL_ATIVACAO
 */
define("CAMINHO_URL_ATIVACAO", CAMINHO_FORM_ACTION."/ativacao.php");

