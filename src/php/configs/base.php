<?php
/**
* Configuração base para o sistema funcionar 
* Contém todas as constantes do ambiente, além da função de autoload das classes
*
* @category Configurações
* @package  Utilidades
* @author Arthur Adolfo <arthur_adolfo@hotmail.com>
* @author Bernardo Lignati <bernardonlignati@hotmail.com>
* @version 1.0
* @copyright CyberFestival
*/

/**
* Se está em modo de desenvolvimento
* @var bool MODO_DEV 
*/
define("MODO_DEV", true);

include_once('paths.php'); //contem as constantes de todos os
include_once('info_contato.php');//Credenciais email de contato
include_once('mensagens_email.php');//Constantes com mensagens para email
include_once('descricoes.php');//Constantes com descrições
include_once('tipos_arquivos.php');//Constantes com tipos de arquivos
include_once('credenciais_bancodedados.php');// credenciais de acesso ao banco de dados caminhos do sistema
include_once('tabelas_db.php');//Constantes com as tabelas do banco de dados
include_once('sistema.php');//constantes com informaçoes importantes para o funcionamento do sistema
include_once('handlers.php'); //precisa para o autoload
include_once(CAMINHO_BIBLIOTECAS.'/autoload.php'); //chama a funcao do autoloader para carregar todas as classes

?>