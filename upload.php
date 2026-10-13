<?php
function uploadFile() {
    $file = $_FILES['file'] ?? [];

    if (!is_array($file) || ($file['error'] ?? 1) !== UPLOAD_ERR_OK) {
        throw new Exception('Choose a file. Check PHP upload limits if it fails.');
    }

    $tmp = $file['tmp_name'];
    $size = filesize($tmp);

    if (!$size || $size > 5 * 1024 * 1024) {
        throw new Exception('File must be between 1 byte and 5 MB.');
    }

    $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    $mime = (new finfo(FILEINFO_MIME_TYPE))->file($tmp);

    $ok = ($ext === 'pdf' && $mime === 'application/pdf') ||
          ($ext === 'txt' && $mime === 'text/plain');

    if ($ext === 'docx' && in_array($mime, [
        'application/zip',
        'application/vnd.openxmlformats-officedocument.wordprocessingml.document'
    ])) {
        if (!class_exists('ZipArchive')) {
            throw new Exception('Enable PHP zip for DOCX uploads.');
        }

        $zip = new ZipArchive();

        if ($zip->open($tmp) === true) {
            $ok = $zip->locateName('[Content_Types].xml') !== false &&
                  $zip->locateName('word/document.xml') !== false;

            $zip->close();
        }
    }

    if (!$ok) {
        throw new Exception('Only genuine PDF, DOCX and TXT files are allowed.');
    }

    $path = 'uploads/' . bin2hex(random_bytes(16)) . '.' . $ext;

    if (!move_uploaded_file($tmp, __DIR__ . '/' . $path)) {
        throw new Exception('Upload failed. Check uploads folder permissions.');
    }

    return $path;
}