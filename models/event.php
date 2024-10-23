<?php
error_reporting(E_ALL); 
ini_set('display_errors', 1);
require_once 'db.php'; // Ensure this line is added

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;

class Event {
    private $db;

    public function __construct() {
        $this->db = new Database(); 
    }

    public function getAllEvents() {
        $stmt = $this->db->runQuery("SELECT * FROM events");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function deleteRegistrationsByEventId($event_id) {
        $stmt = $this->db->runQuery("DELETE FROM registrations WHERE event_id = ?");
        return $stmt->execute([$event_id]);
    }    

    public function updateEventStatus($event_id, $status) {
        try {
            $sql = "UPDATE events SET `status` = :status WHERE id = :event_id";
            $stmt = $this->db->runQuery($sql);
            $stmt->bindParam(':status', $status, PDO::PARAM_STR);
            $stmt->bindParam(':event_id', $event_id, PDO::PARAM_INT);
    
            if ($stmt->execute()) {
                return true;
            } else {
                return false;
            }
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
            return false;
        }
    }
    
    public function cancelEvent($event_id) {
        return $this->updateEventStatus($event_id, 'cancel');
    }

    public function getEventById($event_id) {
        $sql = "SELECT * FROM events WHERE id = ?";
        return $this->db->fetch($sql, [$event_id]);
    }

    public function createEvent($event_name, $event_date, $start_date, $end_date, $event_time, $location, $description, $max_participants, $price, $banner, $status) {
    $sql = "INSERT INTO events (event_name, event_date, start_date, end_date, event_time, location, description, max_participants, price, banner, status)
            VALUES (:event_name, :event_date, :start_date, :end_date, :event_time, :location, :description, :max_participants, :price, :banner, :status)";
    
    $params = [
        ':event_name' => $event_name,
        ':event_date' => $event_date,
        ':start_date' => $start_date,
        ':end_date' => $end_date,
        ':event_time' => $event_time,
        ':location' => $location,
        ':description' => $description,
        ':max_participants' => $max_participants,
        ':price' => $price,
        ':banner' => $banner,
        ':status' => $status
    ];

    $this->db->execute($sql, $params);
    }

    public function registerUserForEvent($user_id, $event_id, $participant_name) {
        $sql = "INSERT INTO event_participants (user_id, event_id, participant_name) VALUES (?, ?, ?)";
        return $this->db->execute($sql, [$user_id, $event_id, $participant_name]);
    }
    
    public function getParticipantsByEventId($event_id) {
        $sql = "SELECT * FROM event_participants WHERE event_id = ?";
        return $this->db->fetchAll($sql, [$event_id]);
    }    

    // public function updateEvent($event_id, $event_name, $event_date, $start_date, $end_date, $event_time, $location, $description, $max_participants, $price, $banner) {
    //     $sql = "UPDATE events 
    //             SET event_name = :event_name, event_date = :event_date, start_date = :start_date, end_date = :end_date, 
    //                 event_time = :event_time, location = :location, description = :description, 
    //                 max_participants = :max_participants, price = :price, banner = :banner
    //             WHERE id = :event_id";
        
    //     $params = [
    //         ':event_name' => $event_name,
    //         ':event_date' => $event_date,
    //         ':start_date' => $start_date,
    //         ':end_date' => $end_date,
    //         ':event_time' => $event_time,
    //         ':location' => $location,
    //         ':description' => $description,
    //         ':max_participants' => $max_participants,
    //         ':price' => $price,
    //         ':banner' => $banner,
    //         ':event_id' => $event_id
    //     ];
    
    //     return $this->db->execute($sql, $params);
    // }
    
    public function updateEvent($event_id, $event_name, $event_date, $start_date, $end_date, $event_time, $location, $description, $max_participants, $price, $banner) {
        $sql = "UPDATE events SET event_name = ?, event_date = ?, start_date = ?, end_date = ?, event_time = ?, location = ?, description = ?, max_participants = ?, price = ?, banner = ? WHERE id = ?";
        return $this->db->execute($sql, [$event_name, $event_date, $start_date, $end_date, $event_time, $location, $description, $max_participants, $price, $banner, $event_id]);
    }

    public function editEvent($event_id, $event_name, $event_date, $event_time, $location, $description, $max_participants) {
        $sql = "UPDATE events SET event_name = ?, event_date = ?, event_time = ?, location = ?, description = ?, max_participants = ? WHERE id = ?";
        return $this->db->execute($sql, [$event_name, $event_date, $event_time, $location, $description, $max_participants, $event_id]);
    }

    public function deleteEvent($event_id) {
        $stmt = $this->db->runQuery("DELETE FROM events WHERE id = ?");
        if (!$stmt->execute([$event_id])) {
            $errorInfo = $stmt->errorInfo();
            echo "Error executing query: " . $errorInfo[2]; 
            return false;
        }
        return true;
    }    

    public function registerEvent($user_id, $event_id) {
        $registration_date = date('Y-m-d H:i:s');
        $sql = "INSERT INTO registrations (user_id, event_id, registration_date) VALUES (?, ?, ?)";
        return $this->db->execute($sql, [$user_id, $event_id, $registration_date]);
    }

    public function cancelRegistration($event_id, $user_id) {
        $sql = "DELETE FROM event_participants WHERE event_id = ? AND user_id = ?";
        return $this->db->execute($sql, [$event_id, $user_id]);
    }
      

    public function getEventsByUserId($user_id) {
        $query = "SELECT e.id, e.event_name 
                  FROM registrations r 
                  JOIN events e ON r.event_id = e.id 
                  WHERE r.user_id = :user_id";
        $stmt = $this->db->runQuery($query, [':user_id' => $user_id]);
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
        error_log("Registered Events: " . json_encode($results));
        return $results;
    }
      
    public function getRegisteredEventsByUserId($user_id) {
        $sql = "SELECT events.id, events.event_name 
                FROM event_participants 
                INNER JOIN events ON event_participants.event_id = events.id 
                WHERE event_participants.user_id = :user_id";

        return $this->db->runQuery($sql, ['user_id' => $user_id])->fetchAll(PDO::FETCH_ASSOC);
    }

    public function generateExcelFile() {
        $events = $this->getAllEvents();
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $sheet->setCellValue('A1', 'Event ID');
        $sheet->setCellValue('B1', 'Event Name');
        $sheet->setCellValue('C1', 'Start Date');
        $sheet->setCellValue('D1', 'End Date');
        $sheet->setCellValue('E1', 'Event Date');
        $sheet->setCellValue('F1', 'Location');
        $sheet->setCellValue('G1', 'Price');
        $sheet->setCellValue('H1', 'Status');
        $sheet->setCellValue('I1', 'Banner');
        $row = 2;
        foreach ($events as $event_item) {
            $sheet->setCellValue('A' . $row, htmlspecialchars($event_item['id']));
            $sheet->setCellValue('B' . $row, htmlspecialchars($event_item['event_name']));
            $sheet->setCellValue('C' . $row, htmlspecialchars($event_item['start_date']));
            $sheet->setCellValue('D' . $row, htmlspecialchars($event_item['end_date']));
            $sheet->setCellValue('E' . $row, htmlspecialchars($event_item['event_date']));
            $sheet->setCellValue('F' . $row, htmlspecialchars($event_item['location']));
            $sheet->setCellValue('G' . $row, htmlspecialchars($event_item['price']));
            $sheet->setCellValue('H' . $row, htmlspecialchars($event_item['status']));

            // Add the event banner image
            $bannerPath = __DIR__ . '/../../uploads/' . htmlspecialchars($event_item['banner']);
            if (file_exists($bannerPath)) {
                $drawing = new Drawing();
                $drawing->setName('Banner');
                $drawing->setDescription('Event Banner');
                $drawing->setPath($bannerPath);
                $drawing->setHeight(80); // Set height
                $drawing->setCoordinates('F' . $row); // Set the cell to insert the image
                $drawing->setWorksheet($sheet);
            }
            $row++;
        }

        // Save the spreadsheet to the specified location
        $writer = new Xlsx($spreadsheet);
        $writer->save(__DIR__ . '../events_list.xlsx'); // Save to the root folder of the website
    }
}
