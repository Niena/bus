<?php

include_once("adb.php");

class bus_class extends adb {

    function bus_class() {
        adb::adb();
    }

    function updateSeats($seatNo, $lat, $long) {
        return $this->query("UPDATE `bus` SET `available_seats`=$seatNo,`latitude`=$lat,`longitude`=$long");
    }

    function add_user($id, $role) {
        return $this->query("INSERT INTO `bus_users`(ID, role) VALUES ($id,'$role')");
    }

    function select_user($id) {
        return $this->query("SELECT `ID` FROM `bus_users` WHERE ID=$id");
    }

    function getDetails($id) {
        return $this->query("Select ID,FirstName,Lastname,role from bus_users inner join 
            account_holders on bus_users.ID=account_holders.UserID where bus_users.ID=$id");
    }

    function addReservation($user, $user_location) {
        return $this->query("INSERT INTO `bus-reservations`(`user`, `user_location`, `fare`, `r_date`) 
            VALUES ($user,'$user_location',3.00,CURDATE())");
    }

    function getBusDetails() {

        return $this->query("SELECT `available_seats`, `latitude`, `longitude` FROM `bus` ");
    }

    function deductFare($userID) {
        return $this->query("UPDATE `account_holders` SET `Balance`=`Balance`-3.00 where `UserID`=$userID");
    }

    function reduceS() {
        return $this->query("UPDATE `bus` SET `available_seats`=`available_seats`-1");
    }
    
    function getReservations(){
        
        return $this->query("SELECT `r_no`, `user`, `user_location`, `fare`, `r_date`,`Firstname`,`Lastname` FROM `bus-reservations` inner join account_holders on UserID=user where `r_date`=CURDATE()");
    }
    function getAccounts(){
    return $this->query("SELECT sum(fare) as `total` FROM `bus-reservations` where r_date=CURDATE()");
    }
}

?>
