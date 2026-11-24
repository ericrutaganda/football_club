<?php
declare(strict_types=1);
class AuthController extends Controller {
    public function login(): void {
        if ($_SERVER['REQUEST_METHOD']==='POST') {
            verify_csrf();
            $user=(new User())->findByLogin(post('username'));
            $passwordHash = $user['password'] ?? null;
            if($user && is_string($passwordHash) && password_verify(post_raw('password'),$passwordHash)){
                session_regenerate_id(true);
                $_SESSION['user']=['id'=>$user['id'],'username'=>$user['username'],'role'=>$user['role']];
                redirect('index.php?route=dashboard');
            }
            flash('error','Incorrect username or password.');
            redirect('index.php?route=login');
        }
        $this->view('auth/login',['title'=>'Sign in']);
    }
    public function changePassword(): void {
        require_login();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            verify_csrf();
            $userModel = new User();
            $hash = $userModel->passwordHashById((int)$_SESSION['user']['id']);
            $currentPassword = post_raw('current_password');
            $newPassword = post_raw('new_password');
            $confirmation = post_raw('confirm_password');
            $length = preg_match_all('/./us', $newPassword, $characters);
            if ($hash === null || !password_verify($currentPassword, $hash)) {
                flash('error', 'Current password is incorrect.');
            } elseif ($length === false || $length < 12 || strlen($newPassword) > 72) {
                flash('error', 'Use at least 12 characters and no more than 72 bytes for your new password.');
            } elseif ($newPassword !== $confirmation) {
                flash('error', 'The new password confirmation does not match.');
            } elseif (!$userModel->updatePassword((int)$_SESSION['user']['id'], $newPassword)) {
                flash('error', 'Your password could not be updated. Please try again.');
            } else {
                session_regenerate_id(true);
                flash('success', 'Password updated successfully.');
                redirect('index.php?route=dashboard');
            }
            redirect('index.php?route=account/password');
        }
        $this->view('auth/password',['title'=>'Change password']);
    }
    public function logout(): void {
        if($_SERVER['REQUEST_METHOD']!=='POST'){
            http_response_code(405);
            header('Allow: POST');
            exit('Method not allowed.');
        }
        verify_csrf();
        $_SESSION=[];
        if(ini_get('session.use_cookies')) { $p=session_get_cookie_params(); setcookie(session_name(),'',time()-42000,$p['path'],$p['domain'],$p['secure'],$p['httponly']); }
        session_destroy(); redirect('index.php?route=login');
    }
}
