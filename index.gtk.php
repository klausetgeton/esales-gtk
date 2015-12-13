<?php
include_once 'lib/adianti/util/TAdiantiLoader.class.php';
spl_autoload_register(array('TAdiantiLoader', 'autoload_gtk'));
$ini  = parse_ini_file('application.ini');
date_default_timezone_set($ini['timezone']);
TAdiantiCoreTranslator::setLanguage( $ini['language'] );
TApplicationTranslator::setLanguage( $ini['language'] );
define('APPLICATION_NAME', $ini['application']);
define('OS', strtoupper(substr(PHP_OS, 0, 3)));
define('PATH', dirname(__FILE__));
ini_set('php-gtk.codepage', 'UTF8');

class TApplication extends TCoreApplication
{
    private $scroll;
    function __construct()
    {
        parent::__construct();
        parent::set_title(':: ESales ::');
        $this->content = new GtkFixed;

        $vbox = new GtkVBox;
        // $vbox->pack_start(GtkImage::new_from_file('app/images/pageheader-gtk.png'), false, false);
        
        $hbox=new GtkHBox;
        $vbox->pack_start($hbox, true, true);
        
        $frame=new GtkFrame;
        $frame->add($this->content);
        
        $this->scroll = new GtkScrolledWindow;
        $this->scroll->set_policy(GTK::POLICY_AUTOMATIC, GTK::POLICY_ALWAYS);
        $this->scroll->set_size_request(200, -1);
        
        $hbox->pack_start($this->scroll, false, false);
        $hbox->pack_start($frame, true, true);
        parent::add($vbox);
        parent::show_all();
        
        $this->run('LoginForm');
    }
    
