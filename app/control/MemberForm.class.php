<?php
/**
 * MemberForm Registration
 *
 * @version    1.0
 * @package    samples
 * @subpackage library
 * @author     Pablo Dall'Oglio
 * @copyright  Copyright (c) 2006-2011 Adianti Solutions Ltd. (http://www.adianti.com.br)
 * @license    http://www.adianti.com.br/framework-license
 */
class MemberForm extends TPage
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
        $this->form = new TForm('form_Member');
        
        try
        {
            // TUIBuilder object
            $ui = new TUIBuilder(500,500);
            $ui->setController($this);
            $ui->setForm($this->form);
            
            // reads the xml form
            $ui->parseFile('app/forms/member.form.xml');
            $ui->getWidget('code_label')->setValue(_t('Code'));
            $ui->getWidget('name_label')->setValue(_t('Name'));
            $ui->getWidget('address_label')->setValue(_t('Address'));
            $ui->getWidget('city_label')->setValue(_t('City'));
            $ui->getWidget('category_label')->setValue(_t('Category'));
            $ui->getWidget('birthdate_label')->setValue(_t('Birthdate'));
            $ui->getWidget('phone_label')->setValue(_t('Phone'));
            $ui->getWidget('email_label')->setValue(_t('Email'));
            $ui->getWidget('login_label')->setValue(_t('Login'));
            $ui->getWidget('password_label')->setValue(_t('Password'));
            $ui->getWidget('registration_label')->setValue(_t('Registration'));
            $ui->getWidget('expiration_label')->setValue(_t('Expiration'));
            $ui->getWidget('new_button')->setLabel(_t('New'));
            $ui->getWidget('save_button')->setLabel(_t('Save'));
            
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
     * method onSave()
     * Executed whenever the user clicks at the save button
     */
    function onSave()
    {
        try
        {
            // open a transaction with database 'library'
            TTransaction::open('library');
            
            // get the form data into an active record Member
            $object = $this->form->getData('Member');
            
            // form validation
            $this->form->validate();
            
            // stores the object
            $object->store();
            
            // set the data back to the form
            $this->form->setData($object);
            
            // close the transaction
            TTransaction::close();
            
            // shows the success message
            new TMessage('info', TAdiantiCoreTranslator::translate('Record saved'));
            // reload the listing
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
     * method onEdit()
     * Executed whenever the user clicks at the edit button da datagrid
     */
    function onEdit($param)
    {
        try
        {
            if (isset($param['key']))
            {
                // get the parameter $key
                $key=$param['key'];
                
                // open a transaction with database 'library'
                TTransaction::open('library');
                
                // instantiates object Member
                $object = new Member($key);
                
                // fill the form with the active record data
                $this->form->setData($object);
                
                // close the transaction
                TTransaction::close();
            }
            else
            {
                $this->form->clear();
            }
        }
        catch (Exception $e) // in case of exception
        {
            // shows the exception error message
            new TMessage('error', '<b>Error</b> ' . $e->getMessage());
            
            // undo all pending operations
            TTransaction::rollback();
        }
    }
}
?>