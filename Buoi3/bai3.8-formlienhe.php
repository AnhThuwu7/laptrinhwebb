<?php
$errors  = [];
$success = false;
$old = [
    'ho_ten'   => '',
    'email'    => '',
    'chu_de'   => 'Hỗ trợ kỹ thuật',
    'noi_dung' => ''
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $old['ho_ten']   = trim($_POST['ho_ten'] ?? '');
    $old['email']    = trim($_POST['email'] ?? '');
    $old['chu_de']   = trim($_POST['chu_de'] ?? '');
    $old['noi_dung'] = trim($_POST['noi_dung'] ?? '');

    // 1. Họ tên không rỗng
    if ($old['ho_ten'] === '') {
        $errors['ho_ten'] = 'Vui lòng nhập họ tên.';
    }

    // 2. Email đúng định dạng
    if ($old['email'] === '') {
        $errors['email'] = 'Vui lòng nhập email.';
    } elseif (!filter_var($old['email'], FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = 'Email không đúng định dạng.';
    }

    // 3. Nội dung không rỗng + độ dài
    if ($old['noi_dung'] === '') {
        $errors['noi_dung'] = 'Vui lòng nhập nội dung.';
    } elseif (mb_strlen($old['noi_dung']) < 10 || mb_strlen($old['noi_dung']) > 500) {
        $errors['noi_dung'] = 'Nội dung phải từ 10 đến 500 ký tự.';
    }

    // 4. Upload ảnh đại diện (3 kiểm tra)
    if (!isset($_FILES['anh']) || $_FILES['anh']['error'] === UPLOAD_ERR_NO_FILE) {
        $errors['anh'] = 'Vui lòng chọn ảnh đại diện.';
    } else {
        $file = $_FILES['anh'];
        $maxSize = 2 * 1024 * 1024; // 2MB
        $allowed = ['jpg', 'jpeg', 'png', 'gif', 'webp'];

        if ($file['error'] !== UPLOAD_ERR_OK) {
            $errors['anh'] = 'Lỗi upload ảnh.';
        } elseif ($file['size'] > $maxSize) {
            $errors['anh'] = 'Ảnh không được lớn hơn 2MB.';
        } else {
            $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
            if (!in_array($ext, $allowed)) {
                $errors['anh'] = 'Chỉ chấp nhận ảnh JPG, PNG, GIF, WEBP.';
            }
        }
    }

    // Thành công
    if (!$errors) {
        $success = true;
        // Có thể lưu ảnh ở đây nếu cần
        $old = ['ho_ten'=>'','email'=>'','chu_de'=>'Hỗ trợ kỹ thuật','noi_dung'=>''];
    }
}

function e($v) {
    return htmlspecialchars($v ?? '', ENT_QUOTES, 'UTF-8');
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Form Liên hệ</title>
    <style>
        body { font-family: Arial, sans-serif; max-width: 480px; margin: 40px auto; }
        label { display: block; margin-top: 12px; font-weight: bold; }
        input, select, textarea { width: 100%; padding: 8px; margin-top: 4px; }
        .error { color: red; font-size: 14px; }
        .success { color: green; background: #e8f5e9; padding: 10px; border-radius: 6px; }
        button { margin-top: 16px; padding: 10px 24px; background: #2563eb; color: white; border: none; cursor: pointer; }
    </style>
</head>
<body>
    <h2>Liên hệ</h2>
    <p>Vui lòng nhập đầy đủ thông tin bên dưới.</p>

    <?php if ($success): ?>
        <div class="success">Gửi liên hệ thành công!</div>
    <?php endif; ?>

    <form method="post" enctype="multipart/form-data">
        <label>Họ tên</label>
        <input type="text" name="ho_ten" value="<?= e($old['ho_ten']) ?>">
        <?php if (!empty($errors['ho_ten'])): ?><div class="error"><?= e($errors['ho_ten']) ?></div><?php endif; ?>

        <label>Email</label>
        <input type="email" name="email" value="<?= e($old['email']) ?>">
        <?php if (!empty($errors['email'])): ?><div class="error"><?= e($errors['email']) ?></div><?php endif; ?>

        <label>Chủ đề</label>
        <select name="chu_de">
            <option value="Hỗ trợ kỹ thuật" <?= $old['chu_de']==='Hỗ trợ kỹ thuật'?'selected':'' ?>>Hỗ trợ kỹ thuật</option>
            <option value="Góp ý" <?= $old['chu_de']==='Góp ý'?'selected':'' ?>>Góp ý</option>
            <option value="Khiếu nại" <?= $old['chu_de']==='Khiếu nại'?'selected':'' ?>>Khiếu nại</option>
        </select>

        <label>Nội dung</label>
        <textarea name="noi_dung" rows="4"><?= e($old['noi_dung']) ?></textarea>
        <?php if (!empty($errors['noi_dung'])): ?><div class="error"><?= e($errors['noi_dung']) ?></div><?php endif; ?>

        <label>Ảnh đại diện</label>
        <input type="file" name="anh" accept="image/*">
        <?php if (!empty($errors['anh'])): ?><div class="error"><?= e($errors['anh']) ?></div><?php endif; ?>

        <button type="submit">Gửi liên hệ</button>
    </form>
</body>
</html>