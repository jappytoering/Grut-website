<?php

require_once __DIR__ . '/db_helper.php';
/**
 * Auth Helper
 * Beheer van sessies, inloggen en RBAC (Role-Based Access Control)
 */

session_start();

class AuthEngine {
    
    /**
     * Start een inlogsessie
     */
    public static function login($email, $password) {
        $dbPath = __DIR__ . '/../storage/content.sqlite';
        if (!file_exists($dbPath)) {
            return false;
        }

        try {
            $pdo = get_cms_connection();
$stmt = $pdo->prepare("SELECT id, email, password_hash, role FROM users WHERE email = :email LIMIT 1");
            $stmt->execute([':email' => $email]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($user && password_verify($password, $user['password_hash'])) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_email'] = $user['email'];
                $_SESSION['user_role'] = $user['role'];
                $_SESSION['last_activity'] = time();
                
                // Voorkom sessie fixatie
                session_regenerate_id(true);
                return true;
            }
        } catch (Exception $e) {
            error_log("Login error: " . $e->getMessage());
        }

        return false;
    }

    /**
     * Log de huidige gebruiker uit
     */
    public static function logout() {
        session_unset();
        session_destroy();
    }

    /**
     * Controleer of een gebruiker is ingelogd
     */
    public static function is_logged_in() {
        if (isset($_SESSION['user_id'])) {
            // Check timeout (bijv 2 uur = 7200 sec)
            if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity'] > 7200)) {
                self::logout();
                return false;
            }
            $_SESSION['last_activity'] = time(); // update activity time
            return true;
        }
        return false;
    }

    /**
     * Redirect naar login als men niet is ingelogd
     */
    public static function require_login() {
        if (!self::is_logged_in()) {
            header("Location: /admin/login.php");
            exit;
        }
    }

    /**
     * Role-Based Access Control (RBAC) controle
     */
    public static function has_role($role) {
        if (!self::is_logged_in()) return false;
        
        $current_role = $_SESSION['user_role'] ?? '';
        
        // super_admin mag altijd alles
        if ($current_role === 'super_admin') {
            return true;
        }
        
        return $current_role === $role;
    }
}
