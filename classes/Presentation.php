<?php

class Presentation
{
    private $_db,
        $_data;

    public function __construct($presentation = null)
    {
        $this->_db = ProjectDB::getInstance();
    }

    public function create($fields = array())
    {
        if (!$this->_db->insert('project_presentations', $fields)) {
            throw new Exception('There was a problem saving your presentation.');
        }
    }

    public function update($pres_id, $fields)
    {
        if (!$this->_db->update('project_presentations', 'id', $pres_id, $fields)) {
            throw new Exception('There was an issue updating this presentation.');
        }
    }

    //find a presentation by id
    public function find($presentationId = null)
    {
        if ($presentationId) {
            $data = $this->_db->get('project_presentations', array('id', '=', $presentationId));
            //if records are found get the first record (should only be one)
            if ($data->count()) {
                $this->_data = $data->getFirst();
                return true;
            }
        }
        return false;// if presentation not found
    }

//    public function presentationAttr($id, $title, $contents, $user_id){
//        return "{$id} by: {$user_id} - {$title} {$contents}";
//    }
    public function getAllPresentations()
    {
        $this->_data = $this->_db->queryAll("SELECT * FROM project_presentations ORDER BY title ASC");
        return $this->data();
    }

    public function getAllUserPresentations($user_id)
    {
        $this->_data = $this->_db->queryAll("SELECT * FROM project_presentations WHERE user_id = $user_id ORDER BY title ASC");
        return $this->data();
    }

    public function getContents($where = array())
    {
        $this->_db->get('project_presentation', $where);
    }

    public function getTagNames($tag_id)
    {
        $data = $this->_db->get("project_tags", array("tag_id", "=", $tag_id));
        if ($data->count()) {
            $this->_data = $data->getFirst();
            return true;
        }
    }

    //get field data from user record
    public function data()
    {
        return $this->_data;
    }
}