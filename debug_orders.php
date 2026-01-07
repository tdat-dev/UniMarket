<?php
$c = require __DIR__ . '/config/database.php';
$p = new PDO('mysql:host=' . $c['host'] . ';dbname=' . $c['db_name'], $c['username'], $c['password']);
$r = $p->query('SELECT id, payment_method, payment_status, payos_order_code, payment_link_id FROM orders ORDER BY id DESC LIMIT 5');
foreach ($r as $row) {
    print_r($row);
}