    /**
     * Define the menu based on user profile
     */
    public function configureMenu()
    {
        if ($this->scroll->get_child())
        {
            $this->scroll->remove($this->scroll->get_child());
        }
        
        TTransaction::open('library');
        $member = User::newFromLogin(TSession::getValue('login'));
        if ($member-> role -> mnemonic == 'LIBRARIAN')
        {
            $buttons = array();
            $buttons[] = array('<b>'._t('Cataloging').'</b>');
            $buttons[] = array(_t('Books'), 'BookList');
            $buttons[] = array(_t('Collections'), 'CollectionFormList');
            // $buttons[] = array(_t('Classifications'), 'ClassificationFormList');
            // $buttons[] = array(_t('Subjects'), 'SubjectList');
            // $buttons[] = array(_t('Authors'), 'AuthorList');
            // $buttons[] = array(_t('Publishers'), 'PublisherList');
            // $buttons[] = array(_t('Status'), 'VendaTeste');
            // $buttons[] = array('<b>'._t('Members').'</b>');
            // $buttons[] = array(_t('Members'), 'MemberList');
            // $buttons[] = array(_t('Categories'), 'CategoryFormList');
            // $buttons[] = array(_t('Cities'), 'CityList');
            // $buttons[] = array('<b>'._t('Circulation').'</b>');
            // $buttons[] = array(_t('Check in'), 'CheckInForm');
            // $buttons[] = array(_t('Check out'), 'CheckOutForm');
            $buttons[] = array('<b>'._t('Reports').'</b>');
            $buttons[] = array(_t('Loans'), 'LoanReport');
            $buttons[] = array(_t('Member'), 'MemberReport');
            $buttons[] = array(_t('Books'), 'BookReport');
            // $buttons[] = array('Programas','SystemProgramList');
            // $buttons[] = array('Grupos','CaixaList');
            // $buttons[] = array('Usuários','SystemUserList');
            // $buttons[] = array('Caixa','CaixaList');
            // $buttons[] = array('Compra','CompraList');
            // $buttons[] = array('Venda','VendaList');
            // $buttons[] = array('Filial','FilialList');
            // $buttons[] = array('Marca','MarcaList');
            // $buttons[] = array('Pessoa','PessoaList');
            // $buttons[] = array('Produto','ProdutoList');
            $buttons[] = array('Logout', array('LoginForm', 'onLogout'));
        }
        else if ($member-> role -> mnemonic == 'OPERATOR')
        {
            $buttons = array();
            $buttons[] = array('<b>'._t('Members').'</b>');
            $buttons[] = array(_t('Members'), 'MemberList');
            $buttons[] = array(_t('Categories'), 'CategoryFormList');
            $buttons[] = array(_t('Cities'), 'CityList');
            $buttons[] = array('<b>'._t('Circulation').'</b>');
            $buttons[] = array(_t('Check in'), 'CheckInForm');
            $buttons[] = array(_t('Check out'), 'CheckOutForm');
            $buttons[] = array('<b>'._t('Reports').'</b>');
            $buttons[] = array(_t('Loans'), 'LoanReport');
            $buttons[] = array(_t('Member'), 'MemberReport');
            $buttons[] = array(_t('Books'), 'BookReport');
            $buttons[] = array('Logout', array('LoginForm', 'onLogout'));
        }
        else if ($member-> role -> mnemonic == 'ADMINISTRATOR')
        {
            $buttons = array();
            $buttons[] = array('<b>'.'Admin'.'</b>');
            $buttons[] = array(_t('Users'), 'UserList');
            $buttons[] = array(_t('Roles'), 'RoleList');
            $buttons[] = array('Logout', array('LoginForm', 'onLogout'));
        }
        TTransaction::close();


        $buttons[] = array('<b>Sistemas</b>');
        $buttons[] = array('Programas','SystemProgramList');
        $buttons[] = array('Usuarios','SystemUserList');
        $buttons[] = array('Grupos','SystemGroupList');
        $buttons[] = array('Filial','FilialList');
        $buttons[] = array('Marca','MarcaList');
        $buttons[] = array('Produto','ProdutoList');
        $buttons[] = array('Compra','CompraList');
        $buttons[] = array('Venda','VendaList');


        // $buttons[] = array('Pessoa','PessoaList');
        // $buttons[] = array('Caixa','CaixaList');
        // $buttons[] = array('Compra','CompraList');
        // $buttons[] = array('Venda','VendaList');


        /////////////////////////////////////////////////////////////////////////////////////////////
        /////////////////////////////////////////////////////////////////////////////////////////////
        /////////////////////////////////////////////////////////////////////////////////////////////

        // TTransaction::open('esales');

        // $usuario = new SystemUser(1);
        // $programas = $usuario->getProgramas();

        // $buttons[] = array('<b>Permissoes</b>');

        // foreach ($programas as $name => $controller)
        // {
        //     $buttons[] = array($name, $controller);
        // }

        // TTransaction::close();


        /////////////////////////////////////////////////////////////////////////////////////////////
       
        /////////////////////////////////////////////////////////////////////////////////////////////
        /////////////////////////////////////////////////////////////////////////////////////////////


        // Nao tem form para cadastrar
        
        $vbox_buttons = new GtkVBox(FALSE, 0);
        $this->scroll->add_with_viewport($vbox_buttons);
        $this->scroll->get_child()->modify_bg(Gtk::STATE_NORMAL,   GdkColor::parse('#EFEFEF'));
        foreach ($buttons as $button_info)
        {
            if (!isset($button_info[1]))
            {
                $button = new GtkEventBox;
                $button->add($label=new GtkLabel($button_info[0]));
                $button->set_size_request(-1,20);
                $label->set_use_markup(TRUE);
                $button->modify_bg(Gtk::STATE_NORMAL,   GdkColor::parse('#C0C0C0'));
            }
            else
            {
                $button = new GtkButton($button_info[0]);
                $button->get_child()->set_alignment(0,0);
                $button->set_property('can-focus', FALSE);
                $button->set_relief(Gtk::RELIEF_NONE);
                $button->modify_bg(Gtk::STATE_PRELIGHT, GdkColor::parse('#CFCFCF'));
                $button->modify_bg(Gtk::STATE_ACTIVE, GdkColor::parse('#CFCFCF'));
                $button->set_size_request(-1,24);
                $button->connect_simple('clicked', array($this, 'run'), $button_info[1]);
            }
            
            $vbox_buttons->pack_start($button, FALSE, FALSE, 0);
        }
        $this->scroll->show_all();
    }
    
    /**
     * executed when the user double click at the menu
     */
    public function onDoubleClick()
    {
        $selection = $this->tree->get_selection();
        list($model, $iter) = $selection->get_selected();

        if ($iter)
        {
            $class = $this->model->get_value($iter, 2);
            if ($class)
            {
                $this->run($class);
            }
        }
    }
    
    /**
     * Pack a class inside the application window
     * @param $callback
     */
    public function run($callback)
    {
        if (TSession::getValue('logged'))
        {
            $this->scroll->show_all();
        }
        else
        {
            $this->scroll->hide();
        }
        $class = is_array($callback) ? $callback[0] : $callback;
        
        if ($class == 'SetupPage') // after login
        {
            $this->configureMenu();
        }
        
        return parent::run($callback);
    }
}
$app = new TApplication;

try
{
    Gtk::Main();
}
catch (Exception $e)
{
    $app->destroy();
    new TExceptionView($e);
    Gtk::main();
}
?>