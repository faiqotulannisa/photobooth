<?php
if (!isset($_POST['image'])) exit;

$image = $_POST['image'];
$image = str_replace('data:image/png;base64,', '', $image);
$image = str_replace(' ', '+', $image);

$data = base64_decode($image);

$filename = 'uploads/photo_' . time() . '.png';
file_put_contents($filename, $data);

echo $filename;
