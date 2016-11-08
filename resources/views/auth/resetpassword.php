<html>
<head>
    <title></title>    
</head>
<body>
    <form action="/newpassword" method="POST">
        Please input new password.. your resetcode is : <?php echo $resetcode;?> and i put it in hidden field (this message is for mas mahdan) please delete it whenenever you've read it
        Jangan lupa ngirim endpoint ke API nya hasil MD5 dari password ini .. mirip kaya register
        <input type="hidden" name="resetcode" value="<?php echo $resetcode;?>">
        <p>Password :</p>
        <input type="text" name="password">
        <p>Confirm Password :</p>
        <input type="text" name="confirm_password">
        <input type="submit">
    </form>
</body>
</html>