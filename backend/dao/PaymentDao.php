<?php
require_once 'BaseDao.php';

class PaymentDao extends BaseDao {
    public function __construct() {
        parent::__construct('payments');
    }

    public function createPayment($payment) {
        $data = [
            'reservation_id' => $payment['reservation_id'],
            'amount'         => $payment['amount'],
            'method'         => $payment['method'] ?? 'Card',
            'date'           => $payment['date'] ?? date('Y-m-d H:i:s')
        ];
        return $this->insert($data);
    }

    public function getAllPayments() {
        return $this->getAll();
    }

    public function getPaymentById($id) {
        return $this->getById($id);
    }

    public function updatePayment($id, $payment) {
        $data = [
            'amount' => $payment['amount'],
            'method' => $payment['method'],
            'date'   => $payment['date']
        ];
        return $this->update($id, $data);
    }

    public function deletePayment($id) {
        return $this->delete($id);
    }
}
?>