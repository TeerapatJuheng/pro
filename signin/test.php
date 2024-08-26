<?php
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <style>

    [popover]::backdrop {
        backdrop-filter: blur(1.5pxX);
    }

    </style>
    <title>Document</title>
</head>
<body>
    
    <button popovertarget="confirm">
        delete
    </button>

    <div id="confirm" popover>
        <p>Aer you sure though?</p>
        <button>no</button>
    </div>

</body>
</html>