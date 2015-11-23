<?php
/**
 * FilialList Listing
 * @author  <your name here>
 */
class FilialList extends TStandardList
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
        parent::setActiveRecord('Filial');   // defines the active record
        parent::setDefaultOrder('id', 'asc');         // defines the default order
        parent::addFilterField('id', 'like'); // add a filter field
        parent::addFilterField('nome', 'like'); // add a filter field
        parent::addFilterField('endereco', 'like'); // add a filter field
        parent::addFilterField('numero', 'like'); // add a filter field
        parent::addFilterField('email', 'like'); // add a filter field
        parent::addFilterField('telefone', 'like'); // add a filter field
        parent::addFilterField('fl_matriz', 'like'); // add a filter field
        
        // creates the form, with a table inside
        $this->form = new TQuickForm('form_search_Filial');
        $this->form->class = 'tform'; // CSS class
        $this->form->setFormTitle('Filial');
        

        // create the form fields
        $id                             = new TEntry('id');
        $nome                           = new TEntry('nome');
        $endereco                       = new TEntry('endereco');
        $numero                         = new TEntry('numero');
        $email                          = new TEntry('email');
        $telefone                       = new TEntry('telefone');
        $fl_matriz                      = new TEntry('fl_matriz');


        // add the fields
        $this->form->addQuickField('id', $id,  100);
        $this->form->addQuickField('Nome', $nome,  200);
        $this->form->addQuickField('Endereço', $endereco,  200);
        $this->form->addQuickField('Número', $numero,  200);
        $this->form->addQuickField('Email', $email,  200);
        $this->form->addQuickField('Telefone', $telefone,  200);
        $this->form->addQuickField('Matriz', $fl_matriz,  200);



        
        // keep the form filled during navigation with session data
        $this->form->setData( TSession::getValue('Filial_filter_data') );
        
        // add the search form actions
        $this->form->addQuickAction('Buscar', new TAction(array($this, 'onSearch')), 'ico_find.png');
        $this->form->addQuickAction('Novo',  new TAction(array('FilialForm', 'onEdit')), 'ico_new.png');
        
        // creates a DataGrid
        $this->datagrid = new TQuickGrid;
        $this->datagrid->setHeight(320);
        

        // creates the datagrid columns
        $id = $this->datagrid->addQuickColumn('id', 'id', 'right', 100);
        $nome = $this->datagrid->addQuickColumn('Nome', 'nome', 'left', 200);
        $endereco = $this->datagrid->addQuickColumn('endereco', 'endereco', 'left', 200);
        $numero = $this->datagrid->addQuickColumn('numero', 'numero', 'left', 200);
        $email = $this->datagrid->addQuickColumn('email', 'email', 'left', 200);
        $telefone = $this->datagrid->addQuickColumn('telefone', 'telefone', 'left', 200);
        $fl_matriz = $this->datagrid->addQuickColumn('fl_matriz', 'fl_matriz', 'left', 200);

        
        // create the datagrid actions
        $edit_action   = new TDataGridAction(array('FilialForm', 'onEdit'));
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
        $container = TVBox::pack( $this->form, $this->datagrid, $this->pageNavigation);
        parent::add($container);
    }
}
