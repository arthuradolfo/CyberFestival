<?php
/**
 * Classe Utilidades e seus métodos.
 */

/**
 * Classe estática com diversas utlidades usadas no sistema
 * 
 * @category Base
 * @package  Utilidades
 */
class Utilidades{  
    /**
     * Gera o backtrace mostrando de onde a função chamada
     * @return string   backtrace formatado
     */
    static public function debugBacktrace(){
        if(!MODO_DEV){
            return;
        }
        
        $funcao  = debug_backtrace()[1]['function'];
        $arquivo = addslashes(debug_backtrace()[1]['file']);
        $linha   = debug_backtrace()[1]['line'];

        $mensagem = '\n\nChamada inicial:\nFuncão:  '.$funcao.'\nArquivo: '.$arquivo.':'.$linha.'\n';

        return $mensagem;
    }
    
    /**
     * Gera o um hash apartir da senha passada
     *
     * @link   http://php.net/manual/pt_BR/function.password-hash.php
     * @param  string $senha Senha a ter seu hash gerado
     * @return string        Hash da senha
     */
    static public function geraHashSenha($senha){
        $opcoes = array(
            //custo da geração da senha, quanto maior mais seguro, 
            //porém mais custoso em termos de performance
            'cost' => 14, 
            'salt' => uniqid(mt_rand(), true) // adiciona o 'salt' do hash
        );

        return password_hash($senha, PASSWORD_BCRYPT, $opcoes);
    }
    
    /**
     * Gera o um hash apartir do email passado
     *
     * @link   http://php.net/manual/pt_BR/function.password-hash.php
     * @param  string $email Email a ter seu hash gerado
     * @return string        Hash do email
     */
    static public function geraCodigoAtivacao($email){
        $opcoes = array(
            //custo da geração da senha, quanto maior mais seguro, 
            //porém mais custoso em termos de performance
            'cost' => 5, 
            'salt' => uniqid(mt_rand(), true) // adiciona o 'salt' do hash
        );

        return password_hash($email, PASSWORD_BCRYPT, $opcoes);
    }

    
    /**
     * Gera o um hash apartir da senha passada
     *
     * @link   http://php.net/manual/pt_BR/function.password-hash.php
     * @param  string $senha Senha a ter seu hash gerado
     * @return string        Hash da senha
     */
    static public function geraHashSenha($senha){
        $opcoes = array(
            //custo da geração da senha, quanto maior mais seguro, 
            //porém mais custoso em termos de performance
            'cost' => 14, 
            'salt' => uniqid(mt_rand(), true) // adiciona o 'salt' do hash
        );

        return password_hash($senha, PASSWORD_BCRYPT, $opcoes);
    }
    /**
     * Purifica a entrada HTML, usando a classe HTMLPurifier
     *                                                           
     * @link   http://htmlpurifier.org/docs
     * @param  string $rawString Valor a ser purificado
     * @param  array  $config Array das configurações a serem setadas
     *                        Sendo:  o índice a opção a ser setada e o
     *                                conteúdo o valor da opção
     * @return string         String purificada WOLOLOLO
     */
    static public function purificaHTML($rawString, $config = array()){
        $purifierConfig = HTMLPurifier_Config::createDefault();

        $purifierConfig->set('Core.Encoding', 'UTF-8' );
        $purifierConfig->set('HTML.Doctype', 'HTML 4.01 Transitional');

        foreach ($config as $configuracao => $valor) {
           $purifierConfig->set($configuracao, $valor);  
        }

        $purifier = new HTMLPurifier($config);
        return self::converteQuebraDeLinha($purifier->purify(trim($rawString)));
    }

    /**
     * Converte quebras de linhas (\r) para a tag <br />
     * @param $rawTexto string texto a ser convertido
     * @link http://htmlpurifier.org/phorum/read.php?2,3034
     * @return string texto convertido
     */
    static public function converteQuebraDeLinha($rawTexto){
        return preg_replace("/(\015\012)|(\015)|(\012)/", "<br />", $rawTexto);
    }

    /**
     * Método para verificar ser o(s) valores existem em um mesmo registro da tabela
     * @param  mixed[] $campos uma array indexada da seguinte maneira
     *                         $campos['coluna'] = valor dela
     *                  
     * @param  string $tabela tabela usada na query
     * @throws InvalidArgumentException Uso de argumentos inválidos
     * @return mixed[]|null registro(s) caso seja(o) encontrado(s) ou NULL
     */ 
    static public function valoresExistenteDB($campos, $tabela){
        if(!is_array($campos)){
            throw new InvalidArgumentException("Erro ao definar os campos, esperava uma array de campos. Recebeu ".gettype($campos), E_USER_ERROR);
        }

        if(!is_string($tabela)){
            throw new InvalidArgumentException("Erro ao definar a tabela, esperava uma string. Recebeu ".gettype($tabela), E_USER_ERROR);
        }

    	$query = new MysqliDb();

        foreach ($campos as $coluna => $valor) {
              $query->where($coluna, $valor);
        }

    	// o método getOne retorna uma array caso encontre alguma linha
		return $query->getOne($tabela);
    }

