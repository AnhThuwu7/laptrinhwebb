<?php
$diem = 8.5; 

if (is_numeric($diem) && $diem >= 0 && $diem <= 10) {
    echo "Điểm hợp lệ";
} else {
    echo "Điểm không hợp lệ";
}