<?php
/**
 * SubjectForm Registration
 *
 * @version    1.0
 * @package    samples
 * @subpackage library
 * @author     Pablo Dall'Oglio
 * @copyright  Copyright (c) 2006-2011 Adianti Solutions Ltd. (http://www.adianti.com.br)
 * @license    http://www.adianti.com.br/framework-license
 */
class SubjectForm extends TStandardForm
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
        if (User::newFromLogin(TSession::getValue('login'))-> role -> mnemonic !== 'LIBRARIAN')
        {
            throw new Exception(_t('Permission denied'));
        }
        TTransaction::close();
        
        // defines the database
        parent::setDatabase('library');
        
        // defines the active record
        parent::setActiveRecord('Subject');
        
        // creates the form
        $this->form = new TQuickForm('form_Subject');
        $this->form->class = 'tform';
        $this->form->setFormTitle(_t('Subjects'));
        $this->form->style = 'width: 500px';
        
        // create the form fields
        $id        = new TEntry('id');
        $name      = new TEntry('name');

        $id->setEditable(FALSE);
        
        // define the sizes
        $this->form->addQuickField(_t('Code'), $id,  100);
        $this->form->addQuickField(_t('Name'), $name,  200);

        // define the form action
        $this->form->addQuickAction(_t('Save'), new TAction(array($this, 'onSave')), 'ico_save.png');

        $vbox = new TVBox;
        $vbox->add($this->form);
        
        // add the form to the page
        parent::add($vbox);
    }
}
?>