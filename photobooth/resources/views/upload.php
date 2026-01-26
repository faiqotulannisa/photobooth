<?php
if (!isset($_POST['image'])) {
    http_response_code(400);
    exit;
}

$image = $_POST['image'];
$image = str_replace('data:image/png;base64,', '', $image);
$image = base64_decode($image);

if (!is_dir('photos')) {
    mkdir('photos', 0777, true);
}

$filename = 'photo_' . time() . '.png';
file_put_contents('photos/' . $filename, $image);

echo json_encode([
    'success' => true,
    'file' => $filename
]);
