<?php
class StudentRepository {
    public function __construct(private PDO $db) {}

    public function countAll(string $keyword = ''): int {
        $sql = "SELECT COUNT(*) AS total FROM students";
        if ($keyword !== '') $sql .= " WHERE full_name LIKE :kw OR email LIKE :kw";
        $stmt = $this->db->prepare($sql);
        if ($keyword !== '') $stmt->bindValue(':kw', "%$keyword%");
        $stmt->execute(); return (int) ($stmt->fetch()['total'] ?? 0);
    }

    public function getPaginated(string $kw, int $limit, int $offset, string $sort, string $dir): array {
        $sorts = ['id', 'full_name', 'email', 'status', 'created_at'];
        $sort = in_array($sort, $sorts) ? $sort : 'created_at';
        $dir = strtolower($dir) === 'asc' ? 'asc' : 'desc';
        
        $sql = "SELECT * FROM students" . ($kw !== '' ? " WHERE full_name LIKE :kw OR email LIKE :kw" : "") . " ORDER BY {$sort} {$dir} LIMIT :limit OFFSET :offset";
        $stmt = $this->db->prepare($sql);
        if ($kw !== '') $stmt->bindValue(':kw', "%$kw%");
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute(); return $stmt->fetchAll();
    }

    public function findById(int $id): ?array {
        $stmt = $this->db->prepare("SELECT * FROM students WHERE id = :id");
        $stmt->execute(['id' => $id]); return $stmt->fetch() ?: null;
    }

    public function create(array $data): bool {
        try {
            return $this->db->prepare("INSERT INTO students (full_name, email, phone, status, note) VALUES (:name, :email, :phone, :status, :note)")
                ->execute(['name' => $data['name'], 'email' => $data['email'], 'phone' => $data['phone'], 'status' => $data['status'], 'note' => $data['note']]);
        } catch (PDOException $e) {
            if (($e->errorInfo[1] ?? 0) === 1062) throw new DuplicateRecordException('Email đã tồn tại.');
            throw $e;
        }
    }

    public function update(int $id, array $data): bool {
        try {
            return $this->db->prepare("UPDATE students SET full_name=:name, email=:email, phone=:phone, status=:status, note=:note WHERE id=:id")
                ->execute(['id' => $id, 'name' => $data['name'], 'email' => $data['email'], 'phone' => $data['phone'], 'status' => $data['status'], 'note' => $data['note']]);
        } catch (PDOException $e) {
            if (($e->errorInfo[1] ?? 0) === 1062) throw new DuplicateRecordException('Email đã tồn tại.');
            throw $e;
        }
    }

    public function delete(int $id): bool { return $this->db->prepare("DELETE FROM students WHERE id = :id")->execute(['id' => $id]); }
}