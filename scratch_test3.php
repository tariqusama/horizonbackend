<?php
$conn = new mysqli('127.0.0.1', 'root', '', 'horizon_db');
$res = $conn->query("SELECT id, title FROM services");
$services = [];
while($row = $res->fetch_assoc()) $services[$row['id']] = $row['title'];

$res2 = $conn->query("SELECT service_id, name, price FROM service_packages");
while($row = $res2->fetch_assoc()) {
    echo "Service: " . $services[$row['service_id']] . " | Pkg: " . $row['name'] . " | Price: " . $row['price'] . "\n";
}
