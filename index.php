<?php
session_start();

if (!isset($_SESSION['initialized'])) {
    session_unset();
    $_SESSION['initialized'] = true;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['setRange'])) {
        $_SESSION['maxRange'] = (int)$_POST['setRange'];
    }
    
    if (isset($_POST['generate'])) {
        $_SESSION['num1'] = rand(0, $_SESSION['maxRange'] ?? 10);
        $_SESSION['num2'] = rand(0, $_SESSION['maxRange'] ?? 10);
        $_SESSION['operator'] = $_POST['generate'];
        $_SESSION['feedback'] = '';
    }
    
    if (isset($_POST['check'])) {
        $num1 = $_SESSION['num1'];
        $num2 = $_SESSION['num2'];
        $operator = $_SESSION['operator'];
        $userResult = (int)$_POST['result'];
        
        switch ($operator) {
            case '+': $correctResult = $num1 + $num2; break;
            case '-': $correctResult = $num1 - $num2; break;
            case '*': $correctResult = $num1 * $num2; break;
            default: $correctResult = null;
        }
        
        $_SESSION['feedback'] = ($userResult === $correctResult) ? "Correct!" : "Wrong! Try again.";
    }
    
    if (isset($_POST['showAnswer'])) {
        $num1 = $_SESSION['num1'];
        $num2 = $_SESSION['num2'];
        $operator = $_SESSION['operator'];
        $correctResult = eval('return '.$num1 . $operator . $num2.';');
        $_SESSION['feedback'] = $correctResult;
    }
    
    header("Location: {$_SERVER['PHP_SELF']}");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Math Game</title>
    <link rel="stylesheet" href="task.css">
</head>
<body>
    <form method="post">
        <div class="buttons">
        <?php $selectedRange = $_SESSION['maxRange'] ?? 10; ?>
            <button name="setRange" value="10" <?= $selectedRange == 10 ? 'class="selected"' : '' ?>>0-10</button>
            <button name="setRange" value="20" <?= $selectedRange == 20 ? 'class="selected"' : '' ?>>0-20</button>
            <button name="setRange" value="100" <?= $selectedRange == 100 ? 'class="selected"' : '' ?>>0-100</button>
            <button name="setRange" value="150" <?= $selectedRange == 150 ? 'class="selected"' : '' ?>>0-150</button>
        </div>
        <div class="operations">
            <button name="generate" value="+">+</button>
            <button name="generate" value="-">-</button>
            <button name="generate" value="*">*</button>
        </div>
        <div class="input-panel">
            <input type="text" value="<?= $_SESSION['num1'] ?? '' ?>" disabled>
            <input type="text" value="<?= $_SESSION['operator'] ?? '' ?>" disabled>
            <input type="text" value="<?= $_SESSION['num2'] ?? '' ?>" disabled>
            <span>=</span>
            <input type="text" name="result">
            <button name="showAnswer">?</button>
            <input type="text" value="<?= $_SESSION['feedback'] ?? '???' ?>" disabled>
        </div>
        <div class="number-panel">
            <?php for ($i = 0; $i <= 9; $i++): ?>
                <button type="button" class="number-button" onclick="document.querySelector('[name=result]').value += '<?= $i ?>';"><?= $i ?></button>
            <?php endfor; ?>
            <button class="submit-button" type="submit" name="check">Submit</button>
        </div>
    </form>
</body>
</html>