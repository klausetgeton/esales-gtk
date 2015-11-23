<?php
/**
 * FilialForm Registration
 * @author  <your name here>
 */
class FilialForm extends TStandardForm
{
    protected $form; // form
    
    /**
     * Class constructor
     * Creates the page and the registration form
     */
    function __construct()
    {
        parent::__construct();
        
        parent::setDatabase('esales');              // defines the database
        parent::setActiveRecord('Filial');     // defines the active record
        
        // creates the form
        $this->form = new TQuickForm('form_Filial');
        $this->form->class = 'tform'; // CSS class
        $this->form->style = 'width: 500px';
        
        // define the form title
        $this->form->setFormTitle('Filial');
        


        // create the form fields
        $id                             = new TEntry('id');
        $nome                           = new TEntry('nome');
        $endereco                       = new TEntry('endereco');
        $numero                         = new TEntry('numero');
        $email                          = new TEntry('email');
        $telefone                       = new TEntry('telefone');
        $fl_matriz                      = new TRadioGroup('fl_matriz');


        // add the fields
        $this->form->addQuickField('Código', $id,  100);
        $this->form->addQuickField('Nome', $nome,  200, new TRequiredValidator );
        $this->form->addQuickField('Endereco', $endereco,  200);
        $this->form->addQuickField('Número', $numero,  200);
        $this->form->addQuickField('Email', $email,  200);
        $this->form->addQuickField('Telefone', $telefone,  200);
        $this->form->addQuickField('fl_matriz', $fl_matriz,  200);




        
        // create the form actions
        $this->form->addQuickAction(_t('Save'), new TAction(array($this, 'onSave')), 'ico_save.png');
        $this->form->addQuickAction(_t('New'),  new TAction(array($this, 'onEdit')), 'ico_new.png');
        
        // add the form to the page
        parent::add($this->form);
    }
}
