<?php
$errors = [];
$data = [
    'name'     => '',
    'email'    => '',
    'birthday' => ''
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data['name']     = trim($_POST['name'] ?? '');
    $data['email']    = trim($_POST['email'] ?? '');
    $data['birthday'] = trim($_POST['birthday'] ?? '');

    // Kiểm tra bắt buộc
    if ($data['name'] === '') {
        $errors['name'] = 'Vui lòng nhập họ tên';
    }
    if ($data['email'] === '') {
        $errors['email'] = 'Vui lòng nhập email';
    } elseif (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = 'Email không hợp lệ';
    }
    if ($data['birthday'] === '') {
        $errors['birthday'] = 'Vui lòng nhập ngày sinh';
    }

    // Nếu không có lỗi → chuyển trang thành công
    if (!$errors) {
        // Có thể lưu vào session hoặc database ở đây
        header('Location: success.php');
        exit;
    }
}

// Hàm mã hóa output
function e($v) {
    return htmlspecialchars($v, ENT_QUOTES, 'UTF-8');
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Đăng ký sinh viên</title>
</head>
<body>
    <h2>Đăng ký sinh viên</h2>

    <form method="post">
        <p>
            <label>Họ tên</label><br>
            <input type="text" name="name" value="<?= e($data['name']) ?>">
            <?php if (!empty($errors['name'])): ?>
                <span style="color:red"><?= e($errors['name']) ?></span>
            <?php endif; ?>
        </p>

        <p>
            <label>Email</label><br>
            <input type="email" name="email" value="<?= e($data['email']) ?>">
            <?php if (!empty($errors['email'])): ?>
                <span style="color:red"><?= e($errors['email']) ?></span>
            <?php endif; ?>
        </p>

        <p>
            <label>Ngày sinh</label><br>
            <input type="date" name="birthday" value="<?= e($data['birthday']) ?>">
            <?php if (!empty($errors['birthday'])): ?>
                <span style="color:red"><?= e($errors['birthday']) ?></span>
            <?php endif; ?>
        </p>

        <button type="submit">Đăng ký</button>
    </form>
</body>
</html>