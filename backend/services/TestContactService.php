<?php
require_once 'ContactMessageService.php';

$contact_service = new ContactMessageService();

try {
    $new_message = [
        'user_id' => 1,
        'subject' => 'Test Subject',
        'message' => 'This is a test message sent from the ContactMessageService test.',
    ];

    $result = $contact_service->createContactMessage($new_message);
    echo "Contact message created successfully:\n";
    print_r($result);

    $messages = $contact_service->getAll();
    echo "\nAll messages:\n";
    print_r($messages);

    $user_messages = $contact_service->getMessagesByUser(1);
    echo "\nMessages by user_id (1):\n";
    print_r($user_messages);

} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
?>
