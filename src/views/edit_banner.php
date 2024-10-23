<?php
require_once __DIR__ .'/../../models/db.php';
$database = new Database();
$db = $database->getConnection();

if (isset($_GET['id'])) {
    $banner_id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
    $query = "SELECT * FROM banner_promote WHERE id = :id";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':id', $banner_id);
    $stmt->execute();
    $banner = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$banner) {
        header('Location: banner_list.php?message=Banner not found');
        exit();
    }
} else {
    header('Location: banner_list.php?message=Invalid banner ID');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $subtitle = $_POST['subtitle'];
    $description = $_POST['description'];

    $query = "UPDATE banner_promote SET subtitle = :subtitle, description = :description WHERE id = :id";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':subtitle', $subtitle);
    $stmt->bindParam(':description', $description);
    $stmt->bindParam(':id', $banner_id);
    $stmt->execute();

    header('Location: banner_list.php?message=Banner updated successfully');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Banner - Eventure</title>
    <link href="https://cdn.jsdelivr.net/npm/flowbite@2.5.2/dist/flowbite.min.css" rel="stylesheet" />
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body>
    <div class="container mx-auto p-6">
        <h1 class="text-2xl font-bold mb-4">Edit Banner</h1>
        
        <form action="edit_banner.php?id=<?php echo $banner_id; ?>" method="POST" class="space-y-4">
            <div>
                <label for="subtitle" class="block text-sm font-medium text-gray-700">Subtitle</label>
                <input type="text" name="subtitle" id="subtitle" class="block w-full mt-1 border-gray-300 rounded-md shadow-sm" value="<?php echo htmlspecialchars($banner['subtitle']); ?>" required>
            </div>

            <div>
                <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                <textarea name="description" id="description" class="block w-full mt-1 border-gray-300 rounded-md shadow-sm" required><?php echo htmlspecialchars($banner['description']); ?></textarea>
            </div>

            <input type="submit" value="Update Banner" class="px-4 py-2 bg-indigo-600 text-white rounded cursor-pointer hover:bg-indigo-700">
        </form>
    </div>
</body>
</html>
