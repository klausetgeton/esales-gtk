<?php
/**
 * Filial Active Record
 * @author  <your-name-here>
 */
class Filial extends TRecord
{
    const TABLENAME = 'public.filial';
    const PRIMARYKEY= 'id';
    const IDPOLICY =  'serial'; // {max, serial}
    
    
    /**
     * Constructor method
     */
    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('nome');
        parent::addAttribute('endereco');
        parent::addAttribute('numero');
        parent::addAttribute('email');
        parent::addAttribute('telefone');
        parent::addAttribute('fl_matriz');
    }


}
