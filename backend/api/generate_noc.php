<?php
/**
 * API: Generate & Issue New Digital NOC / Certificate with Cryptographic QR Hash
 * Method: POST
 * Author: MD. Mahamudul Hasan
 */

require_once __DIR__ . '/../db_connect.php';

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

$input = json_decode(file_get_contents('php://input'), true);

if (!$input) {
    http_response_code(400);
    echo json_encode(['status' => 'error', 'message' => 'Invalid JSON payload.']);
    exit;
}

$member_id      = isset($input['member_id']) ? intval($input['member_id']) : 0;
$document_type  = $input['document_type'] ?? 'NOC - Grid Interconnection';
$purpose_title  = trim($input['purpose_title'] ?? '');
$representative = trim($input['authorized_representative'] ?? '');
$validity_months= isset($input['validity_months']) ? intval($input['validity_months']) : 12;

if (!$member_id || empty($purpose_title) || empty($representative)) {
    http_response_code(422);
    echo json_encode(['status' => 'error', 'message' => 'Member ID, purpose title, and authorized representative are required.']);
    exit;
}

try {
    // Generate Document Number: e.g. NOC-2026-SOLAR-XXXX
    $prefix = (strpos($document_type, 'Certificate') !== false) ? 'CERT' : 'NOC';
    $year   = date('Y');
    $rand   = str_pad(mt_rand(100, 999), 3, '0', STR_PAD_LEFT);
    $doc_no = "{$prefix}-{$year}-GEN-{$rand}";

    $issue_date = date('Y-m-d');
    $valid_until= date('Y-m-d', strtotime("+{$validity_months} months"));

    // Cryptographic signature hash
    $raw_str   = $doc_no . $member_id . $issue_date . microtime(true) . 'BSREA_SECRET_SALT_2026';
    $crypto_hash = hash('sha256', $raw_str);

    $verify_url = "https://verify.bsreabd.org/verify?doc={$doc_no}&hash=" . substr($crypto_hash, 0, 32);
    $qr_payload = $verify_url;

    $stmt = $pdo->prepare("
        INSERT INTO tbl_noc_certificates 
        (document_number, member_id, document_type, purpose_title, authorized_representative, issue_date, valid_until, issuing_authority, qr_payload, cryptographic_hash, verification_url, status)
        VALUES (?, ?, ?, ?, ?, ?, ?, 'BSREA Secretariat, Gulshan-2, Dhaka', ?, ?, ?, 'Valid')
    ");

    $stmt->execute([
        $doc_no, $member_id, $document_type, $purpose_title, $representative, $issue_date, $valid_until, $qr_payload, $crypto_hash, $verify_url
    ]);

    $lastId = $pdo->lastInsertId();

    echo json_encode([
        'status'         => 'success',
        'message'        => 'Digital NOC issued successfully.',
        'document_id'    => $lastId,
        'document_number'=> $doc_no,
        'issue_date'     => $issue_date,
        'valid_until'    => $valid_until,
        'hash'           => $crypto_hash,
        'verification_url'=> $verify_url,
        'qr_payload'     => $qr_payload
    ]);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
}
