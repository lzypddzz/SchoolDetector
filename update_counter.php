<?php
$file = "counter.txt";

// 如果请求是GET方法，返回当前计数器值
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $counter = file_get_contents($file);
    echo json_encode(['counter' => intval($counter)]);
    exit();
}

// 如果请求是POST方法，增加计数器
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $handle = fopen($file, "r+");

    if (flock($handle, LOCK_EX)) {
        $counter = intval(fread($handle, filesize($file)));
        $counter++;
        ftruncate($handle, 0);
        rewind($handle);
        fwrite($handle, $counter);
        flock($handle, LOCK_UN);
    }

    fclose($handle);
    echo json_encode(['counter' => $counter]);
    exit();
}
?>
