<?php
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

	#*****************************************************************************
	#
	# article.php
	#
	# Author: 		Wayne Beaton
	# Date:			2005-11-16
	#
	# Description: This page provides a frame for articles.
	#
	#
	#****************************************************************************
	
	$root = $_SERVER['DOCUMENT_ROOT'] . '/articles';
	$file = $_GET['file'];
	
	// If the requested article does not exist, redirect to a warning page.
	if (!file_exists("$root/$file")) $file = 'nosucharticle.html';
	
	$host = $_SERVER['HTTP_HOST'];
	#
	# Begin: page-specific settings.  Change these. 
	$pageTitle 		= "Eclipse Corner Articles";
	// TODO Should be able to extract the title from the XML data.
	$pageKeywords	= "article, articles, tutorial, tutorials, how-to, howto, whitepaper, whitepapers, white, paper";
	$pageAuthor		= "Wayne Beaton";
	
	# End: page-specific settings
	#
	
?>
<html>
<head>
	<base href="http://<?= $host ?>/articles/<?= $file ?>"/>
	<title>Eclipse Corner Article</title>
</head>
<body onLoad="window.print();">
	<?php include "$file" ?>	
</body>
