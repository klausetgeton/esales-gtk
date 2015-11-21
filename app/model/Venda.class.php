<?php
/**
 * Venda Active Record
 * @author  <your-name-here>
 */
class Venda extends TRecord
{
    const TABLENAME = 'public.venda';
    const PRIMARYKEY= 'id';
    const IDPOLICY =  'serial'; // {max, serial}
    
    
    private $filial;
    private $entrada;
    private $pessoa;

    /**
     * Constructor method
     */
    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('vendedor_id');
        parent::addAttribute('comprador_id');
        parent::addAttribute('filial_id');
        parent::addAttribute('data');
        parent::addAttribute('entrada_id');
    }

    
    /**
     * Method set_filial
     * Sample of usage: $venda->filial = $object;
     * @param $object Instance of Filial
     */
    public function set_filial(Filial $object)
    {
        $this->filial = $object;
        $this->ref_filial = $object->id;
    }
    
    /**
     * Method get_filial
     * Sample of usage: $venda->filial->attribute;
     * @returns Filial instance
     */
    public function get_filial()
    {
        // loads the associated object
        if (empty($this->filial))
            $this->filial = new Filial($this->ref_filial);
    
        // returns the associated object
        return $this->filial;
    }
    
    
    /**
     * Method set_entrada
     * Sample of usage: $venda->entrada = $object;
     * @param $object Instance of Entrada
     */
    public function set_entrada(Entrada $object)
    {
        $this->entrada = $object;
        $this->ref_entrada = $object->id;
    }
    
    /**
     * Method get_entrada
     * Sample of usage: $venda->entrada->attribute;
     * @returns Entrada instance
     */
    public function get_entrada()
    {
        // loads the associated object
        if (empty($this->entrada))
            $this->entrada = new Entrada($this->ref_entrada);
    
        // returns the associated object
        return $this->entrada;
    }
    
    
    /**
     * Method set_pessoa
     * Sample of usage: $venda->pessoa = $object;
     * @param $object Instance of Pessoa
     */
    public function set_pessoa(Pessoa $object)
    {
        $this->pessoa = $object;
        $this->ref_pessoa = $object->id;
    }
    
    /**
     * Method get_pessoa
     * Sample of usage: $venda->pessoa->attribute;
     * @returns Pessoa instance
     */
    public function get_pessoa()
    {
        // loads the associated object
        if (empty($this->pessoa))
            $this->pessoa = new Pessoa($this->ref_pessoa);
    
        // returns the associated object
        return $this->pessoa;
    }
    


}
