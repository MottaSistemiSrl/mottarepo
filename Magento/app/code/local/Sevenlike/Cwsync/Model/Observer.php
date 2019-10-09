<?php

class SevenLike_Cwsync_Model_Observer
{

    // registro l'utente su CW

    public function customerRegistered(Varien_Event_Observer $observer)
    {
        $customer = $observer->getEvent()->getCustomer();
        $email = $customer->getEmail();
        $name = $customer->getFirstname();
        $surname = $customer->getLastname();
        $role = 'lead';
        $pwd = $customer->getPassword();

        $conn = mysqli_connect('mysql', 'root', 'baras3rv3r', 'burda_video');

        $sql = "INSERT INTO users (userEmail, userPwd, userRole, userActive) ";
        $sql .= "VALUES('" . $email . "', '" . md5($pwd) . "', 'lead', 1)";
        mysqli_query($conn, $sql);

        $idUser = mysqli_insert_id($conn);

        $sql = "INSERT INTO leads (idUser, idCompany, idDepartment, leadName, leadSurname) ";
        $sql .= "VALUES('" . $idUser . "', '" . 1 . "', '', '" . $name . "', '" . $surname . "')";
        mysqli_query($conn, $sql);

    }

}