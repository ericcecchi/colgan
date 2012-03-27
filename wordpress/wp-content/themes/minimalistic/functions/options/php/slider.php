<?php
	function delete_image($filename = MINIMALISTIC_ADMIN_XML)
	{
	  $data = simplexml_load_file($filename);
	  $length = count($data->Image);
	  for($i=0; $i < $length; $i++)
	  {
		unset($data->Image[$i]);
		unset($data->Image[$i]);
		unset($data->Image[$i]);
		unset($data->Image[$i]);
		unset($data->Image[$i]);
	  }
	  file_put_contents($filename, $data->saveXML());
	}
?>