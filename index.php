<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Little Sun</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>

<body>
  <div class="flex">
    <?php include_once(__DIR__ . "/nav.inc.php") ?>
    <div class="ml-72 px-14 py-10">
      <h2 class="font-bold text-3xl pt-1">Hello World!</h2>
    </div>
  </div>
</body>

</html>