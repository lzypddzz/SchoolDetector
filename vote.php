<?php
$file = "votes.txt";

// 获取用户IP地址
function getUserIP() {
    if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
        return $_SERVER['HTTP_CLIENT_IP'];
    } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        return $_SERVER['HTTP_X_FORWARDED_FOR'];
    } else {
        return $_SERVER['REMOTE_ADDR'];
    }
}

$ip = getUserIP();

// 检查IP是否已经投票
$votes = file_get_contents($file);
if (strpos($votes, $ip) !== false) {
    echo json_encode(['success' => false, 'message' => '您已经投过票了']);
    exit();
}

// 添加新的投票记录
$handle = fopen($file, "a");
fwrite($handle, $ip . "\n");
fclose($handle);

// 计算新的票数
$votesArray = explode("\n", trim($votes));
$voteCount = count($votesArray) + 1; // 加上新投的一票

echo json_encode(['success' => true, 'voteCount' => $voteCount]);
?>
