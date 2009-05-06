<? 
/*******************************************************************************
 * Copyright (c) 2007 Eclipse Foundation and others.
 * All rights reserved. This program and the accompanying materials
 * are made available under the terms of the Eclipse Public License v1.0
 * which accompanies this distribution, and is available at
 * http://www.eclipse.org/legal/epl-v10.html
 *
 * Contributors:
 *    Wayne Beaton (Eclipse Foundation)- initial API and implementation
 *******************************************************************************/

/*
 * Let's not make things any harder then they need to be. For our purposes,
 * a valid file name will always be the name of a subdirectory of $root,
 * followed by a forward slash, and the name of a file.
 */
function is_valid_article_file(&$path) {
	global $root;
	
	$parts = split('/', $path);
	if (count($parts) != 2) return false;
	$directory = $parts[0];
	$file = $parts[1];
		
	if (!is_actual_article_directory($directory)) return false;	
	if (!file_exists("$root/$directory/$file")) return false;
		
	return true;
}

/*
 * Sure, we could just do an is_dir call, but I'm being extra paranoid.
 * Since we're only breaking on a backslash, I am somewhat concerned that
 * some future version of PHP might be smart enough to automatically handle
 * backslashes in file names, or some other sort of silliness. So... either
 * the first part of the $file, is the name of an actual subdirectory of
 * "/articles", or it is not.
 */
function is_actual_article_directory(&$directory_name) {
	global $root;
	
	if ($directory == ".") return false;
	if ($directory == "..") return false;
	
	$dir_handle = @opendir($root);
	while ($file = readdir($dir_handle)) {
		if ($directory_name == $file) {
			closedir($dir_handle);
			return true;
		}
	}
	closedir($dir_handle);
	return false;
}
	
function get_title_from_html_document(&$file_name) {
	$header = get_header_from_html_document($file_name);
	
	/*
	 * Break the header up into multiple lines. Handle the
	 * case where line breaks are lf, crlf, or cr.
	 */
	
	$lines = preg_split("/\r?\n|\r/", $header); 
	
	/*
	 * Merge the lines into a single line so that eregi
	 * can find the title even if it is split over multiple
	 * lines
	 */
	$one_line = implode(" ", $lines); 
	
	/*
	 * If we can match a title tag, extract it.
	 */
	if (eregi("<title>(.*)</title>", $one_line, $title)) {
    	return "Eclipse Corner Article: $title[1]";
	}
	
	return "Eclipse Corner Article";
}

function get_header_from_html_document(&$file_name) {
	$handle = @fopen($file_name, "r");
	$content = "";
    while (!feof($handle)) {
        $part = fread($handle, 1024);
        $content .= $part;
        
        /*
         * Only read up to the part that includes the
         * end tag for the header area.
         */
        if (eregi("</head>", $part)) break;
    }
    fclose($handle);
    return $content;
}