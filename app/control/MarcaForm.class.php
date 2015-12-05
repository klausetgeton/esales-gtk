<?php
/**
 * MarcaForm Registration
 * @author  <your name here>
 */
class MarcaForm extends TStandardForm
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
        parent::setActiveRecord('Marca');     // defines the active record
        
        // creates the form
        $this->form = new TQuickForm('form_Marca');
        $this->form->class = 'tform'; // CSS class
        $this->form->style = 'width: 500px';
        
        // define the form title
        $this->form->setFormTitle('Marca');
        


        // create the form fields
        $id                             = new TEntry('id');
        $descricao                      = new TEntry('descricao');


        // add the fields
        $this->form->addQuickField('id', $id,  100);
        $this->form->addQuickField('Descrição', $descricao,  200, new TRequiredValidator );
        
        // create the form actions
        $this->form->addQuickAction('Salvar', new TAction(array($this, 'onSave')), 'ico_save.png');
        $this->form->addQuickAction('Novo',  new TAction(array($this, 'onEdit')), 'ico_new.png');
        
        // add the form to the page
        parent::add($this->form);
    }
}
