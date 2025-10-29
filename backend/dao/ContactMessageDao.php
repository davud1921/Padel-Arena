<?php
require_once 'BaseDao.php';

class ContactMessageDao extends BaseDao {
    public function __construct() {
        parent::__construct("contact_messages");
    }

    public function getByUserId($user_id) {
        return $this->getById($user_id);
    }
}
?>
