<?php
/**
 * BookList Listing
 *
 * @version    1.0
 * @package    samples
 * @subpackage library
 * @author     Pablo Dall'Oglio
 * @copyright  Copyright (c) 2006-2011 Adianti Solutions Ltd. (http://www.adianti.com.br)
 * @license    http://www.adianti.com.br/framework-license
 */
class BookList extends TPage
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
        $this->form = new TForm('form_search_Book');
        $this->form->class = 'tform';
        // creates a table
        $table = new TTable;
        $table->width = '100%';
        
        $table->addRowSet(new TLabel(_t('Books')), '')->class='tformtitle';
        
        // add the table inside the form
        $this->form->add($table);
        
        // create the form fields
        $title             = new TEntry('title');
        $author_id         = new TSeekButton('author_id');
        $author_name       = new TEntry('author_name');
        $collection_id     = new TDBCombo('collection_id', 'library', 'Collection', 'id', 'description');
        $classification_id = new TDBCombo('classification_id', 'library', 'Classification', 'id', 'description');
        
        $title->setValue(TSession::getValue('Book_title'));
        $author_id->setValue(TSession::getValue('Book_author_id'));
        $author_name->setValue(TSession::getValue('Book_author_name'));
        $collection_id->setValue(TSession::getValue('Book_collection_id'));
        $classification_id->setValue(TSession::getValue('Book_classification_id'));
        $author_name->setEditable(FALSE);
        
        $title->setSize(320);
        $author_id->setSize(100);
        $obj = new TStandardSeek;
        $action = new TAction(array($obj, 'onSetup'));
        $action->setParameter('database',      'library');
        $action->setParameter('parent',        'form_search_Book');
        $action->setParameter('model',         'Author');
        $action->setParameter('display_field', 'name');
        $action->setParameter('receive_key',   'author_id');
        $action->setParameter('receive_field', 'author_name');
        $author_id->setAction($action);
        
        // add a row for the title field
        $row=$table->addRow();
        $row->addCell(new TLabel(_t('Title') . ': '));
        $cell=$row->addCell($title);
        
        // add a row for the title field
        $row=$table->addRow();
        $row->addCell(new TLabel(_t('Author') . ': '));
        $row->addMultiCell($author_id, $author_name);
        
        // add a row for the title field
        $row=$table->addRow();
        $row->addCell(new TLabel(_t('Collection') . ': '));
        $cell=$row->addCell($collection_id);
        
        // add a row for the title field
        $row=$table->addRow();
        $row->addCell(new TLabel(_t('Classification') . ': '));
        $cell=$row->addCell($classification_id);
        
        // create two action buttons to the form
        $find_button = new TButton('find');
        $new_button  = new TButton('new');
        // define the button actions
        $find_button->setAction(new TAction(array($this, 'onSearch')), _t('Find'));
        $find_button->setImage('ico_find.png');
        
        $new_button->setAction(new TAction(array('BookForm', 'onEdit')), _t('New'));
        $new_button->setImage('ico_new.png');
        
        $table->addRowSet('', array($find_button, $new_button))->class='tformaction';
        
        // define wich are the form fields
        $this->form->setFields(array($title, $author_id, $author_name, $collection_id, $classification_id, $find_button, $new_button));
        
        // creates a DataGrid
        $this->datagrid = new TDataGrid;
        $this->datagrid->setHeight(280);
        
        // creates the datagrid columns
        $id                = new TDataGridColumn('id', _t('Code'), 'right', 50);
        $title             = new TDataGridColumn('title', _t('Title'), 'left', 200);
        $main_author       = new TDataGridColumn('author_name', _t('Author'), 'left', 160);
        $edition           = new TDataGridColumn('edition', _t('Edition'), 'left', 50);
        $call              = new TDataGridColumn('call_number', _t('Call'), 'left', 80);

        // creates the datagrid actions
        $order1= new TAction(array($this, 'onReload'));
        $order2= new TAction(array($this, 'onReload'));

        // define the ordering parameters
        $order1->setParameter('order', 'id');
        $order2->setParameter('order', 'title');

        // assign the ordering actions
        $id->setAction($order1);
        $title->setAction($order2);
        
        // add the columns to the DataGrid
        $this->datagrid->addColumn($id);
        $this->datagrid->addColumn($title);
        $this->datagrid->addColumn($main_author);
        $this->datagrid->addColumn($edition);
        $this->datagrid->addColumn($call);
        
        // creates two datagrid actions
        $action1 = new TDataGridAction(array('BookForm', 'onEdit'));
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
        $filters = array();
        TSession::setValue('Book_title', '');
        TSession::setValue('Book_author_id', '');
        TSession::setValue('Book_author_name', '');
        TSession::setValue('Book_collection_id', '');
        TSession::setValue('Book_classification_id', '');
        TSession::setValue('Book_filters',   array());
        
        // check if the user has filled the form
        if ($data->title)
        {
            // creates a filter using what the user has typed
            $filter = new TFilter('title', 'like', "%{$data->title}%");
            
            // stores the filter in the session
            TSession::setValue('Book_title', $data->title);
            $filters[] = $filter;
        }
        
        if ($data->author_id)
        {
            // creates a filter using what the user has typed
            $filter = new TFilter('author_id', '=', "{$data->author_id}");
            
            // stores the filter in the session
            TSession::setValue('Book_author_id', $data->author_id);
            TSession::setValue('Book_author_name', $data->author_name);
            $filters[] = $filter;
        }
        
        if ($data->collection_id)
        {
            // creates a filter using what the user has typed
            $filter = new TFilter('collection_id', '=', "{$data->collection_id}");
            
            // stores the filter in the session
            TSession::setValue('Book_collection_id', $data->collection_id);
            $filters[] = $filter;
        }
        
        if ($data->classification_id)
        {
            // creates a filter using what the user has typed
            $filter = new TFilter('classification_id', '=', "{$data->classification_id}");
            
            // stores the filter in the session
            TSession::setValue('Book_classification_id', $data->classification_id);
            $filters[] = $filter;
        }
        
        if ($filters)
        {
            TSession::setValue('Book_filters',   $filters);
        }
        
        // fill the form with data again
        $this->form->setData($data);
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
            
            // creates a repository for Book
            $repository = new TRepository('Book');
            $limit = 10;
            // creates a criteria
            $criteria = new TCriteria;
            $criteria->setProperties($param); // order, offset
            $criteria->setProperty('limit', $limit);
            
            if (TSession::getValue('Book_filters'))
            {
                foreach (TSession::getValue('Book_filters') as $filter)
                {
                    if ($filter instanceof TFilter)
                    {
                        // add the filter stored in the session to the criteria
                        $criteria->add($filter);
                    }
                }
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
            
            // instantiates object Book
            $object = new Book($key);
            
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
