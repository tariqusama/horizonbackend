<?php
$conn = new mysqli('127.0.0.1', 'root', '', 'horizon_db');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
$res = $conn->query("SELECT * FROM services");
if ($res) {
    while($row = $res->fetch_assoc()) {
        echo $row['title'] . " - " . $row['starting_price'] . "\n";
    }
} else {
    echo "Query failed: " . $conn->error;
}
$res2 = $conn->query("SELECT * FROM service_packages");
if ($res2) {
    while($row = $res2->fetch_assoc()) {
        echo $row['name'] . " - " . $row['price'] . "\n";
    }
} else {
    echo "Query failed: " . $conn->error;
}
