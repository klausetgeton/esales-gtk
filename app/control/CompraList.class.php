<?php
/**
 * CompraList Listing
 * @author  <your name here>
 */
class CompraList extends TStandardList
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
        parent::setActiveRecord('Compra');   // defines the active record
        parent::setDefaultOrder('id', 'asc');         // defines the default order
        parent::addFilterField('id', 'like'); // add a filter field
        parent::addFilterField('vendedor_id', 'like'); // add a filter field
        parent::addFilterField('cliente_id', 'like'); // add a filter field
        parent::addFilterField('filial_id', 'like'); // add a filter field
        parent::addFilterField('data_compra', 'like'); // add a filter field
        
        // creates the form, with a table inside
        $this->form = new TQuickForm('form_search_Compra');
        $this->form->class = 'tform'; // CSS class
        $this->form->setFormTitle('Compra');
        

        // create the form fields
        $id                             = new TEntry('id');
        $vendedor_id                    = new TEntry('vendedor_id');
        $cliente_id                     = new TEntry('cliente_id');
        $filial_id                      = new TEntry('filial_id');
        $data_compra                    = new TEntry('data_compra');


        // add the fields
        $this->form->addQuickField('id', $id,  100);
        $this->form->addQuickField('Vendedor', $vendedor_id,  100);
        $this->form->addQuickField('Cliente', $cliente_id,  100);
        $this->form->addQuickField('Filial', $filial_id,  100);
        $this->form->addQuickField('Data compra', $data_compra,  100);



        
        // keep the form filled during navigation with session data
        $this->form->setData( TSession::getValue('Compra_filter_data') );
        
        // add the search form actions
        $this->form->addQuickAction(_t('Find'), new TAction(array($this, 'onSearch')), 'ico_find.png');
        $this->form->addQuickAction(_t('New'),  new TAction(array('CompraForm', 'onEdit')), 'ico_new.png');
        
        // creates a DataGrid
        $this->datagrid = new TQuickGrid;
        $this->datagrid->setHeight(320);
        

        // creates the datagrid columns
        $id = $this->datagrid->addQuickColumn('id', 'id', 'right', 100);
        $vendedor_id = $this->datagrid->addQuickColumn('Vendedor', 'vendedor_id', 'right', 100);
        $cliente_id = $this->datagrid->addQuickColumn('Cliente', 'cliente_id', 'right', 100);
        $filial_id = $this->datagrid->addQuickColumn('Filial', 'filial_id', 'right', 100);
        $data_compra = $this->datagrid->addQuickColumn('Data compra', 'data_compra', 'left', 100);

        
        // create the datagrid actions
        $edit_action   = new TDataGridAction(array('CompraForm', 'onEdit'));
        $delete_action = new TDataGridAction(array($this, 'onDelete'));
        
        // add the actions to the datagrid
        $this->datagrid->addQuickAction(_t('Edit'), $edit_action, 'id', 'ico_edit.png');
        $this->datagrid->addQuickAction(_t('Delete'), $delete_action, 'id', 'ico_delete.png');
        
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
