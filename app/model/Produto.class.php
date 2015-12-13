<?php
/**
 * Produto Active Record
 * @author  <your-name-here>
 */
class Produto extends TRecord
{
    const TABLENAME = 'public.produto';
    const PRIMARYKEY= 'id';
    const IDPOLICY =  'serial'; // {max, serial}
    
    
    private $marca;

    /**
     * Constructor method
     */
    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('descricao');
        parent::addAttribute('quantidade');
        parent::addAttribute('preco_compra');
        parent::addAttribute('preco_venda');
        parent::addAttribute('marca_id');
    }

    
    /**
     * Method set_marca
     * Sample of usage: $produto->marca = $object;
     * @param $object Instance of Marca
     */
    public function set_marca(Marca $object)
    {
        $this->marca = $object;
        $this->ref_marca = $object->id;
    }
    
    /**
     * Method get_marca
     * Sample of usage: $produto->marca->attribute;
     * @returns Marca instance
     */
    public function get_marca()
    {
        // loads the associated object
        if (empty($this->marca))
            $this->marca = new Marca($this->ref_marca);
    
        // returns the associated object
        return $this->marca;
    }
    


}
