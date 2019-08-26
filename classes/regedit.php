<?php

//registry::clean_root();//instantiate on include, can point to upper directory
//registry::create_registry();//instantiate on install, can point to upper directory

class regedit implements I_registry
{
	private static $dir_create_array = [];//for directory creation
	private static $registry = "registry";
	private static $garbage_can = "deleted";
	private static $reg_array = [];
	private static $cwd_folder = "";
	private static $required_registry_entries = ["registry","deleted"];// same as self variables

	/************* functions ******************/
	public static function create_registry($dir=""){
		//self::$top_dir_for_registry = $dir;
		if($dir !== "") chdir($dir);
		foreach(self::$required_registry_entries as $entry){
			self::$dir_create_array[] = $entry;
		}
		self::_recursive_create_dir_array();
		if(self::_save_new_registry()){
			self::_display_creation_results();
		}
	}//////////////////////////////////////////////////////////

	public static function clean_root($dir=""){
		if($dir !== "") chdir($dir);
		self::_check_registry();
		self::_recursive_delete();
	}////////////////////////////////////////////////////////////

/******************* private internal functions *********************/
		private static function _display_creation_results(){
			$file = self::$registry;
			//die($file);
			$check = file($file,FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
			echo "<h1>Registry Created</h1>";
			echo "<pre>";
			var_dump(self::$dir_create_array);
			echo "</pre>";
			echo "<h1>Should Match</h1>";
			echo "<pre>";
			var_dump($check);
			echo "</pre>";
		}////////////////////////////////////////////////////////////////////
		private static function _save_new_registry(){
			$reg_string = "";
			foreach(self::$dir_create_array as $dir){
				$reg_string .= $dir . "\n";
			}
			$file = self::$registry;
			if(file_put_contents($file,$reg_string,LOCK_EX)){
				if(chmod($file,0700)) return true;
			}
			return false;
		}///////////////////////////////////////////////////////////////////
		private static function _recursive_create_dir_array($dir=""){
				$dir = trim($dir);
				$scanme = ($dir === "")? self::_get_cwd_folder() : self::_get_cwd_folder() . "/" . $dir;
				$all = scandir($scanme);
				foreach($all as $is){
					if($is !== "." && $is !== ".."){
						$ff = ($dir !== "")? $dir . "/" . $is : $is;
						if(!in_array($ff,self::$dir_create_array)) self::$dir_create_array[] = $ff;
						if(is_dir($ff) && $ff !== self::$garbage_can){
							self::_recursive_create_dir_array($ff);
						}
				}
			}
		}/////////////////////////////////////////////////////////////////////
		private static function _get_cwd_folder(){
				$c = getcwd();
				$char = (strpos($c,"/") === false)? "\\" : "/";
				$d = explode($char,$c);
				return self::$cwd_folder = implode("/",$d);
		}///////////////////////////////////////////////////////////////
		private static function _check_registry(){
			self::_get_registry();
			self::_add_registry_requirements();
			self::_make_garbage_can();
			self::_test_registry_item_characters();
			self::_verify_registry_item_exists();
		}///////////////////////////////////////////////////////////////////
		private static function _get_registry(){
			if(!is_file(self::$registry))
					// print_r("current directory: " . getcwd()); exit();
					die("No registry exists, or registry must be in this directory.");
			self::$reg_array = file(self::$registry,FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
			if(empty(self::$reg_array)) die("Registry is empty, error!");
		}///////////////////////////////////////////////////////////////////
		private static function _verify_registry_item_exists(){
			foreach(self::$reg_array as $l){
				if(!is_file($l) && !is_dir($l)){
					echo "<br>{$l} does not exist, but is listed in the registry.";
					die("<br>This may not be a valid registry.<br>Update your registry.");
					}
			}
		}////////////////////////////////////////////////////////////////
		private static function _test_registry_item_characters(){
			foreach(self::$reg_array as $l){
				$l = trim($l);
				$l2 = str_replace([".."],[""],$l);
				if($l !== $l2) die("Invalid entry in the registry, {$l} !== {$l2}");
			}
		}////////////////////////////////////////////////////////////////
		private static function _make_garbage_can(){
			if(!is_dir(self::$garbage_can)) mkdir(self::$garbage_can,0700);
		}////////////////////////////////////////////////////////////////

		private static function _add_registry_requirements(){
			$a=[];
			$len = count(self::$reg_array);
				foreach(self::$required_registry_entries as $entry){
						if(!in_array($entry,self::$reg_array)){
							self::$reg_array[] = $entry;
							echo "<br>...{$entry} added to the registry</br>";
						}
					}
			if($len !== count(self::$reg_array)){
					$registry_string = "";
					foreach(self::$reg_array as $reg_item){
						$registry_string .= trim($reg_item) . "\n";
					}
					file_put_contents(self::$registry,$registry_string);
					chmod(self::$registry,0700);
			}
		}////////////////////////////////////////////////////////////
		private static function _recursive_delete($dir=""){
			$dir = trim($dir);
				$scanme = ($dir === "")? self::_get_cwd_folder() : self::_get_cwd_folder() . "/" . $dir;
				$all = scandir($scanme);
				foreach($all as $is){
					//echo "{$is}<br>";
					if($is !== "." && $is !== ".."){
						$ff = ($dir !== "")? $dir . "/" . $is : $is;
						if(!in_array($ff,self::$reg_array)){
							if(is_file($ff)){
								chmod($ff,0700);
								$nn = self::$garbage_can . "/" . time() . $is . ".txt";
								rename($ff,$nn);
								chmod($nn,0700);
								//echo "<br>deleted..." . $ff;
							}
							if(is_dir($ff)){
								self::_recursive_delete($ff);
								if(!in_array($ff,self::$reg_array)){
									chmod($ff,0700);
								$nn = self::$garbage_can . "/" . time() . $is;
								rename($ff,$nn);
								chmod($nn,0700);
								//echo "<br>removed directory..." . $ff;
								}
							}
					}
					//even if directory is good, look inside
					if(is_dir($ff) && $ff !== self::$garbage_can){
						self::_recursive_delete($ff);
					}
				}
			}
		}///////////////////////////////////////////////////////////////////////////////
}//EOF

 ?>
