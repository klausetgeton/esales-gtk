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
        parent::addAttribute('entrada_id');
    }

        /**
     * Reset aggregates
     */
    public function clearParts()
    {
        $this->produtos    = array();
    }

    /**
     * Composition with Item
     */
    public function addProduto(Produto $produto)
    {
        $this->produtos[] = $produto;
    }

    /**
     * Load the object and the aggregates
     */
    public function load($id)
    {
        $produto_rep      = new TRepository('Produto');
        
        $criteria = new TCriteria;
        $criteria->add(new TFilter('id', '=', $id));
     
        // load the Item composition
        $produtos = $produto_rep->load($criteria);
        if ($produtos)
        {
            foreach ($produtos as $produto)
            {
                $this->addProduto($produto);
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
        
        // store the items
        if ($this->produtos)
        {
            foreach ($this->produtos as $produto)
            {
                $venda_produto = new VendaItem;
                $venda_produto-> venda_id   = $this-> id;
                $venda_produto-> produto_id = $produto-> id;
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
        
        $repository = new TRepository('BookAuthor');
        $repository->delete($criteria);
        $repository = new TRepository('BookSubject');
        $repository->delete($criteria);
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
