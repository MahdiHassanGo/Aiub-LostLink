<?php
    require_once('../controllers/sessionCheck.php');


    $users = [
        ['id'=>1, 'username'=>'Asif', 'password'=>'123', 'email'=>'asif@aiub.edu'],
        ['id'=>2, 'username'=>'xyz', 'password'=>'12223', 'email'=>'xyz@aiub.edu'],
        ['id'=>3, 'username'=>'abc', 'password'=>'12', 'email'=>'abc@aiub.edu'],
        ['id'=>4, 'username'=>'pqr', 'password'=>'ad2', 'email'=>'pqr@aiub.edu'],
        ['id'=>5, 'username'=>'asd', 'password'=>'34', 'email'=>'asd@aiub.edu'],
        ['id'=>5, 'username'=>'asd', 'password'=>'34', 'email'=>'asd@aiub.edu']
    ];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User List</title>
    <style>
    body {
        font-family: Arial, Helvetica, sans-serif;
        background-color: #f4f6f8;
    }

    .container {
        width: 800px;
        margin: 50px auto;
        background: #fff;
        padding: 20px;
        border: 1px solid #ccc;
    }

    h2 {
        text-align: center;
    }

    table {
        width: 100%;
        border-collapse: collapse;
    }

    th, td {
        padding: 10px;
        border: 1px solid #bbb;
        text-align: center;
    }

    th {
        background-color: #2c7be5;
        color: white;
    }

    a {
        color: #2c7be5;
        text-decoration: none;
        margin: 0 5px;
    }

    a:hover {
        text-decoration: underline;
    }

    .top-links {
        margin-bottom: 15px;
        text-align: right;
    }
</style>

</head>
<body>
<div class="container">
    <h2>User List</h2>

    <div class="top-links">
        <a href="home.php">Back</a> |
        <a href="../controllers/logout.php">Logout</a>
    </div>

    <table>
        <tr>
            <th>ID</th>
            <th>Username</th>
            <th>Email</th>
            <th>Action</th>
        </tr>

        <?php foreach($users as $u){ ?>
        <tr>
            <td><?= $u['id'] ?></td>
            <td><?= $u['username'] ?></td>
            <td><?= $u['email'] ?></td>
            <td>
                <a href="edit.php?id=<?= $u['id'] ?>">Edit</a> |
                <a href="delete.php">Delete</a> |
                <a href="details.php">Details</a>
            </td>
        </tr>
        <?php } ?>
    </table>
</div>


</body>
</html>