<?php

class Invitation
{
    private $_db, $_data, $_inviteDate;

    public function __construct($invitation = null)
    {
        $this->_db = ProjectDB::getInstance();
    }

    public function sendInvitation($from_name, $from_user_email, $to_user_email, $subject, $message)
    {
        $headers = 'From: ' . $from_name . '<' . $from_user_email . '>' . "\r\n" .
            'Reply-To: ' . $from_user_email . "\r\n" .
            'X-Mailer: PHP/' . phpversion();
        mail($to_user_email, $subject, $message, $headers);

    }

    public function createInvite($fields = array())
    {
        if (!$this->_db->insert('project_invites', $fields)) {
            throw new Exception('There was a problem storing this invite into the database.');
        }
    }

    public function preventMultipleInvites($from_user_id, $to_user_id)
    {
        if ($from_user_id) {
            $data = $this->getAllInvitationByUser($from_user_id);
            foreach ($data as $row) {
                $invite_to_user_id = $row['to_user_id'];
                if ($invite_to_user_id == $to_user_id) {
                    $this->_inviteDate=$row['datetime'];
                    return true;
                }
            }
            return false;
        }
    }

    public function getAllInvitationByUser($from_user_id)
    {
        if ($from_user_id) {
            $this->_data = $this->_db->queryAll("SELECT * FROM project_invites WHERE from_user_id=$from_user_id");
            return $this->data();
        } else {
            throw new Exception('There was an issue retrieving this record from the database.');
        }

    }

    public function data()
    {
        return $this->_data;
    }
    public function getInviteDate(){
        return $this->_inviteDate;
    }
}