<?php
/**
 * UserForm Registration
 *
 * @version    1.0
 * @package    samples
 * @subpackage library
 * @author     Pablo Dall'Oglio
 * @copyright  Copyright (c) 2006-2011 Adianti Solutions Ltd. (http://www.adianti.com.br)
 * @license    http://www.adianti.com.br/framework-license
 */
class UserForm extends TStandardForm
{
    protected $form; // form
    
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
        if (User::newFromLogin(TSession::getValue('login'))-> role -> mnemonic !== 'ADMINISTRATOR')
        {
            throw new Exception(_t('Permission denied'));
        }
        TTransaction::close();
        
        // defines the database
        parent::setDatabase('library');
        
        // defines the active record
        parent::setActiveRecord('User');
        
        // creates the form
        $this->form = new TQuickForm('form_Project');
        $this->form->class = 'tform';
        $this->form->setFormTitle(_t('Users'));
        $this->form->style = 'width: 500px';

        // create the form fields
        $id        = new TEntry('id');
        $login     = new TEntry('login');
        $name      = new TEntry('name');
        $password  = new TPassword('password');
        $id_role   = new TDBCombo('id_role', 'library', 'Role', 'id', 'description');
        
        $id->setEditable(FALSE);
        
        // define the sizes
        $this->form->addQuickField(_t('Code'), $id,  100);
        $this->form->addQuickField('Login', $login,  200);
        $this->form->addQuickField(_t('Name'), $name,  200);
        $this->form->addQuickField(_t('Password'), $password,  200);
        $this->form->addQuickField(_t('Role'), $id_role,  200);

        // define the form action
        $this->form->addQuickAction(_t('Save'), new TAction(array($this, 'onSave')), 'ico_save.png');

        $vbox = new TVBox;
        $vbox->add($this->form);
        // add the form to the page
        parent::add($vbox);
    }
}
?>