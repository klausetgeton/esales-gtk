<?php
/**
 * Entrada Active Record
 * @author  <your-name-here>
 */
class Entrada extends TRecord
{
    const TABLENAME = 'public.entrada';
    const PRIMARYKEY= 'id';
    const IDPOLICY =  'serial'; // {max, serial}
    
    
    private $caixa;

    /**
     * Constructor method
     */
    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('data');
        parent::addAttribute('valor');
        parent::addAttribute('caixa_id');
    }

    
    /**
     * Method set_caixa
     * Sample of usage: $entrada->caixa = $object;
     * @param $object Instance of Caixa
     */
    public function set_caixa(Caixa $object)
    {
        $this->caixa = $object;
        $this->ref_caixa = $object->id;
    }
    
    /**
     * Method get_caixa
     * Sample of usage: $entrada->caixa->attribute;
     * @returns Caixa instance
     */
    public function get_caixa()
    {
        // loads the associated object
        if (empty($this->caixa))
            $this->caixa = new Caixa($this->ref_caixa);
    
        // returns the associated object
        return $this->caixa;
    }
    


}
