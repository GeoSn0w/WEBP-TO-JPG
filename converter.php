<?php
if (!extension_loaded('gd') || !function_exists('imagecreatefromwebp') || !function_exists('imagejpeg')) {
    die('GD extension with WebP support is not available on this server.');
}

function isWebP($filename) {
    $allowed_types = ['image/webp'];
    return in_array(mime_content_type($filename), $allowed_types);
}

function sanitizeFileName($filename) {
    $filename = preg_replace('/[^a-zA-Z0-9_.-]/', '', $filename);
    return $filename;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['webp_file'])) {
    $webp_file = $_FILES['webp_file'];

    if (!isWebP($webp_file['tmp_name'])) {
        die('Invalid file type. Please upload a valid WebP image.');
    }

    $output_file = sanitizeFileName($webp_file['name']);
    $webp_image = imagecreatefromwebp($webp_file['tmp_name']);

    if ($webp_image) {
        $output_file = 'converted_' . $output_file . '.jpg';

        if (imagejpeg($webp_image, $output_file, 100)) {
            echo 'WebP to JPEG conversion successful. <a href="' . htmlspecialchars($output_file) . '">Download JPEG</a>';
        } else {
            echo 'Error saving the JPEG file.';
        }
        imagedestroy($webp_image);
    } else {
        echo 'Error creating GD image resource from the WebP file.';
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>WebP to JPEG Converter</title>
</head>
<body>
    <h1>WebP to JPEG Converter</h1>
    <form method="post" enctype="multipart/form-data">
        <input type="file" name="webp_file" accept="image/webp">
        <input type="submit" value="Convert to JPEG">
    </form>
</body>
</html>
