<?php
/**
 * LoanReport Registration
 *
 * @version    1.0
 * @package    samples
 * @subpackage library
 * @author     Pablo Dall'Oglio
 * @copyright  Copyright (c) 2006-2011 Adianti Solutions Ltd. (http://www.adianti.com.br)
 * @license    http://www.adianti.com.br/framework-license
 */
class LoanReport extends TPage
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
        $this->form = new TForm('form_Loan');
        $this->form->class = 'tform';
        $this->form->style = 'width: 600px';
        
        $table = new TTable;
        $table->width = '100%';
        
        $this->form->add($table);
        
        $table->addRowSet(new TLabel(_t('Report filters')), '')->class='tformtitle';

        // create the form fields
        $member_id    = new TSeekButton('member_id');
        $member_name  = new TEntry('member_name');
        $barcode      = new TSeekButton('barcode_input');
        $book_title   = new TEntry('book_title_input');
        $loan_date1   = new TDate('loan_date1');
        $loan_date2   = new TDate('loan_date2');
        $output_type  = new TRadioGroup('output_type');
        
        $obj = new ItemSeek;
        $action = new TAction(array($obj, 'onReload'));
        $barcode->setAction($action);
        
        $options=array();
        $options['pdf']  = 'PDF';
        $options['rtf']  = 'RTF';
        $output_type->addItems($options);
        $output_type->setValue('pdf');
        $output_type->setLayout('horizontal');
        
        $obj = new TStandardSeek;
        $action = new TAction(array($obj, 'onSetup'));
        $action->setParameter('database',      'library');
        $action->setParameter('parent',        'form_Loan');
        $action->setParameter('model',         'Member');
        $action->setParameter('display_field', 'name');
        $action->setParameter('receive_key',   'member_id');
        $action->setParameter('receive_field', 'member_name');
        $member_id->setAction($action);
        
        // define the sizes
        $member_name->setEditable(FALSE);
        $book_title->setEditable(FALSE);
        $member_id->setSize(100);
        $barcode->setSize(100);
        $loan_date1->setSize(100);
        $loan_date2->setSize(100);
        $loan_date1->setMask('yyyy-mm-dd');
        $loan_date2->setMask('yyyy-mm-dd');
        
        // add a row for the field name
        $row=$table->addRow();
        $row->addCell(new TLabel(_t('Member') . ': '));
        $row->addMultiCell($member_id, $member_name);
        
        // add a row for the field city_id
        $row=$table->addRow();
        $row->addCell(new TLabel(_t('Barcode') . ': '));
        $row->addMultiCell($barcode, $book_title);

        // add a row for the field category_id
        $row=$table->addRow();
        $row->addCell(new TLabel(_t('Loan date') . ': '));
        $row->addMultiCell($loan_date1, $loan_date2);
                
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
        $this->form->setFields(array($member_id, $member_name, $barcode, $book_title, $loan_date1, $loan_date2, $output_type, $save_button));
        
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
            
            // get the form data into an active record Loan
            $object = $this->form->getData();
            
            $repository = new TRepository('Loan');
            $criteria   = new TCriteria;
            if ($object->member_id)
            {
                $criteria->add(new TFilter('member_id', '=', "{$object->member_id}"));
            }
            
            if ($object->barcode_input)
            {
                $criteria->add(new TFilter('barcode', '=', "{$object->barcode_input}"));
            }
            
            if ($object->loan_date1)
            {
                $criteria->add(new TFilter('loan_date', '>=', "{$object->loan_date1}"));
            }
            
            if ($object->loan_date2)
            {
                $criteria->add(new TFilter('loan_date', '<=', "{$object->loan_date2}"));
            }
           
            $loans = $repository->load($criteria);
            $format  = $object->output_type;
            
            if ($loans)
            {
                $widths = array(70, 190, 100, 70, 70);
                
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
                $tr->addCell(_t('Loans'), 'center', 'header', 5);
                
                // add titles row
                $tr->addRow();
                $tr->addCell(_t('Barcode'),     'left', 'title');
                $tr->addCell(_t('Title'),       'left', 'title');
                $tr->addCell(_t('Name'),        'left', 'title');
                $tr->addCell(_t('Loan date'),   'left', 'title');
                $tr->addCell(_t('Arrive date'), 'left', 'title');
                
                // controls the background filling
                $colour= FALSE;
                
                // data rows
                foreach ($loans as $loan)
                {
                    $style = $colour ? 'datap' : 'datai';
                    $tr->addRow();
                    $tr->addCell($loan->barcode,      'left', $style);
                    $tr->addCell($loan->book_title,   'left', $style);
                    $tr->addCell($loan->member_name,  'left', $style);
                    $tr->addCell($loan->loan_date,    'left', $style);
                    $tr->addCell($loan->arrive_date,  'left', $style);
                    
                    $colour = !$colour;
                }
                
                // footer row
                $tr->addRow();
                $tr->addCell(date('Y-m-d h:i:s'), 'center', 'footer', 5);
                
                // stores the file
                $tr->save("app/output/loans.{$format}");
                
                if (OS == 'WIN')
                {
                    parent::openFile("app\output\loans.{$format}");
                }
                else
                {
                    parent::openFile("app/output/loans.{$format}");
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