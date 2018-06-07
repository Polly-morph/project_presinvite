<?php

class Favourite
{
    private $_db,
        $_data,
        $_isFavourite,
        $_currentFavourite;

    public function __construct($favourite = null)
    {
        $this->_db = ProjectDB::getInstance();
        $this->isFavourite = false;

    }

    public function create($fields = array())
    {
        if (!$this->_db->insert('project_favourites', $fields)) {
            throw new Exception ('There was a problem saving this presentation to your favourites.');
        }
    }

    //find favourite by id

    public function isFavourite($user_id = null, $pres_id = null)
    {
        if ($user_id) {
            $data = $this->getAllFavouritesOfUser($user_id);
            //if records are found get the first match
            foreach ($data as $row) {
                $fav_pres_id = $row['pres_id'];
//                    $active = $row['active'];
                if ($pres_id == $fav_pres_id) {
                    $this->_isFavourite = 'true';// true!='true'
                    $this->_currentFavourite = $row['fav_id'];
                    return true;
                }
            }
            return false;//if favourite record is not found
        }
    }

    public function getAllFavouritesOfUser($user_id)
    {
        if ($user_id) {
            $this->_data = $this->_db->queryAll("SELECT * FROM project_favourites WHERE user_id=$user_id");
            return $this->data();
//            $this->_data = $this->_db->get('project_favourites',array('user_id', '=', $user_id));
//            return $this->data();
        } else {
            throw new Exception ('There was a problem fetching presentations in the list of favourites.');

        }
    }

    public function deleteFavourite($fav_id, $user_id, $activeValue)
    {
        if ($fav_id) {
//            $this->_data = $this->_db->update("project_favourites", 'fav_id', $fav_id, array('active' =>
//                $activeValue, 'user_id'=>$user_id));
            $this->_db->delete('project_favourites', array('fav_id', '=', $fav_id));
        } else {
            throw new Exception("There was a problem updating your list of favourites");
        }
    }

    public function data()
    {
        return $this->_data;
    }

    public function checkFavourite()
    {
        return $this->_isFavourite;
    }

    public function getCurrentId()
    {
        return $this->_currentFavourite;
    }
}