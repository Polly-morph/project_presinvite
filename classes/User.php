<?php

class User
{
    private $_db,
        $_data,
        $_sessionName,
        $_isLoggedIn;


    public function __construct($user = null)
    {
        $this->_db = ProjectDB::getInstance();

        $this->_sessionName = Config::get('session/session_name');
        if (!$user) {
            if (Session::exists($this->_sessionName)) {
                $user = Session::get($this->_sessionName);

                if ($this->find($user)) {
                    $this->_isLoggedIn = true;
                } else {
                    //logout useR
                    $this->logout();
                }
            }
        } else {
            $this->find($user);
        }
    }

    public function create($fields = array())
    {
        if (!$this->_db->insert('project_users', $fields)) {
            throw new Exception('There was a problem creating an account.');
        }
    }

    public function update($fields = array(), $id = null)
    {
        if (!$id && $this->isLoggedIn()) {
            $id = $this->data()->user_id;
        }
        if (!$this->_db->update('project_users', 'user_id', $id, $fields)) {
            throw new Exception('There was a problem updating your details.');
        }
    }

    //find a user by id or username
    public function find($user = null)
    {
        if ($user) {
            $field = (is_numeric($user)) ? 'user_id' : 'email';
            //check if a user record exists by verifying
            //user id(numeric)
            // or email(alphanumeric)
            // TO DO: validate no numeric username values are passed
            //Solution: email is going to be validated as type email, so it cannot be numeric
            $data = $this->_db->get('project_users', array($field, '=', $user));
            //if records are found get the first record (should only be one)
            if ($data->count()) {
                $this->_data = $data->getFirst();
                return true;
            }
        }
        // if user not found
        return false;
    }

    //login validation - check if user email and password match
    public function login($email = null, $password = null)
    {
        $user = $this->find($email);
        if ($user) {
            if ($this->data()->password === Hash::make($password, $this->data()->salt)) {
                Session::put($this->_sessionName, $this->data()->user_id);
                return true;
            }
        }
        return false;
    }

    public function hasPermission($key)
    {
        $group = $this->_db->get('project_groups', array('group_id', '=', $this->data()->group));
        if ($group->count()) {
            $permissions = json_decode($group->getFirst()->permissions, true);
            if ($permissions[$key] == true) {
                return true;
            }
        }
        return false;
    }

    public function exists()
    {
        return (!empty($this->_data)) ? true : false;
    }

    public function logout()
    {
        Session::delete($this->_sessionName);
    }

    //get field data from user record
    public function data()
    {
        return $this->_data;
    }

    public function isLoggedIn()
    {
        return $this->_isLoggedIn;
    }
}