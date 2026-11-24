<?php
declare(strict_types=1);
class User {
    private ?array $columns = null;

    public function findByLogin(string $identifier): ?array {
        $column = filter_var($identifier, FILTER_VALIDATE_EMAIL) !== false ? 'email' : 'username';
        $s = Database::connection()->prepare("SELECT * FROM users WHERE {$column} = ? LIMIT 1");
        $s->execute([$identifier]);
        $user = $s->fetch();
        return $user ? $this->normalize($user) : null;
    }
    public function passwordHashById(int $id): ?string {
        $idColumn = $this->hasColumn('id') ? 'id' : 'user_id';
        $s = Database::connection()->prepare("SELECT * FROM users WHERE {$idColumn} = ? LIMIT 1");
        $s->execute([$id]);
        $user = $s->fetch();
        $hash = $user ? ($this->normalize($user)['password'] ?? null) : null;
        return is_string($hash) && $hash !== '' ? $hash : null;
    }
    public function updatePassword(int $id, string $password): bool {
        $idColumn = $this->hasColumn('id') ? 'id' : 'user_id';
        $passwordColumn = $this->hasColumn('password') ? 'password' : 'password_hash';
        $s = Database::connection()->prepare("UPDATE users SET {$passwordColumn} = ? WHERE {$idColumn} = ?");
        $s->execute([password_hash($password, PASSWORD_DEFAULT), $id]);
        return $s->rowCount() === 1;
    }
    public function create(string $username, string $email, string $password): void {
        $s = Database::connection()->prepare("INSERT INTO users(username,email,password,role) VALUES(?,?,?,'staff')");
        $s->execute([$username,$email,password_hash($password,PASSWORD_DEFAULT)]);
    }

    private function normalize(array $user): array {
        if (!isset($user['id']) && isset($user['user_id'])) $user['id'] = $user['user_id'];
        if (!isset($user['password']) && isset($user['password_hash'])) $user['password'] = $user['password_hash'];
        return $user;
    }

    private function hasColumn(string $column): bool {
        if ($this->columns === null) {
            $this->columns = Database::connection()->query('SHOW COLUMNS FROM users')->fetchAll(PDO::FETCH_COLUMN);
        }
        return in_array($column, $this->columns, true);
    }
}
