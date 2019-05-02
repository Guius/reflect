<!-- Undergoing project that aims to record what user has done during the day, whether this has offered satisfaction to the user, and offers a set of statistical analyses on the resulting data -->



<?php

include('connection.php');


if( isset( $_POST["addNewAction"] ) ) {


// change post date format

$newAction = $_POST["newAction"];


    // check to see if inputs are empty
    // create variables with form data
    // wrap the data with our function
    
    if( !$_POST["newAction"] ) {
        $newActionError = "Please enter a new action <br>";
        echo $newActionError;
    }

    
    // check to see if each variable has data
    if( $newAction) {
        $query = "INSERT INTO newaction (id, newAction)
        VALUES (NULL, '$newAction')";

        if( mysqli_query( $conn, $query ) ) {
            echo " ";
        } else {
            echo "Error: ". $query . "<br>" . mysqli_error($conn);
        }
    }
    

}





?>

<!DOCTYPE html>
<html>
<head>
    <title>reflect</title>

    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.min.js"></script>
    <script src="https://code.jquery.com/jquery-2.1.4.min.js"></script>
    
    <!-- Bootstrap JS -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>

    <!-- jQuery UI -->
    <script src="js/jquery-ui-1.11.4/jquery-ui.min.js"></script>
    <meta charset="utf-8">

    <link rel="stylesheet" type="text/css" href="online_happiness_styles.css">


</head>
<body>



    <?php

    $todayDate = date("Y/m/d");
    echo $todayDate;


?>







    <div class="tab">
        <button class="tablinks" onclick="openCity(event, 'completed_action')">Add a completed action</button>
        <button class="tablinks" onclick="openCity(event, 'add_new_task')">Add a new task</button>
        <button class="tablinks" onclick="openCity(event, 'today_actions')">Today's actions</button>
        <button class="tablinks" onclick="openCity(event, 'unhappiness')">Distribution of emotions</button>        
        <button class="tablinks" onclick="openCity(event, 'activities')">Distribution of activities</button>                
    </div>

<div id="completed_action" class="tabcontent">



<?php
    $actions = array();

    $sql = "SELECT newAction FROM newaction";
    $result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) > 0) {
    // output data of each row
    while($row = mysqli_fetch_assoc($result)) {

        // accessing deadline in database and putting month, year, day into discrete variables

        // echo $row["completedAction"]. "<br>";
        array_push($actions, $row['newAction']);
    }
} else {
    echo "0 results";
}


?>

<form action="<?php echo htmlspecialchars( $_SERVER['PHP_SELF'] ); ?>" method="post">

<input type="text" name="completedAction" list="languages" placeholder="Add a completed action" class="inputImportance">

<datalist id="languages">
    <?php

for ($i=0; $i < count($actions); $i++) { 

    echo "

        <option value=" . $actions[$i] . ">


    ";
}


    ?>
</datalist>


<input type='time' name='actionDuration' value='01:00:00'>

<input type='text' name='satisfactionRating' list='options' placeholder='rate your satisfaction'>
<datalist id='options'>
    <option value = satisfied> 
    <option value = neutral> 
    <option value = unhappy>
</datalist> 


<input autocomplete='off' type='date' name='date'>

<button type='text' name='addCompletedAction'>Add Activity</button>


</form>




        <?php

        $sql = "SELECT id, newAction FROM newaction";
        $result = mysqli_query($conn, $sql);


if (mysqli_num_rows($result) > 0) {
    // output data of each row
    while($row = mysqli_fetch_assoc($result)) {

            $completedAction = $_POST["completedAction"];
            $actionDuration = $_POST["actionDuration"];
            $satisfactionRating = $_POST["satisfactionRating"];
            $completedDate = $_POST["date"];
            $date = date_create($completedDate);
            $converted_dates = date_format($date,"w");



    }



            if( isset( $_POST["addCompletedAction"] ) ) {

                    $query = "INSERT INTO completedaction (id, completedAction, duration, satisfaction, completed_date, completed_date_day)
                    VALUES (NULL, '$completedAction', '$actionDuration', '$satisfactionRating', '$completedDate', '$converted_dates')";

                    if( mysqli_query( $conn, $query ) ) {
                        echo "Task added successfully to completed actions";
                    } else {
                        echo "Error: ". $query . "<br>" . mysqli_error($conn);
                    }
                    
            }

    

} else {
    echo "0 results";
}



    ?>

