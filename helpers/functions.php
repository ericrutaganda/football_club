<?php
declare(strict_types=1);

function e(mixed $value): string {
    return htmlspecialchars(is_scalar($value) ? (string)$value : '', ENT_QUOTES, 'UTF-8');
}
function redirect(string $url): never {
    header('Location: ' . $url);
    exit;
}
function csrf_token(): string {
    if (!isset($_SESSION['_csrf']) || !is_string($_SESSION['_csrf']) || $_SESSION['_csrf'] === '') {
        $_SESSION['_csrf'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['_csrf'];
}
function csrf_field(): string {
    return '<input type="hidden" name="_csrf" value="' . e(csrf_token()) . '">';
}
function verify_csrf(): void {
    $token = $_POST['_csrf'] ?? '';
    $expected = $_SESSION['_csrf'] ?? '';
    if (!is_string($token) || !is_string($expected) || $expected === '' || !hash_equals($expected, $token)) {
        http_response_code(419);
        exit('Invalid or expired form token. Please go back and try again.');
    }
}
function flash(string $key, ?string $message = null): ?string {
    if ($message !== null) { $_SESSION['_flash'][$key] = $message; return null; }
    $value = $_SESSION['_flash'][$key] ?? null;
    unset($_SESSION['_flash'][$key]);
    return $value;
}
function require_login(): void {
    if (empty($_SESSION['user'])) redirect('index.php?route=login');
}
function post(string $key, string $default = ''): string {
    $value = $_POST[$key] ?? $default;
    return is_string($value) || is_numeric($value) ? trim((string)$value) : $default;
}
function post_raw(string $key): string {
    $value = $_POST[$key] ?? '';
    return is_string($value) ? $value : '';
}
function request_id(array $source, string $key): int|false|null {
    if (!array_key_exists($key, $source)) return null;
    if (!is_string($source[$key]) && !is_int($source[$key])) return false;
    $id = filter_var((string)$source[$key], FILTER_VALIDATE_INT);
    return $id !== false && $id > 0 ? $id : false;
}
function valid_date(string $value): bool {
    if ($value === '') return true;
    $date = DateTimeImmutable::createFromFormat('!Y-m-d', $value);
    $errors = DateTimeImmutable::getLastErrors();
    return $date !== false
        && ($errors === false || ($errors['warning_count'] === 0 && $errors['error_count'] === 0))
        && $date->format('Y-m-d') === $value;
}
function parse_datetime_local(string $value): ?string {
    $date = DateTimeImmutable::createFromFormat('!Y-m-d\TH:i', $value);
    $errors = DateTimeImmutable::getLastErrors();
    if ($date === false
        || ($errors !== false && ($errors['warning_count'] > 0 || $errors['error_count'] > 0))
        || $date->format('Y-m-d\TH:i') !== $value) {
        return null;
    }
    return $date->format('Y-m-d H:i:s');
}
function valid_nonnegative_integer(string $value): bool {
    return $value === '' || (filter_var($value, FILTER_VALIDATE_INT) !== false && (int)$value >= 0);
}
