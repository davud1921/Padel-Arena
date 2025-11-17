<?php
require_once 'CourtService.php';

$court_service = new CourtService();

try {
    $new_court = [
        'name'           => 'Central Court',
        'type'           => 'Indoor',
        'location'       => 'Main Sport Center',
        'price_per_hour' => 25,
        'status'         => 'Available'
    ];

    $result = $court_service->createCourt($new_court);
    echo "Court created successfully:\n";
    print_r($result);

    $courts = $court_service->getAll();
    echo "\nAll courts:\n";
    print_r($courts);

    $available = $court_service->getCourtsByStatus('Available');
    echo "\nCourts by status (Available):\n";
    print_r($available);

} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
?>
