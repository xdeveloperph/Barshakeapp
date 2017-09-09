<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Barshakeapp" />
    <meta name="keywords" content="storm, news, weather, local, online, daily" />
    <meta name="author" content="Codrops" />
    <script src="//code.jquery.com/jquery-1.11.3.min.js"></script>
    <script src="http://localhost/barshakeapp/subscription/js/parse-1.6.2.min.js"></script>
    <script>


        $(document).ajaxStop(function () {
    var applicationId ="pFf1vJma5zjk7duy9llyDe8pjJk5nlG4OsyjtJxq";
    var javaScriptKey ="hdXFehq10vi444zlBu8RHgjBkTPe9I7MvWpIP4Y9";
    var masterKey ="ILVh4b3cZxqzPbZUFNBu4CCkYQwc7SjK9EU9V0NR";
    Parse.initialize( applicationId, javaScriptKey, masterKey );
    var query = new Parse.Query(Parse.User);
    query.equalTo("username", "<?php echo $_GET["username"] ?>");  // find all the women
    query.find({
        success: function(women) {
            $("#strinvalue").html(women[0].id);
        }
    });
        });
    </script>
</head>
<body>
    <div id="strinvalue"></div>
</body>
</html>
