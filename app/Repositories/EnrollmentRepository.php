<?php
class EnrollmentRepository {
    public function __construct(private PDO $db) {}

    public function countAll(string $kw = ''): int {
        $sql = "SELECT COUNT(*) AS total FROM enrollments";
        if ($kw !== '') $sql .= " WHERE enrollment_code LIKE :kw OR student_email LIKE :kw OR course_name LIKE :kw";
        $stmt = $this->db->prepare($sql);
        if ($kw !== '') $stmt->bindValue(':kw', "%$kw%");
        $stmt->execute(); return (int) ($stmt->fetch()['total'] ?? 0);
    }

    public function getPaginated(string $kw, int $limit, int $offset, string $sort, string $dir): array {
        $sorts = ['id', 'enrollment_code', 'student_email', 'course_name', 'total_fee', 'status', 'created_at'];
        $sort = in_array($sort, $sorts) ? $sort : 'created_at';
        $dir = strtolower($dir) === 'asc' ? 'asc' : 'desc';
        
        $sql = "SELECT * FROM enrollments" . ($kw !== '' ? " WHERE enrollment_code LIKE :kw OR student_email LIKE :kw OR course_name LIKE :kw" : "") . " ORDER BY {$sort} {$dir} LIMIT :limit OFFSET :offset";
        $stmt = $this->db->prepare($sql);
        if ($kw !== '') $stmt->bindValue(':kw', "%$kw%");
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute(); return $stmt->fetchAll();
    }

    public function findById(int $id): ?array {
        $stmt = $this->db->prepare("SELECT * FROM enrollments WHERE id = :id");
        $stmt->execute(['id' => $id]); return $stmt->fetch() ?: null;
    }

    public function create(array $data): bool {
        try {
            return $this->db->prepare("INSERT INTO enrollments (enrollment_code, student_email, course_name, total_fee, status) VALUES (:code, :email, :course, :fee, :status)")
                ->execute(['code' => $data['enrollment_code'], 'email' => $data['student_email'], 'course' => $data['course_name'], 'fee' => $data['total_fee'], 'status' => $data['status']]);
        } catch (PDOException $e) {
            if (($e->errorInfo[1] ?? 0) === 1062) throw new DuplicateRecordException('Mã đăng ký này đã tồn tại!');
            throw $e;
        }
    }

    public function update(int $id, array $data): bool {
        try {
            return $this->db->prepare("UPDATE enrollments SET enrollment_code=:code, student_email=:email, course_name=:course, total_fee=:fee, status=:status WHERE id=:id")
                ->execute(['id' => $id, 'code' => $data['enrollment_code'], 'email' => $data['student_email'], 'course' => $data['course_name'], 'fee' => $data['total_fee'], 'status' => $data['status']]);
        } catch (PDOException $e) {
            if (($e->errorInfo[1] ?? 0) === 1062) throw new DuplicateRecordException('Mã đăng ký này đã tồn tại!');
            throw $e;
        }
    }

    public function delete(int $id): bool { return $this->db->prepare("DELETE FROM enrollments WHERE id = :id")->execute(['id' => $id]); }
}