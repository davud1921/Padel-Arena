<?php
require_once 'BaseDao.php';

class ContactMessageDao extends BaseDao {
    public function __construct() {
        parent::__construct('contactmessages');
    }

    public function createMessage($message) {
        $data = [
            'user_id' => $message['user_id'],
            'subject' => $message['subject'],
            'message' => $message['message'],
            'date'    => $message['date'] ?? date('Y-m-d H:i:s')
        ];
        return $this->insert($data);
    }

    public function getAllMessages() {
        return $this->getAll();
    }

    public function getMessageById($id) {
        return $this->getById($id);
    }

    public function updateMessage($id, $message) {
        $data = [
            'subject' => $message['subject'],
            'message' => $message['message']
        ];
        return $this->update($id, $data);
    }

    public function deleteMessage($id) {
        return $this->delete($id);
    }
}
?>