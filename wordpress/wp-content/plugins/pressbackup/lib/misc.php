<?php

	class pressbackup_misc {

		function zip ($type='shell', $args = array()){
			if ($type == 'shell' && $bin = $this->checkShell('zip') ) {
				$compression = (isset($args['compression']))? '-'.$args['compression']: '';

				$cmd = 'cd '.$args['context_dir'].';';
				$cmd .= $bin.' -r -q '.$compression.' '.$args['zip'].' '.$args['dir'].';';
				$cmd .= 'chmod 0777 '.$args['zip'].';';
				$cmd .= $bin.' -T '.$args['zip'].';';
				$res = $this->ShellRun ($cmd);
				return ($res && strpos(strtolower($res[0]), 'ok') !== false);
			}
			elseif ($type == 'php')
			{
				$zip = new ZipArchive();
				if ($zip->open($args['zip'], ZIPARCHIVE::OVERWRITE)!==true) { return false; }

				@chdir ($args['context_dir']);
				if(!$this->zipFolder($args['dir'], $zip)){ $zip->close(); return false; }
				$zip->close();

				if( $zip->open($args['zip'], ZIPARCHIVE::CHECKCONS) !== TRUE ) { $zip->close(); return false; }
				$zip->close();
				return true;
			}
			return false;
		}

		function php ($args=array()){
			if(isset($args['file']) && !is_file($args['file'])) { return false; }
			if(isset($args['split']) && !preg_match("/^[A-Z\|]*$/", $args['split'])) { return false; }
			if(isset($args['args']) && !preg_match("/^[a-zA-Z0-9\=]*$/", $args['args'])) { return false; }

			$bin = $this->checkShell('php');
			$cmd = $bin.' '.$args['file'].' "'.$args['split'].'" "'.$args['args'].'" > /dev/null  2>&1 &';
			return $this->ShellRun ($cmd);
		}

		function checkShell ($type = 'zip'){

			if($type == 'zip'){
				if ( !$res = $this->ShellRun( 'whereis -b zip' ) ) { return false; }

				$res = str_replace ('zip: ', '', $res[0]);
				$binaries = explode(' ', $res);
				for($i=0; $i < count($binaries); $i++){
					$res = $this->ShellRun( $binaries[$i]. ' -T popo'  );
					if( $res && strpos(strtolower($res[1]), 'zip') !== false ) {
						return $binaries[$i];
					}
				}
			}
			elseif ($type == 'php') {

				if ( !$res = $this->ShellRun( 'whereis -b php' ) ) { return false; }

				$res = str_replace ('php: ', '', $res[0]);
				$binaries = explode(' ', $res);
				for($i=0; $i < count($binaries); $i++){
					$res = $this->ShellRun($binaries[$i]. ' -r "echo \'hola\';"' );
					if(str_replace(array('\n', ''), '', $res[0]) == 'hola') { return $binaries[$i];}
				}
				return false;
			}
		}

		private function ShellRun ($cmd) {
			$output = array(); $return_var = 1;
			exec ($cmd, $output, $return_var);
			return $output;
		}

		function zipFolder($dir, $zipArchive)
		{
			if (!is_dir($dir) || !$dh = opendir($dir)) {return false;}

			// Loop through all the files
			while (($file = readdir($dh)) !== false)
			{
				if( ($file == '.') || ($file == '..')) { continue; }

				//If it's a folder, run the function again!
				if(is_dir($dir . $file)) {
						if(! $this->zipFolder($dir . $file . DS, $zipArchive)){ return false; }
				}
				else
				{
					if (!$zipArchive->addFile($dir . $file)){ return false; }
				}
			}
			closedir($dh);
			return true;
		}

		function perpare_folder ($dir) {
			$this->actionFolder($dir . DS,  array('function'=>'del')); @mkdir($dir);
			$this->actionFolder($dir . DS,  array('function'=>'chmod', 'param'=>array(0777)));
		}

		function actionFolder($dir, $option)
		{
			if(is_file($dir))
			{
				if($option['function']=='del')
				{
					return @unlink($dir);
				}
				elseif($option['function']=='chmod')
				{
					return @chmod($dir, $option['param'][0]);
				}
			}
			elseif(is_dir($dir))
			{
				$scan = scandir($dir);
				foreach($scan as $index=>$path)
				{
					if(!in_array($path, array('.','..')) ) {$this->actionFolder($dir.DS.$path, $option); }
				}
				if($option['function']=='del')
				{
					return @rmdir($dir);
				}
				elseif($option['function']=='chmod')
				{
					return @chmod($dir, $option['param'][0]);
				}
			}
		}

		function msort($type='S3', $array, $id="time")
		{
			$converted_array=array();
			if ($type == 'S3')
			{
				foreach ($array as $item)
				{
					$converted_array[]=$item;
				}
			}
			elseif (is_array($array['items']))
			{
				foreach ($array['items'] as $item)
				{
					$converted_array[]=$item['item'];
				}
			}

			$temp_array = array();
			while(count($converted_array)>0)
			{
				$lowest_id = 0;
				$index=0;
				foreach ($converted_array as $item)
				{
					if (isset($item[$id]) && $converted_array[$lowest_id][$id])
					{
						if (strcmp( $item[$id], $converted_array[$lowest_id][$id]) > 0)
						{
							$lowest_id = $index;
						}
					}
					$index++;
				}
				$temp_array[] = $converted_array[$lowest_id];
				$converted_array = array_merge(array_slice($converted_array, 0,$lowest_id), array_slice($converted_array, $lowest_id+1));
			}
			return $temp_array;
		}

		function filter_files($type='all_sites', $backup_files)
		{
			if ($type=='all_sites') {return $backup_files;}
			$blog_name =str_replace(' ', '-', strtolower(trim (get_bloginfo('name')))).'-backup';
			$filtered_array=array();
			for ($i=0; $i < count($backup_files); $i++)
			{
				$backup_from = explode('_',$backup_files[$i]['name']);
				if (rawurldecode(strtolower(trim($backup_from[0]))) == $blog_name)
				{
					$filtered_array[]= $backup_files[$i];
				}
			}
			return $filtered_array;
		}

		function upload_max_filesize()
		{
			if (! $filesize = ini_get('upload_max_filesize')) {
				$filesize = "5M";
			}

			if ($postsize = ini_get('post_max_size')) {
				return min($this->get_byte_size($filesize), $this->get_byte_size($postsize));
			} else {
				return $this->get_byte_size($filesize);
			}
		}

		function get_byte_size($size = 0)
		{
			if (! $size) {
				return 0;
			}

			$scan['gb'] = 1073741824; //1024 * 1024 * 1024;
			$scan['g']  = 1073741824; //1024 * 1024 * 1024;
			$scan['mb'] = 1048576;
			$scan['m']  = 1048576;
			$scan['kb'] =    1024;
			$scan['k']  =    1024;
			$scan['b']  =       1;

			foreach ($scan as $unit => $factor) {
				if (strlen($size) > strlen($unit)
				 && strtolower(substr($size, strlen($size) - strlen($unit))) == $unit) {
					return substr($size, 0, strlen($size) - strlen($unit)) * $factor;
				}
			}
			return $size;
		}

		function get_size_in($unit = 'm', $size= 0)
		{
			if (! $size) {
				return 0;
			}

			$scan['gb'] = 1073741824; //1024 * 1024 * 1024;
			$scan['g']  = 1073741824; //1024 * 1024 * 1024;
			$scan['mb'] = 1048576;
			$scan['m']  = 1048576;
			$scan['kb'] =    1024;
			$scan['k']  =    1024;
			$scan['b']  =       1;


			return round( $size / $scan[$unit], 2);
		}

	}
?>
