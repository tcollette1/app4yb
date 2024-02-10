<?php
    require_once ('mysql2.php');
    
    if (isset($_GET['term'])){
        $return_arr2 = array();
        
        try {
            $dbc->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $stmt2 = $dbc->prepare('SELECT DISTINCT last_name FROM student_locator WHERE last_name LIKE :term');
            $stmt2->execute(array('term' => $_GET['term'].'%'));
            while($row = $stmt2->fetch()) {
                $return_arr2[] =  $row['last_name'];
            }
        } catch(PDOException $e) {
            echo 'ERROR: ' . $e->getMessage();
        }
        /* Toss back results as json encoded array. */
        echo json_encode($return_arr2);
    }
    $dbc = null;
?>
