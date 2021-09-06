<?php
class DbOperations
{
    private $con;

    function __construct()
    {
        require_once dirname(__FILE__) . '/DbConnect.php';
        $db = new DbConnect();
        $this->con = $db->connect();
    }

    public function createUser($username, $pass, $email, $firstname, $lastname)
    {
        if ($this->isUsernameExist($username)) {
            return 0;
        } elseif ($this->isEmailExist($email)) {
            return 1;
        } else {
            $password = md5($pass);
            $stmt = $this->con->prepare("INSERT INTO providers (provider_id, username, password, email, provider_firstname, provider_lastname) VALUES (NULL, ?, ?, ?, ?, ?)");
            $stmt->bind_param("sssss", $username, $password, $email, $firstname, $lastname);
            if ($stmt->execute()) {
                return 2;
            } else {
                return 3;
            }
        }
    }

    public function getProvidersByCategoryAndCity($category, $city)
    {
        $stmt = $this->con->prepare("SELECT provider_id, provider_firstname, provider_lastname, email FROM providers NATURAL JOIN services WHERE category_id = ? AND city_id = ?");
        $stmt->bind_param("ii", $category, $city);
        $stmt->execute();
        $result = $stmt->get_result();
        $response = array();
        while ($row = $result->fetch_assoc()) {
            $response[] = $row;
        }
        return $response;
    }

