<?php
/**
 * CompraItem Active Record
 * @author  <your-name-here>
 */
class CompraItem extends TRecord
{
    const TABLENAME = 'public.compra_item';
    const PRIMARYKEY= 'id';
    const IDPOLICY =  'serial'; // {max, serial}
    
    
    private $compra;
    private $produto;

    /**
     * Constructor method
     */
    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('compra_id');
        parent::addAttribute('quantidade');
        parent::addAttribute('produto_id');
        parent::addAttribute('valor_unitario');
    }

    
    /**
     * Method set_compra
     * Sample of usage: $compra_item->compra = $object;
     * @param $object Instance of Compra
     */
    public function set_compra(Compra $object)
    {
        $this->compra = $object;
        $this->ref_compra = $object->id;
    }
    
    /**
     * Method get_compra
     * Sample of usage: $compra_item->compra->attribute;
     * @returns Compra instance
     */
    public function get_compra()
    {
        // loads the associated object
        if (empty($this->compra))
            $this->compra = new Compra($this->ref_compra);
    
        // returns the associated object
        return $this->compra;
    }
    
    
    /**
     * Method set_produto
     * Sample of usage: $compra_item->produto = $object;
     * @param $object Instance of Produto
     */
    public function set_produto(Produto $object)
    {
        $this->produto = $object;
        $this->ref_produto = $object->id;
    }
    
    /**
     * Method get_produto
     * Sample of usage: $compra_item->produto->attribute;
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
    


}
