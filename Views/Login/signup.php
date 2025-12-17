<html lang="en">
<head>
    <title>Signup</title>
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
    <h2>User Registration</h2>

    <form method="post" action="../controllers/signupCheck.php">
        <fieldset>
            <legend>Sign Up</legend>

            <table>
                <tr>
                    <td>Username</td>
                    <td><input type="text" name="username"></td>
                </tr>
                <tr>
                    <td>Password</td>
                    <td><input type="password" name="password"></td>
                </tr>
                <tr>
                    <td>Email</td>
                    <td><input type="email" name="email"></td>
                </tr>
                <tr>
                    <td></td>
                    <td>
                        <input type="submit" name="submit" value="Register">
                        <a href="login.php">Back to login</a>
                    </td>
                </tr>
            </table>

        </fieldset>
    </form>
</div>

</body>
</html>
