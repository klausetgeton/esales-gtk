<?php

class LogException
{
    public static function log($message) {
     
    	$nomeArquivo = 'auditoria';

    	$conteudoArquivo = file_get_contents($nomeArquivo);

    	$mensagem = date("Y-m-d H:i:s") . " :: Usuario :: " . TSession::getValue('login') . " ::{$message}";

        $conteudoArquivo .= "\n{$mensagem}\n";

        file_put_contents($nomeArquivo, $conteudoArquivo);
    }
}