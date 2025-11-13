<?php
require_once __DIR__ . '/../dao/PaymentDao.php';
require_once 'BaseService.php';

class PaymentService extends BaseService {

    public function __construct() {
        $dao = new PaymentDao();
        parent::__construct($dao);
    }

    public function createPayment($data) {
        if (!isset($data['reservation_id']) || !is_numeric($data['reservation_id'])) {
            throw new Exception("Invalid or missing reservation ID.");
        }

        if (!isset($data['amount']) || $data['amount'] <= 0) {
            throw new Exception("Amount must be a positive value.");
        }

        if (!isset($data['method']) || trim($data['method']) === '') {
            $data['method'] = 'Card';
        }

        return $this->dao->createPayment($data);
    }

    public function updatePayment($id, $data) {
        if (!isset($data['amount']) || $data['amount'] <= 0) {
            throw new Exception("Amount must be a positive value.");
        }

        return $this->dao->updatePayment($id, $data);
    }
}
?>