    /**
     *
     * Checa se a string passada é nula ou vazia
     *
     * @link  http://stackoverflow.com/questions/381265/better-way-to-check-variable-for-null-or-empty-string
     * @param string $rawString string a ser testada
     * @return bool          retorna true se $string for nulo ou vazia
     */
    static public function stringNulaOuVazia($rawString){
        return (!isset($rawString) || trim($rawString) ==='');
    }

    /**
     * Checa se a string passada é composta por caracteres alfanuméricos.
     * @param  string $rawString
     * @return bool Booleano indicando se a string é composta somente por caracteres alfanuméricos.
     */
    static public function validaAlfanumerico($rawString){
        return ctype_alnum(preg_replace('/\s+/', '',$rawString));
    }

    /**
     * 
     * Valida a data e hora passada
     * 
     * @param  string $dataHora    data e hora a ser validada
     * @param  string $formato formato da data e hora a ser validada 
     * @return bool            resultado da validação
     */
    static public function validaDataHora($dataHora, $formato = "d/m/Y H:i:s"){
        // cria a data usando a classe do php DateTime
        $dataHoraCriada = DateTime::createFromFormat($formato, $dataHora); 

        //depois verifica se a data e hora passada é igual a data criada pela classe
        return ($dataHoraCriada && $dataHoraCriada->format($formato) == $dataHora);
    }
       
    /**
     * Formata a data e/ou hora passada para o formato desejada
     * Ex: 
     *     $data = '2009-3-1'
     *     $formato = 'Y-m-d'
     *     $formatoDesejado = "d/m/Y"
     *
     *     retornará  3/1/2009
     *  
     * @param  string $dataHora        data/hora a ser formatada
     * @param  string $formato         formato da data/hora passada
     * @param  string $formatoDesejado formato desejado da data/hora
     * @return string                  a data/hora já formatada
     */
    static public function formataDataHora($dataHora, $formato = "Y-m-d H:i:s", $formatoDesejado = "d/m/Y H:i:s"){
        // cria a data usando a classe do php DateTime
        $dataHoraCriada = DateTime::createFromFormat($formato, $dataHora);
        return $dataHoraCriada->format($formatoDesejado);
    }

    /**
     *
     * Valida email
     *
     * @param  string $email email a ser validado
     * @return bool          resultado da validação
     */
    static public function validaEmail($email) {
        return filter_var($email, FILTER_VALIDATE_EMAIL);
    }

    /**
     * Valida URL de um site.
     * 
     * @param string $url URL do site a ser testada.
     * @return  bool      Booleano indicando se a URL é válida.
     */    
    static public function validaURL($url){
        return filter_var($url, FILTER_VALIDATE_URL);
    }

