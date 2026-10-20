<?php

// Hàm tính điểm tổng kết
function finalScore(float $cc, float $mid, float $final): float
{
    return $cc * 0.10 + $mid * 0.30 + $final * 0.60;
}

// Thử gọi hàm
$diem = finalScore(8, 7.5, 9);

echo "Điểm tổng kết: " . $diem;