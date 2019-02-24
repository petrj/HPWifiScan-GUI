<?php
require_once('scanner.php');

$scanner = new Scanner();

$ip = Scanner::ValueOfGETOrSETVariable("ip", "");
$email = Scanner::ValueOfGETOrSETVariable("email", "");
$action = Scanner::ValueOfGETOrSETVariable("action", "");

if ($ip == "")
{
    $ip = $scanner->Configuration->ScanerIP;
}

if ($email == "")
{
    $email = $scanner->Configuration->EmailTo;
}

if ($action == "scan")
{
    $scanner->Scan($ip,$email);
} else
{
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>HP Wifi Scanner</title>

        <meta name="description" content="HP Wifi Scanner">
        <meta name="keywords" content="HP Wifi Scanner">
        <meta name="author" content="Petr Janoušek">

        <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">

        <script type="text/javascript" src="ajax.js"></script>
        <script type="text/javascript" src="simpletimer.js"></script>
        <script type="text/javascript" src="scanner.js"></script>
    </head>
    <body>
        <div>
            <div style="float:left">
                <a href="index.php?ip=<?php echo $ip ?>&email=<?php echo $email ?>">
                    <img src="scanner.png" style="width:80px;" />
                </a>
            </div>

            <div style="float:left;margin-left:30px;padding:0px;">

                <h1 style="margin-top:5px;margin-bottom: 0px;">HP Wifi Scan</h1>
                <?php
                if ($ip != "")
                {
                    $capabilitiesUrl = "http://$ip:8080/eSCL/ScannerCapabilities";
                    $statusUrl = "http://$ip:8080/eSCL/ScannerStatus";
                    ?>
                        <a href="<?php echo $capabilitiesUrl ?>" style="font-size: 10px;text-decoration: none" target="Capabilities">Capabilities</a>
                        &nbsp;&nbsp;
                        <a href="<?php echo $statusUrl ?>" style="font-size: 10px;text-decoration: none" target="Status">Status</a>
                <?php
                }
                ?>
            </div>

            <div style="clear:both;">
            </div>
        </div>

        <h4>
            Running on: <?php
                $serverNetworkName = exec("uname -n");
                echo $serverNetworkName; ?>
        </h4>

        <hr/>

             <form id="scanRequestForm" method="POST" action="index.php">
                <div style="width:150px;float:left;">
                    Scanner IP:
                </div>
                <div style="width:100px;float:left">
                    <input type="text" name="ip" id="ip" value="<?php echo $ip ?>"/>
                </div>
                <div style="clear:both"></div>

                <div style="width:150px;float:left;">
                    Email:
                </div>
                <div style="width:100px;float:left">
                    <input type="text" name="email" id="email" value="<?php echo $email ?>"/>
                </div>
                <div style="clear:both"></div>
            </form>
         <hr/>

         <button type="button" id="scanButton" onclick="new Scanner().Scan();" style="display:inline;width:325px;" >Scan</button>

         <div id="scanProgressDiv" style="float:left;display:inline;"></div>
         <div id="scanDiv"  style="float:left;"></div>
         <div style="clear:both"></div>

    </body>
</html>
 <?php
}
?>