<?php
class Course {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    public function getAllCourses() {
        $q = "SELECT * FROM rozvrh";
        $stmt = $this->conn->prepare($q);
        $stmt->execute();
        $courses = [];
        while($row = $stmt->fetchAll(PDO::FETCH_ASSOC)) {
            $courses[] = $row;
        }
        return $courses;
    }
    
    public function getCourseById($id) {
        $q = "SELECT * FROM rozvrh WHERE id = :id";
        $stmt = $this->conn->prepare($q);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        $course = $stmt->fetch(PDO::FETCH_ASSOC);
        return $course;
    }

    public function addCourse($data) {
        if (isset($data['day']) && isset($data['time_start']) && isset($data['time_end']) && isset($data['type']) && isset($data['name']) && isset($data['room']) && isset($data['teacher'])) {
            $q = "INSERT INTO rozvrh (den, cas_od, cas_do, typ_akcie, nazov_akcie, miestnost, vyucujuci) VALUES (:den, :cas_od, :cas_do, :typ_akcie, :nazov_akcie, :miestnost, :vyucujuci)";
            $stmt = $this->conn->prepare($q);
            $stmt->bindParam(':den', $data['day']);
            $stmt->bindParam(':cas_od', $data['time_start']);
            $stmt->bindParam(':cas_do', $data['time_end']);
            $stmt->bindParam(':typ_akcie', $data['type']);
            $stmt->bindParam(':nazov_akcie', $data['name']);
            $stmt->bindParam(':miestnost', $data['room']);
            $stmt->bindParam(':vyucujuci', $data['teacher']);
            if ($stmt->execute()) {
                return true;
            } else {
                return false;
            }
        } else {
            return false; 
        }
    }

    public function updateCourse($id, $data) {
        $q = "UPDATE rozvrh SET den = :den, cas_od = :cas_od, cas_do = :cas_do, typ_akcie = :typ_akcie, nazov_akcie = :nazov_akcie, miestnost = :miestnost, vyucujuci = :vyucujuci WHERE id = :id";
        $stmt = $this->conn->prepare($q);
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':den', $data['day']);
        $stmt->bindParam(':cas_od', $data['time_start']);
        $stmt->bindParam(':cas_do', $data['time_end']);
        $stmt->bindParam(':typ_akcie', $data['type']);
        $stmt->bindParam(':nazov_akcie', $data['name']);
        $stmt->bindParam(':miestnost', $data['room']);
        $stmt->bindParam(':vyucujuci', $data['teacher']);
        if ($stmt->execute()) {
            return true;
        } else {
            return false;
        }
    }

    public function deleteCourse($id) {
        $q = "DELETE FROM rozvrh WHERE id = :id";
        $stmt = $this->conn->prepare($q);
        $stmt->bindParam(':id', $id);
        if ($stmt->execute()) {
            return true;
        } else {
            return false;
        }
    }
}
?>