    public function getProviderById($provider)
    {
        $stmt = $this->con->prepare("SELECT provider_id, provider_firstname, provider_lastname, email FROM providers WHERE provider_id = ?");
        $stmt->bind_param("i", $provider);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    public function userLogin($username, $pass)
    {
        $password = md5($pass);
        $stmt = $this->con->prepare("SELECT provider_id FROM providers WHERE username = ? AND password = ?");
        $stmt->bind_param("ss", $username, $password);
        $stmt->execute();
        $stmt->store_result();
        return $stmt->num_rows > 0;
    }

    public function getUserByUsername($username)
    {
        $stmt = $this->con->prepare("SELECT provider_id, username, email, provider_firstname, provider_lastname FROM providers WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    private function isUsernameExist($username)
    {
        $stmt = $this->con->prepare("SELECT provider_id FROM providers WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $stmt->store_result();
        return $stmt->num_rows > 0;
    }

    private function isEmailExist($email)
    {
        $stmt = $this->con->prepare("SELECT provider_id FROM providers WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();
        return $stmt->num_rows > 0;
    }

    public function updateProfile($id, $username, $email, $firstname, $lastname)
    {
        $stmt = $this->con->prepare("UPDATE providers SET username = ?, email = ?, provider_firstname = ?, provider_lastname = ? WHERE provider_id = ?");
        $stmt->bind_param("ssssi", $username, $email, $firstname, $lastname, $id);
        $stmt->execute();
        return $stmt->affected_rows > 0;
    }

    public function updatePassword($id, $oldPass, $newPass)
    {
        $oldPassword = md5($oldPass);
        $newPassword = md5($newPass);
        $stmt = $this->con->prepare("UPDATE providers SET password = ? WHERE provider_id = ? AND password = ?");
        $stmt->bind_param("sis", $newPassword, $id, $oldPassword);
        $stmt->execute();
        return $stmt->affected_rows > 0;
    }

    public function addCategory($name)
    {
        if ($this->isCategoryExist($name)) {
            return 0;
        } else {
            $stmt = $this->con->prepare("INSERT INTO categories (category_id, category_name) VALUES (NULL, ?);");
            $stmt->bind_param("s", $name);
            if ($stmt->execute()) {
                return 1;
            } else {
                return 2;
            }
        }
    }

    public function getCategories()
    {
        $stmt = $this->con->prepare("SELECT category_id, category_name FROM categories ORDER BY category_name ASC");
        $stmt->execute();
        $result = $stmt->get_result();
        $response = array();
        while ($row = $result->fetch_assoc()) {
            $response[] = $row;
        }
        return $response;
    }

    private function isCategoryExist($name)
    {
        $stmt = $this->con->prepare("SELECT category_id FROM categories WHERE category_name = ?");
        $stmt->bind_param("s", $name);
        $stmt->execute();
        $stmt->store_result();
        return $stmt->num_rows > 0;
    }

    public function addCity($name)
    {
        if ($this->isCityExist($name)) {
            return 0;
        } else {
            $stmt = $this->con->prepare("INSERT INTO cities (city_id, city_name) VALUES (NULL, ?);");
            $stmt->bind_param("s", $name);
            if ($stmt->execute()) {
                return 1;
            } else {
                return 2;
            }
        }
    }

    private function isCityExist($name)
    {
        $stmt = $this->con->prepare("SELECT city_id FROM cities WHERE city_name = ?");
        $stmt->bind_param("s", $name);
        $stmt->execute();
        $stmt->store_result();
        return $stmt->num_rows > 0;
    }

    public function getCities()
    {
        $stmt = $this->con->prepare("SELECT city_id, city_name FROM cities ORDER BY city_name ASC");
        $stmt->execute();
        $result = $stmt->get_result();
        $response = array();
        while ($row = $result->fetch_assoc()) {
            $response[] = $row;
        }
        return $response;
    }

    public function addService($serviceName, $serviceDescription, $address, $cityId, $serviceEmail, $phoneNumber, $categoryId, $providerId)
    {
        $stmt = $this->con->prepare("INSERT INTO services (service_id, service_name, service_description, address, city_id, service_email, phone_number, category_id, provider_id) VALUES (NULL, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sssissii", $serviceName, $serviceDescription, $address, $cityId, $serviceEmail, $phoneNumber, $categoryId, $providerId);
        if ($stmt->execute()) {
            return $this->con->insert_id;
        } else {
            return 0;
        }
    }

    public function getServicesByCategoryAndCity($category, $city)
    {
        $stmt = $this->con->prepare("SELECT service_id, service_name, service_description, provider_firstname, provider_lastname FROM services NATURAL JOIN providers WHERE category_id = ? AND city_id = ?");
        $stmt->bind_param("ii", $category, $city);
        $stmt->execute();
        $result = $stmt->get_result();
        $response = array();
        while ($row = $result->fetch_assoc()) {
            $response[] = $row;
        }
        return $response;
    }

    public function getServicesByProvider($provider)
    {
        $stmt = $this->con->prepare("SELECT service_id, service_name, service_description, city_name, category_name FROM services NATURAL JOIN cities NATURAL JOIN categories WHERE provider_id = ?");
        $stmt->bind_param("i", $provider);
        $stmt->execute();
        $result = $stmt->get_result();
        $response = array();
        while ($row = $result->fetch_assoc()) {
            $response[] = $row;
        }
        return $response;
    }

    public function getServiceById($service)
    {
        $stmt = $this->con->prepare("SELECT service_id, service_name, service_description, address, city_name, service_email, phone_number, category_name, provider_id, provider_firstname, provider_lastname FROM services NATURAL JOIN cities NATURAL JOIN categories NATURAL JOIN providers WHERE service_id = ?");
        $stmt->bind_param("i", $service);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    public function getServiceIdBySubservice($subservice)
    {
        $stmt = $this->con->prepare("SELECT service_id FROM subservices WHERE sub_service_id = ?");
        $stmt->bind_param("i", $subservice);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    public function updateService($serviceId, $serviceName, $serviceDescription, $address, $cityId, $serviceEmail, $phoneNumber, $categoryId)
    {
        $stmt = $this->con->prepare("UPDATE services SET service_name = ?, service_description = ?, address = ?, city_id = ?, service_email = ?, phone_number = ?, category_id = ? WHERE service_id = ?");
        $stmt->bind_param("sssissii", $serviceName, $serviceDescription, $address, $cityId, $serviceEmail, $phoneNumber, $categoryId, $serviceId);
        $stmt->execute();
        return $stmt->affected_rows > 0;
    }

    public function deleteService($id)
    {
        $stmt = $this->con->prepare("DELETE FROM services WHERE service_id = ?");
        $stmt->bind_param("i", $id);
        if ($stmt->execute()) {
            if ($this->deleteWorkTimeByService($id) and $this->deleteReservedServiceByService($id) and $this->deleteSubServiceByService($id)) {
                return 1;
            } else {
                return 0;
            }
        } else {
            return 0;
        }
    }

    public function addSubService($subserviceName, $subserviceDescription, $price, $duration, $serviceId)
    {
        $stmt = $this->con->prepare("INSERT INTO subservices (sub_service_id, sub_service_name, sub_service_description, price, duration, service_id) VALUES (NULL, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssdii", $subserviceName, $subserviceDescription, $price, $duration, $serviceId);
        if ($stmt->execute()) {
            return 1;
        } else {
            return 0;
        }
    }

    public function getSubservicesByService($service)
    {
        $stmt = $this->con->prepare("SELECT sub_service_id, sub_service_name, sub_service_description FROM subservices NATURAL JOIN services WHERE service_id = ?");
        $stmt->bind_param("i", $service);
        $stmt->execute();
        $result = $stmt->get_result();
        $response = array();
        while ($row = $result->fetch_assoc()) {
            $response[] = $row;
        }
        return $response;
    }

    public function getSubserviceById($subservice)
    {
        $stmt = $this->con->prepare("SELECT sub_service_id, sub_service_name, sub_service_description, price, duration, service_id, service_name FROM subservices NATURAL JOIN services WHERE sub_service_id = ?");
        $stmt->bind_param("i", $subservice);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    public function updateSubService($subserviceId, $subserviceName, $subserviceDescription, $price, $duration)
    {
        $price = floatval($price);
        $stmt = $this->con->prepare("UPDATE subservices SET sub_service_name = ?, sub_service_description = ?, price = ?, duration = ? WHERE sub_service_id = ?");
        $stmt->bind_param("ssdii", $subserviceName, $subserviceDescription, $price, $duration, $subserviceId);
        $stmt->execute();
        return $stmt->affected_rows > 0;
    }

    public function deleteSubService($id)
    {
        $stmt = $this->con->prepare("DELETE FROM subservices WHERE sub_service_id = ?");
        $stmt->bind_param("i", $id);
        if ($stmt->execute()) {
            return 1;
        } else {
            return 0;
        }
    }

    public function deleteSubServiceByService($service)
    {
        $stmt = $this->con->prepare("DELETE FROM subservices WHERE service_id = ?");
        $stmt->bind_param("i", $service);
        if ($stmt->execute()) {
            return 1;
        } else {
            return 0;
        }
    }

    public function addWorkTime($dayId, $timeStart, $timeEnd, $serviceId)
    {
        $stmt = $this->con->prepare("INSERT INTO work_time (work_time_id, day_id, time_start, time_end, service_id) VALUES (NULL, ?, ?, ?, ?)");
        $stmt->bind_param("issi", $dayId, $timeStart, $timeEnd, $serviceId);
        return $stmt->execute();
    }

    public function getWorkTimeByService($service)
    {
        $stmt = $this->con->prepare("SELECT work_time_id, day_id, day_name, time_start, time_end FROM work_time NATURAL JOIN days_week WHERE service_id = ? ORDER BY day_id ASC");
        $stmt->bind_param("i", $service);
        $stmt->execute();
        $result = $stmt->get_result();
        $response = array();
        while ($row = $result->fetch_assoc()) {
            $response[] = $row;
        }
        return $response;
    }

    public function getWorkTimeBySubservice($subservice)
    {
        $stmt = $this->con->prepare("SELECT work_time_id, day_id, time_start, time_end FROM work_time NATURAL JOIN subservices WHERE sub_service_id = ? ORDER BY day_id ASC");
        $stmt->bind_param("i", $subservice);
        $stmt->execute();
        $result = $stmt->get_result();
        $response = array();
        while ($row = $result->fetch_assoc()) {
            $response[] = $row;
        }
        return $response;
    }

    public function checkWorkTime($id, $timeStart, $timeEnd)
    {
        $stmt = $this->con->prepare("SELECT work_time_id FROM work_time WHERE work_time_id = ? AND time_start = ? AND time_end = ?");
        $stmt->bind_param("iss", $id, $timeStart, $timeEnd);
        $stmt->execute();
        $stmt->store_result();
        return $stmt->num_rows > 0;
    }

    public function updateWorkTime($id, $timeStart, $timeEnd)
    {
        if ($this->checkWorkTime($id, $timeStart, $timeEnd)) {
            return 1;
        } else {
            $stmt = $this->con->prepare("UPDATE work_time SET time_start = ?, time_end = ? WHERE work_time_id = ?");
            $stmt->bind_param("ssi", $timeStart, $timeEnd, $id);
            $stmt->execute();
            if ($stmt->affected_rows > 0) {
                return 1;
            } else {
                return 0;
            }
        }
    }

    public function deleteWorkTime($id)
    {
        $stmt = $this->con->prepare("DELETE FROM work_time WHERE work_time_id = ?");
        $stmt->bind_param("i", $id);
        if ($stmt->execute()) {
            return 1;
        } else {
            return 0;
        }
    }

    public function deleteWorkTimeByService($service)
    {
        $stmt = $this->con->prepare("DELETE FROM work_time WHERE service_id = ?");
        $stmt->bind_param("i", $service);
        if ($stmt->execute()) {
            return 1;
        } else {
            return 0;
        }
    }

    public function getMinDurationByService($service)
    {
        $stmt = $this->con->prepare("SELECT MIN(duration) AS min_duration FROM subservices NATURAL JOIN services WHERE service_id = ?");
        $stmt->bind_param("i", $service);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    public function getWeekdays()
    {
        $stmt = $this->con->prepare("SELECT day_id, day_name FROM days_week");
        $stmt->execute();
        $result = $stmt->get_result();
        $response = array();
        while ($row = $result->fetch_assoc()) {
            $response[] = $row;
        }
        return $response;
    }

    public function addFreeTime($allDay, $timeStart, $timeEnd, $serviceId)
    {
        $stmt = $this->con->prepare("INSERT INTO free_time (free_time_id, all_day, date_time_start, date_time_end, service_id) VALUES (NULL, ?, ?, ?, ?)");
        $stmt->bind_param("issi", $allDay, $timeStart, $timeEnd, $serviceId);
        return $stmt->execute();
    }

    public function getFreeTimeByServiceAndDate($service, $date, $allDay)
    {
        if ($allDay == 0) {
            $datetime = "{$date}%";
            $stmt = $this->con->prepare("SELECT free_time_id, date_time_start, date_time_end FROM free_time WHERE service_id = ? AND date_time_start LIKE ? AND all_day = 0");
            $stmt->bind_param("is", $service, $datetime);
        } else {
            $stmt = $this->con->prepare("SELECT free_time_id, date_time_start, date_time_end FROM free_time WHERE service_id = ? AND DATE(date_time_end) > ? AND all_day = 1");
            $stmt->bind_param("is", $service, $date);
        }
        $stmt->execute();
        $result = $stmt->get_result();
        $response = array();
        while ($row = $result->fetch_assoc()) {
            $response[] = $row;
        }
        return $response;
    }

    public function getAllFreeTimeByServiceAndDate($service, $datetime)
    {
        $stmt = $this->con->prepare("SELECT free_time_id, date_time_start, date_time_end, all_day FROM free_time WHERE service_id = ? AND date_time_end > ? ORDER BY date_time_start ASC");
        $stmt->bind_param("is", $service, $datetime);
        $stmt->execute();
        $result = $stmt->get_result();
        $response = array();
        while ($row = $result->fetch_assoc()) {
            $response[] = $row;
        }
        return $response;
    }

    public function getFreeTimeBySubserviceAndDate($subservice, $date, $allDay)
    {
        if ($allDay == 0) {
            $datetime = "{$date}%";
            $stmt = $this->con->prepare("SELECT free_time_id, date_time_start, date_time_end FROM free_time NATURAL JOIN subservices WHERE sub_service_id = ? AND date_time_start LIKE ? AND all_day = 0");
            $stmt->bind_param("is", $subservice, $datetime);
        } else {
            $stmt = $this->con->prepare("SELECT free_time_id, date_time_start, date_time_end FROM free_time NATURAL JOIN subservices WHERE sub_service_id = ? AND DATE(date_time_end) > ? AND all_day = 1");
            $stmt->bind_param("is", $subservice, $date);
        }
        $stmt->execute();
        $result = $stmt->get_result();
        $response = array();
        while ($row = $result->fetch_assoc()) {
            $response[] = $row;
        }
        return $response;
    }

    public function deleteFreeTime($id)
    {
        $stmt = $this->con->prepare("DELETE FROM free_time WHERE free_time_id = ?");
        $stmt->bind_param("i", $id);
        if ($stmt->execute()) {
            return 1;
        } else {
            return 0;
        }
    }

    public function bookAppointment($subservice, $clientFirstname, $clientLastname, $clientEmail, $clientPhoneNumber, $datetime)
    {
        if ($this->getReservedServiceBySubServiceAndDateTime($subservice, $datetime)) {
            return 0;
        } else {
            $stmt = $this->con->prepare("INSERT INTO reserved_services (reserved_service_id, client_firstname, client_lastname, client_email, client_phone_number, date_time, realised, canceled, sub_service_id) VALUES (NULL, ?, ?, ?, ?, ?, 0, 0, ?);");
            $stmt->bind_param("sssssi", $clientFirstname, $clientLastname, $clientEmail, $clientPhoneNumber, $datetime, $subservice);
            if ($stmt->execute()) {
                return $this->con->insert_id;
            } else {
                return 0;
            }
        }
    }

    public function getReservedServiceBySubServiceAndDateTime($subservice, $datetime)
    {
        $result = $this->getServiceIdBySubservice($subservice);
        $service = $result['service_id'];
        $stmt = $this->con->prepare("SELECT reserved_service_id FROM reserved_services NATURAL JOIN subservices WHERE service_id = ? AND date_time = ? AND canceled = 0");
        $stmt->bind_param("is", $service, $datetime);
        $stmt->execute();
        $result = $stmt->get_result();
        $response = array();
        while ($row = $result->fetch_assoc()) {
            $response[] = $row;
        }
        return $response;
    }

    public function getReservedServiceByServiceAndDate($service, $date)
    {
        $datetime = "{$date}%";
        $stmt = $this->con->prepare("SELECT reserved_service_id, date_time, duration FROM reserved_services NATURAL JOIN subservices WHERE service_id = ? AND date_time LIKE ? AND canceled = 0");
        $stmt->bind_param("is", $service, $datetime);
        $stmt->execute();
        $result = $stmt->get_result();
        $response = array();
        while ($row = $result->fetch_assoc()) {
            $response[] = $row;
        }
        return $response;
    }

    public function getReservedServicseByServiceAndDate($service, $date)
    {
        $stmt = $this->con->prepare("SELECT reserved_service_id, sub_service_name, TIME(date_time) AS time, client_email FROM reserved_services NATURAL JOIN subservices WHERE service_id = ? AND DATE(date_time) = ?");
        $stmt->bind_param("is", $service, $date);
        $stmt->execute();
        $result = $stmt->get_result();
        $response = array();
        while ($row = $result->fetch_assoc()) {
            $response[] = $row;
        }
        return $response;
    }

    public function getReservedServiceByIdAndEmail($id, $email)
    {
        $stmt = $this->con->prepare("SELECT service_name, provider_firstname, provider_lastname, client_firstname, client_lastname, sub_service_name, price, date_time, duration, address, city_name, service_email, phone_number, client_phone_number, realised, canceled FROM reserved_services NATURAL JOIN subservices NATURAL JOIN services NATURAL JOIN providers NATURAL JOIN cities WHERE reserved_service_id = ? AND client_email = ?");
        $stmt->bind_param("is", $id, $email);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    public function getLatestReservedServiceByProvider($provider)
    {
        $stmt = $this->con->prepare("SELECT MAX(reserved_service_id) AS reserved_service_id, sub_service_name, service_name, date_time, client_email FROM reserved_services NATURAL JOIN subservices NATURAL JOIN services WHERE provider_id = ?");
        $stmt->bind_param("i", $provider);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    public function getLatestCanceledServiceByProvider($provider)
    {
        $stmt = $this->con->prepare("SELECT MAX(date_time_canceled) AS date_time_canceled, reserved_service_id, sub_service_name, service_name, date_time, client_email FROM reserved_services NATURAL JOIN subservices NATURAL JOIN services WHERE provider_id = ?");
        $stmt->bind_param("i", $provider);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    public function getReservationInfoById($id)
    {
        $stmt = $this->con->prepare("SELECT sub_service_name, service_name, date_time, client_email, canceled FROM reserved_services NATURAL JOIN subservices NATURAL JOIN services WHERE reserved_service_id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    public function cancelReservedService($id)
    {
        $datetime = date("Y-m-d H:i:s");
        $stmt = $this->con->prepare("UPDATE reserved_services SET canceled = 1, date_time_canceled = ? WHERE reserved_service_id = ?");
        $stmt->bind_param("si", $datetime, $id);
        $stmt->execute();
        return $stmt->affected_rows > 0;
    }

    public function cancelReservedServiceByDate($datetimeStart, $datetimeEnd)
    {
        $stmt = $this->con->prepare("UPDATE reserved_services SET canceled = 1 WHERE date_time >= ? AND date_time < ?");
        $stmt->bind_param("ss", $datetimeStart, $datetimeEnd);
        $stmt->execute();
        return $stmt->affected_rows > 0;
    }

    public function realiseReservedService($id)
    {
        $stmt = $this->con->prepare("UPDATE reserved_services SET realised = 1 WHERE reserved_service_id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        return $stmt->affected_rows > 0;
    }

    public function deleteReservedServiceByService($service)
    {
        $stmt = $this->con->prepare("DELETE reserved_services FROM reserved_services NATURAL JOIN subservices WHERE service_id = ?");
        $stmt->bind_param("i", $service);
        if ($stmt->execute()) {
            return 1;
        } else {
            return 0;
        }
    }

    public function getCountClientsByProvider($provider)
    {
        $stmt = $this->con->prepare("SELECT COUNT(*) AS sum FROM reserved_services NATURAL JOIN subservices NATURAL JOIN services WHERE provider_id = ? GROUP BY client_email");
        $stmt->bind_param("i", $provider);
        $stmt->execute();
        $result = $stmt->get_result();
        $response = array();
        while ($row = $result->fetch_assoc()) {
            $response[] = $row;
        }
        return $response;
    }

    public function getMostPopularServicesByProvider($provider)
    {
        $stmt = $this->con->prepare("SELECT COUNT(*) AS sum, service_name FROM reserved_services NATURAL JOIN subservices NATURAL JOIN services WHERE provider_id = ? GROUP BY service_id ORDER BY sum DESC LIMIT 3");
        $stmt->bind_param("i", $provider);
        $stmt->execute();
        $result = $stmt->get_result();
        $response = array();
        while ($row = $result->fetch_assoc()) {
            $response[] = $row;
        }
        return $response;
    }

    public function getMostPopularSubServicesByProvider($provider)
    {
        $stmt = $this->con->prepare("SELECT COUNT(*) AS sum, sub_service_name, service_name FROM reserved_services NATURAL JOIN subservices NATURAL JOIN services WHERE provider_id = ? GROUP BY sub_service_id ORDER BY sum DESC LIMIT 3");
        $stmt->bind_param("i", $provider);
        $stmt->execute();
        $result = $stmt->get_result();
        $response = array();
        while ($row = $result->fetch_assoc()) {
            $response[] = $row;
        }
        return $response;
    }

    public function getMostProfitableServicesByProvider($provider)
    {
        $stmt = $this->con->prepare("SELECT SUM(price) AS sum, service_name FROM reserved_services NATURAL JOIN subservices NATURAL JOIN services WHERE provider_id = ? GROUP BY service_id ORDER BY sum DESC LIMIT 3");
        $stmt->bind_param("i", $provider);
        $stmt->execute();
        $result = $stmt->get_result();
        $response = array();
        while ($row = $result->fetch_assoc()) {
            $response[] = $row;
        }
        return $response;
    }

    public function getMostProfitableSubServicesByProvider($provider)
    {
        $stmt = $this->con->prepare("SELECT SUM(price) AS sum, sub_service_name, service_name FROM reserved_services NATURAL JOIN subservices NATURAL JOIN services WHERE provider_id = ? GROUP BY sub_service_id ORDER BY sum DESC LIMIT 3");
        $stmt->bind_param("i", $provider);
        $stmt->execute();
        $result = $stmt->get_result();
        $response = array();
        while ($row = $result->fetch_assoc()) {
            $response[] = $row;
        }
        return $response;
    }

    public function getCountReservedServicesByProvider($provider)
    {
        $stmt = $this->con->prepare("SELECT COUNT(*) AS sum, realised, canceled FROM reserved_services NATURAL JOIN subservices NATURAL JOIN services WHERE provider_id = ? GROUP BY realised, canceled");
        $stmt->bind_param("i", $provider);
        $stmt->execute();
        $result = $stmt->get_result();
        $response = array();
        while ($row = $result->fetch_assoc()) {
            $response[] = $row;
        }
        return $response;
    }

    public function getMostPopularHoursByProvider($provider)
    {
        $stmt = $this->con->prepare("SELECT COUNT(*) AS sum, TIME(date_time) AS time FROM reserved_services NATURAL JOIN subservices NATURAL JOIN services WHERE provider_id = ? GROUP BY time ORDER BY sum DESC LIMIT 3");
        $stmt->bind_param("i", $provider);
        $stmt->execute();
        $result = $stmt->get_result();
        $response = array();
        while ($row = $result->fetch_assoc()) {
            $response[] = $row;
        }
        return $response;
    }

    public function getRatingsByProvider($service)
    {
        $stmt = $this->con->prepare("SELECT AVG(value) AS avg FROM ratings NATURAL JOIN reserved_services NATURAL JOIN subservices NATURAL JOIN services WHERE provider_id = ?");
        $stmt->bind_param("i", $service);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    public function setRatingsByService($value, $service)
    {
        $stmt = $this->con->prepare("INSERT INTO ratings (rating_id, value, reserved_service_id) VALUES (NULL, ?, ?)");
        $stmt->bind_param("ii", $value, $service);
        return $stmt->execute();
    }

    public function getRatingsByService($service)
    {
        $stmt = $this->con->prepare("SELECT ROUND(AVG(value)) AS avg FROM ratings NATURAL JOIN reserved_services NATURAL JOIN subservices WHERE service_id = ?");
        $stmt->bind_param("i", $service);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    public function getRatingsByReservedService($service)
    {
        $stmt = $this->con->prepare("SELECT value FROM ratings WHERE reserved_service_id = ?");
        $stmt->bind_param("i", $service);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }
}
