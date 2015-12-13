<?php
/**
 * ProdutoList Listing
 * @author  <your name here>
 */
class ProdutoList extends TStandardList
{
    protected $form;     // registration form
    protected $datagrid; // listing
    protected $pageNavigation;
    
    /**
     * Class constructor
     * Creates the page, the form and the listing
     */
    public function __construct()
    {
        parent::__construct();
        
        parent::setDatabase('esales');            // defines the database
        parent::setActiveRecord('Produto');   // defines the active record
        // parent::setDefaultOrder('id', 'asc');         // defines the default order
        // parent::addFilterField('id', 'like'); // add a filter field
        // parent::addFilterField('descricao', 'like'); // add a filter field
        // parent::addFilterField('preco_compra', 'like'); // add a filter field
        // parent::addFilterField('preco_venda', 'like'); // add a filter field
        // parent::addFilterField('marca_id', 'like'); // add a filter field
        
        // creates the form, with a table inside
        $this->form = new TQuickForm('form_search_Produto');
        $this->form->class = 'tform'; // CSS class
        $this->form->setFormTitle('Produto');
        

        // create the form fields
        $id                             = new TEntry('id');
        $descricao                      = new TEntry('descricao');
        $preco_compra                   = new TEntry('preco_compra');
        $preco_venda                    = new TEntry('preco_venda');
        $marca_id                       = new TEntry('marca_id');
        $quantidade                     = new TEntry('quantidade');


        // add the fields
        $this->form->addQuickField('id', $id,  100);
        $this->form->addQuickField('descricao', $descricao,  200);

        
        // keep the form filled during navigation with session data
        $this->form->setData( TSession::getValue('Produto_filter_data') );
        
        // add the search form actions
        $this->form->addQuickAction('Buscar', new TAction(array($this, 'onSearch')), 'ico_find.png');
        $this->form->addQuickAction('Novo',  new TAction(array('ProdutoForm', 'onEdit')), 'ico_new.png');
        
        // creates a DataGrid
        $this->datagrid = new TQuickGrid;
        $this->datagrid->setHeight(320);
        

        // creates the datagrid columns
        $id = $this->datagrid->addQuickColumn('Codigo', 'id', 'right', 30);
        $descricao = $this->datagrid->addQuickColumn('Descricao', 'descricao', 'left', 200);
        $quantidade = $this->datagrid->addQuickColumn('Quantidade', 'quantidade', 'right', 60);

        
        // create the datagrid actions
        $edit_action   = new TDataGridAction(array('ProdutoForm', 'onEdit'));
        $delete_action = new TDataGridAction(array($this, 'onDelete'));
        
        // add the actions to the datagrid
        $this->datagrid->addQuickAction('Editar', $edit_action, 'id', 'ico_edit.png');
        $this->datagrid->addQuickAction('Deletar', $delete_action, 'id', 'ico_delete.png');
        
        // create the datagrid model
        $this->datagrid->createModel();
        
        // create the page navigation
        $this->pageNavigation = new TPageNavigation;
        $this->pageNavigation->setAction(new TAction(array($this, 'onReload')));
        $this->pageNavigation->setWidth($this->datagrid->getWidth());
        
        // create the page container
        $container = new TVBox;
        $container->add($this->form);
        $container->add($this->datagrid);
        $container->add($this->pageNavigation);
        parent::add($container);
    }
}
