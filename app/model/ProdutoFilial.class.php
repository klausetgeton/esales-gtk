<?php
/**
 * ProdutoFilial Active Record
 * @author  <your-name-here>
 */
class ProdutoFilial extends TRecord
{
    const TABLENAME = 'public.produto_filial';
    const PRIMARYKEY= 'id';
    const IDPOLICY =  'serial'; // {max, serial}
    
    
    private $produto;
    private $filial;

    /**
     * Constructor method
     */
    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('produto_id');
        parent::addAttribute('filial_id');
        parent::addAttribute('quantidade');
    }

    
    /**
     * Method set_produto
     * Sample of usage: $produto_filial->produto = $object;
     * @param $object Instance of Produto
     */
    public function set_produto(Produto $object)
    {
        $this->produto = $object;
        $this->ref_produto = $object->id;
    }
    
    /**
     * Method get_produto
     * Sample of usage: $produto_filial->produto->attribute;
     * @returns Produto instance
     */
    public function get_produto()
    {
        // loads the associated object
        if (empty($this->produto))
            $this->produto = new Produto($this->ref_produto);
    
        // returns the associated object
        return $this->produto;
    }
    
    
    /**
     * Method set_filial
     * Sample of usage: $produto_filial->filial = $object;
     * @param $object Instance of Filial
     */
    public function set_filial(Filial $object)
    {
        $this->filial = $object;
        $this->ref_filial = $object->id;
    }
    
    /**
     * Method get_filial
     * Sample of usage: $produto_filial->filial->attribute;
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
