<?php

class Table extends Model
{

    const TBL         = 'table';

    const COL_ID      = 'id';
    const COL_TITLE   = 'title';
    const COL_TEXT    = 'text';

    /**
     * @param string $className
     * @return mixed
     */
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

	/** USE **/

    public function getRecAll()
    {
        $result = $this->db->getAll(self::TBL);

        if(!empty($result))
            return $result;
        else
            return false;
    }


    public function getRecById($id)
    {
        $result = $this->db->getById(self::TBL, $id);

        if(!empty($result))
            return $result;
        else
            return false;
    }


}
