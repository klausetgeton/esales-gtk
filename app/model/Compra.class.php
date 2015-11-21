<?php
/**
 * Compra Active Record
 * @author  <your-name-here>
 */
class Compra extends TRecord
{
    const TABLENAME = 'public.compra';
    const PRIMARYKEY= 'id';
    const IDPOLICY =  'serial'; // {max, serial}
    
    
    private $pessoa;
    private $saida;
    private $filial;

    /**
     * Constructor method
     */
    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('vendedor_id');
        parent::addAttribute('cliente_id');
        parent::addAttribute('filial_id');
        parent::addAttribute('data');
        parent::addAttribute('saida_id');
    }

    
    /**
     * Method set_pessoa
     * Sample of usage: $compra->pessoa = $object;
     * @param $object Instance of Pessoa
     */
    public function set_pessoa(Pessoa $object)
    {
        $this->pessoa = $object;
        $this->ref_pessoa = $object->id;
    }
    
    /**
     * Method get_pessoa
     * Sample of usage: $compra->pessoa->attribute;
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
    
    
    /**
     * Method set_saida
     * Sample of usage: $compra->saida = $object;
     * @param $object Instance of Saida
     */
    public function set_saida(Saida $object)
    {
        $this->saida = $object;
        $this->ref_saida = $object->id;
    }
    
    /**
     * Method get_saida
     * Sample of usage: $compra->saida->attribute;
     * @returns Saida instance
     */
    public function get_saida()
    {
        // loads the associated object
        if (empty($this->saida))
            $this->saida = new Saida($this->ref_saida);
    
        // returns the associated object
        return $this->saida;
    }
    
    
    /**
     * Method set_filial
     * Sample of usage: $compra->filial = $object;
     * @param $object Instance of Filial
     */
    public function set_filial(Filial $object)
    {
        $this->filial = $object;
        $this->ref_filial = $object->id;
    }
    
    /**
     * Method get_filial
     * Sample of usage: $compra->filial->attribute;
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
    


}
