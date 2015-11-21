<?php
/**
 * VendaItem Active Record
 * @author  <your-name-here>
 */
class VendaItem extends TRecord
{
    const TABLENAME = 'public.venda_item';
    const PRIMARYKEY= 'id';
    const IDPOLICY =  'serial'; // {max, serial}
    
    
    private $produto;
    private $venda;

    /**
     * Constructor method
     */
    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('quantidade');
        parent::addAttribute('produto_id');
        parent::addAttribute('venda_id');
        parent::addAttribute('valor_unitario');
    }

    
    /**
     * Method set_produto
     * Sample of usage: $venda_item->produto = $object;
     * @param $object Instance of Produto
     */
    public function set_produto(Produto $object)
    {
        $this->produto = $object;
        $this->ref_produto = $object->id;
    }
    
    /**
     * Method get_produto
     * Sample of usage: $venda_item->produto->attribute;
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
     * Method set_venda
     * Sample of usage: $venda_item->venda = $object;
     * @param $object Instance of Venda
     */
    public function set_venda(Venda $object)
    {
        $this->venda = $object;
        $this->ref_venda = $object->id;
    }
    
    /**
     * Method get_venda
     * Sample of usage: $venda_item->venda->attribute;
     * @returns Venda instance
     */
    public function get_venda()
    {
        // loads the associated object
        if (empty($this->venda))
            $this->venda = new Venda($this->ref_venda);
    
        // returns the associated object
        return $this->venda;
    }
    


}
