<?php

class Docs extends Model
{

    const TBL         = 'docs';

    const COL_ID      = 'id';
    const COL_CLASS   = 'class';
    const COL_TYPE    = 'type';
    const COL_TITLE   = 'title';
    const COL_INFO    = 'info';
    const COL_TEXT    = 'text';
    const COL_PAGE_ID = 'page_id';

    /**
     * @param string $className
     * @return mixed
     */
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

	/** USE 
	Нужно построить стандартнуую модель с методами
	- выборка всего (вместить два следующих)
	- выборка одного|всего по id
	- выборка одного|всего по атрибуту
	- удаление по id
	- updete
	- insert
	- count
	- lest id
	************************************************************ **/

    
    public function queryAll()
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

	//
    public function getDocsMenu()
    {
        return array(
                array( 'link'=>'App', 'title'=>'Class App{}' ),
                array( 'link'=>'Controller', 'title'=>'Class Controller{}' ),
                array( 'link'=>'Model', 'title'=>'Class Model{}' ),
                array( 'link'=>'SimplePDO', 'title'=>'Class SimplePDO{}' ),
                array( 'link'=>'Config', 'title'=>'Configuration' )
            );
    }


    public function insertDocs($data){

        $result = $this->db->insert(self::TBL,
            array("class","type","title","info","text","page_id"),
            array(
                'class'=>$data['class'],
                'type'=>$data['type'],
                'title'=>$data['title'],
                'info'=>$data['info'],
                'text'=>$data['text'],
                'page_id'=>Auth::$id
            ));

        if(!empty($result)) return $result;
        else return false;
    }


    public function updateDocs($data, $id){

        $result = $this->db->update(self::TBL,
            array("class","type","title","info","text","page_id"),
            array(
                'class'=>$data['class'],
                'type'=>$data['type'],
                'title'=>$data['title'],
                'info'=>$data['info'],
                'text'=>$data['text'],
                'page_id'=>Auth::$id
            ),
            array("id=:updId",
                array('updId'=>$id)
            )
        );

        if(!empty($result)) return $result;
        else return false;
    }


    public function deleteDocs($id)
    {
        $result = $this->db->delete(self::TBL,
            array('id=:id',
                array(
                    'id'=>$id
                )
            )
        );

        if(!empty($result)) return $result;
        else return false;
    }


    public function docsLastId()
    {
        $result = $this->lastId($this->db, self::TBL, 'id');

        if(!empty($result)) return $result;
        else return false;
    }    


    public function queryByClass($class, $where='')
    {
        $result = $this->db->getAllByAttr(self::TBL, 'class', $class, $where);
        if(!empty($result)) return $result;
        else return false;
    }

    
}
