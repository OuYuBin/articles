<?php

	# Set the theme for your project's web pages.
	# See the Committer Tools "How Do I" for list of themes
	# https://dev.eclipse.org/committers/ 
	$theme = "Phoenix";

	# Define your project-wide Nav bars here.
	# Format is Link text, link URL (can be http://www.someothersite.com/), target (_self, _blank), level (1, 2 or 3)
	$Nav->addNavSeparator("Articles", "index.php");
	$Nav->addCustomNav("RCP Articles", "index.php?filter=rcp", "_self", 1);
	$Nav->addCustomNav("Contributing", "contributing.php", "_self", 1);
	#$Nav->addCustomNav("Contact the editor", "mailto:articles_editor@eclipse.org", "_self", 1);

?>
