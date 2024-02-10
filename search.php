<?php
    require_once ('mysql2.php');
    
    if (isset($_GET['term'])){
        $return_arr = array();
        
        try {
            $dbc->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $stmt = $dbc->prepare('SELECT DISTINCT first_name FROM student_locator WHERE first_name LIKE :term');
            $stmt->execute(array('term' => $_GET['term'].'%'));
            
            while($row = $stmt->fetch()) {
                $return_arr[] =  $row['first_name'];
            }
        } catch(PDOException $e) {
            echo 'ERROR: ' . $e->getMessage();
        }
        /* Toss back results as json encoded array. */
        echo json_encode($return_arr);
    }
    $dbc = null;
?>
