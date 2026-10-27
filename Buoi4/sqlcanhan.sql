
-- Câu 1: Lọc danh sách Độc giả đang bị khóa tài khoản kèm lý do (Dùng WHERE)
SELECT id, ma_doc_gia, ho_ten, email, so_dien_thoai, ly_do_khoa
FROM nguoi_dung
WHERE vai_tro = 'doc_gia' AND bi_khoa = 1;

-- Câu 2: Xem danh sách Độc giả và thông tin lượt mượn gần nhất của họ (Dùng JOIN + WHERE)
SELECT nd.id, nd.ho_ten, nd.email, pm.id AS ma_phieu_muon, pm.muon_luc, pm.han_tra
FROM nguoi_dung nd
JOIN phieu_muon pm ON nd.id = pm.nguoi_dung_id
WHERE nd.vai_tro = 'doc_gia' AND nd.bi_khoa = 0
ORDER BY pm.muon_luc DESC;

-- Câu 3: Thống kê số lượng lượt mượn sách của từng Độc giả (Dùng GROUP BY + JOIN + WHERE)
SELECT nd.id, nd.ho_ten, nd.email, COUNT(pm.id) AS tong_so_luot_muon
FROM nguoi_dung nd
LEFT JOIN phieu_muon pm ON nd.id = pm.nguoi_dung_id
WHERE nd.vai_tro = 'doc_gia'
GROUP BY nd.id, nd.ho_ten, nd.email
HAVING tong_so_luot_muon > 0;