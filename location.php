<?php
$conn = new PDO('mysql:host=localhost;dbname=littlesun', "root", "root");

// Check if the delete form is submitted
if(isset($_POST['delete'])) {
    $id = $_POST['id'];
    $statement = $conn->prepare("DELETE FROM locations WHERE id = :id");
    $statement->bindValue(":id", $id);
    $statement->execute();
    // Redirect to prevent form resubmission
    header("Location: location.php");
    exit;
}

// Insert new location
if (!empty($_POST)) {
    $location = $_POST["location"];
    $statement = $conn->prepare("INSERT INTO locations (location) VALUES (:location)");
    $statement->bindValue(":location", $location); 
    $statement->execute();
}

// Fetch locations from the database
$statement = $conn->prepare("SELECT * FROM locations");
$statement->execute();
$locations = $statement->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <form action="" method="post">
        <div class="form__field">
            <label for="Location">Create new Hub Location</label>
            <input type="text" name="location">
            <input type="submit" value="add" class="btn btn--primary"> 
        </div>
    </form>

    <ul>
        <?php foreach ($locations as $location): ?>
            <li><?php echo htmlspecialchars($location['location']) ?>
                <form action="" method="post" style="display: inline;">
                    <input type="hidden" name="id" value="<?php echo $location['id']; ?>">
                    <button type="submit" name="delete">Delete</button>
                </form>
            </li>
        <?php endforeach; ?>
    </ul>
</body>
</html>
