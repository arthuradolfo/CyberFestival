<?php
/**
* Configuração base para o sistema funcionar 
* Contém todas as constantes do ambiente, além da função de autoload das classes
*
* @category Configurações
* @package  Utilidades
* @author Arthur Adolfo & Bernardo Lignati <arthur_adolfo@hotmail.com> <bernardonlignati@hotmail.com>
* @version 1.0
* @copyright CyberFestival
*/

/**
* Se está em modo de desenvolvimento
* @var bool MODO_DEV 
*/
define("MODO_DEV", true);

include_once('paths.php'); //contem as constantes de todos os
include_once('infoContato.php');//Credenciais email de contato
include_once('credenciais_bancodedados.php');// credenciais de acesso ao banco de dados caminhos do sistema
include_once('sistema.php');//constantes com informaçoes importantes para o funcionamento do sistema
include_once('handlers.php'); //precisa para o autoload
include_once(CAMINHO_BIBLIOTECAS.'/autoload.php'); //chama a funcao do autoloader para carregar todas as classes

?>