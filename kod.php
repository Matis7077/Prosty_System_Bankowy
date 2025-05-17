<?php
$uzytkownicy = [
    'Jan Kowalski' => 1000.00,
    'Anna Nowak' => 500.00,
    'Piotr Zieliński' => 750.00
];

$komunikat = "";
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $od = $_POST['od'] ?? '';
    $do = $_POST['do'] ?? '';
    $kwota = floatval($_POST['kwota'] ?? 0);
    
    if (!isset($uzytkownicy[$od])) {
        $komunikat = '❌ Konto źródłowe nie istnieje';
    } 
    elseif (!isset($uzytkownicy[$do])) {
        $komunikat = '❌ Konto docelowe nie istnieje';
    }
    elseif ($od === $do) {
        $komunikat = '❌ Nie można wykonać przelewu na to samo konto';
    }
    elseif ($kwota <= 0) {
        $komunikat = '❌ Kwota musi być większa od zera';
    }
    elseif ($uzytkownicy[$od] < $kwota) {
        $komunikat = '❌ Niewystarczające środki';
    }
    else {
        $uzytkownicy[$od] -= $kwota;
        $uzytkownicy[$do] += $kwota;
        $komunikat = "✅ Przelano $kwota PLN z konta $od na konto $do";
    }
}
?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <title>Prosty Bank</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f3f3f3;
            padding: 40px;
        }
        .container {
            max-width: 600px;
            background: white;
            padding: 30px;
            margin: auto;
            border-radius: 10px;
            box-shadow: 0 0 10px #ccc;
        }
        input, select {
            width: 100%;
            padding: 10px;
            margin-top: 10px;
            margin-bottom: 20px;
        }
        button {
            background-color: #28a745;
            color: white;
            padding: 12px;
            border: none;
            width: 100%;
            border-radius: 5px;
            font-weight: bold;
            cursor: pointer;
        }
        .message {
            padding: 10px;
            margin-bottom: 20px;
            font-weight: bold;
        }
        .saldo {
            background: #f9f9f9;
            padding: 15px;
            margin-bottom: 30px;
            border-left: 5px solid #007bff;
        }
    </style>
</head>
<body>
<div class="container">
    <h2>💰 Prosty Bank - Demo</h2>

    <?php if ($komunikat): ?>
        <div class="message"><?= $komunikat ?></div>
    <?php endif; ?>

    <div class="saldo">
        <strong>Salda użytkowników:</strong><br>
        <?php foreach ($uzytkownicy as $uzytkownik => $saldo): ?>
            <?= htmlspecialchars($uzytkownik) ?>: <?= number_format($saldo, 2) ?> PLN<br>
        <?php endforeach; ?>
    </div>

    <form method="POST">
        <label for="od">Od (nadawca):</label>
        <select name="od" required>
            <?php foreach ($uzytkownicy as $uzytkownik => $_): ?>
                <option value="<?= $uzytkownik ?>"><?= $uzytkownik ?></option>
            <?php endforeach; ?>
        </select>

        <label for="do">Do (odbiorca):</label>
        <select name="do" required>
            <?php foreach ($uzytkownicy as $uzytkownik => $_): ?>
                <option value="<?= $uzytkownik ?>"><?= $uzytkownik ?></option>
            <?php endforeach; ?>
        </select>

        <label for="kwota">Kwota (PLN):</label>
        <input type="number" name="kwota" step="0.01" min="0.01" required>

        <button type="submit">Wykonaj przelew</button>
    </form>
</div>
</body>
</html>
