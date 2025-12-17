<?php
/**
 * 모든 회장/총무의 SMS_2를 자동 업데이트하는 함수
 * 회원 추가/수정/삭제 시 호출
 * 
 * @param PDO $pdo - 데이터베이스 연결 객체
 * @return bool - 성공 여부
 */
function updateLeadersSms2($pdo) {
    try {
        // 전체 회원 전화번호 조회
        $stmt_all = $pdo->prepare("SELECT tel FROM tel WHERE tel != '' ORDER BY name ASC");
        $stmt_all->execute();
        $all_numbers = $stmt_all->fetchAll(PDO::FETCH_COLUMN);

        if (empty($all_numbers)) return true;

        // 회장/총무 목록 조회
        $stmt_leaders = $pdo->prepare("SELECT idx, tel, remark FROM tel WHERE (remark LIKE '%회장%' OR remark LIKE '%총무%')");
        $stmt_leaders->execute();
        $leaders = $stmt_leaders->fetchAll(PDO::FETCH_ASSOC);

        if (empty($leaders)) return true;

        $stmt_update = $pdo->prepare("UPDATE tel SET sms_2 = :sms_2 WHERE idx = :idx");

        foreach ($leaders as $leader) {
            $filtered = array_filter($all_numbers, fn($num) => $num !== $leader['tel']);
            $sms_2 = implode(',', $filtered);

            $stmt_update->execute([
                ':sms_2' => $sms_2,
                ':idx' => $leader['idx']
            ]);
        }

        return true;

    } catch (PDOException $e) {
        error_log("SMS_2 업데이트 오류: " . $e->getMessage());
        return false;
    }
}
?>
