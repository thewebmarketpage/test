<?php
// File Manager Script

// Configuration
$uploadDir = __DIR__ . '/'; // Directory to manage

// Handle file upload
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['file'])) {
    $uploadFile = $uploadDir . basename($_FILES['file']['name']);
    if (move_uploaded_file($_FILES['file']['tmp_name'], $uploadFile)) {
        $message = "File uploaded successfully.";
    } else {
        $message = "File upload failed.";
    }
}

// Handle file deletion
if (isset($_GET['delete'])) {
    $fileToDelete = $uploadDir . basename($_GET['delete']);
    if (file_exists($fileToDelete)) {
        unlink($fileToDelete);
        $message = "File deleted successfully.";
    } else {
        $message = "File not found.";
    }
}

// Handle file renaming
if (isset($_POST['old_name']) && isset($_POST['new_name'])) {
    $oldName = $uploadDir . basename($_POST['old_name']);
    $newName = $uploadDir . basename($_POST['new_name']);
    if (file_exists($oldName)) {
        rename($oldName, $newName);
        $message = "File renamed successfully.";
    } else {
        $message = "File not found.";
    }
}

// Get list of files
$files = array_diff(scandir($uploadDir), array('.', '..', basename(__FILE__)));

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>File Manager</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-900 text-white p-6">
    <div class="container mx-auto max-w-2xl">
        <h1 class="text-2xl font-bold mb-4 text-center text-gray-200">File Manager</h1>

        <?php if (isset($message)): ?>
            <div class="mb-4 p-2 bg-blue-500 text-white rounded">
                <?= htmlspecialchars($message) ?>
            </div>
        <?php endif; ?>

        <!-- File Upload Form -->
        <form action="" method="post" enctype="multipart/form-data" class="mb-4">
            <input type="file" name="file" class="block w-full text-white bg-gray-800 border border-gray-600 rounded p-2">
            <button type="submit" class="w-full bg-blue-500 text-white py-2 rounded mt-2 hover:bg-blue-600">Upload File</button>
        </form>

        <!-- File List -->
        <h2 class="text-lg font-medium text-gray-300">Files:</h2>
        <ul class="mt-2 bg-gray-800 p-4 rounded border border-gray-600">
            <?php foreach ($files as $file): ?>
                <li class="flex justify-between items-center p-2 border-b border-gray-600">
                    <span><?= htmlspecialchars($file) ?></span>
                    <div>
                        <!-- Rename Form -->
                        <form action="" method="post" class="inline">
                            <input type="hidden" name="old_name" value="<?= htmlspecialchars($file) ?>">
                            <input type="text" name="new_name" class="text-black p-1 rounded" placeholder="New name" required>
                            <button type="submit" class="bg-yellow-500 px-2 py-1 text-sm rounded">Rename</button>
                        </form>
                        <!-- Delete Link -->
                        <a href="?delete=<?= urlencode($file) ?>" class="bg-red-500 px-2 py-1 text-sm rounded ml-2">Delete</a>
                    </div>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>
</body>
</html>
