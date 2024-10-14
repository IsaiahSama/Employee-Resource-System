<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $title; ?></title>
    <!-- <meta http-equiv="refresh" content="2"> -->
    <link
        rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/bulma@1.0.2/css/bulma.min.css"
        >
</head>
<body class="section">
    <?php if (isset($header)) require_once $header; ?>
    <?php require_once $content; ?>
</body>
</html>