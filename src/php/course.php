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
        if (isset($data['den']) && isset($data['cas_od']) && isset($data['cas_do']) && isset($data['typ_akcie']) && isset($data['nazov_akcie']) && isset($data['miestnost']) && isset($data['vyucujuci'])) {
            $q = "INSERT INTO rozvrh (den, cas_od, cas_do, typ_akcie, nazov_akcie, miestnost, vyucujuci) VALUES (:den, :cas_od, :cas_do, :typ_akcie, :nazov_akcie, :miestnost, :vyucujuci)";
            $stmt = $this->conn->prepare($q);
            $stmt->bindParam(':den', $data['den']);
            $stmt->bindParam(':cas_od', $data['cas_od']);
            $stmt->bindParam(':cas_do', $data['cas_do']);
            $stmt->bindParam(':typ_akcie', $data['typ_akcie']);
            $stmt->bindParam(':nazov_akcie', $data['nazov_akcie']);
            $stmt->bindParam(':miestnost', $data['miestnost']);
            $stmt->bindParam(':vyucujuci', $data['vyucujuci']);
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
        // Initialize an array to hold the fields to update
        $fieldsToUpdate = array();

        // Iterate over each field in the $data array
        foreach ($data as $key => $value) {
            // Check if the field is set and not empty
            if (isset($data[$key]) && !empty($data[$key])) {
                // Add the field to the list of fields to update
                $fieldsToUpdate[] = "$key = :$key";
            }
        }

        if (empty($fieldsToUpdate)) {
            return false;
        }

        // Construct the SET clause of the SQL query
        $setClause = implode(', ', $fieldsToUpdate);

        // Construct the SQL query
        $q = "UPDATE rozvrh SET $setClause WHERE id = :id";
        $stmt = $this->conn->prepare($q);

        // Bind parameters
        $stmt->bindParam(':id', $id);
        foreach ($data as $key => $value) {
            if (isset($data[$key]) && !empty($data[$key])) {
                $stmt->bindParam(":$key", $data[$key]);
            }
        }

        // Execute the query
        if ($stmt->execute()) {
            return true;
        } else {
            return false;
        }
        /*
        $q = "UPDATE rozvrh SET den = :den, cas_od = :cas_od, cas_do = :cas_do, typ_akcie = :typ_akcie, nazov_akcie = :nazov_akcie, miestnost = :miestnost, vyucujuci = :vyucujuci WHERE id = :id";
        $stmt = $this->conn->prepare($q);
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':den', $data['den']);
        $stmt->bindParam(':cas_od', $data['cas_od']);
        $stmt->bindParam(':cas_do', $data['cas_do']);
        $stmt->bindParam(':typ_akcie', $data['typ_akcie']);
        $stmt->bindParam(':nazov_akcie', $data['nazov_akcie']);
        $stmt->bindParam(':miestnost', $data['miestnost']);
        $stmt->bindParam(':vyucujuci', $data['vyucujuci']);
        if ($stmt->execute()) {
            return true;
        } else {
            return false;
        }*/
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