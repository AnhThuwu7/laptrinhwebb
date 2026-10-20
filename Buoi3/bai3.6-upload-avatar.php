<?php
$thong_bao = '';
$anh_url   = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // 1. Kiểm tra có file được gửi lên không
    if (!isset($_FILES['avatar']) || $_FILES['avatar']['error'] === UPLOAD_ERR_NO_FILE) {
        $thong_bao = 'Vui lòng chọn ảnh.';
    } else {
        $file = $_FILES['avatar'];

        // 2. Kiểm tra lỗi upload
        if ($file['error'] !== UPLOAD_ERR_OK) {
            $thong_bao = 'Lỗi khi upload file.';
        } else {
            // 3. Kiểm tra kích thước (tối đa 2MB)
            $maxSize = 2 * 1024 * 1024;
            if ($file['size'] > $maxSize) {
                $thong_bao = 'Ảnh không được lớn hơn 2MB.';
            } else {
                // 4. Kiểm tra phần mở rộng
                $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
                $allowedExt = ['jpg', 'jpeg', 'png', 'gif', 'webp'];

                if (!in_array($ext, $allowedExt)) {
                    $thong_bao = 'Chỉ chấp nhận ảnh JPG, PNG, GIF, WEBP.';
                } else {
                    // 5. (Khuyến nghị) Kiểm tra MIME type thật
                    $finfo = finfo_open(FILEINFO_MIME_TYPE);
                    $mime  = finfo_file($finfo, $file['tmp_name']);
                    finfo_close($finfo);

                    $allowedMime = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
                    if (!in_array($mime, $allowedMime)) {
                        $thong_bao = 'File không phải là ảnh hợp lệ.';
                    } else {
                        // 6. Đổi tên file an toàn
                        $newName = uniqid('avatar_', true) . '.' . $ext;
                        $uploadDir = __DIR__ . '/uploads/';

                        // Tạo thư mục nếu chưa có
                        if (!is_dir($uploadDir)) {
                            mkdir($uploadDir, 0755, true);
                        }

                        $dest = $uploadDir . $newName;

                        if (move_uploaded_file($file['tmp_name'], $dest)) {
                            $thong_bao = 'Upload thành công!';
                            $anh_url = 'uploads/' . $newName;
                        } else {
                            $thong_bao = 'Không thể lưu file.';
                        }
                    }
                }
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Upload ảnh đại diện</title>
</head>
<body>
    <h2>Upload ảnh đại diện</h2>

    <form method="post" enctype="multipart/form-data">
        <input type="file" name="avatar" accept="image/*">
        <button type="submit">Upload</button>
    </form>

    <?php if ($thong_bao): ?>
        <p><?= htmlspecialchars($thong_bao) ?></p>
    <?php endif; ?>

    <?php if ($anh_url): ?>
        <p>Ảnh đã upload:</p>
        <img src="<?= htmlspecialchars($anh_url) ?>" alt="Avatar" width="150">
    <?php endif; ?>
</body>
</html>