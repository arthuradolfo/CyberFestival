<?php 
/**
*	Código da inclusão das classes ao serem chamadas
*/

/**
* Essa será a função que incluirá as classes ao serem chamadas
*
* spl_autoload_register — Registra a função dada como implementação de __autoload()
* 
* @see spl_autoload_register
* @param $classe string O nome da classe que tentará ser incluída pelo php
*/
function autoload($classe) {
	$arquivo = CAMINHO_PHP.'/classes/'.strtolower($classe).'.php';
	if(file_exists($arquivo)){
	   include_once($arquivo);
	}
}

/**
* spl_autoload_register — Registra a função dada como implementação de __autoload()
* @link http://php.net/manual/pt_BR/function.spl-autoload-register.php
*/
spl_autoload_register('autoload');

/**
* set_error_handler — Define uma função do usuário para manipular erros
* @link http://php.net/manual/pt_BR/function.set-error-handler.php
*/
//set_error_handler('Log::trataErro'); 

/**
* set_excpetion_handler — Função que será chamada quando haver exceptions sem blocos try/catch para tratá-las
* @link http://php.net/manual/pt_BR/function.set-exception-handler.php
*/
//set_exception_handler('Log::trataErro'); 