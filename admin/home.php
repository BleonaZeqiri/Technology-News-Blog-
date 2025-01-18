<?php
session_start();
include("db_conn.php");

if (!isset($_SESSION['id'])) {
    header("Location: index.php");
    exit();
}

$user_id = $_SESSION['id'];

$query = "SELECT * FROM posts WHERE user_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Home</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column;
            align-items: center;
            min-height: 100vh;
        }
        h1, h2 {
            color: #333;
        }
        a {
            text-decoration: none;
            color: #fff;
            background-color: #ff6b6b;
            padding: 8px 16px;
            border-radius: 5px;
            transition: background-color 0.3s;
        }
        a:hover {
            background-color: #ff5252;
        }
        form {
            background-color: #fff;
            padding: 20px;
            margin: 20px 0;
            border-radius: 5px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 500px;
        }
        label {
            display: block;
            margin: 10px 0 5px;
            font-weight: bold;
            color: #555;
        }
        input[type="text"], textarea {
            width: 100%;
            padding: 10px;
            margin-bottom: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 16px;
            box-sizing: border-box;
        }
        textarea {
            resize: none;
        }
        button {
            background-color: #4CAF50;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
            font-size: 16px;
        }

        button:hover {
            background-color: #45a049;
        }
        table {
            width: 100%;
            max-width: 800px;
            margin: 20px auto;
            border-collapse: collapse;
            background-color: #fff;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        th, td {
            text-align: left;
            padding: 12px;
            border: 1px solid #ddd;
        }
        th {
            background-color: #f2f2f2;
            font-weight: bold;
        }
        tr:hover {
            background-color: #f9f9f9;
        }
        .actions {
            display: flex;
            gap: 10px;
        }
        .actions form {
            margin: 0;
        }
    </style>
</head>
<body>
    <h1>Welcome, <?php echo $_SESSION['user_name']; ?></h1>
    <a href="logout.php" class="button">Logout</a>

    <h2>Create New Post</h2>
    <form action="process_post.php" method="POST" class="form" enctype="multipart/form-data">
        <label for="title" class="label">Title:</label>
        <input type="text" name="title" id="title" class="input" required>
        <label for="content" class="label">Content:</label>
        <textarea name="content" id="content" rows="4" class="input" required></textarea>
        <label for="image" class="label">Image:</label>
        <input type="file" name="image" id="image" class="input">
        <button type="submit" name="action" value="create" class="button">Create Post</button>
    </form>

    <h2>Your Posts</h2>
    <table border="1" class="table">
        <thead>
            <tr>
                <th>Title</th>
                <th>Content</th>
                <th>Image</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['title']); ?></td>
                    <td><?php echo htmlspecialchars($row['content']); ?></td>
                    <td>
                        <?php if (!empty($row['image'])): ?>
                            <img src="<?php echo $row['image']; ?>" alt="Post Image" width="100" height="100">
                        <?php else: ?>
                            No image uploaded
                        <?php endif; ?>
                    </td>
                    <td>
                        <a href="edit_post.php?id=<?php echo $row['id']; ?>" class="button">Edit</a>
                        <form action="process_post.php" method="POST" style="display:inline;">
                            <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                            <button type="submit" name="action" value="delete" class="button delete" onclick="return confirm('Are you sure you want to delete this post?')">Delete</button>
                        </form>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</body>
</html>

