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

        $marca_id                      = new TSeekButton('marca_id');
        $marca_name                    = new TEntry('marca_name');

        $obj = new TStandardSeek;
        $action = new TAction(array($obj, 'onSetup'));
        $action->setParameter('database',      'esales');
        $action->setParameter('parent',        'form_Produto');
        $action->setParameter('model',         'Marca');
        $action->setParameter('display_field', 'descricao');
        $action->setParameter('receive_key',   'marca_id');
        $action->setParameter('receive_field', 'marca_name');
        $marca_id->setAction($action);


        // add the fields
        $this->form->addQuickField('Codigo', $id,  100);
        $this->form->addQuickField('Descricao', $descricao,  200);
        $this->form->addQuickField('Preco compra', $preco_compra,  200);
        $this->form->addQuickField('Preco venda', $preco_venda,  200);
        $this->form->addQuickFields('Marca', array($marca_id, $marca_name));


        // create the form actions
        $this->form->addQuickAction('Salvar', new TAction(array($this, 'onSave')), 'ico_save.png');
        $this->form->addQuickAction('Novo',  new TAction(array($this, 'onEdit')), 'ico_new.png');
        
        // add the form to the page
        parent::add($this->form);
    }
}
