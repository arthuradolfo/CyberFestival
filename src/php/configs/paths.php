<?php 
 /**
 * Define de todas constantes de ENDEREÇO
 */

 /**
 * definições do ambiente
 * @var string PASTA_RAIZ
 */
define("PASTA_RAIZ","/CyberFestival");

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

//!!!!!A implementar !!!!!!!
/**
 * A url do painel de controle, onde só o adminsitrador do sistema deve ter acesso
 * @var string URL_PAINEL_DE_CONTROLE
 */
//define("URL_PAINEL_DE_CONTROLE", URL_SISTEMA.'/admin/');

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
 * @var string  CAMINHO_UPLOAD_ARQUIVO
 */
define("CAMINHO_UPLOAD_ARQUIVO", CAMINHO_UPLOAD."/arquivos");

 /**
 * O caminho nível-servidor das fotos enviado pelos usuários
 * @var string  CAMINHO_FOTO
 */
define("CAMINHO_FOTO", CAMINHO_UPLOAD."/fotos"); 
 
 /**
 * O caminho nível-servidor dos videos enviados pelos usuários
 * @var string  CAMINHO_VIDEO
 */
define("CAMINHO_VIDEO", CAMINHO_UPLOAD."/videos"); 
 
 
 /**
 * O caminho nível-servidor dos audios enviados pelos usuários
 * @var string  CAMINHO_AUDIO
 */
define("CAMINHO_AUDIO", CAMINHO_UPLOAD."/audios"); 
 

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

