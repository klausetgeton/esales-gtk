<?php
/**
 * SaidaList Listing
 * @author  <your name here>
 */
class SaidaList extends TStandardList
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
        parent::setActiveRecord('Saida');   // defines the active record
        parent::setDefaultOrder('id', 'asc');         // defines the default order
        parent::addFilterField('id', 'like'); // add a filter field
        parent::addFilterField('data', 'like'); // add a filter field
        parent::addFilterField('valor', 'like'); // add a filter field
        parent::addFilterField('caixa_id', 'like'); // add a filter field
        
        // creates the form, with a table inside
        $this->form = new TQuickForm('form_search_Saida');
        $this->form->class = 'tform'; // CSS class
        $this->form->setFormTitle('Saida');
        

        // create the form fields
        $id                             = new TEntry('id');
        $data                           = new TEntry('data');
        $valor                          = new TEntry('valor');
        $caixa_id                       = new TEntry('caixa_id');


        // add the fields
        $this->form->addQuickField('Código', $id,  100);
        $this->form->addQuickField('Data', $data,  100);
        $this->form->addQuickField('Valor', $valor,  200);
        $this->form->addQuickField('Caixa', $caixa_id,  100);



        
        // keep the form filled during navigation with session data
        $this->form->setData( TSession::getValue('Saida_filter_data') );
        
        // add the search form actions
        $this->form->addQuickAction('Buscar', new TAction(array($this, 'onSearch')), 'ico_find.png');
        $this->form->addQuickAction('Novo',  new TAction(array('SaidaForm', 'onEdit')), 'ico_new.png');
        
        // creates a DataGrid
        $this->datagrid = new TQuickGrid;
        $this->datagrid->setHeight(320);
        

        // creates the datagrid columns
        $id = $this->datagrid->addQuickColumn('id', 'id', 'right', 100);
        $data = $this->datagrid->addQuickColumn('data', 'data', 'left', 100);
        $valor = $this->datagrid->addQuickColumn('valor', 'valor', 'left', 200);
        $caixa_id = $this->datagrid->addQuickColumn('caixa_id', 'caixa_id', 'right', 100);

        
        // create the datagrid actions
        $edit_action   = new TDataGridAction(array('SaidaForm', 'onEdit'));
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
