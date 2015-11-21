<?php
require_once 'init.php';

class TApplication extends AdiantiCoreApplication
{
    protected $content;
    function __construct()
    {
        parent::__construct();
        parent::set_title('Adianti Framework :: Samples');
        $this->content = new GtkFixed;

        $vbox = new GtkVBox;

        $scroll = new GtkScrolledWindow;
        $scroll->set_policy(GTK::POLICY_AUTOMATIC, GTK::POLICY_ALWAYS);
        $scroll->set_size_request(200, -1);

        $vbox->pack_start(GtkImage::new_from_file('app/images/pageheader.png'), false, false);
        $MenuBar = TMenuBar::newFromXML('menu.xml');
        
        $vbox->pack_start($MenuBar, false, false);
        $vbox->pack_start($this->content, true, true);
        
        parent::add($vbox);
        parent::show_all();
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
