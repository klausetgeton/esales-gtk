<?php
/**
 * SystemProgramForm Registration
 * @author  <your name here>
 */
class SystemProgramForm extends TStandardForm
{
    protected $form; // form
    
    /**
     * Class constructor
     * Creates the page and the registration form
     */
    function __construct()
    {
        parent::__construct();
                
        // creates the form
        
        $this->form = new TQuickForm('form_SystemProgram');
        $this->form->setFormTitle('Programa');
        $this->form->class = 'tform'; // CSS class
        
        // defines the database
        parent::setDatabase('esales');
        
        // defines the active record
        parent::setActiveRecord('SystemProgram');
        
        // create the form fields
        $id            = new TEntry('id');
        $name          = new TEntry('name');
        $controller    = new TEntry('controller');
        
        $id->setEditable(false);

        // add the fields
        $this->form->addQuickField('ID', $id,  50);
        $this->form->addQuickField('Nome' . ': ', $name,  200);
        $this->form->addQuickField('Control' . ': ', $controller,  200);

        // validations
        $name->addValidation('Nome', new TRequiredValidator);
        $controller->addValidation(('Controller'), new TRequiredValidator);

        // add form actions
        $this->form->addQuickAction('Salvar', new TAction(array($this, 'onSave')), 'ico_save.png');
        $this->form->addQuickAction('Novo', new TAction(array($this, 'onEdit')), 'ico_new.png');
        $this->form->addQuickAction('Voltar para listagem',new TAction(array('SystemProgramList','onReload')),'ico_datagrid.png');

        $container = new TTable;
        $container->style = 'width: 80%';
        // $container->addRow()->addCell(new TXMLBreadCrumb('menu.xml','SystemProgramList'));
        $container->addRow()->addCell($this->form);
        
        
        // add the form to the page
        parent::add($container);
    }
}
?>