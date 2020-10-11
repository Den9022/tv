<?php

require 'vendor/autoload.php';

use App\SQLiteConnection as SQLiteConnection;
use App\SQLiteCreateTable as SQLiteCreateTable;

try {

    $pdo = (new SQLiteConnection())->connect();

    $sqlite = new SQLiteCreateTable($pdo);
    // create new tables
    $sqlite->createTables();
    // get the table list
    $tables = $sqlite->getTableList();
} catch (PDOException $e) {
    echo $e->getMessage();
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <title>Tv Műsor</title>
    <link href="http://v4-alpha.getbootstrap.com/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div class="container">
        <div class="page-header">
            <h1>Tv Műsor</h1>
        </div>

        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Tables</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($tables as $table) : ?>
                    <tr>

                        <td><?php echo $table ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>

</html>