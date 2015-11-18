<?php
/**
 * Role Active Record
 * @author  <your-name-here>
 */
class Role extends TRecord
{
    const TABLENAME = 'role';
    const PRIMARYKEY= 'id';
    const IDPOLICY =  'max'; // {max, serial} 
}
?>