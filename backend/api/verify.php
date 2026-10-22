<?php
/**
 * API: Verify NOC / Certificate by Document Number or QR Hash
 * Method: GET or POST
 * Author: MD. Mahamudul Hasan (IT Officer Candidate)
 */

require_once __DIR__ . '/../db_connect.php';

// Allow CORS
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

$query_param = isset($_GET['doc']) ? trim($_GET['doc']) : '';
$hash_param  = isset($_GET['hash']) ? trim($_GET['hash']) : '';

if (empty($query_param) && empty($hash_param)) {
    // Check POST payload if JSON sent
    $input = json_decode(file_get_contents('php://input'), true);
    if (!empty($input['doc']))  $query_param = trim($input['doc']);
    if (!empty($input['hash'])) $hash_param  = trim($input['hash']);
}

if (empty($query_param) && empty($hash_param)) {
    http_response_code(400);
    echo json_encode([
        'status'  => 'error',
        'message' => 'Please provide document number (doc) or QR hash (hash).'
    ]);
    exit;
}

try {
    if (!empty($query_param)) {
        $stmt = $pdo->prepare("
            SELECT 
                c.id, c.document_number, c.document_type, c.purpose_title,
                c.authorized_representative, c.issue_date, c.valid_until,
                c.issuing_authority, c.cryptographic_hash, c.status AS document_status,
                m.organization_name, m.sector, m.contact_email, m.contact_phone, m.membership_type
            FROM tbl_noc_certificates c
            JOIN tbl_members m ON c.member_id = m.id
            WHERE c.document_number = :doc
            LIMIT 1
        ");
        $stmt->execute(['doc' => $query_param]);
    } else {
        $stmt = $pdo->prepare("
            SELECT 
                c.id, c.document_number, c.document_type, c.purpose_title,
                c.authorized_representative, c.issue_date, c.valid_until,
                c.issuing_authority, c.cryptographic_hash, c.status AS document_status,
                m.organization_name, m.sector, m.contact_email, m.contact_phone, m.membership_type
            FROM tbl_noc_certificates c
            JOIN tbl_members m ON c.member_id = m.id
            WHERE c.cryptographic_hash LIKE :hash
            LIMIT 1
        ");
        $stmt->execute(['hash' => '%' . $hash_param . '%']);
    }

    $doc = $stmt->fetch();

    $ip = $_SERVER['REMOTE_ADDR'] ?? '127.0.0.1';
    $ua = $_SERVER['HTTP_USER_AGENT'] ?? 'Unknown';

    if ($doc) {
        // Record audit log
        $log = $pdo->prepare("INSERT INTO tbl_verification_logs (document_number, ip_address, user_agent, verification_result) VALUES (?, ?, ?, ?)");
        $log->execute([$doc['document_number'], $ip, $ua, 'Verified Authentic']);

        echo json_encode([
            'status'     => 'success',
            'verified'   => true,
            'result'     => 'AUTHENTIC & ACTIVE',
            'data'       => $doc,
            'verified_at'=> date('Y-m-d H:i:s'),
            'verifier'   => 'BSREA Digital Security Gateway'
        ]);
    } else {
        $searchKey = $query_param ?: $hash_param;
        $log = $pdo->prepare("INSERT INTO tbl_verification_logs (document_number, ip_address, user_agent, verification_result) VALUES (?, ?, ?, ?)");
        $log->execute([$searchKey, $ip, $ua, 'Not Found']);

        http_response_code(404);
        echo json_encode([
            'status'   => 'not_found',
            'verified' => false,
            'message'  => 'No authentic BSREA document matches the specified identification number or hash.'
        ]);
    }
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode([
        'status'  => 'error',
        'message' => 'Query error: ' . $e->getMessage()
    ]);
}
