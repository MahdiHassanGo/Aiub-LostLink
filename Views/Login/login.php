<html lang="en">
<head>
    <title>Login</title>
    <!-- paste common style here -->
     <style>
    body {
        font-family: Arial, Helvetica, sans-serif;
        background-color: #f4f6f8;
    }

    .container {
        width: 400px;
        margin: 80px auto;
        background: #ffffff;
        padding: 25px;
        border: 1px solid #ccc;
    }

    h2 {
        text-align: center;
        margin-bottom: 20px;
        color: #333;
    }

    table {
        width: 100%;
    }

    td {
        padding: 8px;
    }

    input[type="text"],
    input[type="password"],
    input[type="email"] {
        width: 100%;
        padding: 6px;
        border: 1px solid #bbb;
    }

    input[type="submit"] {
        padding: 6px 15px;
        background-color: #2c7be5;
        border: none;
        color: white;
        cursor: pointer;
    }

    input[type="submit"]:hover {
        background-color: #1a5fd0;
    }

    a {
        text-decoration: none;
        color: #2c7be5;
        font-size: 14px;
        margin-left: 10px;
    }

    a:hover {
        text-decoration: underline;
    }

    fieldset {
        border: 1px solid #ccc;
        padding: 15px;
    }

    legend {
        font-weight: bold;
        color: #333;
    }
</style>

</head>
<body>

<div class="container">
    <h2>User Login</h2>

  <form method="post" action="../controllers/loginCheck.php">
    <input type="email" name="email">
    <input type="password" name="password">
    <input type="submit" name="submit" value="Login">
</form>

</div>

</body>
</html>


