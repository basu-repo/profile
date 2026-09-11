<?php
declare(strict_types=1);

/**
 * Storage for images uploaded through the admin content editor.
 */

function app_store_uploaded_image(array $file, string $subdirectory): ?string
{
    if (($file['error'] ?? UPLOAD_ERR_NO_FILE) === UPLOAD_ERR_NO_FILE) {
        return null;
    }

    if (($file['error'] ?? UPLOAD_ERR_OK) !== UPLOAD_ERR_OK) {
        throw new RuntimeException('Image upload failed.');
    }

    $tmpName = (string)($file['tmp_name'] ?? '');
    if ($tmpName === '' || !is_uploaded_file($tmpName)) {
        throw new RuntimeException('Invalid uploaded file.');
    }

    $originalName = (string)($file['name'] ?? 'upload');
    $extension = strtolower(pathinfo($originalName, PATHINFO_EXTENSION));
    $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
    if (!in_array($extension, $allowedExtensions, true)) {
        throw new RuntimeException('Unsupported image type. Use JPG, PNG, GIF, or WEBP.');
    }

    $baseDirectory = base_path('uploads/' . trim($subdirectory, '/'));
    if (!is_dir($baseDirectory) && !mkdir($baseDirectory, 0755, true) && !is_dir($baseDirectory)) {
        throw new RuntimeException('Upload directory could not be created.');
    }

    $fileName = date('YmdHis') . '-' . bin2hex(random_bytes(6)) . '.' . $extension;
    $destination = $baseDirectory . '/' . $fileName;

    if (!move_uploaded_file($tmpName, $destination)) {
        throw new RuntimeException('Image upload could not be saved.');
    }

    return 'uploads/' . trim($subdirectory, '/') . '/' . $fileName;
}
