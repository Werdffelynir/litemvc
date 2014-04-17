<?php
	
class Pages extends Model
{

    const TBL          = 'pages';
    
    const COL_ID       = 'id';
    const COL_LINK     = 'link';
    const COL_CATEGORY = 'category';
    const COL_TITLE    = 'title';
    const COL_TEXT     = 'text';
    const COL_DATETIME = 'datetime';
    const COL_USER_ID  = 'user_id';

    /**
     * @param string $className
     * @return mixed
     */
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

	/** *******  ****** **/
    public function queryId($id)
    {
        $result = $this->db->getByAttr(self::TBL, self::COL_ID, $id);
        if(!empty($result)) return $result;
        else return false;
    }

    public function queryLink($link)
    {
        $result = $this->db->query(
            "SELECT p.id, p.link, p.title, p.text, p.datetime, u.name
                FROM pages p
                LEFT JOIN users u ON u.id = p.user_id
                WHERE p.link=:link",
            array(":link"=>$link)
        )->row();

        if(!empty($result)) return $result;
        else return false;
    }

    public function menuListPages()
    {
        $result = $this->db->getAllByAttr(self::TBL, self::COL_CATEGORY, 'left_menu');
        if(!empty($result)) return $result;
        else return false;
    }

    public function updatePageLeftMenu($data, $id){

        $result = $this->db->update("pages",
            array("link","title","text"),
            array(
                'link'=>$data['link'],
                'title'=>$data['title'],
                'text'=>$data['text']
            ),
            array("id=:updId",
                array('updId'=>$id)
            )
        );

        if(!empty($result)) return $result;
        else return false;
    }

    public function insertPageLeftMenu($data){

        $result = $this->db->insert("pages",
            array("link","category","title","text","datetime","user_id"),
            array(
                'link'=>$data['link'],
                'category'=>'left_menu',
                'title'=>$data['title'],
                'text'=>$data['text'],
                'datetime'=>time(),
                'user_id'=>Auth::$id
            ));

        if(!empty($result)) return $result;
        else return false;
    }

    public function deletePageLeftMenu($id)
    {
        $result = $this->db->delete(self::TBL,
            array(
                'id=:id',
                array(
                    'id'=>$id
                )
            )
        );

        if(!empty($result)) return $result;
        else return false;
    }

}
