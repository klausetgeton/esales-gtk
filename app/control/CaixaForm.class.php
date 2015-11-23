<?php
/**
 * CaixaForm Registration
 * @author  <your name here>
 */
class CaixaForm extends TStandardForm
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
        parent::setActiveRecord('Caixa');     // defines the active record
        
        // creates the form
        $this->form = new TQuickForm('form_Caixa');
        $this->form->class = 'tform'; // CSS class
        $this->form->style = 'width: 500px';
        
        // define the form title
        $this->form->setFormTitle('Caixa');
        


        // create the form fields
        $id                             = new TEntry('id');
        $descricao                      = new TEntry('descricao');
        $pessoa_id                      = new TSeekButton('pessoa_id');
        $saldo_inicial                  = new TEntry('saldo_inicial');
        $filial_id                      = new TSeekButton('filial_id');


        // add the fields
        $this->form->addQuickField('Código', $id,  100);
        $this->form->addQuickField('Descrição', $descricao,  200, new TRequiredValidator );
        $this->form->addQuickField('Pessoa', $pessoa_id,  100, new TRequiredValidator );
        $this->form->addQuickField('Saldo', $saldo_inicial,  200);
        $this->form->addQuickField('Filial', $filial_id,  100);

        
        // create the form actions
        $this->form->addQuickAction('Salvar', new TAction(array($this, 'onSave')), 'ico_save.png');
        $this->form->addQuickAction('Novo',  new TAction(array($this, 'onEdit')), 'ico_new.png');
        
        // add the form to the page
        parent::add($this->form);
    }
}
