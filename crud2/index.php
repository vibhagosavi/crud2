<?php 
include("db.php"); 

// VIEW LOGIC
$editData = null;

if(isset($_GET['view_id'])){
    $id = $_GET['view_id'];
    $result = mysqli_query($conn,"SELECT * FROM users WHERE id=$id");
    $editData = mysqli_fetch_assoc($result);
}

// INSERT
if(isset($_POST['submit'])){
    $category = $_POST['category'];
    $name = $_POST['name'];

    $image = $_FILES['image']['name'];
    $tmp = $_FILES['image']['tmp_name'];

    move_uploaded_file($tmp,"uploads/".$image);

    mysqli_query($conn,"INSERT INTO users(category,name,image) 
    VALUES('$category','$name','$image')");

    header("Location:index.php");
    exit();
}

// UPDATE
if(isset($_POST['update'])){
    $id = $_GET['view_id'];

    $category = $_POST['category'];
    $name = $_POST['name'];

    $image = $_FILES['image']['name'];
    $tmp = $_FILES['image']['tmp_name'];

    if($image != ""){
        move_uploaded_file($tmp,"uploads/".$image);
    } else {
        $image = $editData['image'];
    }

    mysqli_query($conn,"UPDATE users SET 
        category='$category',
        name='$name',
        image='$image',
        updated_at = NOW()
        WHERE id=$id");

    header("Location:index.php");
    exit();
}

// DELETE
if(isset($_POST['delete'])){
    $id = $_GET['view_id'];

    mysqli_query($conn,"DELETE FROM users WHERE id=$id");

    header("Location:index.php");
    exit();
}

// PAGINATION
$limit = 3;
$page = isset($_GET['page']) ? $_GET['page'] : 1;
$offset = ($page - 1) * $limit;
?>

<!DOCTYPE html>
<html>
<head>
    <title>CRUD Form</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<!-- FORM -->
<div class="form-container">
    <h2>User Entry Form</h2>

    <form method="POST" enctype="multipart/form-data">

        <div class="row">

            <!-- Category -->
            <div class="form-group">
                <label>Form Category</label>
                <select name="category" required>
                    <option value="">Select Category</option>

                    <option value="Doctor"
                    <?php if($editData && $editData['category']=="Doctor") echo "selected"; ?>>
                    Doctor</option>

                    <option value="Patient"
                    <?php if($editData && $editData['category']=="Patient") echo "selected"; ?>>
                    Patient</option>

                </select>
            </div>

            <!-- Name -->
            <div class="form-group">
                <label>Form Name</label>
                <input type="text" name="name" class="nameinput"
                value="<?php echo $editData['name'] ?? ''; ?>" required>
            </div>

            <!-- Image -->
            <div class="form-group">
                <label>Form Image</label>
                <input type="file" name="image" class="imageinput">

                <?php if($editData){ ?>
                    <img src="uploads/<?php echo $editData['image']; ?>" width="60">
                <?php } ?>
            </div>

        </div>

        <!-- Buttons -->
        <div class="btn-group">


            <button type="reset" class="newbutton">New</button>

            <button type="submit" name="submit" class="submitbutton">Submit</button>

            <button type="submit" name="update" class="submitbutton">Edit</button>

            <button type="submit" name="delete" class="submitbutton">Delete</button>

        </div>

        </div>

    </form>
</div>

<!-- TABLE -->
<div class="table-container">
    <h2>All Records</h2>

    <table>
        <tr class="tableth">
            <th>ID</th>
            <th>Category</th>
            <th>Name</th>
            <th>Image</th>
            <th>Date</th>
            <th>Action</th>
        </tr>

        <?php
        $result = mysqli_query($conn,"SELECT * FROM users LIMIT $limit OFFSET $offset");

        while($row = mysqli_fetch_assoc($result)){
        ?>
        <tr>
            <td><?php echo $row['id']; ?></td>
            <td><?php echo $row['category']; ?></td>
            <td><?php echo $row['name']; ?></td>

            <td>
                <img src="uploads/<?php echo $row['image']; ?>" width="50">
            </td>

            <td>
                <?php 
                if(!empty($row['updated_at'])){
                    echo $row['updated_at'];
                } else {
                    echo $row['created_at'];
                }
                ?>
            </td>

            <td>
                <a href="index.php?view_id=<?php echo $row['id']; ?>" class="action-btn">View</a>
            </td>
        </tr>
        <?php } ?>
    </table>

    <!-- Pagination -->
    <?php 
    $total_result = mysqli_query($conn,"SELECT COUNT(*) as total FROM users");
    $total_row = mysqli_fetch_assoc($total_result);
    $total_pages = ceil($total_row['total'] / $limit);
    ?>

    <div class="pagination" style="text-align:center; margin-top:20px;">
        <?php
        for($i = 1; $i <= $total_pages; $i++){
            echo "<a href='index.php?page=".$i."'>".$i."</a>";
        }
        ?>
    </div>

</div>

</body>
</html>