<?php

require 'vendor/autoload.php';

use App\SQLiteConnection as SQLiteConnection;
use App\SQLiteCreateTable as SQLiteCreateTable;
use App\ExtractProgramData as ExtractProgramData;

try {

    $pdo = (new SQLiteConnection())->connect();

    $sqlite = new SQLiteCreateTable($pdo);
    // create new table
    $sqlite->createTables();

    // get the data from the website
    $extractor = new ExtractProgramData;
    $extractor->extractData();

    if (!empty($_GET['channel'])) {
        $channel = $_GET['channel'];
    } else {
        $channel = null;
    }

    $sqlite->insertProgram($extractor->getExtractedPrograms(5));
    // get the list
    $result = $sqlite->getTableList($channel);

    //get channels
    $channels = $sqlite->getChannels();
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
            <?php foreach ($channels as $channel) : ?>
                <p><a href="/tv/?channel=<?= $channel['channel_name'] ?>"> <?= $channel['channel_name'] ?> </a> </p>
            <?php endforeach; ?>
        </div>

        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Csatora</th>
                    <th>Időpont</th>
                    <th>Cím</th>
                    <th>Leírás</th>
                </tr>
            </thead>

            <?php echo $result ?>
        </table>
    </div>
</body>

</html>