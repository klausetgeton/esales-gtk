<?php
/**
 * Wrapper class to deal with forms
 *
 * @version    1.0
 * @package    widget_web
 * @subpackage form
 * @author     Pablo Dall'Oglio
 * @copyright  Copyright (c) 2006-2014 Adianti Solutions Ltd. (http://www.adianti.com.br)
 * @license    http://www.adianti.com.br/framework-license
 */
class TForm
{
    protected $fields; // array containing the form fields
    protected $name;   // form name
    protected $child;
    protected $js_function;
    protected $element;
    static private $forms;
    
    /**
     * Class Constructor
     * @param $name Form Name
     */
    public function __construct($name = 'my_form')
    {
        // register this form
        self::$forms[$name] = $this;
        if ($name)
        {
            $this->setName($name);
        }
        $this->element = new TElement('form');
    }
    
    /**
     * Intercepts whenever someones assign a new property's value
     * @param $name     Property Name
     * @param $value    Property Value
     */
    public function __set($name, $value)
    {
        if (in_array(get_class($this), array('TForm', 'TQuickForm')))
        {
            // objects and arrays are not set as properties
            if (is_scalar($value))
            {              
                // store the property's value
                $this->element->$name = $value;
            }
        }
        else
        {
            $this->$name = $value;
        }
    }
    
    /**
     * Returns the form object by its name
     */
    public static function getFormByName($name)
    {
        if (isset(self::$forms[$name]))
        {
            return self::$forms[$name];
        }
    }
    
    /**
     * Define the form name
     * @param $name A string containing the form name
     */
    public function setName($name)
    {
        $this->name = $name;
    }
    
    /**
     * Returns the form name
     */
    public function getName()
    {
        return $this->name;
    }
    
