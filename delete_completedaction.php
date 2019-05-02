<?php
include('connection.php');
if($_POST['submit']) {
    

    $id = $_POST['ID'];

    $sql = "DELETE FROM completedaction WHERE id='$id'"; 


    if ($conn->query($sql) === TRUE) {
    echo "Record deleted successfully <br><br>";
    echo "<a href='onlinehappiness.php'>Return to main page</a>";
} else {
    echo "Error deleting record: " . $conn->error;
} 



}












// echo $rowID + $numberRand;

?>