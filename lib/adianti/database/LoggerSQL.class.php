<?php

class LoggerSQL extends TLogger
{
    /**
     * Writes an message in the LOG file
     * @param  $message Message to be written
     */
    public function write($message)
    {
        if(substr(trim($message), 0, 6) !== 'SELECT')
        {
            
            $conteudoArquivo = file_get_contents($this->filename);

            $mensagem = date("Y-m-d H:i:s") . " :: Usuario :: " . TSession::getValue('login') . " ::{$message}";

            $conteudoArquivo .= "\n{$mensagem}\n";

            file_put_contents($this->filename, $conteudoArquivo);

        }
    }
}
?>