</div>

<div id="add_new_task" class="tabcontent">

    <form action="<?php echo htmlspecialchars( $_SERVER['PHP_SELF'] ); ?>" method="post">

        <input autocomplete="off" type="text" name="newAction" placeholder="Add to your list of activities">
        <br>


    <button type="text" name="addNewAction">
        Add new task
    </button>

    </form>

</div>

<div id="today_actions" class="tabcontent">
    <h3>Today</h3>


<?php

$sql = "SELECT id, completedAction, duration, satisfaction, completed_date FROM completedaction
WHERE completed_date = '$todayDate'
";
$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) > 0) {
    // output data of each row
    while($row = mysqli_fetch_assoc($result)) {

        echo $row["id"] . "<br>";
        echo $row["completedAction"] . "<br>";
        echo $row["duration"] . "<br>";
        echo $row["satisfaction"] . "<br>";
        echo $row["completed_date"] . "<br>" . "<br>";
        
        echo "
        <form action = delete_completedaction.php method=POST>

            <input type = hidden name=ID value =".$row['id'].">
            <input type = submit name=submit value=Remove>


        </form>

        ";
}



} else {
    echo "0 results";
}


?>

</div>


<div id="unhappiness" class="tabcontent">

    <h3>distribution of emotions</h3>

    <p id="highestUnhappinessDay"></p>

    <?php

        $unhappy_date = array();
        $sql = "SELECT * FROM completedaction WHERE satisfaction = 'unhappy'";
        $result = mysqli_query($conn, $sql);

        if (mysqli_num_rows($result) > 0) {



            // output data of each row
            while($row = mysqli_fetch_assoc($result)) {
                // echo $unhappy_date;
                $date=date_create($row['completed_date']);
                // echo date_format($date, "w") . "<br>";
                array_push($unhappy_date, date_format($date, "w"));
            }
        } else {
            echo "0 results";
        }


                echo "<br><br><br>";


                $days = array(0, 0, 0, 0, 0, 0);
                // $days[4] += 1;
                // echo $days[4];


                for ($j=0; $j < count($unhappy_date); $j++) { 
                    for ($k=0; $k < count($unhappy_date); $k++) { 
                        if ($unhappy_date[$j] = $unhappy_date[$k]) {
                            $days[$unhappy_date[$j]] += 1;
                        }
                    }
                }

                // print_r($days);


                // echo(max($days));


            ?>

