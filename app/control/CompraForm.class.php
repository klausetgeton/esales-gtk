<?php
/**
 * CompraForm Registration
 * @author  <your name here>
 */
class CompraForm extends TStandardForm
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
        parent::setActiveRecord('Compra');     // defines the active record
        
        // creates the form
        $this->form = new TQuickForm('form_Compra');
        $this->form->class = 'tform'; // CSS class
        $this->form->style = 'width: 500px';
        
        // define the form title
        $this->form->setFormTitle('Compra');
        


        // create the form fields
        $id                             = new TEntry('id');
        $vendedor_id                    = new TSeekButton('vendedor_id');
        $cliente_id                     = new TEntry('cliente_id');
        $filial_id                      = new TEntry('filial_id');
        $data                           = new TDate('data');
        $saida_id                       = new TEntry('saida_id');


        // add the fields
        $this->form->addQuickField('Código', $id,  100);
        $this->form->addQuickField('Vendedor', $vendedor_id,  100, new TRequiredValidator );
        $this->form->addQuickField('Cliente', $cliente_id,  100, new TRequiredValidator );
        $this->form->addQuickField('Filial', $filial_id,  100, new TRequiredValidator );
        $this->form->addQuickField('data', $data,  100);
        $this->form->addQuickField('saida_id', $saida_id,  100);




        
        // create the form actions
        $this->form->addQuickAction(_t('Save'), new TAction(array($this, 'onSave')), 'ico_save.png');
        $this->form->addQuickAction(_t('New'),  new TAction(array($this, 'onEdit')), 'ico_new.png');
        
        // add the form to the page
        parent::add($this->form);
    }
}
