<?php
    // login.php
?>

<html lang="en">
<head>
    <title>Login</title>

    <style>
        *{box-sizing:border-box}
        body{
            margin:0;
            font-family: Arial, Helvetica, sans-serif;
            min-height:100vh;
            display:flex;
            align-items:center;
            justify-content:center;
            background: linear-gradient(135deg, #5d22ff 0%, #3c0fb0 45%, #240064 100%);
        }

        form{width:420px; max-width:92vw;}

        fieldset{
            border:0;
            background:#ffffff;
            padding:32px 34px 26px;
            border-radius:12px;
            box-shadow: 0 18px 45px rgba(0,0,0,.28);
        }

        legend{
            width:100%;
            text-align:center;
            font-size:30px;
            font-weight:700;
            color:#3a1695;
            padding:0 10px 10px;
        }

        table{width:100%; border-collapse:collapse;}
        td{padding:8px 0; vertical-align:middle;}

        .line{
            border-bottom:1px solid rgba(0,0,0,.35);
        }

        .ico{
            width:30px;
            padding-right:10px;
            opacity:.65;
        }

        .ico svg{display:block; width:18px; height:18px; fill:#1a1a1a;}

        input[type="email"], input[type="password"]{
            width:100%;
            border:0;
            outline:none;
            background:transparent;
            padding:10px 0;
            font-size:14px;
            color:#111;
        }

        input[type="email"]::placeholder,
        input[type="password"]::placeholder{
            color:rgba(0,0,0,.45);
        }

        tr.line:focus-within{
            border-bottom-color:#6b2cff;
            box-shadow: 0 10px 18px -16px rgba(107,44,255,.75);
        }

        .opt{
            padding-top:10px;
            font-size:12px;
            color:#555;
        }

        .optBox{
            display:flex;
            align-items:center;
            justify-content:space-between;
            gap:10px;
            flex-wrap:wrap;
        }

        .remember{
            display:flex;
            align-items:center;
            gap:8px;
            user-select:none;
        }

        input[type="checkbox"]{
            margin:0;
            transform:translateY(1px);
            accent-color:#6b2cff;
        }

        a{color:#6b2cff; text-decoration:none;}
        a:hover{text-decoration:underline;}

        .btn{
            width:100%;
            padding:11px 12px;
            border:0;
            border-radius:8px;
            cursor:pointer;
            font-weight:700;
            letter-spacing:.2px;
            color:#fff;
            background: linear-gradient(90deg, #1b103a 0%, #0e0a1e 100%);
            box-shadow: 0 12px 18px rgba(0,0,0,.18);
        }
        .btn:hover{filter:brightness(1.08);}

        .or{
            text-align:center;
            font-size:12px;
            color:#666;
            padding:16px 0 8px;
            position:relative;
        }
        .or:before, .or:after{
            content:"";
            position:absolute;
            top:50%;
            width:42%;
            height:1px;
            background:rgba(0,0,0,.18);
        }
        .or:before{left:0;}
        .or:after{right:0;}

        .help{
            text-align:center;
            font-size:13px;
            color:#444;
            padding-top:6px;
        }

        @media (max-width:420px){
            fieldset{padding:28px 22px 22px}
            legend{font-size:28px}
        }
    </style>
</head>

<body>

    <form method="post" action="../../Controllers/loginCheck.php">
      <label>Email</label>
      <input type="email" name="email" required placeholder="name@aiub.edu">

                            <a href="#">Forgot Password?</a>
                        </div>
                    </td>
                </tr>

                <tr>
                    <td colspan="2">
                        <input class="btn" type="submit" name="submit" value="Log in">
                    </td>
                </tr>

                <tr>
                    <td colspan="2" class="or">or</td>
                </tr>

                <tr>
                    <td colspan="2" class="help">
                        Don’t have an account? <a href="signup.php">Sign up</a>
                    </td>
                </tr>
            </table>

        </fieldset>
    </form>

</body>
</html>