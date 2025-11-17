<?php
require_once 'UserService.php';

$user_service = new UserService();

try {
    $new_user = [
        'name'     => 'Test User',
        'email'    => 'testuser' . time() . '@example.com', 
        'password' => '123456',  
        'role'     => 'user'
    ];

    $create_result = $user_service->createUser($new_user);
    echo "User created successfully:\n";
    print_r($create_result);

    $users = $user_service->getAll();
    echo "\nAll users:\n";
    print_r($users);

    $last_user = end($users);
    $user_id = $last_user['id'];

    echo "\nUser fetched by ID ($user_id):\n";
    $user = $user_service->getById($user_id);
    print_r($user);

    echo "\nLogin attempt:\n";
    $logged_in = $user_service->login($new_user['email'], $new_user['password']);

    if ($logged_in) {
        echo "Login successful! User data:\n";
        print_r($logged_in);
    } else {
        echo "Login failed.\n";
    }


} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
?>
