<?php 
    function recordbrowse($URL)
    {
        if (isset($_SESSION['Browsing'])) 
        {
            $count=count($_SESSION['Browsing']);
			
            if ($count==0) 
            {
                date_default_timezone_set("Asia/Yangon");
                $_SESSION['Browsing'][0]['PageName']=$URL;
                $_SESSION['Browsing'][0]['DateTime']=date('Y-m-d H:i:sa');
            }
			
            else
            {
                date_default_timezone_set("Asia/Yangon");
                $_SESSION['Browsing'][$count]['PageName']=$URL;
                $_SESSION['Browsing'][$count]['DateTime']=date('Y-m-d H:i:sa');
            }
        }

        else
        {
            $_SESSION['Browsing']=array();
        }
    }
 ?>