     /**
     * Remove os acentos da string
     *
     * @param $texto string
     * @return mixed
     * @link http://myshadowself.com/coding/php-function-to-convert-accented-characters-to-their-non-accented-equivalant/
     */
    static public function removeAcentos($texto) {
        $acentos = array('À', 'Á', 'Â', 'Ã', 'Ä', 'Å', 'Æ', 'Ç', 'È', 'É', 'Ê', 'Ë', 'Ì', 'Í', 'Î', 'Ï', 'Ð', 'Ñ', 'Ò', 'Ó', 'Ô', 'Õ', 'Ö', 'Ø', 'Ù', 'Ú', 'Û', 'Ü', 'Ý', 'ß', 'à', 'á', 'â', 'ã', 'ä', 'å', 'æ', 'ç', 'è', 'é', 'ê', 'ë', 'ì', 'í', 'î', 'ï', 'ñ', 'ò', 'ó', 'ô', 'õ', 'ö', 'ø', 'ù', 'ú', 'û', 'ü', 'ý', 'ÿ', 'Ā', 'ā', 'Ă', 'ă', 'Ą', 'ą', 'Ć', 'ć', 'Ĉ', 'ĉ', 'Ċ', 'ċ', 'Č', 'č', 'Ď', 'ď', 'Đ', 'đ', 'Ē', 'ē', 'Ĕ', 'ĕ', 'Ė', 'ė', 'Ę', 'ę', 'Ě', 'ě', 'Ĝ', 'ĝ', 'Ğ', 'ğ', 'Ġ', 'ġ', 'Ģ', 'ģ', 'Ĥ', 'ĥ', 'Ħ', 'ħ', 'Ĩ', 'ĩ', 'Ī', 'ī', 'Ĭ', 'ĭ', 'Į', 'į', 'İ', 'ı', 'Ĳ', 'ĳ', 'Ĵ', 'ĵ', 'Ķ', 'ķ', 'Ĺ', 'ĺ', 'Ļ', 'ļ', 'Ľ', 'ľ', 'Ŀ', 'ŀ', 'Ł', 'ł', 'Ń', 'ń', 'Ņ', 'ņ', 'Ň', 'ň', 'ŉ', 'Ō', 'ō', 'Ŏ', 'ŏ', 'Ő', 'ő', 'Œ', 'œ', 'Ŕ', 'ŕ', 'Ŗ', 'ŗ', 'Ř', 'ř', 'Ś', 'ś', 'Ŝ', 'ŝ', 'Ş', 'ş', 'Š', 'š', 'Ţ', 'ţ', 'Ť', 'ť', 'Ŧ', 'ŧ', 'Ũ', 'ũ', 'Ū', 'ū', 'Ŭ', 'ŭ', 'Ů', 'ů', 'Ű', 'ű', 'Ų', 'ų', 'Ŵ', 'ŵ', 'Ŷ', 'ŷ', 'Ÿ', 'Ź', 'ź', 'Ż', 'ż', 'Ž', 'ž', 'ſ', 'ƒ', 'Ơ', 'ơ', 'Ư', 'ư', 'Ǎ', 'ǎ', 'Ǐ', 'ǐ', 'Ǒ', 'ǒ', 'Ǔ', 'ǔ', 'Ǖ', 'ǖ', 'Ǘ', 'ǘ', 'Ǚ', 'ǚ', 'Ǜ', 'ǜ', 'Ǻ', 'ǻ', 'Ǽ', 'ǽ', 'Ǿ', 'ǿ', 'Ά', 'ά', 'Έ', 'έ', 'Ό', 'ό', 'Ώ', 'ώ', 'Ί', 'ί', 'ϊ', 'ΐ', 'Ύ', 'ύ', 'ϋ', 'ΰ', 'Ή', 'ή');
        $replace = array('A', 'A', 'A', 'A', 'A', 'A', 'AE', 'C', 'E', 'E', 'E', 'E', 'I', 'I', 'I', 'I', 'D', 'N', 'O', 'O', 'O', 'O', 'O', 'O', 'U', 'U', 'U', 'U', 'Y', 's', 'a', 'a', 'a', 'a', 'a', 'a', 'ae', 'c', 'e', 'e', 'e', 'e', 'i', 'i', 'i', 'i', 'n', 'o', 'o', 'o', 'o', 'o', 'o', 'u', 'u', 'u', 'u', 'y', 'y', 'A', 'a', 'A', 'a', 'A', 'a', 'C', 'c', 'C', 'c', 'C', 'c', 'C', 'c', 'D', 'd', 'D', 'd', 'E', 'e', 'E', 'e', 'E', 'e', 'E', 'e', 'E', 'e', 'G', 'g', 'G', 'g', 'G', 'g', 'G', 'g', 'H', 'h', 'H', 'h', 'I', 'i', 'I', 'i', 'I', 'i', 'I', 'i', 'I', 'i', 'IJ', 'ij', 'J', 'j', 'K', 'k', 'L', 'l', 'L', 'l', 'L', 'l', 'L', 'l', 'l', 'l', 'N', 'n', 'N', 'n', 'N', 'n', 'n', 'O', 'o', 'O', 'o', 'O', 'o', 'OE', 'oe', 'R', 'r', 'R', 'r', 'R', 'r', 'S', 's', 'S', 's', 'S', 's', 'S', 's', 'T', 't', 'T', 't', 'T', 't', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'W', 'w', 'Y', 'y', 'Y', 'Z', 'z', 'Z', 'z', 'Z', 'z', 's', 'f', 'O', 'o', 'U', 'u', 'A', 'a', 'I', 'i', 'O', 'o', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'A', 'a', 'AE', 'ae', 'O', 'o', 'Α', 'α', 'Ε', 'ε', 'Ο', 'ο', 'Ω', 'ω', 'Ι', 'ι', 'ι', 'ι', 'Υ', 'υ', 'υ', 'υ', 'Η', 'η');
        return str_replace($acentos, $replace, $texto);
    }

    /**
     * Retorna o nome sanitizado (sem caracteres especiais)
     * Ideal para ser usado como nome de arquivo
     * Remove todos caracteres não alfanúmericos e acentos
     * 
     * @param  string $nome nome para ser sanitizado
     * @return string       nome sanitizado
     * @throws InvalidArgumentException Uso de argumentos inválidos
     */
    static public function sanitizaNome($nome){
        if(!is_string($nome)){
            throw new InvalidArgumentException("Erro ao definar o nome esperava uma string. Recebeu ".gettype($nome), E_USER_ERROR);
        }

        return preg_replace("/[^a-z0-9\.]/", "", $nome);
    } 

    /**
     * Retorna a atual URL completa 
     * @link http://stackoverflow.com/questions/6768793/get-the-full-url-in-php
     * @return string url
     */
    static public function getFullUrl(){
        return '//'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
    }
}