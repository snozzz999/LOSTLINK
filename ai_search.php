<?php
// ai_search.php  (JSON endpoint for browse search - FULLTEXT ranked)
if (session_status() === PHP_SESSION_NONE) session_start();

require_once __DIR__ . "/config.php"; // must define $conn (mysqli)

header("Content-Type: application/json; charset=utf-8");

// If not logged in, return JSON (DO NOT redirect)
if (empty($_SESSION["user_id"])) {
    http_response_code(401);
    echo json_encode(["error" => "not_logged_in"]);
    exit;
}

$qRaw  = trim($_GET["q"] ?? "");
$type  = strtolower(trim($_GET["type"] ?? "all")); // all|lost|found
$q     = preg_replace('/\s+/', ' ', $qRaw);        // normalize spaces

// If query too short, fallback to LIKE (FULLTEXT ignores very short words sometimes)
$useFulltext = (mb_strlen($q) >= 3);

$where = [];
$params = [];
$types  = "";

// Optional type filter
$typeSql = "";
if (in_array($type, ["lost", "found"], true)) {
    $typeSql = " AND li.item_type = ? ";
}

// Build SQL
if ($useFulltext && $q !== "") {
    // FULLTEXT ranked search
    $sql = "
      SELECT li.item_name, li.item_type, li.date_lost,
             MATCH(li.item_name, li.description) AGAINST (? IN NATURAL LANGUAGE MODE) AS score
      FROM lost_items li
      WHERE MATCH(li.item_name, li.description) AGAINST (? IN NATURAL LANGUAGE MODE)
      $typeSql
      ORDER BY score DESC, li.date_lost DESC
      LIMIT 100
    ";

    // MATCH query appears twice
    $params[] = $q;
    $params[] = $q;
    $types   .= "ss";

    if ($typeSql !== "") {
        $params[] = $type;
        $types   .= "s";
    }

} else {
    // Fallback: LIKE search (or no query -> show latest)
    $sql = "
      SELECT li.item_name, li.item_type, li.date_lost
      FROM lost_items li
      WHERE 1=1
    ";

    if ($q !== "") {
        $sql .= " AND (li.item_name LIKE ? OR li.description LIKE ?) ";
        $like = "%" . $q . "%";
        $params[] = $like;
        $params[] = $like;
        $types   .= "ss";
    }

    if ($typeSql !== "") {
        $sql .= " AND li.item_type = ? ";
        $params[] = $type;
        $types   .= "s";
    }

    $sql .= " ORDER BY li.date_lost DESC LIMIT 100 ";
}

try {
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        http_response_code(500);
        echo json_encode(["error" => "prepare_failed", "details" => $conn->error]);
        exit;
    }

    if ($types !== "") {
        $stmt->bind_param($types, ...$params);
    }

    $stmt->execute();
    $res = $stmt->get_result();

    $rows = [];
    while ($r = $res->fetch_assoc()) {
        $rows[] = $r;
    }

    echo json_encode($rows);

} catch (Throwable $e) {
    http_response_code(500);
    echo json_encode(["error" => "server_error", "details" => $e->getMessage()]);
}