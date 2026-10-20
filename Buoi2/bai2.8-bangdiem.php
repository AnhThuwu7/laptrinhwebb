<?php
// 1. Mảng 3 sinh viên
$students = [
    ['name' => 'An',   'midterm' => 7.5, 'final' => 8.0],
    ['name' => 'Bình', 'midterm' => 5.0, 'final' => 4.5],
    ['name' => 'Chi',  'midterm' => 8.5, 'final' => 9.0],
];

// 2. Hàm tính điểm trung bình
function calculateAverage(float $midterm, float $final): float
{
    return ($midterm + $final) / 2;
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Bảng điểm sinh viên</title>
    <style>
        table { border-collapse: collapse; width: 500px; }
        th, td { border: 1px solid #333; padding: 8px 12px; text-align: center; }
        th { background: #eee; }
    </style>
</head>
<body>
    <h2>Bảng điểm sinh viên</h2>
    <table>
        <tr>
            <th>Họ tên</th>
            <th>Giữa kỳ</th>
            <th>Cuối kỳ</th>
            <th>Trung bình</th>
            <th>Kết quả</th>
        </tr>

        <?php foreach ($students as $sv): ?>
            <?php
                $avg = calculateAverage($sv['midterm'], $sv['final']);
                $ket_qua = $avg >= 5 ? 'Đạt' : 'Chưa đạt';
            ?>
            <tr>
                <td><?= htmlspecialchars($sv['name']) ?></td>
                <td><?= $sv['midterm'] ?></td>
                <td><?= $sv['final'] ?></td>
                <td><?= number_format($avg, 2) ?></td>
                <td><?= $ket_qua ?></td>
            </tr>
        <?php endforeach; ?>
    </table>
</body>
</html>