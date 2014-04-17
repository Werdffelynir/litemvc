<?php

class Original extends Model
{

    const TBL         = 'docs';

    //const COL_ID      = 'id';

    /**
     * @param string $className
     * @return mixed
     */
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

	/** USE **/

    public function getAll()
    {
        $result = $this->db->getAll(self::TBL);
        if(!empty($result)) return $result;
        else return false;
    }


    public function getById($id)
    {
        $result = $this->db->getById(self::TBL, $id);
        if(!empty($result)) return $result;
        else return false;
    }


    public function insert($data){

        $result = $this->db->insert(self::TBL,
            array("__","__","__"),
            array(
                '__'=>$data['__'],
                '__'=>$data['__'],
                '__'=>$data['__']
            ));

        if(!empty($result)) return $result;
        else return false;
    }


    public function update($data, $id){

        $result = $this->db->update(self::TBL,
            array("__","__","__"),
            array(
                '__'=>$data['__'],
                '__'=>$data['__'],
                '__'=>$data['__']
            ),
            array("id=:updId",
                array('updId'=>$id)
            )
        );

        if(!empty($result)) return $result;
        else return false;
    }


    public function delete($id)
    {
        $result = $this->db->delete(self::TBL,
            array('id=:id', array('id'=>$id))
        );
        if(!empty($result)) return $result;
        else return false;
    }


    public function getLastId()
    {
        $result = $this->lastId($this->db, self::TBL, 'id');

        if(!empty($result)) return $result;
        else return false;
    }


    public function getCount()
    {
        $result = $this->lastId($this->db, self::TBL, 'id');

        if(!empty($result)) return $result;
        else return false;
    }
}
