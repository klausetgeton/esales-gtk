<?php
/**
 * MemberReport Registration
 *
 * @version    1.0
 * @package    samples
 * @subpackage library
 * @author     Pablo Dall'Oglio
 * @copyright  Copyright (c) 2006-2011 Adianti Solutions Ltd. (http://www.adianti.com.br)
 * @license    http://www.adianti.com.br/framework-license
 */
class MemberReport extends TPage
{
    private $form; // form
    
    /**
     * Class constructor
     * Creates the page and the registration form
     */
    function __construct()
    {
        parent::__construct();
        
        // security check
        if (TSession::getValue('logged') !== TRUE)
        {
            throw new Exception(_t('Not logged'));
        }
        
        // security check
        TTransaction::open('library');
        if ( (User::newFromLogin(TSession::getValue('login'))-> role -> mnemonic !== 'OPERATOR') AND
             (User::newFromLogin(TSession::getValue('login'))-> role -> mnemonic !== 'LIBRARIAN') )
        {
            throw new Exception(_t('Permission denied'));
        }
        TTransaction::close();
        
        // creates the form
        $this->form = new TForm('form_Member_Report');
        $this->form->class = 'tform';
        $this->form->style = 'width: 600px';
        
        $table = new TTable;
        $table->width = '100%';
        
        $this->form->add($table);
        $table->addRowSet(new TLabel(_t('Report filters')), '')->class='tformtitle';

        // create the form fields
        $name            = new TEntry('name');
        $city_id         = new TSeekButton('city_id');
        $city_name       = new TEntry('city_name');
        $category_id     = new TDBCombo('category_id', 'library', 'Category', 'id', 'description');
        $output_type     = new TRadioGroup('output_type');
        
        $options=array();
        $options['pdf']  = 'PDF';
        $options['rtf']  = 'RTF';
        $output_type->addItems($options);
        $output_type->setValue('pdf');
        $output_type->setLayout('horizontal');
        
        $obj = new TStandardSeek;
        $action = new TAction(array($obj, 'onSetup'));
        $action->setParameter('database',      'library');
        $action->setParameter('parent',        'form_Member_Report');
        $action->setParameter('model',         'City');
        $action->setParameter('display_field', 'name');
        $action->setParameter('receive_key',   'city_id');
        $action->setParameter('receive_field', 'city_name');
        $city_id->setAction($action);
        
        // define the sizes
        $name->setSize(200);
        $city_id->setSize(100);
        $category_id->setSize(100);
        $city_name->setEditable(FALSE);
        
        // add a row for the field name
        $row=$table->addRow();
        $row->addCell(new TLabel(_t('Name') . ': '));
        $cell=$row->addCell($name);
        
        // add a row for the field city_id
        $row=$table->addRow();
        $row->addCell(new TLabel(_t('City') . ': '));
        $row->addMultiCell($city_id, $city_name);

        // add a row for the field category_id
        $row=$table->addRow();
        $row->addCell(new TLabel(_t('Category') . ': '));
        $cell=$row->addCell($category_id);
        
        // add a row for the field category_id
        $row=$table->addRow();
        $row->addCell(new TLabel(_t('Output') . ': '));
        $cell=$row->addCell($output_type);
        
        // create an action button (save)
        $save_button=new TButton('generate');
        // define the button action
        $save_button->setAction(new TAction(array($this, 'onGenerate')), _t('Generate'));
        $save_button->setImage('ico_save.png');

        $table->addRowSet($save_button, '')->class='tformaction';

        // define wich are the form fields
        $this->form->setFields(array($name,$city_id,$city_name,$category_id,$output_type,$save_button));
        
        $vbox = new TVBox;
        $vbox->add($this->form);
        
        // add the form to the page
        parent::add($vbox);
    }

    /**
     * method onGenerate()
     * Executed whenever the user clicks at the generate button
     */
    function onGenerate()
    {
        try
        {
            // open a transaction with database 'library'
            TTransaction::open('library');
            
            // get the form data into an active record Member
            $object = $this->form->getData();
            
            $repository = new TRepository('Member');
            $criteria   = new TCriteria;
            if ($object->name)
            {
                $criteria->add(new TFilter('name', 'like', "%{$object->name}%"));
            }
            
            if ($object->city_id)
            {
                $criteria->add(new TFilter('city_id', '=', "{$object->city_id}"));
            }
            
            if ($object->category_id)
            {
                $criteria->add(new TFilter('category_id', '=', "{$object->category_id}"));
            }
           
            $members = $repository->load($criteria);
            $format  = $object->output_type;
            
            if ($members)
            {
                $widths = array(40, 150, 80, 140, 80);
                
                switch ($format)
                {
                    case 'pdf':
                        $tr = new TTableWriterPDF($widths);
                        break;
                    case 'rtf':
                        if (!class_exists('PHPRtfLite_Autoloader'))
                        {
                            PHPRtfLite::registerAutoloader();
                        }
                        $tr = new TTableWriterRTF($widths);
                        break;
                }
                
                // create the document styles
                $tr->addStyle('title',  'Arial', '10', 'B',  '#ffffff', '#7C81BA');
                $tr->addStyle('datap',  'Arial', '10', '',   '#000000', '#DFEAF6');
                $tr->addStyle('datai',  'Arial', '10', '',   '#000000', '#ffffff');
                $tr->addStyle('header', 'Times', '16', 'BI', '#ff0000', '#FFF1B2');
                $tr->addStyle('footer', 'Times', '12', 'BI', '#2B2B2B', '#B5FFB4');
                
                // add a header row
                $tr->addRow();
                $tr->addCell(_t('Members'), 'center', 'header', 5);
                
                // add titles row
                $tr->addRow();
                $tr->addCell(_t('Code'),     'left', 'title');
                $tr->addCell(_t('Name'),     'left', 'title');
                $tr->addCell(_t('Category'), 'left', 'title');
                $tr->addCell(_t('Email'),    'left', 'title');
                $tr->addCell(_t('Phone'),    'left', 'title');
                
                // controls the background filling
                $colour= FALSE;
                
                // data rows
                foreach ($members as $member)
                {
                    $style = $colour ? 'datap' : 'datai';
                    $tr->addRow();
                    $tr->addCell($member->id,                   'left', $style);
                    $tr->addCell($member->name,                 'left', $style);
                    $tr->addCell($member->category_description, 'left', $style);
                    $tr->addCell($member->email,                'left', $style);
                    $tr->addCell($member->phone_number,         'left', $style);
                    
                    $colour = !$colour;
                }
                
                // footer row
                $tr->addRow();
                $tr->addCell(date('Y-m-d h:i:s'), 'center', 'footer', 5);
                
                // stores the file
                $tr->save("app/output/members.{$format}");
                
                if (OS == 'WIN')
                {
                    parent::openFile("app\output\members.{$format}");
                }
                else
                {
                    parent::openFile("app/output/members.{$format}");
                }
                
                // shows the success message
                new TMessage('info', _t('Report generated'));
            }
            else
            {
                new TMessage('error', _t('No records found'));
            }
    
            // fill the form with the active record data
            $this->form->setData($object);
            
            // close the transaction
            TTransaction::close();
        }
        catch (Exception $e) // in case of exception
        {
            // shows the exception error message
            new TMessage('error', '<b>Error</b> ' . $e->getMessage());
            
            // undo all pending operations
            TTransaction::rollback();
        }
    }
}
?>