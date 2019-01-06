<!DOCTYPE html>
<html class="no-js " lang="en">
<head>
    <!-- Basic Page Needs -->
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>King Maps - Error {{$code}}</title>
    <meta name="description" content="#">
    <meta name="keywords" content="#">
    <meta name="author" content="#">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link href="https://fonts.googleapis.com/css?family=Lato:400,700" rel="stylesheet">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.6/css/bootstrap.min.css">
    <link rel="stylesheet" href="/frontend/assets/fonts/stylesheet.css">
    <link rel="stylesheet" href="/frontend/assets/fonts/ionicons/css/fontello.css">
    <!-- Custom CSS  -->
    <link rel="stylesheet" href="/frontend/assets/css/main.min.css">
</head>

<body>
    @include("Location.error.".$code)
</body>

</html>