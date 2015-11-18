<?php
/**
 * RoleList Listing
 * @author  <your name here>
 */
class RoleList extends TStandardList
{
    protected $datagrid; // listing
    protected $pageNavigation;
    
    /**
     * Class constructor
     * Creates the page, and the listing
     */
    public function __construct()
    {
        parent::__construct();
        
        // security check
        if (TSession::getValue('logged') !== TRUE)
        {
            throw new Exception(_t('Not logged'));
        }
        
        // security check
        TTransaction::open('library');
        if (User::newFromLogin(TSession::getValue('login'))-> role -> mnemonic !== 'ADMINISTRATOR')
        {
            throw new Exception(_t('Permission denied'));
        }
        TTransaction::close();
        
        // defines the database
        parent::setDatabase('library');
        
        // defines the active record
        parent::setActiveRecord('Role');
        
        // defines the filter field
        parent::setFilterField('description');
        
        
        // creates a DataGrid
        $this->datagrid = new TQuickGrid;
        $this->datagrid->setHeight(320);
        

        // creates the datagrid columns
        $this->datagrid->addQuickColumn('ID', 'id', 'right', 100, new TAction(array($this, 'onReload')), array('order', 'id'));
        $this->datagrid->addQuickColumn(_t('Description'), 'description', 'left', 200, new TAction(array($this, 'onReload')), array('order', 'description'));
        $this->datagrid->addQuickColumn(_t('Mnemonic'), 'mnemonic', 'left', 200, new TAction(array($this, 'onReload')), array('order', 'mnemonic'));

        // create the datagrid model
        $this->datagrid->createModel();
        
        // creates the page navigation
        $this->pageNavigation = new TPageNavigation;
        $this->pageNavigation->setAction(new TAction(array($this, 'onReload')));
        $this->pageNavigation->setWidth($this->datagrid->getWidth());
        
        // creates the page structure using a table
        $table = new TTable;
        // add a row to the datagrid
        $row = $table->addRow();
        $row->addCell($this->datagrid);
        // add a row for page navigation
        $row = $table->addRow();
        $row->addCell($this->pageNavigation);
        // add the table inside the page
        parent::add($table);
    }
}
?>