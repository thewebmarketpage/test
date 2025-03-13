<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>File Manager</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-900 text-white p-6">
  <div class="container mx-auto max-w-2xl">
    <h1 class="text-2xl font-bold mb-4 text-center text-gray-200">File Manager</h1>
    
    <form id="uploadForm" class="mb-4" enctype="multipart/form-data" method="post" action="file_manager.php">
      <input type="file" name="file" class="block w-full text-white bg-gray-800 border border-gray-600 rounded p-2">
      <button type="submit" class="w-full bg-blue-500 text-white py-2 rounded mt-2 hover:bg-blue-600">Upload File</button>
    </form>
    
    <h2 class="text-lg font-medium text-gray-300">Files:</h2>
    <ul id="fileList" class="mt-2 bg-gray-800 p-4 rounded border border-gray-600">
      <!-- File items will be loaded here dynamically -->
    </ul>
  </div>

  <script>
    function loadFiles() {
      fetch('file_manager.php?action=list')
        .then(response => response.json())
        .then(files => {
          const fileList = document.getElementById("fileList");
          fileList.innerHTML = "";
          files.forEach(file => {
            fileList.innerHTML += `
              <li class='flex justify-between items-center p-2 border-b border-gray-600'>
                <span>${file}</span>
                <div>
                  <button onclick="renameFile('${file}')" class='bg-yellow-500 px-2 py-1 text-sm rounded'>Rename</button>
                  <button onclick="deleteFile('${file}')" class='bg-red-500 px-2 py-1 text-sm rounded'>Delete</button>
                </div>
              </li>`;
          });
        });
    }
    
    function deleteFile(fileName) {
      fetch(`file_manager.php?action=delete&file=${fileName}`)
        .then(() => loadFiles());
    }
    
    function renameFile(fileName) {
      let newName = prompt("Enter new name:", fileName);
      if (newName) {
        fetch(`file_manager.php?action=rename&file=${fileName}&newName=${newName}`)
          .then(() => loadFiles());
      }
    }
    
    document.addEventListener("DOMContentLoaded", loadFiles);
  </script>
</body>
</html>
