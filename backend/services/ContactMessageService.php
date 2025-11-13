<?php
require_once __DIR__ . '/../dao/ContactMessageDao.php';
require_once 'BaseService.php';

class ContactMessageService extends BaseService {

    public function __construct() {
        $dao = new ContactMessageDao();
        parent::__construct($dao);
    }

    public function getMessagesByUser($userId) {
        return $this->dao->getMessagesByUserId($userId);
    }

    public function createContactMessage($data) {
        if (!isset($data['subject']) || trim($data['subject']) === '') {
            throw new Exception("Subject ne može biti prazan.");
        }

        if (!isset($data['message']) || trim($data['message']) === '') {
            throw new Exception("Message ne može biti prazan.");
        }

        if (!isset($data['user_id']) || !is_numeric($data['user_id'])) {
            throw new Exception("Nevažeći user_id.");
        }

        return $this->dao->createMessage($data);
    }
}
?>
