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
class VendasReport extends TPage
{
    private $form; // form
    
    /**
     * Class constructor
     * Creates the page and the registration form
     */
    function __construct()
    {
        parent::__construct();
    
        
        // creates the form
        $this->form = new TForm('form_Vendas_Report');
        $this->form->class = 'tform';
        $this->form->style = 'width: 600px';
        
        $table = new TTable;
        $table->width = '100%';
        
        $this->form->add($table);
        $table->addRowSet(new TLabel(_t('Report filters')), '')->class='tformtitle';
        
        // Comprador
            $comprador_id         = new TSeekButton('comprador_id');
            $comprador_nome       = new TEntry('comprador_nome');
            $comprador_id->setSize(100);
            $comprador_nome->setEditable(FALSE);

            $obj = new TStandardSeek;
            $action = new TAction(array($obj, 'onSetup'));
            $action->setParameter('database',      'esales');
            $action->setParameter('parent',        'form_Vendas_Report');
            $action->setParameter('model',         'Pessoa');
            $action->setParameter('display_field', 'nome');
            $action->setParameter('receive_key',   'comprador_id');
            $action->setParameter('receive_field', 'comprador_nome');
            $comprador_id->setAction($action);

            // Tela
            $row=$table->addRow();
            $row->addCell(new TLabel('Comprador' . ': '));
            $row->addMultiCell($comprador_id, $comprador_nome);
        // fim - Comprador

        // Vendedor
            $vendedor_id         = new TSeekButton('vendedor_id');
            $vendedor_nome       = new TEntry('vendedor_nome');
            $vendedor_id->setSize(100);
            $vendedor_nome->setEditable(FALSE);

            $obj = new TStandardSeek;
            $action = new TAction(array($obj, 'onSetup'));
            $action->setParameter('database',      'esales');
            $action->setParameter('parent',        'form_Vendas_Report');
            $action->setParameter('model',         'Pessoa');
            $action->setParameter('display_field', 'nome');
            $action->setParameter('receive_key',   'vendedor_id');
            $action->setParameter('receive_field', 'vendedor_nome');
            $vendedor_id->setAction($action);

            // Tela
            $row=$table->addRow();
            $row->addCell(new TLabel('Vendedor' . ': '));
            $row->addMultiCell($vendedor_id, $vendedor_nome);
        // fim - Vendedor


        // Data inicial
            $data_inicial = new TDate('data_inicial');
            $row=$table->addRow();
            $row->addCell(new TLabel('Data Inicial' . ': '));
            $row->addMultiCell($data_inicial);
        // fim - Data inicial

        // Data Final
            $data_final = new TDate('data_final');
            $row=$table->addRow();
            $row->addCell(new TLabel('Data Final' . ': '));
            $row->addMultiCell($data_final);
        // fim - Data Final
       
        // Tipo de saida

            $output_type     = new TRadioGroup('output_type');
            $options=array();
            $options['pdf']  = 'PDF';
            $options['rtf']  = 'RTF';
            $output_type->addItems($options);
            $output_type->setValue('pdf');
            $output_type->setLayout('horizontal');

            $row=$table->addRow();
            $row->addCell(new TLabel(_t('Output') . ': '));
            $cell=$row->addCell($output_type);
        // fim - Tipo de saida
        
        // Botoes
            $save_button=new TButton('generate');
            $save_button->setAction(new TAction(array($this, 'onGenerate')), _t('Generate'));
            $save_button->setImage('ico_save.png');

            $table->addRowSet($save_button, '')->class='tformaction';
        // fim  - Botoes

        // define wich are the form fields
        $this->form->setFields(array(
                                        $comprador_id,
                                        $comprador_nome,
                                        $vendedor_id,
                                        $vendedor_nome,
                                        $data_inicial,
                                        $data_final,
                                        $output_type,
                                        $save_button
                                    ));
        
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
            TTransaction::open('esales');

            $conn = TTransaction::get();
    
            // get the form data into an active record Member
            $object = $this->form->getData();

            $SQL = " SELECT *, date(data_venda) as dt_venda_formatada FROM venda WHERE 1=1 ";

            if ($object->comprador_id)
            {
                $SQL .= " and comprador_id = {$object->comprador_id}";
            }

            if ($object->vendedor_id)
            {
                $SQL .= " and vendedor_id = {$object->vendedor_id}";
            }

            if ($object->data_inicial)
            {
                $SQL .= " and (data_venda) >= '{$object->data_inicial}'";
            }

            if ($object->data_final)
            {
                $SQL .= " and date(data_venda) <= '{$object->data_final}'";
            }

            // execute the query
            $result= $conn->Query($SQL);
            $results = array();
            
            if ($result)
            { 
                $objetos = $result->fetchAll();
            }
            else 
            {
                new TMessage('error', 'Nao houve registros!');
                return;
            }


            
            // if ($object->category_id)
            // {
            //     $criteria->add(new TFilter('category_id', '=', "{$object->category_id}"));
            // }
           
            // $members = $repository->load($criteria);
            $format  = $object->output_type;
            
            if ($objetos)
            {
                $widths = array(40, 150, 80, 140);
                
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
                $tr->addCell('Vendas', 'center', 'header', 5);
                
                // add titles row
                $tr->addRow();
                $tr->addCell('Codigo',        'left', 'title');
                $tr->addCell('Vendedor',      'left', 'title');
                $tr->addCell('Comprador',     'left', 'title');
                $tr->addCell('Data Venda',    'left', 'title');
                // $tr->addCell(_t('Phone'),    'left', 'title');
                
                // controls the background filling
                $colour= FALSE;
                
                // data rows
                foreach ($objetos as $objeto)
                {
                    $objeto = (object) $objeto;

                    $comprador = new Pessoa($objeto->comprador_id);
                    $vendedor = new Pessoa($objeto->vendedor_id);

                    $style = $colour ? 'datap' : 'datai';
                    $tr->addRow();
                    $tr->addCell($objeto->id,                      'left', $style);
                    $tr->addCell($vendedor->nome,                  'left', $style);
                    $tr->addCell($comprador->nome,                 'left', $style);
                    $tr->addCell($objeto->dt_venda_formatada,              'left', $style);
                    
                    $colour = !$colour;
                }
                
                // footer row
                $tr->addRow();
                $tr->addCell(date('Y-m-d h:i:s'), 'center', 'footer', 5);
                
                // stores the file
                $tr->save("app/output/vendas.{$format}");
                
                if (OS == 'WIN')
                {
                    parent::openFile("app\output\vendas.{$format}");
                }
                else
                {
                    parent::openFile("app/output/vendas.{$format}");
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