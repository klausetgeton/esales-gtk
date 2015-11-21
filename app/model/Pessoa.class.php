<?php
/**
 * Pessoa Active Record
 * @author  <your-name-here>
 */
class Pessoa extends TRecord
{
    const TABLENAME = 'public.pessoa';
    const PRIMARYKEY= 'id';
    const IDPOLICY =  'serial'; // {max, serial}
    
    
    private $cidade;

    /**
     * Constructor method
     */
    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('nome');
        parent::addAttribute('telefone');
        parent::addAttribute('email');
        parent::addAttribute('endereco');
        parent::addAttribute('numero');
        parent::addAttribute('cidade_id');
        parent::addAttribute('cep');
        parent::addAttribute('cpf_cnpj');
        parent::addAttribute('tipo_pessoa');
    }

    
    /**
     * Method set_cidade
     * Sample of usage: $pessoa->cidade = $object;
     * @param $object Instance of Cidade
     */
    public function set_cidade(Cidade $object)
    {
        $this->cidade = $object;
        $this->ref_cidade = $object->id;
    }
    
    /**
     * Method get_cidade
     * Sample of usage: $pessoa->cidade->attribute;
     * @returns Cidade instance
     */
    public function get_cidade()
    {
        // loads the associated object
        if (empty($this->cidade))
            $this->cidade = new Cidade($this->ref_cidade);
    
        // returns the associated object
        return $this->cidade;
    }
    


}
