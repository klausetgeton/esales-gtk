<?php
/**
 * VendaForm Registration
 * @author  <your name here>
 */
class VendaForm extends TStandardForm
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
        parent::setActiveRecord('Venda');     // defines the active record
        
        // creates the form
        $this->form = new TQuickForm('form_Venda');
        $this->form->class = 'tform'; // CSS class
        $this->form->style = 'width: 500px';
        
        // define the form title
        $this->form->setFormTitle('Venda');
        


        // create the form fields
        $id                             = new TEntry('id');
        $vendedor_id                    = new TEntry('vendedor_id');
        $comprador_id                   = new TEntry('comprador_id');
        $filial_id                      = new TEntry('filial_id');
        $data                           = new TDate('data');
        $entrada_id                     = new TEntry('entrada_id');


        // add the fields
        $this->form->addQuickField('id', $id,  100);
        $this->form->addQuickField('vendedor_id', $vendedor_id,  100);
        $this->form->addQuickField('comprador_id', $comprador_id,  100);
        $this->form->addQuickField('filial_id', $filial_id,  100);
        $this->form->addQuickField('data', $data,  100);
        $this->form->addQuickField('entrada_id', $entrada_id,  100);




        
        // create the form actions
        $this->form->addQuickAction('Salvar', new TAction(array($this, 'onSave')), 'ico_save.png');
        $this->form->addQuickAction('Novo',  new TAction(array($this, 'onEdit')), 'ico_new.png');
        
        // add the form to the page
        parent::add($this->form);
    }
}
