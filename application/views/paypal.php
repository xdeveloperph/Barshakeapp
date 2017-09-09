<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Barshakeapp" />
    <title>Bar Shake</title>
</head>
<body>

<?php

    $attributes = array( "id" => "userform", "name" => "userform");
    echo form_open("https://www.paypal.com/cgi-bin/webscr", $attributes,$hidden);
?>

</form>
<script>
    document.getElementById("userform").submit();
</script>
</body>

</html>
