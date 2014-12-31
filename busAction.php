<?php

/*
  Author: Niena Rahma Alhassan

 */
include("gen.php");
$cmd = get_datan("cmd");
switch ($cmd) {
    case 1:
        upDateBus();
        break;
    case 2:
        addNewUser();

        break;
    case 3;
        loginUser();
        break;

    case 4;
        reserveSeat();
        break;
    case 5;
        busDetails();
        break;
    case 6;
        getReservations();
        break;
    case 7;
        accounts();
        break;

    default:
        echo "{";
        echo jsonn("result", 0) . ",";
        echo jsons("message", "unknown command");
        echo "}";
}

function upDateBus() {
    include_once("bus_class.php");

    $seatNo = get_datan("seats");
    $lat = get_datan("latitude");
    $long = get_datan("longitude");

    $v = new bus_class();
    $row = $v->updateSeats($seatNo, $lat, $long);

    if (!$row) {
        echo "{";
        echo jsonn("result", 0) . ", ";
        echo jsons("message", "Record not found");
        echo "}";
        return;
    }

    echo " {
    ";
    echo jsonn("result", 1) . ", ";
    echo jsons("message", "successful");
    echo "
}";
}

function addNewUser() {
    include_once("bus_class.php");
    $id = get_datan("id");
    $role = get_data("role");

    $v = new bus_class();
    $row = $v->add_user($id, $role);
    if (!$row) {
        //display error message
        echo " {
    ";
        echo jsonn("result", 0) . ", ";
        echo jsons("message", "registration failure!");
        echo "
}";
        return;
    }
    //display success message
    echo " {
    ";
    echo jsonn("result", 1) . ", ";
    echo jsons("message", "successful");
    echo "
}";
}

function loginUser() {
    include_once("bus_class.php");
    $ID = get_datan("id");
    $v = new bus_class(); // creates a new object
    $row = $v->getDetails($ID);
    $row = $v->fetch();
    if (!$row) {
        //display error message
        echo " {
    ";
        echo jsonn("result", 0) . ", ";
        echo jsons("message", "Credentials wrong");
        echo "
}";
        return;
    }


    echo " {
    ";
    echo jsonn("result", 1) . ", ";
    echo '"details":{';

    echo jsonn("id", $row['ID']) . ", ";
    echo jsons("fname", $row['FirstName']) . ", ";
    echo jsons("lname", $row['Lastname']) . ", ";
    echo jsons("role", $row['role']);

    echo '}';
    echo "
}";
}

function busDetails() {

    include_once("bus_class.php");
    $v = new bus_class(); // creates a new object
    $row = $v->getBusDetails();
    $row = $v->fetch();
    if (!$row) {
        //display error message
        echo " {
    ";
        echo jsonn("result", 0) . ", ";
        echo jsons("message", "Error!");
        echo "
}";
        return;
    }


    echo " {
    ";
    echo jsonn("result", 1) . ", ";
    echo '"details":{';

    echo jsonn("seats", $row['available_seats']) . ", ";
    echo jsonn("lat", $row['latitude']) . ", ";
    echo jsonn("long", $row['longitude']);

    echo '}';
    echo "
}";
}

function reserveSeat() {
    include_once("bus_class.php");
    $user = get_datan("user");
    $location = get_data("location");

    $v = new bus_class();
    $row = $v->addReservation($user, $location);
    if (!$row) {
        //display error message
        echo " {
    ";
        echo jsonn("result", 0) . ", ";
        echo jsons("message", "reservation failed!");
        echo "
}";
        return;
    }

    $v->deductFare($user);
    $v->reduceS();
    //display success message
    echo " {
    ";
    echo jsonn("result", 1) . ", ";
    echo jsons("message", "successful");
    echo "
}";
}

function getReservations() {
    include_once("bus_class.php");
    $v = new bus_class();
    $row = $v->getReservations();
    $row = $v->fetch();
    if (!$row) {
        //display error message
        echo " {
    ";
        echo jsonn("result", 0) . ", ";
        echo jsons("message", "Credentials wrong");
        echo "
}";
        return;
    }


    echo " {
    ";
    echo jsonn("result", 1) . ", ";
    echo '"details":';
    echo "[";
    echo "{";
    echo jsonn("res_no", $row['r_no']) . ", ";
    echo jsonn("user", $row['user']) . ", ";
    echo jsons("firstname", $row['Firstname']) . ", ";
    echo jsons("lastname", $row['Lastname']) . ", ";
    echo jsons("location", $row['user_location']) . ", ";
    echo jsonn("fare", $row['fare']) . ", ";
    echo jsons("date", $row['r_date']);
    echo "}";
    while ($row = $v->fetch()) {
        echo ",";

        echo "{";
        echo jsonn("res_no", $row['r_no']) . ", ";
        echo jsonn("user", $row['user']) . ", ";
        echo jsons("firstname", $row['Firstname']) . ", ";
        echo jsons("lastname", $row['Lastname']) . ", ";
        echo jsons("location", $row['user_location']) . ", ";
        echo jsonn("fare", $row['fare']) . ", ";
        echo jsons("date", $row['r_date']);
        echo "}";
    }
    echo "]";


    echo "
}";
}

function accounts() {
    include_once("bus_class.php");
    $v = new bus_class();
    $row = $v->getAccounts();
    if (!$row) {
        //display error message
        echo " {
    ";
        echo jsonn("result", 0) . ", ";
        echo jsons("message", "reservation failed!");
        echo "
}";
        return;
    }
    $row = $v->fetch();

    //display success message
    echo " {
    ";
    echo jsonn("result", 1) . ", ";
    echo '"details":{';
   if ($row['total'] == NULL) {
        echo jsonn("total", 0);
        
    } else  {
        echo jsonn("total", $row['total']);
   }
    echo '}';
    echo "
}";
}

?>