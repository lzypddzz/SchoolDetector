<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>开学辣！！！！！</title>
    <style>
        body {
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            height: 100vh;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            font-size: 5vw;
            margin: 0;
            padding: 20px;
            box-sizing: border-box;
            background: #f0f4f8;
        }
        #countdown-container {
            display: flex;
            flex-direction: column;
            align-items: center;
            width: 100%;
            max-width: 500px;
        }
        #progress-container {
            width: 100%;
            height: 8vw;
            max-height: 40px;
            background-color: #e0e0e0;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
            margin-bottom: 20px;
        }
        #progress-bar {
            height: 100%;
            width: 0%;
            background: linear-gradient(90deg, #4caf50, #81c784);
            border-radius: 20px;
            transition: width 0.5s ease;
        }
        #countdown {
            text-align: center;
            color: #333;
        }
        #counter-container {
            margin-top: 30px;
            text-align: center;
        }
        #counter {
            font-size: 6vw;
            color: #e53935;
        }
        #vote-container {
            margin-top: 30px;
            text-align: center;
        }
        #vote-count {
            font-size: 6vw;
            color: #e53935;
        }
        button {
            font-size: 5vw;
            padding: 10px 20px;
            background-color: #4caf50;
            color: white;
            border: none;
            border-radius: 10px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }
        button:disabled {
            background-color: #c0c0c0;
            cursor: not-allowed;
        }
        button:hover:enabled {
            background-color: #388e3c;
        }
    </style>
</head>
<body>
    <div id="countdown-container">
        <div id="progress-container">
            <div id="progress-bar"></div>
        </div>
        <div id="countdown"></div>
    </div>

    <div id="counter-container">
        <div>不想开学加一：</div>
        <div id="counter">
            <?php
                // 读取计数器的当前值
                $counter = file_get_contents("counter.txt");
                echo $counter;
            ?>
        </div>
        <button id="increment-button">加一</button>
    </div>

    <div id="vote-container">
        <div>不想开学请愿投票：</div>
        <div id="vote-count">
            <?php
                // 读取票数的当前值
                $votes = file_get_contents("votes.txt");
                $votesArray = explode("\n", trim($votes));
                $voteCount = count($votesArray);
                echo $voteCount;
            ?>
        </div>
        <button id="vote-button">投票</button>
    </div>

    <script>
        function updateCountdown() {
            const targetDate = new Date('2024-08-25T18:30:00').getTime();
            const now = new Date().getTime();
            const timeLeft = targetDate - now;
            const totalMilliseconds = 42 * 24 * 60 * 60 * 1000;
            const percentage = 100 - (timeLeft / totalMilliseconds) * 100;
            document.getElementById('countdown').innerText = `距离开学还有 ${timeLeft} 毫秒`;
            document.getElementById('progress-bar').style.width = `${percentage}%`;
            setTimeout(updateCountdown, 1);
        }

        async function fetchCounter() {
            const response = await fetch('update_counter.php', {
                method: 'GET'
            });
            const data = await response.json();
            document.getElementById('counter').innerText = data.counter;
        }

        async function incrementCounter() {
            const response = await fetch('update_counter.php', {
                method: 'POST'
            });
            const data = await response.json();
            document.getElementById('counter').innerText = data.counter;
        }

        async function vote() {
            const response = await fetch('vote.php', {
                method: 'POST'
            });
            const data = await response.json();
            if (data.success) {
                document.getElementById('vote-count').innerText = data.voteCount;
                document.getElementById('vote-button').disabled = true;
            } else {
                alert(data.message);
            }
        }

        document.getElementById('increment-button').addEventListener('click', incrementCounter);
        document.getElementById('vote-button').addEventListener('click', vote);

        updateCountdown();
        setInterval(fetchCounter, 1000); // 每秒更新一次计数器
    </script>
</body>
</html>
