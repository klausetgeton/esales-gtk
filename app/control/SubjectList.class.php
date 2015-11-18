<?php
/**
 * SubjectList Listing
 *
 * @version    1.0
 * @package    samples
 * @subpackage library
 * @author     Pablo Dall'Oglio
 * @copyright  Copyright (c) 2006-2011 Adianti Solutions Ltd. (http://www.adianti.com.br)
 * @license    http://www.adianti.com.br/framework-license
 */
class SubjectList extends TStandardList
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
        
        // security check
        if (TSession::getValue('logged') !== TRUE)
        {
            throw new Exception(_t('Not logged'));
        }
        
        // security check
        TTransaction::open('library');
        if (User::newFromLogin(TSession::getValue('login'))-> role -> mnemonic !== 'LIBRARIAN')
        {
            throw new Exception(_t('Permission denied'));
        }
        TTransaction::close();
        
        // defines the database
        parent::setDatabase('library');
        
        // defines the active record
        parent::setActiveRecord('Subject');
        
        // defines the filter field
        parent::setFilterField('name');
        
        // creates the form
        $this->form = new TForm('form_search_Subject');
        $this->form->class = 'tform';
        
        // creates a table
        $table = new TTable;
        $table->width = '100%';
        $table->addRowSet(new TLabel(_t('Subjects')), '')->class='tformtitle';
        
        // add the table inside the form
        $this->form->add($table);
        
        // create the form fields
        $name = new TEntry('name');
        $name->setValue(TSession::getValue('Subject_name'));
        
        // add a row for the field name
        $row=$table->addRow();
        $row->addCell(new TLabel(_t('Name').':'));
        $row->addCell($name);
        
        // create two action buttons to the form
        $find_button = new TButton('find');
        $new_button  = new TButton('new');
        // define the button actions
        $find_button->setAction(new TAction(array($this, 'onSearch')), _t('Find'));
        $find_button->setImage('ico_find.png');
        
        $new_button->setAction(new TAction(array('SubjectForm', 'onEdit')), _t('New'));
        $new_button->setImage('ico_new.png');
        
        $table->addRowSet('', array($find_button, $new_button))->class='tformaction';
        
        // define wich are the form fields
        $this->form->setFields(array($name, $find_button, $new_button));
        
        // creates a DataGrid
        $this->datagrid = new TQuickGrid;
        $this->datagrid->setHeight(320);
        

        // creates the datagrid columns
        $this->datagrid->addQuickColumn(_t('Code'), 'id', 'right', 100, new TAction(array($this, 'onReload')), array('order', 'id'));
        $this->datagrid->addQuickColumn(_t('Name'), 'name', 'left', 200, new TAction(array($this, 'onReload')), array('order', 'name'));

        
        // add the actions to the datagrid
        $this->datagrid->addQuickAction(_t('Edit'), new TDataGridAction(array('SubjectForm', 'onEdit')), 'id', 'ico_edit.png');
        $this->datagrid->addQuickAction(_t('Delete'), new TDataGridAction(array($this, 'onDelete')), 'id', 'ico_delete.png');
        
        // create the datagrid model
        $this->datagrid->createModel();
        
        // creates the page navigation
        $this->pageNavigation = new TPageNavigation;
        $this->pageNavigation->setAction(new TAction(array($this, 'onReload')));
        $this->pageNavigation->setWidth($this->datagrid->getWidth());
        
        // creates the page structure using a table
        $container = new TVBox;
        $container->add($this->form);
        $container->add($this->datagrid);
        $container->add($this->pageNavigation);
        
        // add the vbox inside the page
        parent::add($container);
    }
}
?>
