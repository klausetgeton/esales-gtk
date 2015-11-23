<?php
/**
 * PessoaList Listing
 * @author  <your name here>
 */
class PessoaList extends TStandardList
{
    protected $form;     // registration form
    protected $datagrid; // listing
    protected $pageNavigation;
    
    /**
     * Class constructor
     * Creates the page, the form and the listing
     */
    public function __construct()
    {
        parent::__construct();
        
        parent::setDatabase('esales');            // defines the database
        parent::setActiveRecord('Pessoa');   // defines the active record
        parent::setDefaultOrder('id', 'asc');         // defines the default order
        parent::addFilterField('id', 'like'); // add a filter field
        parent::addFilterField('nome', 'like'); // add a filter field
        parent::addFilterField('telefone', 'like'); // add a filter field
        parent::addFilterField('email', 'like'); // add a filter field
        parent::addFilterField('endereco', 'like'); // add a filter field
        parent::addFilterField('numero', 'like'); // add a filter field
        parent::addFilterField('cidade_id', 'like'); // add a filter field
        parent::addFilterField('cep', 'like'); // add a filter field
        parent::addFilterField('cpf_cnpj', 'like'); // add a filter field
        parent::addFilterField('tipo_pessoa', 'like'); // add a filter field
        
        // creates the form, with a table inside
        $this->form = new TQuickForm('form_search_Pessoa');
        $this->form->class = 'tform'; // CSS class
        $this->form->setFormTitle('Pessoa');
        

        // create the form fields
        $id                             = new TEntry('id');
        $nome                           = new TEntry('nome');
        $telefone                       = new TEntry('telefone');
        $email                          = new TEntry('email');
        $endereco                       = new TEntry('endereco');
        $numero                         = new TEntry('numero');
        $cidade_id                      = new TEntry('cidade_id');
        $cep                            = new TEntry('cep');
        $cpf_cnpj                       = new TEntry('cpf_cnpj');
        $tipo_pessoa                    = new TEntry('tipo_pessoa');


        // add the fields
        $this->form->addQuickField('id', $id,  100);
        $this->form->addQuickField('nome', $nome,  200);
        $this->form->addQuickField('telefone', $telefone,  200);
        $this->form->addQuickField('email', $email,  200);
        $this->form->addQuickField('endereco', $endereco,  200);
        $this->form->addQuickField('numero', $numero,  200);
        $this->form->addQuickField('cidade_id', $cidade_id,  100);
        $this->form->addQuickField('cep', $cep,  200);
        $this->form->addQuickField('cpf_cnpj', $cpf_cnpj,  100);
        $this->form->addQuickField('tipo_pessoa', $tipo_pessoa,  200);



        
        // keep the form filled during navigation with session data
        $this->form->setData( TSession::getValue('Pessoa_filter_data') );
        
        // add the search form actions
        $this->form->addQuickAction('Buscar', new TAction(array($this, 'onSearch')), 'ico_find.png');
        $this->form->addQuickAction('Novo',  new TAction(array('PessoaForm', 'onEdit')), 'ico_new.png');
        
        // creates a DataGrid
        $this->datagrid = new TQuickGrid;
        $this->datagrid->setHeight(320);
        

        // creates the datagrid columns
        $id = $this->datagrid->addQuickColumn('id', 'id', 'right', 100);
        $nome = $this->datagrid->addQuickColumn('Nome', 'nome', 'left', 200);
        $telefone = $this->datagrid->addQuickColumn('Telefone', 'telefone', 'left', 200);
        $email = $this->datagrid->addQuickColumn('Email', 'email', 'left', 200);
        $endereco = $this->datagrid->addQuickColumn('Endereço', 'endereco', 'left', 200);
        $numero = $this->datagrid->addQuickColumn('Número', 'numero', 'left', 200);
        $cidade_id = $this->datagrid->addQuickColumn('Cidade', 'cidade_id', 'right', 100);
        $cep = $this->datagrid->addQuickColumn('CEP', 'cep', 'left', 200);
        $cpf_cnpj = $this->datagrid->addQuickColumn('Cpf/Cnpj', 'cpf_cnpj', 'right', 100);
        $tipo_pessoa = $this->datagrid->addQuickColumn('Tipo Pessoa', 'tipo_pessoa', 'left', 200);

        
        // create the datagrid actions
        $edit_action   = new TDataGridAction(array('PessoaForm', 'onEdit'));
        $delete_action = new TDataGridAction(array($this, 'onDelete'));
        
        // add the actions to the datagrid
        $this->datagrid->addQuickAction('Editar', $edit_action, 'id', 'ico_edit.png');
        $this->datagrid->addQuickAction('Deletar', $delete_action, 'id', 'ico_delete.png');
        
        // create the datagrid model
        $this->datagrid->createModel();
        
        // create the page navigation
        $this->pageNavigation = new TPageNavigation;
        $this->pageNavigation->setAction(new TAction(array($this, 'onReload')));
        $this->pageNavigation->setWidth($this->datagrid->getWidth());
        
        // create the page container
        $container = TVBox::pack( $this->form, $this->datagrid, $this->pageNavigation);
        parent::add($container);
    }
}
