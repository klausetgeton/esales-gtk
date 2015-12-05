<?php
/**
 * BookReport Registration
 *
 * @version    1.0
 * @package    samples
 * @subpackage library
 * @author     Pablo Dall'Oglio
 * @copyright  Copyright (c) 2006-2011 Adianti Solutions Ltd. (http://www.adianti.com.br)
 * @license    http://www.adianti.com.br/framework-license
 */
class BookReport extends TPage
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
        $this->form = new TForm('form_Book_Report');
        $this->form->class = 'tform';
        $this->form->style = 'width: 600px';
        
        $table = new TTable;
        $table->width = '100%';
        
        $this->form->add($table);
        $table->addRowSet(new TLabel(_t('Report filters')), '')->class='tformtitle';

        // create the form fields
        $title           = new TEntry('title');
        $author_id       = new TSeekButton('author_id');
        $author_name     = new TEntry('author_name');
        $collection_id   = new TDBCombo('collection_id', 'library', 'Collection', 'id', 'description');
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
        $action->setParameter('parent',        'form_Book_Report');
        $action->setParameter('model',         'Author');
        $action->setParameter('display_field', 'name');
        $action->setParameter('receive_key',   'author_id');
        $action->setParameter('receive_field', 'author_name');
        $author_id->setAction($action);
        
        // define the sizes
        $title->setSize(200);
        $author_id->setSize(100);
        $collection_id->setSize(100);
        $author_name->setEditable(FALSE);
        
        // add a row for the field title
        $row=$table->addRow();
        $row->addCell(new TLabel(_t('Title') . ': '));
        $cell=$row->addCell($title);
        
        // add a row for the field author_id
        $row=$table->addRow();
        $row->addCell(new TLabel(_t('Author') . ': '));
        $row->addMultiCell($author_id, $author_name);

        // add a row for the field collection_id
        $row=$table->addRow();
        $row->addCell(new TLabel(_t('Collection') . ': '));
        $cell=$row->addCell($collection_id);
        
        // add a row for the field collection_id
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
        $this->form->setFields(array($title,$author_id,$author_name,$collection_id,$output_type,$save_button));
        
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
            
            // get the form data into an active record Book
            $object = $this->form->getData('Book');
            
            $repository = new TRepository('Book');
            $criteria   = new TCriteria;
            if ($object->title)
            {
                $criteria->add(new TFilter('title', 'like', "%{$object->title}%"));
            }
            
            if ($object->author_id)
            {
                $criteria->add(new TFilter('author_id', '=', "{$object->author_id}"));
            }
            
            if ($object->collection_id)
            {
                $criteria->add(new TFilter('collection_id', '=', "{$object->collection_id}"));
            }
           
            $books = $repository->load($criteria);
            $format  = $object->output_type;
            
            if ($books)
            {
                $widths = array(40, 120, 190, 65, 65);
                
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
                $tr->addCell(_t('Books'), 'center', 'header', 5);
                
                // add titles row
                $tr->addRow();
                $tr->addCell(_t('Code'),       'left', 'title');
                $tr->addCell(_t('Author'),     'left', 'title');
                $tr->addCell(_t('Title'),      'left', 'title');
                $tr->addCell(_t('Edition'),    'left', 'title');
                $tr->addCell(_t('Collection'), 'left', 'title');
                
                // controls the background filling
                $colour= FALSE;
                
                // data rows
                foreach ($books as $book)
                {
                    $style = $colour ? 'datap' : 'datai';
                    $tr->addRow();
                    $tr->addCell($book->id,                     'left', $style);
                    $tr->addCell($book->author_name,            'left', $style);
                    $tr->addCell($book->title,                  'left', $style);
                    $tr->addCell($book->edition,                'left', $style);
                    $tr->addCell($book->collection_description, 'left', $style);
                    
                    $colour = !$colour;
                }
                
                // footer row
                $tr->addRow();
                $tr->addCell(date('Y-m-d h:i:s'), 'center', 'footer', 5);
                
                // stores the file
                $tr->save("app/output/books.{$format}");
                
                if (OS == 'WIN')
                {
                    parent::openFile("app\output\books.{$format}");
                }
                else
                {
                    parent::openFile("app/output/books.{$format}");
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