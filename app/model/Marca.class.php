<?php
/**
 * Marca Active Record
 * @author  <your-name-here>
 */
class Marca extends TRecord
{
    const TABLENAME = 'public.marca';
    const PRIMARYKEY= 'id';
    const IDPOLICY =  'serial'; // {max, serial}
    
    
    /**
     * Constructor method
     */
    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('descricao');
    }


}
