<?php
/**
 * PessoaForm Registration
 * @author  <your name here>
 */
class PessoaForm extends TStandardForm
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
        parent::setActiveRecord('Pessoa');     // defines the active record
        
        // creates the form
        $this->form = new TQuickForm('form_Pessoa');
        $this->form->class = 'tform'; // CSS class
        $this->form->style = 'width: 500px';
        
        // define the form title
        $this->form->setFormTitle('Pessoa');
        


        // create the form fields
        $id                             = new TEntry('id');
        $nome                           = new TEntry('nome');
        $telefone                       = new TEntry('telefone');
        $email                          = new TEntry('email');
        $endereco                       = new TEntry('endereco');
        $numero                         = new TEntry('numero');
        
        $cidade_id                      = new TSeekButton('cidade_id');
        $cidade_id->addValidation('Cidade', new TRequiredValidator);
        $cidade_name                    = new TEntry('cidade_name');

        $obj = new TStandardSeek;
        $action = new TAction(array($obj, 'onSetup'));
        $action->setParameter('database',      'esales');
        $action->setParameter('parent',        'form_Pessoa');
        $action->setParameter('model',         'Cidade');
        $action->setParameter('display_field', 'descricao');
        $action->setParameter('receive_key',   'cidade_id');
        $action->setParameter('receive_field', 'cidade_name');
        $cidade_id->setAction($action);



        $cep                            = new TEntry('cep');
        $cpf_cnpj                       = new TEntry('cpf_cnpj');
        $tipo_pessoa                    = new TRadioGroup('tipo_pessoa');
        $tipo_pessoa->addItems(array('F' => 'Fisica', 'J' => 'Juridica'));


        // add the fields
        $this->form->addQuickField('Codigo', $id,  100);
        $this->form->addQuickField('Nome', $nome,  200);
        $this->form->addQuickField('Telefone', $telefone,  200);
        $this->form->addQuickField('Email', $email,  200);
        $this->form->addQuickField('Endereço', $endereco,  200);
        $this->form->addQuickField('Numero', $numero,  200);

        $this->form->addQuickFields('Cidade', array($cidade_id, $cidade_name), true);

        $this->form->addQuickField('CEP', $cep,  200);
        $this->form->addQuickField('CPF/CNPJ', $cpf_cnpj,  100);
        $this->form->addQuickField('Tipo de pessoa', $tipo_pessoa,  200);




        
        // create the form actions
        $this->form->addQuickAction('Salvar', new TAction(array($this, 'onSave')), 'ico_save.png');
        $this->form->addQuickAction('Novo',  new TAction(array($this, 'onEdit')), 'ico_new.png');
        
        // add the form to the page
        parent::add($this->form);
    }
}