    /**
     * Send data for a form located in the parent window
     * @param $form_name  Form Name
     * @param $object     An Object containing the form data
     */
    public static function sendData($form_name, $object, $aggregate = FALSE)
    {
        // iterate the object properties
        if ($object)
        {
            foreach ($object as $field => $value)
            {
                if (is_object($value))  // TMultiField
                {
                    foreach ($value as $property=>$data)
                    {
                        // if inside ajax request, then utf8_encode if isn't utf8
                        if (utf8_encode(utf8_decode($data)) !== $data )
                        {
                            $data = utf8_encode(addslashes($data));
                        }
                        else
                        {
                            $data = addslashes($data);
                        }
                        // send the property value to the form
                        $script = new TElement('script');
                        $script->{'language'} = 'JavaScript';
                        $script->setUseSingleQuotes(TRUE);
                        $script->setUseLineBreaks(FALSE);
                        $script->add( " try {
                                                \$('form[name={$form_name}]').find('[name={$field}_{$property}]').val('$data');
                                             } catch (e) { } " );
                        $script->show();
                    }
                }
                else
                {
                    // if inside ajax request, then utf8_encode if isn't utf8
                    if (utf8_encode(utf8_decode($value)) !== $value )
                    {
                        $value = utf8_encode(addslashes($value));
                    }
                    else
                    {
                        $value = addslashes($value);
                    }
                    // send the property value to the form
                    $script = new TElement('script');
                    $script->{'language'} = 'JavaScript';
                    $script->setUseSingleQuotes(TRUE);
                    $script->setUseLineBreaks(FALSE);
                    if ($aggregate)
                    {
                        $script->add( "try
                                        {
                                            if (document.{$form_name}.{$field}.value == \"\")
                                            {
                                                \$('form[name={$form_name}]').find('[name={$field}]').val('$value');
                                            }
                                            else
                                            {
                                                document.{$form_name}.{$field}.value = document.{$form_name}.{$field}.value + ', {$value}'
                                            }
                                        } catch (e) { } " );
                    }
                    else
                    {
                        $script->add( "
                                            \$('form[name={$form_name}]').find('[name={$field}]').val('$value');
                                            if (\$('form[name={$form_name}]').find('[name={$field}]').attr('exitaction'))
                                            {
                                                eval(\$('form[name={$form_name}]').find('[name={$field}]').attr('exitaction'));
                                            }
                                        " );
                    }
                    $script->show();
                }
            }
        }
    }
    
    /**
     * Define if the form will be editable
     * @param $bool A Boolean
     */
    public function setEditable($bool)
    {
        if ($this->fields)
        {
            foreach ($this->fields as $object)
            {
                $object->setEditable($bool);
            }
        }
    }
    
    /**
     * Add a Form Field
     * @param $field Object
     */
    public function addField(IWidget $field)
    {
        if ($field instanceof TField)
        {
            $name = $field->getName();
            if (isset($this->fields[$name]))
            {
                throw new Exception(TAdiantiCoreTranslator::translate('You have already added a field called "^1" inside the form', $name));
            }
            
            if ($name)
            {
                $this->fields[$name] = $field;
                $field->setFormName($this->name);
                
                if ($field instanceof TButton)
                {
                    $field->addFunction($this->js_function);
                }
            }
        }
        if ($field instanceof TMultiField)
        {
            $this->js_function .= "mtf{$name}.parseTableToJSON();";
            
            if ($this->fields)
            {
                // if the button was added before multifield
                foreach ($this->fields as $field)
                {
                    if ($field instanceof TButton)
                    {
                        $field->addFunction($this->js_function);
                    }
                }
            }
        }
    }
    
    /**
     * Remove a form field
     * @param $field Object
     */
    public function delField(IWidget $field)
    {
        if ($this->fields)
        {
            foreach($this->fields as $name => $object)
            {
                if ($field === $object)
                {
                    unset($this->fields[$name]);
                }
            }
        }
    }
    
    /**
     * Remove all form fields
     */
    public function delFields()
    {
        $this->fields = array();
    }
    
    /**
     * Define wich are the form fields
     * @param $fields An array containing a collection of TField objects
     */
    public function setFields($fields)
    {
        if (is_array($fields))
        {
            $this->fields = array();
            $this->js_function = '';
            // iterate the form fields
            foreach ($fields as $field)
            {
                $this->addField($field);
            }
        }
        else
        {
            throw new Exception(TAdiantiCoreTranslator::translate('Method ^1 must receive a paremeter of type ^2', __METHOD__, 'Array'));
        }
    }
    
    /**
     * Returns a form field by its name
     * @param $name  A string containing the field's name
     * @return       The Field object
     */
    public function getField($name)
    {
        if (isset($this->fields[$name]))
        {
            return $this->fields[$name];
        }
    }
    
    /**
     * Returns an array with the form fields
     * @return Array of form fields
     */
    public function getFields()
    {
        return $this->fields;
    }
    
    /**
     * clear the form Data
     */
    public function clear()
    {
        // iterate the form fields
        foreach ($this->fields as $name => $field)
        {
            if ($name) // labels don't have name
            {
                $field->setValue(NULL);
            }
        }
    }
    
    /**
     * Define the data of the form
     * @param $object An Active Record object
     */
    public function setData($object)
    {
        // iterate the form fields
        foreach ($this->fields as $name => $field)
        {
            if ($name) // labels don't have name
            {
                if (isset($object->$name))
                {
                    $field->setValue($object->$name);
                }
            }
        }
    }
    
    /**
     * Returns the form data as an object
     * @param $class A string containing the class for the returning object
     */
    public function getData($class = 'StdClass')
    {
        $object = new $class;
        foreach ($this->fields as $key => $fieldObject)
        {
            if (!$fieldObject instanceof TButton)
            {
                $object->$key = $fieldObject->getPostData();
            }
        }
        
        return $object;
    }

    /**
     * Validate form
     */
    public function validate()
    {
        // assign post data before validation
        // validation exception would prevent
        // the user code to execute setData()
        $this->setData($this->getData());
        
        $errors = array();
        foreach ($this->fields as $fieldObject)
        {
            try
            {
                $fieldObject->validate();
            }
            catch (Exception $e)
            {
                $errors[] = $e->getMessage() . '.';
            }
        }
        
        if (count($errors) > 0)
        {
            throw new Exception(implode("<br>", $errors));
        }
    }
    
    /**
     * Add a container to the form (usually a table of panel)
     * @param $object Any Object that implements the show() method
     */
    public function add($object)
    {
        $this->child = $object;
    }
    
    /**
     * Shows the form at the screen
     */
    public function show()
    {
        TPage::include_css('lib/adianti/include/tform/tform.css');
        
        // define form properties
        $this->element-> enctype = "multipart/form-data";
        $this->element-> name    = $this->name; // form name
        $this->element-> id      = $this->name; // form id
        $this->element-> method  = 'post';      // transfer method
        
        // add the container to the form
        if (isset($this->child))
        {
            $this->element->add($this->child);
        }
        // show the form
        $this->element->show();
    }
}
?>