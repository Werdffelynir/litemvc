<?php
	
class Users extends Model
{

    const TBL       = 'Users';

    const COL_ID    = 'id';
    const COL_NAME  = 'name';
    const COL_EMAIL = 'email';
    const COL_LOGIN = 'login';
    const COL_PASS  = 'pass';
    const COL_INFO  = 'info';


    /**
     * @param string $className
     * @return mixed
     */
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    public function userAuth($login)
    {
        $result = $this->db->getByAttr(self::TBL, self::COL_LOGIN, $login);
        if(!empty($result)) return $result;
        else return false;
    }

    public function getId($id)
    {
        $result = $this->db->getByAttr(self::TBL, self::COL_ID, $id);
        if(!empty($result)) return $result;
        else return false;
    }

}
