<?php
/**
 * User Active Record
 *
 * @version    1.0
 * @package    samples
 * @subpackage library
 * @author     Pablo Dall'Oglio
 * @copyright  Copyright (c) 2006-2011 Adianti Solutions Ltd. (http://www.adianti.com.br)
 * @license    http://www.adianti.com.br/framework-license
 */
class User extends TRecord
{
    const TABLENAME = 'sysuser';
    const PRIMARYKEY= 'id';
    const IDPOLICY =  'max'; // {max, serial}
    
    /**
     * Autenticates the user
     * @param $login User login
     * @param $password User password
     */
    public static function autenticate($login, $password)
    {
        $user = self::newFromLogin($login);
        
        if ($user instanceof User)
        {
            if (isset($user->{'password'}) AND ($user->{'password'} == $password) )
            {
                return TRUE;
            }
            else
            {
                throw new Exception(_t('Incorrect password'));
            }
        }
        else
        {
            throw new Exception(_t('User not found'));
        }
    }
    
    /**
     * Retorna uma instância de usuário a partir do login
     * @param $login Login do usuário
     */
    static public function newFromLogin($login)
    {
        $repos = new TRepository('User');
        $criteria = new TCriteria;
        $criteria->add(new TFilter('login', '=', $login));
        $objects = $repos->load($criteria);
        if (isset($objects[0]))
        {
            return $objects[0];
        }
    }
    
    /**
     * Return the User's role
     */
    public function get_role()
    {
        $role = new Role($this-> id_role);
        return $role;
    }
}
?>