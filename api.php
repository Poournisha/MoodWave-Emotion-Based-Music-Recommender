<?php
require_once 'db.php';
require_once 'auth.php';

header('Content-Type: application/json');

// Initialize database and auth
$db = new Database();
$auth = new Auth();

// Handle API requests
$method = $_SERVER['REQUEST_METHOD'];
$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$pathParts = explode('/', trim($path, '/'));

// Remove empty parts
$pathParts = array_filter($pathParts);

// API endpoint routing
try {
    switch ($method) {
        case 'GET':
            handleGetRequest($pathParts, $db, $auth);
            break;
        case 'POST':
            handlePostRequest($pathParts, $db, $auth);
            break;
        case 'PUT':
            handlePutRequest($pathParts, $db, $auth);
            break;
        case 'DELETE':
            handleDeleteRequest($pathParts, $db, $auth);
            break;
        default:
            http_response_code(405);
            echo json_encode(['error' => 'Method not allowed']);
    }
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
}

function handleGetRequest($pathParts, $db, $auth) {
    $endpoint = $pathParts[1] ?? '';
    
    switch ($endpoint) {
        case 'songs':
            if (isset($_GET['mood'])) {
                // Get songs by mood
                $mood = $_GET['mood'];
                $sql = "
                    SELECT s.*, m.name as mood_name, m.color as mood_color, m.icon as mood_icon
                    FROM songs s
                    JOIN moods m ON s.mood_id = m.id
                    WHERE m.name = ?
                ";
                $songs = $db->fetchAll($sql, [$mood]);
            } else {
                // Get all songs
                $sql = "
                    SELECT s.*, m.name as mood_name, m.color as mood_color, m.icon as mood_icon
                    FROM songs s
                    JOIN moods m ON s.mood_id = m.id
                ";
                $songs = $db->fetchAll($sql);
            }
            echo json_encode($songs);
            break;
            
        case 'moods':
            $moods = $db->fetchAll("SELECT * FROM moods");
            echo json_encode($moods);
            break;
            
        case 'user':
            if (!$auth->isLoggedIn()) {
                http_response_code(401);
                echo json_encode(['error' => 'Not authenticated']);
                return;
            }
            $user = $auth->getCurrentUser();
            unset($user['password']); // Remove password from response
            echo json_encode($user);
            break;
            
        case 'recently-played':
            if (!$auth->isLoggedIn()) {
                http_response_code(401);
                echo json_encode(['error' => 'Not authenticated']);
                return;
            }
            $userId = $_SESSION['user_id'];
            $sql = "
                SELECT s.*, m.name as mood_name, m.color as mood_color, m.icon as mood_icon
                FROM recently_played rp
                JOIN songs s ON rp.song_id = s.id
                JOIN moods m ON s.mood_id = m.id
                WHERE rp.user_id = ?
                ORDER BY rp.played_at DESC
                LIMIT 10
            ";
            $songs = $db->fetchAll($sql, [$userId]);
            echo json_encode($songs);
            break;
            
        default:
            http_response_code(404);
            echo json_encode(['error' => 'Endpoint not found']);
    }
}

function handlePostRequest($pathParts, $db, $auth) {
    $endpoint = $pathParts[1] ?? '';
    $input = json_decode(file_get_contents('php://input'), true);
    
    switch ($endpoint) {
        case 'login':
            if (!isset($input['username']) || !isset($input['password'])) {
                http_response_code(400);
                echo json_encode(['error' => 'Username and password required']);
                return;
            }
            
            if ($auth->login($input['username'], $input['password'])) {
                $user = $auth->getCurrentUser();
                unset($user['password']);
                echo json_encode(['success' => true, 'user' => $user]);
            } else {
                http_response_code(401);
                echo json_encode(['error' => 'Invalid credentials']);
            }
            break;
            
        case 'register':
            if (!isset($input['username']) || !isset($input['email']) || !isset($input['password'])) {
                http_response_code(400);
                echo json_encode(['error' => 'Username, email, and password required']);
                return;
            }
            
            if ($auth->register($input['username'], $input['email'], $input['password'])) {
                echo json_encode(['success' => true]);
            } else {
                http_response_code(400);
                echo json_encode(['error' => 'Registration failed']);
            }
            break;
            
        case 'logout':
            $auth->logout();
            echo json_encode(['success' => true]);
            break;
            
        case 'recently-played':
            if (!$auth->isLoggedIn()) {
                http_response_code(401);
                echo json_encode(['error' => 'Not authenticated']);
                return;
            }
            
            if (!isset($input['song_id'])) {
                http_response_code(400);
                echo json_encode(['error' => 'Song ID required']);
                return;
            }
            
            $userId = $_SESSION['user_id'];
            $songId = $input['song_id'];
            
            // Check if song exists
            $song = $db->fetchOne("SELECT id FROM songs WHERE id = ?", [$songId]);
            if (!$song) {
                http_response_code(404);
                echo json_encode(['error' => 'Song not found']);
                return;
            }
            
            // Add to recently played (replace if exists)
            $sql = "INSERT INTO recently_played (user_id, song_id) VALUES (?, ?) 
                    ON DUPLICATE KEY UPDATE played_at = CURRENT_TIMESTAMP";
            $db->query($sql, [$userId, $songId]);
            
            echo json_encode(['success' => true]);
            break;
            
        default:
            http_response_code(404);
            echo json_encode(['error' => 'Endpoint not found']);
    }
}

function handlePutRequest($pathParts, $db, $auth) {
    // Handle PUT requests if needed
    http_response_code(501);
    echo json_encode(['error' => 'Not implemented']);
}

function handleDeleteRequest($pathParts, $db, $auth) {
    // Handle DELETE requests if needed
    http_response_code(501);
    echo json_encode(['error' => 'Not implemented']);
}
?>