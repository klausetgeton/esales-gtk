<?php
/**
 * CheckOutForm Registration
 *
 * @version    1.0
 * @package    samples
 * @subpackage library
 * @author     Pablo Dall'Oglio
 * @copyright  Copyright (c) 2006-2011 Adianti Solutions Ltd. (http://www.adianti.com.br)
 * @license    http://www.adianti.com.br/framework-license
 */
class CheckOutForm extends TPage
{
    private $form;
    private $datagrid;
    private $pageNavigation;
    private $loaded;
    
    /**
     * Class constructor
     * Creates the page and the registration form
     */
    function __construct()
    {
        parent::__construct();
        
        // security check
        if (TSession::getValue('logged') !== TRUE)
        {
            throw new Exception(_t('Not logged'));
        }
        
        // security check
        TTransaction::open('library');
        if ( (User::newFromLogin(TSession::getValue('login'))-> role -> mnemonic !== 'OPERATOR') AND
             (User::newFromLogin(TSession::getValue('login'))-> role -> mnemonic !== 'LIBRARIAN') )
        {
            throw new Exception(_t('Permission denied'));
        }
        TTransaction::close();
        
        // creates the form
        $this->form = new TForm('form_Loan');
        
        try
        {
            // TUIBuilder object
            $ui = new TUIBuilder(500,500);
            $ui->setController($this);
            $ui->setForm($this->form);
            
            // reads the xml form
            $ui->parseFile('app/forms/checkout.form.xml');
            
            $ui->getWidget('member_label')->setValue(_t('Member'));
            $ui->getWidget('barcode_label')->setValue(_t('Barcode'));
            $ui->getWidget('add_button')->setLabel(_t('Add'));
            $ui->getWidget('apply_button')->setLabel(_t('Apply'));
            $ui->getWidget('barcode')->setLabel(_t('Barcode'));
            $ui->getWidget('book_title')->setLabel(_t('Title'));
            
            // get the interface widgets
            $fields = $ui->getWidgets();
            // look for the TDataGrid object
            foreach ($fields as $name => $field)
            {
                if ($field instanceof TDataGrid)
                {
                    $this->datagrid = $field;
                    $this->pageNavigation = $this->datagrid->getPageNavigation();
                }
            }
            
            $item_barcode=$ui->getWidget('barcode_input');
            $obj = new ItemSeek;
            $action = new TAction(array($obj, 'onReload'));
            $item_barcode->setAction($action);
            
            // add the TUIBuilder panel inside the TForm object
            $this->form->add($ui);
            // set form fields from interface fields
            $this->form->setFields($ui->getFields());
        }
        catch (Exception $e)
        {
            new TMessage('error', $e->getMessage());
        }
        
        // add the form to the page
        parent::add($this->form);
    }
    

    /**
     * method onAdd()
     * Executed whenever the user clicks at the save button
     */
    function onAdd()
    {
        try
        {
            // get the form data into an object
            $object = $this->form->getData();
            
            $persisted_objects = TSession::getValue('checkout_objects');
            $list_object = new StdClass;
            $list_object->barcode    = $object->barcode_input;
            $list_object->book_title = $object->book_title_input;
            
            $persisted_objects[$list_object->barcode] = $list_object;
            TSession::setValue('checkout_objects', $persisted_objects);
            
            $new_object = clone($object);
            unset($new_object->barcode_input);
            unset($new_object->book_title_input);
            $this->form->setData($new_object);
            
            // shows the success message
            new TMessage('info', _t('Record added'));
            // reload the listing
            $this->onReload();
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
     * method onSave()
     * Executed whenever the user clicks at the save button
     */
    function onSave()
    {
        try
        {
            // open a transaction with database 'library'
            TTransaction::open('library');
            
            // get the form data into an active record Loan
            $data = $this->form->getData('Loan');
            $msg = '';
            
            $persisted_objects = TSession::getValue('checkout_objects');
            if ($persisted_objects)
            {
                // iterate the collection of active records
                foreach ($persisted_objects as $object)
                {
                    // add the object inside the datagrid
                    $this->datagrid->addItem($object);
                    
                    $item = Item::newFromBarcode($object->barcode);
                    if ($item->status_id == '1')
                    {
                        $loan = new Loan;
                        $loan->member_id = $data->member_id;
                        $loan->barcode   = $item->barcode;
                        $loan->loan_date = date('Y-m-d');
                        $loan->due_date  = date('Y-m-d', strtotime ( '+7 day' , strtotime ( date('Y-m-d') ) ) );
                        
                        // store the item
                        $item->status_id = '2';
                        $item->store();
                        
                        // stores the loan
                        $loan->store();
                        
                        $msg .= "{$item->barcode} - {$loan->due_date} - " . _t('Success') . "<br>";
                    }
                    else
                    {
                        $msg .= "{$item->barcode} - " . _t('Not available') . "<br>";
                    }
                }
                TSession::setValue('checkout_objects', NULL);
                
                // set the data back to the form
                $this->form->setData($object);
                
                // shows the success message
                new TMessage('info', $msg);
            }
            
            // close the transaction
            TTransaction::close();
            // reload the listing
            $this->onReload();
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
            
            $persisted_objects = TSession::getValue('checkout_objects');
            unset($persisted_objects[$key]);
            TSession::setValue('checkout_objects', $persisted_objects);
            
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
        }
    }

    /**
     * method onReload()
     * Load the datagrid with the database objects
     */
    function onReload($param = NULL)
    {
        try
        {
            $this->datagrid->clear();
            $persisted_objects = TSession::getValue('checkout_objects');
            
            if ($persisted_objects)
            {
                // iterate the collection of active records
                foreach ($persisted_objects as $object)
                {
                    // add the object inside the datagrid
                    $this->datagrid->addItem($object);
                }
            }
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