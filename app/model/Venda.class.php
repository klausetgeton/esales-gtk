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
    private $produtos;
    private $vendedor;
    private $comprador;

    /**
     * Constructor method
     */
    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('vendedor_id');
        parent::addAttribute('comprador_id');
        parent::addAttribute('filial_id');
        parent::addAttribute('data_venda');
    }

    /**
     * Returns the book author name
     */
    function get_vendedor_nome()
    {
        if (empty($this->vendedor))
            $this->vendedor = new Pessoa($this->vendedor_id);
        
        return $this->vendedor->nome;
    }

    /**
     * Returns the book author name
     */
    function get_comprador_nome()
    {
        if (empty($this->comprador))
            $this->comprador = new Pessoa($this->comprador_id);
        
        return $this->comprador->nome;
    }

    /**
     * Returns the book author name
     */
    function get_filial_nome()
    {
        if (empty($this->filial))
            $this->filial = new Filial($this->filial_id);
        
        return $this->filial->nome;
    }

        /**
     * Reset aggregates
     */
    public function clearParts()
    {
        $this->produtos    = array();
    }

    /**
     * Return Items composition
     */
    public function getProdutos()
    {
        return $this->produtos;
    }
    

    /**
     * Composition with Item
     */
    public function addProduto($produto)
    {
        $this->produtos[] = $produto;
    }

    // /**
    //  * Load the object and the aggregates
    //  */
    // public function load($id)
    // {
    //     $produto_rep      = new TRepository('Produto');
        
    //     $criteria = new TCriteria;
    //     $criteria->add(new TFilter('id', '=', $id));
     
    //     // load the Item composition
    //     $produtos = $produto_rep->load($criteria);
    //     if ($produtos)
    //     {
    //         foreach ($produtos as $produto)
    //         {
    //             $this->addProduto($produto);
    //         }
    //     }
        
    //     // load the object itself
    //     return parent::load($id);
    // }

    public function load($id)
    {
        $venda_rep      = new TRepository('VendaItem');
        $produto          = new TRepository('Produto');
        
        $criteria = new TCriteria;
        $criteria->add(new TFilter('venda_id', '=', $id));
     
        // load the Item composition
        $vendasitens = $venda_rep->load($criteria);
        if ($vendasitens)
        {
            foreach ($vendasitens as $item)
            {
                $criteria = new TCriteria;
                $criteria->add(new TFilter('id', '=', $item-> produto_id));
                $prods = new Produto;
                $prods = $produto->load($criteria);
                $teste = new stdClass();
                $teste-> id = $prods[0]-> id;
                $teste-> nome = $prods[0]-> descricao;
                $teste-> quantidade = $item-> quantidade;
                
                $this->addProduto($teste);
            }
        }
        
        // load the object itself
        return parent::load($id);
    }

    /**
     * Stores the book and the aggregates (authors, subjects, items)
     */
    public function store()
    {
        // stores the Book
        parent::store();

        // delete the aggregates
        $criteria = new TCriteria;
        $criteria->add(new TFilter('venda_id', '=', $this->id));
        
        $repository = new TRepository('VendaItem');
        $repository->delete($criteria);
        
        // store the items
        if ($this->produtos)
        {
            foreach ($this->produtos as $produto)
            {
                $prod = new Produto($produto-> id);
                $venda_produto = new VendaItem;
                $venda_produto-> venda_id   = $this-> id;
                $venda_produto-> produto_id = $produto-> id;
                $venda_produto-> quantidade = $produto-> quantidade;
                $estoque = $prod-> quantidade;

                $prod-> quantidade = $prod-> quantidade - $venda_produto-> quantidade;

                if($prod-> quantidade < 0)
                {
                    throw new Exception("Quantidade não disponível para venda, disponível :" . $estoque);
                }

                $prod->store();
                $venda_produto->store();
            }
        }

        // // store the authors
        // if ($this->authors)
        // {
        //     foreach ($this->authors as $author)
        //     {
        //         $book_author = new BookAuthor;
        //         $book_author-> book_id    = $this-> id;
        //         $book_author-> author_id  = $author-> id;
        //         $book_author->store();
        //     }
        // }


    }

    /**
     * Delete the book and its aggregates
     */
    public function delete($id = NULL)
    {
        $id = isset($id) ? $id : $this->{'id'};
        
        $criteria = new TCriteria;
        $criteria->add(new TFilter('book_id', '=', $id));
        
        $repository = new TRepository('Item');
        $repository->delete($criteria);
        
        // delete the object itself
        parent::delete($id);
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
