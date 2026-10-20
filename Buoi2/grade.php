<?php
// Hàm kiểm tra điểm hợp lệ (0–10)
function isValidScore($score): bool
{
    return is_numeric($score) && $score >= 0 && $score <= 10;
}

// Hàm tính điểm tổng kết
function finalScore(float $cc, float $mid, float $final): float
{
    return $cc * 0.10 + $mid * 0.30 + $final * 0.60;
}

// Hàm xếp loại
function xepLoai(float $diem): string
{
    if ($diem >= 8) return 'Giỏi';
    if ($diem >= 6.5) return 'Khá';
    if ($diem >= 5) return 'Trung bình';
    return 'Chưa đạt';
}

// Dữ liệu form
$old = [
    'hoten'  => '',
    'cc'     => '',
    'mid'    => '',
    'final'  => ''
];
$errors = [];
$result = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $hoten = trim($_POST['hoten'] ?? '');
    $cc    = $_POST['cc'] ?? '';
    $mid   = $_POST['mid'] ?? '';
    $final = $_POST['final'] ?? '';

    // Giữ lại dữ liệu đã nhập
    $old = compact('hoten', 'cc', 'mid', 'final');

    // Kiểm tra họ tên
    if ($hoten === '') {
        $errors['hoten'] = 'Vui lòng nhập họ tên.';
    }

    // Kiểm tra điểm
    if (!isValidScore($cc)) {
        $errors['cc'] = 'Điểm chuyên cần phải từ 0 đến 10.';
    }
    if (!isValidScore($mid)) {
        $errors['mid'] = 'Điểm giữa kỳ phải từ 0 đến 10.';
    }
    if (!isValidScore($final)) {
        $errors['final'] = 'Điểm cuối kỳ phải từ 0 đến 10.';
    }

    // Nếu không có lỗi → tính điểm
    if (empty($errors)) {
        $tongket = finalScore((float)$cc, (float)$mid, (float)$final);
        $result = [
            'hoten'   => $hoten,
            'tongket' => $tongket,
            'xeploai' => xepLoai($tongket)
        ];
    }
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Máy tính điểm học phần</title>
    <style>
        body { font-family: Arial, sans-serif; max-width: 480px; margin: 40px auto; }
        label { display: block; margin-top: 12px; font-weight: bold; }
        input { width: 100%; padding: 8px; margin-top: 4px; }
        .error { color: red; font-size: 14px; }
        .result { margin-top: 20px; padding: 12px; background: #e8f5e9; border-radius: 6px; }
        button { margin-top: 16px; padding: 10px 20px; cursor: pointer; }
    </style>
</head>
<body>
    <h2>Máy tính điểm học phần</h2>

    <form method="post">
        <label>Họ tên</label>
        <input type="text" name="hoten" value="<?= htmlspecialchars($old['hoten']) ?>">
        <?php if (!empty($errors['hoten'])): ?>
            <div class="error"><?= $errors['hoten'] ?></div>
        <?php endif; ?>

        <label>Điểm chuyên cần (10%)</label>
        <input type="number" step="0.1" name="cc" value="<?= htmlspecialchars($old['cc']) ?>">
        <?php if (!empty($errors['cc'])): ?>
            <div class="error"><?= $errors['cc'] ?></div>
        <?php endif; ?>

        <label>Điểm giữa kỳ (30%)</label>
        <input type="number" step="0.1" name="mid" value="<?= htmlspecialchars($old['mid']) ?>">
        <?php if (!empty($errors['mid'])): ?>
            <div class="error"><?= $errors['mid'] ?></div>
        <?php endif; ?>

        <label>Điểm cuối kỳ (60%)</label>
        <input type="number" step="0.1" name="final" value="<?= htmlspecialchars($old['final']) ?>">
        <?php if (!empty($errors['final'])): ?>
            <div class="error"><?= $errors['final'] ?></div>
        <?php endif; ?>

        <button type="submit">Tính điểm</button>
    </form>

    <?php if ($result): ?>
        <div class="result">
            <p><strong>Họ tên:</strong> <?= htmlspecialchars($result['hoten']) ?></p>
            <p><strong>Điểm tổng kết:</strong> <?= number_format($result['tongket'], 2) ?></p>
            <p><strong>Xếp loại:</strong> <?= $result['xeploai'] ?></p>
        </div>
    <?php endif; ?>
</body>
</html>