<?php

    define('dire', '../../');
    include(dire . '_env/exec.php');

    if(isset($_POST) and $_SERVER['REQUEST_METHOD'] == "POST") {

        $query = mysql_query('SELECT i.*, 
                                     c.name AS categoryname,
                                     b.barcode as fullbarcode,
                                     s.status as statusname
                                FROM item i 
                                LEFT JOIN 
                                category c ON (i.category = c.id)
                                LEFT JOIN
                                barcode b ON (i.barcode = b.id)
                                LEFT JOIN
                                status s ON (i.status = s.id)
                                WHERE i.id="12"') or sqlError(__FILE__,__LINE__,__FUNCTION__);
        $item = mysql_fetch_array($query);
        
        echo json_encode($item);
        
        exit;
        
    } else {
        
        header('Location: ../');
    
    }
    
?>