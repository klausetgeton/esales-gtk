<?php
/**
 * CaixaList Listing
 * @author  <your name here>
 */
class CaixaList extends TStandardList
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
        parent::setActiveRecord('Caixa');   // defines the active record
        parent::setDefaultOrder('id', 'asc');         // defines the default order
        parent::addFilterField('id', 'like'); // add a filter field
        parent::addFilterField('descricao', 'like'); // add a filter field
        parent::addFilterField('pessoa_id', 'like'); // add a filter field
        parent::addFilterField('saldo_inicial', 'like'); // add a filter field
        parent::addFilterField('filial_id', 'like'); // add a filter field
        
        // creates the form, with a table inside
        $this->form = new TQuickForm('form_search_Caixa');
        $this->form->class = 'tform'; // CSS class
        $this->form->setFormTitle('Caixa');
        

        // create the form fields
        $id                             = new TEntry('id');
        $descricao                      = new TEntry('descricao');
        $pessoa_id                      = new TEntry('pessoa_id');
        $saldo_inicial                  = new TEntry('saldo_inicial');
        $filial_id                      = new TEntry('filial_id');


        // add the fields
        $this->form->addQuickField('id', $id,  100);
        $this->form->addQuickField('Descrição', $descricao,  200);
        $this->form->addQuickField('Pessoa', $pessoa_id,  100);
        $this->form->addQuickField('Saldo', $saldo_inicial,  200);
        $this->form->addQuickField('Filial', $filial_id,  100);



        
        // keep the form filled during navigation with session data
        $this->form->setData( TSession::getValue('Caixa_filter_data') );
        
        // add the search form actions
        $this->form->addQuickAction('Buscar', new TAction(array($this, 'onSearch')), 'ico_find.png');
        $this->form->addQuickAction('Novo',  new TAction(array('CaixaForm', 'onEdit')), 'ico_new.png');
        
        // creates a DataGrid
        $this->datagrid = new TQuickGrid;
        $this->datagrid->setHeight(320);
        

        // creates the datagrid columns
        $id = $this->datagrid->addQuickColumn('id', 'id', 'right', 100);
        $descricao = $this->datagrid->addQuickColumn('Descrição', 'descricao', 'left', 200);
        $pessoa_id = $this->datagrid->addQuickColumn('Pessoa', 'pessoa_id', 'right', 100);
        $saldo_inicial = $this->datagrid->addQuickColumn('Saldo', 'saldo_inicial', 'left', 200);
        $filial_id = $this->datagrid->addQuickColumn('Filial', 'filial_id', 'right', 100);

        
        // create the datagrid actions
        $edit_action   = new TDataGridAction(array('CaixaForm', 'onEdit'));
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
