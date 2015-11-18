<?php
/**
 * AuthorList Listing
 *
 * @version    1.0
 * @package    samples
 * @subpackage library
 * @author     Pablo Dall'Oglio
 * @copyright  Copyright (c) 2006-2011 Adianti Solutions Ltd. (http://www.adianti.com.br)
 * @license    http://www.adianti.com.br/framework-license
 */
class AuthorList extends TPage
{
    private $form;     // registration form
    private $datagrid; // listing
    private $pageNavigation;
    private $loaded;
    
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
        
        // creates the form
        $this->form = new TForm('form_search_Author');
        $this->form->class = 'tform';
        
        // creates a table
        $table = new TTable;
        $table->width = '100%';
        
        // add the table inside the form
        $this->form->add($table);
        
        $table->addRowSet(new TLabel(_t('Authors')), '')->class='tformtitle';        
        
        // create the form fields
        $filter = new TEntry('name');
        $filter->setValue(TSession::getValue('Author_name'));
        
        // add a row for the filter field
        $row=$table->addRow();
        $row->addCell(new TLabel(_t('Name').': '));
        $row->addCell($filter);
        
        // create two action buttons to the form
        $find_button = new TButton('find');
        $new_button  = new TButton('new');
        // define the button actions
        $find_button->setAction(new TAction(array($this, 'onSearch')), _t('Find'));
        $find_button->setImage('ico_find.png');
        
        $new_button->setAction(new TAction(array('AuthorForm', 'onEdit')), _t('New'));
        $new_button->setImage('ico_new.png');
        
        $table->addRowSet('', array($find_button, $new_button))->class='tformaction';
        
        // define wich are the form fields
        $this->form->setFields(array($filter, $find_button, $new_button));
        
        // creates a DataGrid
        $this->datagrid = new TDataGrid;
        $this->datagrid->setHeight(320);
        

        // creates the datagrid columns
        $id   = new TDataGridColumn('id', 'id', 'right', 100);
        $name   = new TDataGridColumn('name', 'name', 'left', 200);


        // creates the datagrid actions
        $order1= new TAction(array($this, 'onReload'));
        $order2= new TAction(array($this, 'onReload'));


        // define the ordering parameters
        $order1->setParameter('order', 'id');
        $order2->setParameter('order', 'name');


        // assign the ordering actions
        $id->setAction($order1);
        $name->setAction($order2);


        // add the columns to the DataGrid
        $this->datagrid->addColumn($id);
        $this->datagrid->addColumn($name);

        
        // creates two datagrid actions
        $action1 = new TDataGridAction(array('AuthorForm', 'onEdit'));
        $action1->setLabel(_t('Edit'));
        $action1->setImage('ico_edit.png');
        $action1->setField('id');
        
        $action2 = new TDataGridAction(array($this, 'onDelete'));
        $action2->setLabel(_t('Delete'));
        $action2->setImage('ico_delete.png');
        $action2->setField('id');
        
        // add the actions to the datagrid
        $this->datagrid->addAction($action1);
        $this->datagrid->addAction($action2);
        
        // create the datagrid model
        $this->datagrid->createModel();
        
        // creates the page navigation
        $this->pageNavigation = new TPageNavigation;
        $this->pageNavigation->setAction(new TAction(array($this, 'onReload')));
        $this->pageNavigation->setWidth($this->datagrid->getWidth());
        
        // creates the page structure using a vbox
        $container = new TVBox;
        $container->add($this->form);
        $container->add($this->datagrid);
        $container->add($this->pageNavigation);
        
        // add the vbox inside the page
        parent::add($container);
    }
    
    /**
     * method onSearch()
     * Register the filter in the session when the user performs a search
     */
    function onSearch()
    {
        // get the search form data
        $data = $this->form->getData();
        
        // check if the user has filled the form
        if (isset($data->name))
        {
            // creates a filter using what the user has typed
            $filter = new TFilter('name', 'like', "%{$data->name}%");
            
            // stores the filter in the session
            TSession::setValue('Author_filter',   $filter);
            TSession::setValue('Author_name', $data->name);
            
            // fill the form with data again
            $this->form->setData($data);
        }
        
        $param=array();
        $param['offset']    =0;
        $param['first_page']=1;
        $this->onReload($param);
    }
    
    /**
     * method onReload()
     * Load the datagrid with the database objects
     */
    function onReload($param = NULL)
    {
        try
        {
            // open a transaction with database 'library'
            TTransaction::open('library');
            
            // creates a repository for Author
            $repository = new TRepository('Author');
            $limit = 10;
            // creates a criteria
            $criteria = new TCriteria;
            $criteria->setProperties($param); // order, offset
            $criteria->setProperty('limit', $limit);
            
            if (TSession::getValue('Author_filter'))
            {
                // add the filter stored in the session to the criteria
                $criteria->add(TSession::getValue('Author_filter'));
            }
            
            // load the objects according to criteria
            $objects = $repository->load($criteria);
            
            $this->datagrid->clear();
            if ($objects)
            {
                // iterate the collection of active records
                foreach ($objects as $object)
                {
                    // add the object inside the datagrid
                    $this->datagrid->addItem($object);
                }
            }
            
            // reset the criteria for record count
            $criteria->resetProperties();
            $count= $repository->count($criteria);
            
            $this->pageNavigation->setCount($count); // count of records
            $this->pageNavigation->setProperties($param); // order, page
            $this->pageNavigation->setLimit($limit); // limit
            
            // close the transaction
            TTransaction::close();
            $this->loaded = true;
        }
        catch (Exception $e) // in case of exception
        {
            // shows the exception error message
            new TMessage('error', '<b>Error</b> ' . $e->getMessage());
            
            // undo all pending operations
            TTransaction::rollback();
        }
    }
    
    /**
     * method onDelete()
     * executed whenever the user clicks at the delete button
     * Ask if the user really wants to delete the record
     */
    function onDelete($param)
    {
        // get the parameter $key
        $key=$param['key'];
        
        // define two actions
        $action = new TAction(array($this, 'Delete'));
        
        // define the action parameters
        $action->setParameter('key', $key);
        
        // shows a dialog to the user
        new TQuestion(TAdiantiCoreTranslator::translate('Do you really want to delete ?'), $action);
    }
    
    /**
     * method Delete()
     * Delete a record
     */
    function Delete($param)
    {
        try
        {
            // get the parameter $key
            $key=$param['key'];
            // open a transaction with database 'library'
            TTransaction::open('library');
            
            // instantiates object Author
            $object = new Author($key);
            
            // deletes the object from the database
            $object->delete();
            
            // close the transaction
            TTransaction::close();
            
            // reload the listing
            $this->onReload();
            // shows the success message
            new TMessage('info', TAdiantiCoreTranslator::translate('Record deleted'));
        }
        catch (Exception $e) // in case of exception
        {
            // shows the exception error message
            new TMessage('error', '<b>Error</b> ' . $e->getMessage());
            
            // undo all pending operations
            TTransaction::rollback();
        }
    }
    
    /**
     * method show()
     * Shows the page
     */
    function show()
    {
        // check if the datagrid is already loaded
        if (!$this->loaded)
        {
            $this->onReload();
        }
        parent::show();
    }
}
?>
