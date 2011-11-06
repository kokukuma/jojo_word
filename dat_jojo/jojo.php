<?php

	//$r = new HttpRequest('http://wikipedia.simpleapi.net/api?keyword=TCP/IP',HttpRequest::METH_GET);

$urls = array("http://homepage2.nifty.com/pdness/jojo/001.html"
		      , "http://homepage2.nifty.com/pdness/jojo/002.html"
		      , "http://homepage2.nifty.com/pdness/jojo/003.html"
		      , "http://homepage2.nifty.com/pdness/jojo/004.html"
		      , "http://homepage2.nifty.com/pdness/jojo/005.html"
);

$i=1;
foreach ($urls as $url){
	$r = new HttpRequest($url,HttpRequest::METH_GET);
	try{
		$r->send();
		if ($r->getResponseCode()==200) {
			$str = strip_tags($r->getResponseBody());
			$str = mb_convert_encoding($str, "UTF-8", "SJIS");
			$str = ereg_replace("(\r|\n|\r\n)+", "\n", $str);

			$file_name = "jojo$i.txt";
			$fp = fopen($file_name,"w");
			fwrite($fp,$str);
			fclose($fp);

			echo $str;
		}else {
			echo "error";
		}
	}catch(HttpException $ex){
		echo $ex;
	}
	$i++;
}
?>