<!--                 <h3>Unhappiness is caused by</h3>
 -->
                <p id="demo"></p>

                <?php


                    $allActions = array();

                    $sql = "SELECT newAction FROM newaction";
                    $result = mysqli_query($conn, $sql);

                    if (mysqli_num_rows($result) > 0) {
                        while($row = mysqli_fetch_assoc($result)) {

                            array_push($allActions, $row['newAction']);

                        }
                    } else {
                        echo "0 results";
                    }


                    // echo "here are all the actions:";
                    // print_r($allActions);
                    echo "<br>";


                    // print_r($allActions[1]);

                    for ($d=0; $d < count($allActions); $d++) { 
                        
                    }


                    $durationMeditationUnhappiness = array();

                    $testTest = 'meditation';

                    $sql = "SELECT duration FROM completedaction WHERE completedAction = '$allActions[0]' AND satisfaction = 'unhappy'";
                    $result = mysqli_query($conn, $sql);

                    if (mysqli_num_rows($result) > 0) {
                        while($row = mysqli_fetch_assoc($result)) {

                            array_push($durationMeditationUnhappiness, $row['duration']);

                        }
                    } else {
                        echo "0 results";
                    }

                    // echo "<br>";
                    // echo "duration of unhappiness:";
                    // print_r($durationMeditationUnhappiness);
                    // echo "<br>";

                    $durationMeditationHappiness = array();
                    $dateMeditationHappiness = array();

                    $sql = "SELECT duration FROM completedaction WHERE completedAction = '$allActions[0]' AND satisfaction = 'satisfied'";
                    $result = mysqli_query($conn, $sql);

                    if (mysqli_num_rows($result) > 0) {
                        while($row = mysqli_fetch_assoc($result)) {

                            array_push($durationMeditationHappiness, $row['duration']);

                        }
                    } else {
                        echo "0 results";
                    }

                    // echo "<br>";
                    // echo "duration of happiness";
                    // print_r($durationMeditationHappiness);
                    // echo "<br>";

     
                   ?>

                   <script type="text/javascript">
                        var meditationUnhappiness = <?php echo json_encode($durationMeditationUnhappiness)?>;
                        var meditationHappiness = <?php echo json_encode($durationMeditationHappiness)?>;
                        var allActions = <?php echo json_encode($allActions)?>;

                        var totalMedUn = 0;
                        var totalMedUnIndex = 0;
                        // console.log(meditationUnhappiness.length);

                        for (var b = 0; b < meditationUnhappiness.length; b += 1) {
                            totalMedUnIndex += parseFloat(meditationUnhappiness[b].substr(6, 2)) +  60 * parseFloat(meditationUnhappiness[b].substr(3, 2)) + 60 * 60 * parseFloat(meditationUnhappiness[b].substr(0, 2));
                            totalMedUn += totalMedUnIndex;
                            totalMedUnIndex = 0;
                        }

                        var totalMedHap = 0;
                        var totalMedHapIndex = 0;

                        for (var c = 0; c < meditationHappiness.length; c += 1) {
                            totalMedHapIndex = parseFloat(meditationHappiness[c].substr(6, 2)) +  60 * parseFloat(meditationHappiness[c].substr(3, 2)) + 60 * 60 * parseFloat(meditationHappiness[c].substr(0, 2));
                            totalMedHap += totalMedHapIndex;
                            avgMedHap = totalMedHap / meditationHappiness.length;
                            totalMedHapIndex = 0;
                        }

                     
                   </script>




<script>
   var days = <?php echo json_encode($days); ?>;
   // console.log(days);
   var n = Math.max.apply(Math, days);
   // console.log(n);


    function maxUnhappiness(day) {
        return day >= n;
    }


    var m = days.findIndex(maxUnhappiness);

    // console.log(m);

    if (m = 0) {
        document.getElementById("highestUnhappinessDay").innerHTML = "The day where you are on average the most unhappy is Sunday";
    }
    if (m = 1) {
        document.getElementById("highestUnhappinessDay").innerHTML = "The day where you are on average the most unhappy is Monday";
    }
    if (m = 2) {
        document.getElementById("highestUnhappinessDay").innerHTML = "The day where you are on average the most unhappy is Tuesday";
    }
    if (m = 3) {
        document.getElementById("highestUnhappinessDay").innerHTML = "The day where you are on average the most unhappy is Wednesday";
    }
    if (m = 4) {
        document.getElementById("highestUnhappinessDay").innerHTML = "The day where you are on average the most unhappy is Thursday";
    }
    if (m = 5) {
        document.getElementById("highestUnhappinessDay").innerHTML = "The day where you are on average the most unhappy is Friday";
    }
    if (m = 6) {
        document.getElementById("highestUnhappinessDay").innerHTML = "The day where you are on average the most unhappy is Saturday";
    }



</script>


<?php
echo "<br>";

// print_r($unhappy_date);




$today_date = date("Y/m/d");
// print_r($today_date);

// echo "<br><br><br><br>";

// $date=date_create("$unhappy_date");
// echo date_format($date,"Y/m/d D");



    ?>


<div class="chart_container">
        
        <canvas id="myChart"></canvas>

    </div>



    <?php

        $durationSatisfied = array();

        $sql = "SELECT duration FROM completedaction WHERE satisfaction = 'satisfied'";
        $result = mysqli_query($conn, $sql);

        if (mysqli_fetch_assoc($result)) {
            
            while($row = mysqli_fetch_assoc($result)) {
                // echo $row["duration"];
                array_push($durationSatisfied, $row["duration"]);
            }
        } else {
            echo "0 results";
        }

            // print_r($duration);

    ?>


    <script type="text/javascript">
        
        var durationSatisfied = <?php echo json_encode($durationSatisfied)?>;
        console.log(durationSatisfied);


        var totalMedSat = 0;
        var totalMedSatIndex = 0;


        for (var b = 0; b < durationSatisfied.length; b += 1) {
            totalMedSatIndex += parseFloat(durationSatisfied[b].substr(6, 2)) +  60 * parseFloat(durationSatisfied[b].substr(3, 2)) + 60 * 60 * parseFloat(durationSatisfied[b].substr(0, 2));
            totalMedSat += totalMedSatIndex;
            totalMedSatIndex = 0;
    }

    totalMedSatConvert = totalMedSat / 3600;

