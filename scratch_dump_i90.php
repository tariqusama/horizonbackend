<?php
$conn = new mysqli('127.0.0.1', 'root', '', 'horizon_db');
if ($conn->connect_error) die("Connection failed");

$res = $conn->query("SELECT sections FROM checklists WHERE `key` = 'sibling_abroad'");
if ($row = $res->fetch_assoc()) {
    echo $row['sections'];
} else {
    echo "Not found";
}
