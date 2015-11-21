<?php
/**
 * Log Active Record
 * @author  <your-name-here>
 */
class Log extends TRecord
{
    const TABLENAME = 'public.log';
    const PRIMARYKEY= 'id';
    const IDPOLICY =  'serial'; // {max, serial}
    
    
    /**
     * Constructor method
     */
    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('tabela');
        parent::addAttribute('sql');
    }


}
