<?php


error_reporting(-1);
ini_set('display_errors', 'On');
// 
date_default_timezone_set('America/Los_Angeles');


$devices = array();

include_once("inc.classes.php");


 if (!isset($_GET["verified"])){

 ?>
<!DOCTYPE html>
<html>
  <head>
    <meta name="viewport" content="initial-scale=1.0, user-scalable=no">
    <meta charset="utf-8">
    <title>Device Locator | Login</title>
    <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css">
    </head>

    <body>
    <div class="container">
    <h1>iPhone Live Tracker Login</h1>
    <form action="" method="GET">

       <div class="form-group">
        <label for="exampleInputEmail1">Password</label>
        <input type="password" class="form-control" id="pass" name="verified" placeholder="password">
      </div>
    <input type="hidden" name="phone" value="1">
    <input type="submit" class="btn btn-primary" value="login">
    </form>
    </div>
    </body>
    </html>
    <?php
    exit();

 }

 if (md5($_GET["verified"])!=""){
    print "Incorrect password";
    exit();
 }

include_once("devices.php");



if (empty($devices)){
    print "<h2>No iCloud accounts found. Add one <a href='add_device.php'>here</a></h2>";
    exit();
}


 $x = 1;
 foreach ($devices as $iphone) {
    if ($x == $_GET["phone"]){
        $ssm = $iphone->connect();
        foreach ($ssm->devices as $device) {
             $bl = $device->batteryLevel*100;
             //print "Battery = $bl %";
             # code...
             $location_info = $ssm->locate($device->id);
             $location_data = $location_info;
             $location_data["battery"]=$bl;
             //$location_data["timestamp"]=$loca;
         }
    }
    $x++;
    
 }

 ?>



<!DOCTYPE html>
<html>
  <head>
    <meta name="viewport" content="initial-scale=1.0, user-scalable=no">
    <meta charset="utf-8">
    <title>Device Locator</title>
    <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css">

    <style>
      html, body, #map-canvas {
        height: 600px;
        margin: 0px;
        padding: 0px
      }
    </style>
    <script src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.4/js/bootstrap.min.js"></script>
    <script src="https://code.jquery.com/jquery-2.1.3.min.js"></script>
    <script src="https://maps.googleapis.com/maps/api/js?v=3.exp&signed_in=true"></script>
    <script>
    var map;
        function initialize() {
          
        }

        $(document).ready(function() {
         // code here
         var myLatlng = new google.maps.LatLng(<?php print $location_data["latitude"]; ?>,<?php print $location_data["longitude"]; ?>);
          var mapOptions = {
            zoom: 16,
            center: myLatlng
          }
          map = new google.maps.Map(document.getElementById('map-canvas'), mapOptions);

          var marker = new google.maps.Marker({
              position: myLatlng,
              map: map,
              title: 'Location'
          });
          
        });

        

    </script>
  </head>
  <body>
  <div class="container">
  <div class="row">
  <div class="col-md-8">
   <h1>iPhone Live Tracker</h1>
  </div>
    <div class="col-md-4">
    <br>
    <a href='#' class="btn btn-primary pull-right" onclick="window.open('add_device.php?verified=<?php echo $_GET["verified"]; ?>','Add Device','resizable=1,status=1,menubar=1,toolbar=0,scrollbars=0,location=1,directories=1,width=350,height=350,top=60,left=60');return false;">Add a Device</a>
  </div>
  </div>
 
  <div role="tabpanel">
  <ul class="nav nav-tabs" role="tablist">
  
  <?php
  $currentdevice = 1;
  foreach ($devices as $iDevice) {
  ?>

  <li role="presentation" <?php if ($_GET['phone']==$currentdevice){echo 'class="active"';}?>><a href="<?php echo basename($_SERVER['PHP_SELF']); ?>?verified=<?php echo $_GET['verified']; ?>&phone=<?php echo $currentdevice; ?>" >
  <?php echo $iDevice->devicename; ?></a></li>

  <?php
      $currentdevice++;
  }
  ?>
  </ul>
   


    <div class="panel panel-default">
        <div class="panel-body">

            <p>Phone battery <b><?php echo $location_data["battery"]; ?>%</b> </p>
            <p>Accuracy of Location : <b><?php echo $location_data["accuracy"]; ?>m</b> </p>
            <p>Time of information : <b><?php echo $location_data["timestamp"]; ?></b> </p>
            
        </div>
    </div>
</div>
    <div class="panel panel-default">
        <div class="panel-body">
            <div id="map-canvas"></div>
        </div>
    </div>


  </body>
  </div>
</html>
