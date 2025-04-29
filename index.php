<?php
define("JSON_PATH", __DIR__ . "/data/records.json");
define("XML_PATH", __DIR__ . "/data/records.xml");

function readJson() {
    return file_exists(JSON_PATH) ? json_decode(file_get_contents(JSON_PATH), true) : [];
}

function writeJson($data) {
    file_put_contents(JSON_PATH, json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
}

function writeXml($data) {
    $xml = new SimpleXMLElement('<records/>');
    foreach ($data as $record) {
        $r = $xml->addChild('record');
        foreach ($record as $key => $value) {
            $r->addChild($key, htmlspecialchars($value));
        }
    }
    file_put_contents(XML_PATH, $xml->asXML());
}

$records = readJson();

// Handle form submission (add record)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add'])) {
    $newRecord = [
        'id' => time(),
        'firstName' => $_POST['firstName'] ?? '',
        'lastName' => $_POST['lastName'] ?? '',
        'roomCount' => $_POST['roomCount'] ?? 0,
        'specialReq' => $_POST['specialReq'] ?? '',
        'stayDuration' => $_POST['stayDuration'] ?? 0
    ];
    $records[] = $newRecord;
    writeJson($records);
    writeXml($records);
    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
}

// Handle delete
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $records = array_filter($records, fn($r) => $r['id'] != $id);
    writeJson(array_values($records));
    writeXml(array_values($records));
    header("Location: " . strtok($_SERVER["REQUEST_URI"], '?'));
    exit;
}
?>

<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <title>Dormitory Registration</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <h1>Dormitory Registration Form</h1>
    <form method="POST">
        <label>Name: <input type="text" name="firstName" required></label>
        <label>Surname: <input type="text" name="lastName" required></label>
        <label>Number of Rooms: <input type="number" name="roomCount" min="1" required></label>
        <label>Special Requirements: <input type="text" name="specialReq"></label>
        <label>Stay Duration (days): <input type="number" name="stayDuration" min="1" required></label>
        <button type="submit" name="add" class="btn">Submit</button>
    </form>

    <h2>Registered Records</h2>
    <?php if (count($records) === 0): ?>
        <p>No records yet.</p>
    <?php else: ?>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Surname</th>
                    <th>Rooms</th>
                    <th>Requirements</th>
                    <th>Duration</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($records as $record): ?>
                    <tr>
                        <td><?= htmlspecialchars($record['id']) ?></td>
                        <td><?= htmlspecialchars($record['firstName']) ?></td>
                        <td><?= htmlspecialchars($record['lastName']) ?></td>
                        <td><?= htmlspecialchars($record['roomCount']) ?></td>
                        <td><?= htmlspecialchars($record['specialReq']) ?></td>
                        <td><?= htmlspecialchars($record['stayDuration']) ?></td>
                        <td><a class="btn" href="?delete=<?= $record['id'] ?>" onclick="return confirm('Delete this record?')">Delete</a></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</body>
</html>
