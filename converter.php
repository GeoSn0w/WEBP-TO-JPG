<?php
if (!extension_loaded('gd') || !function_exists('imagecreatefromwebp') || !function_exists('imagejpeg')) {
    die('GD extension with WebP support is not available on this server.');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['webp_file'])) {
    $webp_file = $_FILES['webp_file'];
    $allowed_types = ['image/webp'];
    if (in_array($webp_file['type'], $allowed_types)) {
        // Create a GD image resource from the uploaded webp file
        $webp_image = imagecreatefromwebp($webp_file['tmp_name']);

        if ($webp_image) {
            // Define the output JPEG file path
            $output_file = 'converted.jpg';
            if (imagejpeg($webp_image, $output_file, 100)) {
                echo 'WebP to JPEG conversion successful. <a href="' . $output_file . '">Download JPEG</a>';
            } else {
                echo 'Error saving the JPEG file.';
            }
            imagedestroy($webp_image);
        } else {
            echo 'Error creating GD image resource from the WebP file.';
        }
    } else {
        echo 'Invalid file type. Please upload a valid WebP image.';
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
