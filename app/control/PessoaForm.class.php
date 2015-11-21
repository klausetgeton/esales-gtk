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
        $cep                            = new TEntry('cep');
        $cpf_cnpj                       = new TEntry('cpf_cnpj');
        $tipo_pessoa                    = new TEntry('tipo_pessoa');


        // add the fields
        $this->form->addQuickField('id', $id,  100);
        $this->form->addQuickField('Nome', $nome,  200);
        $this->form->addQuickField('Telefone', $telefone,  200);
        $this->form->addQuickField('email', $email,  200);
        $this->form->addQuickField('endereço', $endereco,  200);
        $this->form->addQuickField('numero', $numero,  200);
        $this->form->addQuickField('Cidade', $cidade_id,  100, new TRequiredValidator );
        $this->form->addQuickField('cep', $cep,  200);
        $this->form->addQuickField('cpf_cnpj', $cpf_cnpj,  100);
        $this->form->addQuickField('tipo_pessoa', $tipo_pessoa,  200);




        
        // create the form actions
        $this->form->addQuickAction(_t('Save'), new TAction(array($this, 'onSave')), 'ico_save.png');
        $this->form->addQuickAction(_t('New'),  new TAction(array($this, 'onEdit')), 'ico_new.png');
        
        // add the form to the page
        parent::add($this->form);
    }
}
