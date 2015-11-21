<?php
/**
 * ProdutoForm Registration
 * @author  <your name here>
 */
class ProdutoForm extends TStandardForm
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
        parent::setActiveRecord('Produto');     // defines the active record
        
        // creates the form
        $this->form = new TQuickForm('form_Produto');
        $this->form->class = 'tform'; // CSS class
        $this->form->style = 'width: 500px';
        
        // define the form title
        $this->form->setFormTitle('Produto');
        


        // create the form fields
        $id                             = new TEntry('id');
        $descricao                      = new TEntry('descricao');
        $preco_compra                   = new TEntry('preco_compra');
        $preco_venda                    = new TEntry('preco_venda');
        $marca_id                       = new TEntry('marca_id');


        // add the fields
        $this->form->addQuickField('id', $id,  100);
        $this->form->addQuickField('descricao', $descricao,  200);
        $this->form->addQuickField('preco_compra', $preco_compra,  200);
        $this->form->addQuickField('preco_venda', $preco_venda,  200);
        $this->form->addQuickField('marca_id', $marca_id,  100);




        
        // create the form actions
        $this->form->addQuickAction(_t('Save'), new TAction(array($this, 'onSave')), 'ico_save.png');
        $this->form->addQuickAction(_t('New'),  new TAction(array($this, 'onEdit')), 'ico_new.png');
        
        // add the form to the page
        parent::add($this->form);
    }
}