console.log(totalMedSatConvert);


    </script>


    <?php

        $durationUnhappy = array();

        $sql = "SELECT duration FROM completedaction WHERE satisfaction = 'unhappy'";
        $result = mysqli_query($conn, $sql);

        if (mysqli_fetch_assoc($result)) {
            
            while($row = mysqli_fetch_assoc($result)) {
                // echo $row["duration"];
                array_push($durationUnhappy, $row["duration"]);
            }
        } else {
            echo "0 results";
        }

            // print_r($duration);

    ?>


    <script type="text/javascript">
        
        var durationUnhappy = <?php echo json_encode($durationUnhappy)?>;
        console.log(durationUnhappy);


        var totalMedUn = 0;
        var totalMedUnIndex = 0;


        for (var b = 0; b < durationUnhappy.length; b += 1) {
            totalMedUnIndex += parseFloat(durationUnhappy[b].substr(6, 2)) +  60 * parseFloat(durationUnhappy[b].substr(3, 2)) + 60 * 60 * parseFloat(durationUnhappy[b].substr(0, 2));
            totalMedUn += totalMedUnIndex;
            totalMedUnIndex = 0;
    }

    totalMedUnConvert = totalMedUn / 3600;

console.log(totalMedUnConvert);


    </script>

    <?php

        $durationNeutral = array();

        $sql = "SELECT duration FROM completedaction WHERE satisfaction = 'neutral'";
        $result = mysqli_query($conn, $sql);

        if (mysqli_fetch_assoc($result)) {
            
            while($row = mysqli_fetch_assoc($result)) {
                // echo $row["duration"];
                array_push($durationNeutral, $row["duration"]);
            }
        } else {
            echo "0 results";
        }

            // print_r($duration);

    ?>


    <script type="text/javascript">
        
        var durationNeutral = <?php echo json_encode($durationNeutral)?>;
        console.log(durationNeutral);


        var totalMedNeut = 0;
        var totalMedNeutIndex = 0;


        for (var b = 0; b < durationNeutral.length; b += 1) {
            totalMedNeutIndex += parseFloat(durationNeutral[b].substr(6, 2)) +  60 * parseFloat(durationNeutral[b].substr(3, 2)) + 60 * 60 * parseFloat(durationNeutral[b].substr(0, 2));
            totalMedNeut += totalMedNeutIndex;
            totalMedNeutIndex = 0;
    }

    totalMedNeutConvert = totalMedNeut / 3600;

console.log(totalMedNeutConvert);


    </script>


  <script>
