<?php

// https://github.com/PHPMailer/PHPMailer
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';

class Scanner
{
    public $Configuration;

    function __construct()
    {
        $json_data = file_get_contents(self::CfgPath());
        $this->Configuration = json_decode($json_data);
    }

    public static function AppPath()
    {
        $AppPath = dirname($_SERVER["SCRIPT_FILENAME"]);
        return $AppPath;
    }

    public static function AppUrlPath()
    {
        $AppUrlPath = "http://" . $_SERVER["HTTP_HOST"] . dirname($_SERVER["SCRIPT_NAME"]);
        return $AppUrlPath;
    }

    public static function CfgPath()
    {
        return self::AppPath() . "/scanner.json";
    }

    public static function ValueOfGETOrSETVariable($name, $defaultValue)
    {
        $res = $defaultValue;
        if ((isset($_GET[$name])) && ($_GET[$name] != ""))
        {
            $res=$_GET[$name];
        }
        if ((isset($_POST[$name])) && ($_POST[$name] != ""))
        {
            $res=$_POST[$name];
        }

        return $res;
    }

    public function Scan($scannerIp,$sendToEmail)
    {
        $date   = new DateTime();
        $dateStr = date_format($date ,"YmdHis");

        $jpgName = "scan" . $dateStr . ".jpg";
        $PDFName = "scan" . $dateStr . ".pdf";

        $JpegOutputPath = self::AppPath() . "/jpg";
        $PDFOutputPath = self::AppPath() . "/pdf";

        $JpegFileName = $JpegOutputPath . "/" . $jpgName;
        $PDFFileName = $PDFOutputPath . "/" . $PDFName;

        $JpegUrlFileName = self::AppUrlPath() . "/jpg" . "/" . $jpgName;
        $PDFUrlFileName = self::AppUrlPath() . "/pdf" . "/" . $PDFName;

        $cmd = "mono " . $this->Configuration->HPWifiScanPath . " " . $scannerIp . " \"" . $JpegFileName . "\"";
        $cmdPDF = "img2pdf $JpegFileName >> $PDFFileName";

        try
        {
            $lastLine = exec($cmd);
            $lastLine = exec($cmdPDF);

            if ($sendToEmail != "")
            {
                $body  = $this->Configuration->EmailBody . " $PDFName";

                $mailer = new PHPMailer();
                $mailer->CharSet = 'UTF-8';
                $mailer->From      = $this->Configuration->EmailFrom;
                $mailer->FromName  = $this->Configuration->EmailFromName;
                $mailer->Subject   = $this->Configuration->EmailSubject;
                $mailer->Body      = $body;
                $mailer->AddAddress($sendToEmail);

                $mailer->AddAttachment( $PDFFileName ,  $PDFName);

                if ( !$mailer->Send())
                {
                        $mailresult = "Sending mail to $sendToEmail failed";
                } else
                {
                        $mailresult = "Scanned document $PDFName was sent to $sendToEmail";
                }
            } else
            {
                $mailresult = "";
            }
            ?>
             <br/>
                    <a href="<?php echo $JpegUrlFileName ?>"><?php echo $JpegUrlFileName ?></a>
                    <br/>
                    <a href="<?php echo $PDFUrlFileName ?>"><?php echo $PDFUrlFileName ?></a>
                    <br/>
                    <?php echo $mailresult; ?>

                    <a href="<?php echo $JpegUrlFileName ?>">
                        <img src="<?php echo $JpegUrlFileName ?>"/>
                    </a>
        <?php

        } catch (Exception $ex)
        {
            echo "Error " . $ex;
        }
    }
}