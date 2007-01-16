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
	
	$file = $_GET['file'];
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

	<?php include "$file" ?>	