var ctx = document.getElementById('myChart').getContext('2d');
var myChart = new Chart(ctx, {
    type: 'pie',
    data: {
        labels: ['Satisfied', 'Unsatisfied', 'Neutral'],
        datasets: [{
            label: 'levels of satisfaction',
            data: [(totalMedSatConvert / (totalMedSatConvert + totalMedUnConvert + totalMedNeutConvert)) * 100, (totalMedUnConvert/ (totalMedSatConvert + totalMedUnConvert + totalMedNeutConvert))*100, (totalMedNeutConvert/ (totalMedSatConvert + totalMedUnConvert + totalMedNeutConvert))*100],
            backgroundColor: [
                'rgba(75, 192, 192, 1)',
                'rgba(255, 99, 132, 1)',
                'rgba(54, 162, 235, 1)',
            ],
            borderColor: [
                'rgba(75, 192, 192, 1)',
                'rgba(255, 99, 132, 1)',
                'rgba(54, 162, 235, 1)',
            ],
            borderWidth: 1
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,        
        title: {
            display: true,
            text: 'Overall distribution of emotions'
        },
    }
});
</script>





<br><hr><br>


   <div class="chart_container">
        
        <canvas id="myChart_week"></canvas>

    </div>



    <?php

        $durationSatisfied_week = array();

        $sql = "SELECT duration FROM completedaction WHERE satisfaction = 'satisfied' AND completed_date_day IN (1, 2, 3, 4, 5)";
        $result = mysqli_query($conn, $sql);

        if (mysqli_fetch_assoc($result)) {
            
            while($row = mysqli_fetch_assoc($result)) {
                // echo $row["duration"];
                array_push($durationSatisfied_week, $row["duration"]);
            }
        } else {
            echo "0 results";
        }

            // print_r($duration);

    ?>


    <script type="text/javascript">
        
        var durationSatisfied_week = <?php echo json_encode($durationSatisfied_week)?>;
        console.log(durationSatisfied_week);


        var totalMedSat_week = 0;
        var totalMedSatIndex_week = 0;


        for (var b = 0; b < durationSatisfied_week.length; b += 1) {
            totalMedSatIndex_week += parseFloat(durationSatisfied_week[b].substr(6, 2)) +  60 * parseFloat(durationSatisfied_week[b].substr(3, 2)) + 60 * 60 * parseFloat(durationSatisfied_week[b].substr(0, 2));
            totalMedSat_week += totalMedSatIndex_week;
            totalMedSatIndex_week = 0;
    }

    totalMedSatConvert_week = totalMedSat_week / 3600;

console.log(totalMedSatConvert_week);


    </script>


    <?php

        $durationUnhappy_week = array();

        $sql = "SELECT duration FROM completedaction WHERE satisfaction = 'unhappy' AND completed_date_day IN (1, 2, 3, 4, 5)";
        $result = mysqli_query($conn, $sql);

        if (mysqli_fetch_assoc($result)) {
            
            while($row = mysqli_fetch_assoc($result)) {
                // echo $row["duration"];
                array_push($durationUnhappy_week, $row["duration"]);
            }
        } else {
            echo "0 results";
        }

            // print_r($duration);

    ?>


    <script type="text/javascript">
        
        var durationUnhappy_week = <?php echo json_encode($durationUnhappy_week)?>;
        console.log(durationUnhappy_week);


        var totalMedUn_week = 0;
        var totalMedUnIndex_week = 0;


        for (var b = 0; b < durationUnhappy_week.length; b += 1) {
            totalMedUnIndex_week += parseFloat(durationUnhappy_week[b].substr(6, 2)) +  60 * parseFloat(durationUnhappy_week[b].substr(3, 2)) + 60 * 60 * parseFloat(durationUnhappy_week[b].substr(0, 2));
            totalMedUn_week += totalMedUnIndex_week;
            totalMedUnIndex_week = 0;
    }

    totalMedUnConvert_week = totalMedUn_week / 3600;

console.log(totalMedUnConvert_week);


    </script>

    <?php

        $durationNeutral_week = array();

        $sql = "SELECT duration FROM completedaction WHERE satisfaction = 'neutral' AND completed_date_day IN (1, 2, 3, 4, 5)";
        $result = mysqli_query($conn, $sql);

        if (mysqli_fetch_assoc($result)) {
            
            while($row = mysqli_fetch_assoc($result)) {
                // echo $row["duration"];
                array_push($durationNeutral_week, $row["duration"]);
            }
        } else {
            echo "0 results";
        }

            // print_r($duration);

    ?>


    <script type="text/javascript">
        
        var durationNeutral_week = <?php echo json_encode($durationNeutral_week)?>;
        console.log(durationNeutral_week);


        var totalMedNeut_week = 0;
        var totalMedNeutIndex_week = 0;


        for (var b = 0; b < durationNeutral_week.length; b += 1) {
            totalMedNeutIndex_week += parseFloat(durationNeutral_week[b].substr(6, 2)) +  60 * parseFloat(durationNeutral_week[b].substr(3, 2)) + 60 * 60 * parseFloat(durationNeutral_week[b].substr(0, 2));
            totalMedNeut_week += totalMedNeutIndex_week;
            totalMedNeutIndex_week = 0;
    }

    totalMedNeutConvert_week = totalMedNeut_week / 3600;

console.log(totalMedNeutConvert_week);


    </script>


  <script>
var ctx = document.getElementById('myChart_week').getContext('2d');
var myChart_week = new Chart(ctx, {
    type: 'pie',
    data: {
        labels: ['Satisfied', 'Unsatisfied', 'Neutral'],
        datasets: [{
            label: 'levels of satisfaction',
            data: [(totalMedSatConvert_week / (totalMedSatConvert_week + totalMedUnConvert_week + totalMedNeutConvert_week)) * 100, (totalMedUnConvert_week/ (totalMedSatConvert_week + totalMedUnConvert_week + totalMedNeutConvert_week))*100, (totalMedNeutConvert_week/ (totalMedSatConvert_week + totalMedUnConvert_week + totalMedNeutConvert_week))*100],
            backgroundColor: [
                'rgba(75, 192, 192, 1)',
                'rgba(255, 99, 132, 1)',
                'rgba(54, 162, 235, 1)',
            ],
            borderColor: [
                'rgba(75, 192, 192, 1)',
                'rgba(255, 99, 132, 1)',
                'rgba(54, 162, 235, 1)',
            ],
            borderWidth: 1
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,        
        title: {
            display: true,
            text: 'Overall distribution of emotions during the week'
        },
    }
});
</script>


<br><hr><br>


    <div class="chart_container">
        
        <canvas id="myChart_weekend"></canvas>

    </div>



    <?php

        $durationSatisfied_weekend = array();

        $sql = "SELECT duration FROM completedaction WHERE satisfaction = 'satisfied' AND completed_date_day IN (0, 6)";
        $result = mysqli_query($conn, $sql);

        if (mysqli_fetch_assoc($result)) {
            
            while($row = mysqli_fetch_assoc($result)) {
                // echo $row["duration"];
                array_push($durationSatisfied_weekend, $row["duration"]);
            }
        } else {
            echo "0 results";
        }

            // print_r($duration);

    ?>


    <script type="text/javascript">
        
        var durationSatisfied_weekend = <?php echo json_encode($durationSatisfied_weekend)?>;
        console.log(durationSatisfied_weekend);


        var totalMedSat_weekend = 0;
        var totalMedSatIndex_weekend = 0;


        for (var b = 0; b < durationSatisfied_weekend.length; b += 1) {
            totalMedSatIndex_weekend += parseFloat(durationSatisfied_weekend[b].substr(6, 2)) +  60 * parseFloat(durationSatisfied_weekend[b].substr(3, 2)) + 60 * 60 * parseFloat(durationSatisfied_weekend[b].substr(0, 2));
            totalMedSat_weekend += totalMedSatIndex_weekend;
            totalMedSatIndex_weekend = 0;
    }

    totalMedSatConvert_weekend = totalMedSat_weekend / 3600;

console.log(totalMedSatConvert_weekend);


    </script>


    <?php

        $durationUnhappy_weekend = array();

        $sql = "SELECT duration FROM completedaction WHERE satisfaction = 'unhappy' AND completed_date_day IN (0, 6)";
        $result = mysqli_query($conn, $sql);

        if (mysqli_fetch_assoc($result)) {
            
            while($row = mysqli_fetch_assoc($result)) {
                // echo $row["duration"];
                array_push($durationUnhappy_weekend, $row["duration"]);
            }
        } else {
            echo "0 results";
        }

            // print_r($duration);

    ?>


    <script type="text/javascript">
        
        var durationUnhappy_weekend = <?php echo json_encode($durationUnhappy_weekend)?>;
        console.log(durationUnhappy_weekend);


        var totalMedUn_weekend = 0;
        var totalMedUnIndex_weekend = 0;


        for (var b = 0; b < durationUnhappy_weekend.length; b += 1) {
            totalMedUnIndex_weekend += parseFloat(durationUnhappy_weekend[b].substr(6, 2)) +  60 * parseFloat(durationUnhappy_weekend[b].substr(3, 2)) + 60 * 60 * parseFloat(durationUnhappy_weekend[b].substr(0, 2));
            totalMedUn_weekend += totalMedUnIndex_weekend;
            totalMedUnIndex_weekend = 0;
    }

    totalMedUnConvert_weekend = totalMedUn_weekend / 3600;

console.log(totalMedUnConvert_weekend);


    </script>

    <?php

        $durationNeutral_weekend = array();

        $sql = "SELECT duration FROM completedaction WHERE satisfaction = 'neutral' AND completed_date_day IN (0, 6)";
        $result = mysqli_query($conn, $sql);

        if (mysqli_fetch_assoc($result)) {
            
            while($row = mysqli_fetch_assoc($result)) {
                // echo $row["duration"];
                array_push($durationNeutral_weekend, $row["duration"]);
            }
        } else {
            echo "0 results";
        }

            // print_r($duration);

    ?>


    <script type="text/javascript">
        
        var durationNeutral_weekend = <?php echo json_encode($durationNeutral_weekend)?>;
        console.log(durationNeutral_weekend);


        var totalMedNeut_weekend = 0;
        var totalMedNeutIndex_weekend = 0;


        for (var b = 0; b < durationNeutral_weekend.length; b += 1) {
            totalMedNeutIndex_weekend += parseFloat(durationNeutral_weekend[b].substr(6, 2)) +  60 * parseFloat(durationNeutral_weekend[b].substr(3, 2)) + 60 * 60 * parseFloat(durationNeutral_weekend[b].substr(0, 2));
            totalMedNeut_weekend += totalMedNeutIndex_weekend;
            totalMedNeutIndex_weekend = 0;
    }

    totalMedNeutConvert_weekend = totalMedNeut_weekend / 3600;

console.log(totalMedNeutConvert_weekend);


    </script>


  <script>
var ctx = document.getElementById('myChart_weekend').getContext('2d');
var myChart_weekend = new Chart(ctx, {
    type: 'pie',
    data: {
        labels: ['Satisfied', 'Unsatisfied', 'Neutral'],
        datasets: [{
            label: 'levels of satisfaction',
            data: [(totalMedSatConvert_weekend / (totalMedSatConvert_weekend + totalMedUnConvert_weekend + totalMedNeutConvert_weekend)) * 100, (totalMedUnConvert_weekend/ (totalMedSatConvert_weekend + totalMedUnConvert_weekend + totalMedNeutConvert_weekend))*100, (totalMedNeutConvert_weekend/ (totalMedSatConvert_weekend + totalMedUnConvert_weekend + totalMedNeutConvert_weekend))*100],
            backgroundColor: [
                'rgba(75, 192, 192, 1)',
                'rgba(255, 99, 132, 1)',
                'rgba(54, 162, 235, 1)',
            ],
            borderColor: [
                'rgba(75, 192, 192, 1)',
                'rgba(255, 99, 132, 1)',
                'rgba(54, 162, 235, 1)',
            ],
            borderWidth: 1
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,        
        title: {
            display: true,
            text: 'Overall distribution of emotions during the weekend'
        },
    }
});
</script>

<br><br>

</div>

<div id="activities" class="tabcontent">

    <div class="chart_container">
        
        <canvas id="myChart_activities_total"></canvas>

    </div>

    <br><hr><br>


    <?php


        $allActions = array();

        $sql = "SELECT newAction FROM newaction";
        $result = mysqli_query($conn, $sql);

        if (mysqli_num_rows($result) > 0) {
            while($row = mysqli_fetch_assoc($result)) {

                array_push($allActions, $row['newAction']);

            }
        } else {
            echo "0 results";
        }

        // print_r($allActions);

?>


<script type="text/javascript">
    
    var allActions = <?php echo json_encode($allActions)?>;

</script>




    <?php

    


            for ($i=0; $i < count($allActions); $i++) { 

                $durationActivity[$i] = array();
                
                $sql = "SELECT duration FROM completedaction WHERE completedAction = '$allActions[$i]'";
                $result = mysqli_query($conn, $sql);

                if (mysqli_fetch_assoc($result)) {
                    
                    while($row = mysqli_fetch_assoc($result)) {
                        echo $row["completed_date"];
                        array_push($durationActivity[$i], $row["duration"]);
                    }
                } else {
                    echo "0 results";
                }

          
            }




    ?>

    

                    <script type='text/javascript'>

                    var durationActivity = <?php echo json_encode($durationActivity)?>;
                    // console.log(durationActivity[2]);

                    var durationActivity_specific = [];
                        var ActivityDurations = [];

                    for (j = 0; j < allActions.length; j++) {

                        var totalDurActConvert = 0;
                    
                        var totalDurationActivity = 0;
                        var totalDurationActivityIndex = 0;


                        for (var b = 0; b < durationActivity[j].length; b += 1) {
                            totalDurationActivityIndex += parseFloat(durationActivity[j][b].substr(6, 2)) +  60 * parseFloat(durationActivity[j][b].substr(3, 2)) + 60 * 60 * parseFloat(durationActivity[j][b].substr(0, 2));
                            totalDurationActivity += totalDurationActivityIndex;
                            totalDurationActivityIndex = 0;
                            var totalDurActConvert = totalDurationActivity / 3600;
                    }

                            ActivityDurations.push(totalDurActConvert);

                        // console.log(totalDurActConvert);



                        // ActivityDurations[j] = totalDurationActivity;


                    }

                        // console.log(ActivityDurations);

                </script>



<style type="text/css">
    
    .chart_container {
    width: 1000px;
    height:600px
}

</style>



  <script>
var ctx = document.getElementById('myChart_activities_total').getContext('2d');
var myChart_activities_total = new Chart(ctx, {
    type: 'radar',
    data: {
        labels: allActions,
        datasets: [{
            label: ActivityDurations,
            data: ActivityDurations,
            backgroundColor: [
                'rgba(75, 192, 192, 1)',
                'rgba(255, 99, 132, 1)',
                'rgba(54, 162, 235, 1)',
            ],
            borderColor: [
                'rgba(75, 192, 192, 1)',
                'rgba(255, 99, 132, 1)',
                'rgba(54, 162, 235, 1)',
            ],
            borderWidth: 1
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        title: {
            display: true,
            text: 'Overall distribution of activities'
        },
    },
});
</script>


</div>


<h3>Find specific date</h3>
    <?php

if( isset( $_POST["find_action"] ) ) {


// change post date format

$action_date = $_POST["action_date"];


    // check to see if inputs are empty
    // create variables with form data
    // wrap the data with our function

    $sql = "SELECT id, completedAction, duration, satisfaction, completed_date FROM completedaction
WHERE completed_date = '$action_date'
";
$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) > 0) {
    // output data of each row
    while($row = mysqli_fetch_assoc($result)) {

        echo $row["id"] . "<br>";
        echo $row["completedAction"] . "<br>";
        echo $row["duration"] . "<br>";
        echo $row["satisfaction"] . "<br>";
        echo $row["completed_date"] . "<br>" . "<br>" . "<br>";

        echo "
        <form action = delete_completedaction.php method=POST>

            <input type = hidden name=ID value =".$row['id'].">
            <input type = submit name=submit value=Remove>


        </form>

        ";

}

} else {
    echo "0 results";
}


    
    if( !$_POST["action_date"] ) {
        $action_date_Error = "Please enter a date <br>";
        echo $newActionError;
    }

    
}


?>
<form action="<?php echo htmlspecialchars( $_SERVER['PHP_SELF'] ); ?>" method="post">

    <input autocomplete="off" type="date" name="action_date" placeholder="Search for a date">
    <br>
    <br>

<button type="text" name="find_action">
    Find actions
</button>

</form>


<?php

print_r($actions)

?>





<script>
function openCity(evt, cityName) {
  var i, tabcontent, tablinks;
  tabcontent = document.getElementsByClassName("tabcontent");
  for (i = 0; i < tabcontent.length; i++) {
    tabcontent[i].style.display = "none";
  }
  tablinks = document.getElementsByClassName("tablinks");
  for (i = 0; i < tablinks.length; i++) {
    tablinks[i].className = tablinks[i].className.replace(" active", "");
  }
  document.getElementById(cityName).style.display = "block";
  evt.currentTarget.className += " active";
}
</script>




</body>

<footer>
    
    <script type="text/javascript">
        
// prevent task from adding every reload of page
if ( window.history.replaceState ) {
  window.history.replaceState( null, null, window.location.href );
}

    </script>


    <style type="text/css">
        
        .chart_container {
        width: 800px;
        height:400px;        

    </style>

</footer>


</html>

