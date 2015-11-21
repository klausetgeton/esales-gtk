<?php
/**
 * Caixa Active Record
 * @author  <your-name-here>
 */
class Caixa extends TRecord
{
    const TABLENAME = 'public.caixa';
    const PRIMARYKEY= 'id';
    const IDPOLICY =  'serial'; // {max, serial}
    
    
    /**
     * Constructor method
     */
    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('descricao');
        parent::addAttribute('pessoa_id');
        parent::addAttribute('saldo_inicial');
        parent::addAttribute('filial_id');
    }


}
