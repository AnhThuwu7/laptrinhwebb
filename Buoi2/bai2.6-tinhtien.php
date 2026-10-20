<?php
$ten = '';
$thanh_tien = 0;
$thong_bao = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $ten      = trim($_POST['ten'] ?? '');
    $soluong  = (float) ($_POST['soluong'] ?? 0);
    $dongia   = (float) ($_POST['dongia'] ?? 0);

    $thanh_tien = $soluong * $dongia;

    if ($ten !== '' && $thanh_tien > 0) {
        $thong_bao = $ten . ': ' . number_format($thanh_tien, 0, ',', '.') . ' đ';
    } else {
        $thong_bao = 'Vui lòng nhập đầy đủ thông tin.';
    }
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Tính tiền tài liệu</title>
</head>
<body>
    <form method="post">
        <input type="text" name="ten" placeholder="Tên tài liệu" value="<?= htmlspecialchars($ten) ?>">
        <input type="number" name="soluong" placeholder="Số lượng" step="1" min="0">
        <input type="number" name="dongia" placeholder="Đơn giá" step="1000" min="0">
        <button type="submit">Tính</button>
    </form>

    <?php if ($thong_bao): ?>
        <p><?= htmlspecialchars($thong_bao) ?></p>
    <?php endif; ?>
</body>
</html>