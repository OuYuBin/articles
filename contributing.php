<?php  																														require_once($_SERVER['DOCUMENT_ROOT'] . "/eclipse.org-common/system/app.class.php");	require_once($_SERVER['DOCUMENT_ROOT'] . "/eclipse.org-common/system/nav.class.php"); 	require_once($_SERVER['DOCUMENT_ROOT'] . "/eclipse.org-common/system/menu.class.php"); 	$App 	= new App();	$Nav	= new Nav();	$Menu 	= new Menu();		include($App->getProjectCommon());    # All on the same line to unclutter the user's desktop'

	#*****************************************************************************
	#
	# template.php
	#
	# Author: 		Wayne Beaton
	# Date:			2005-11-23
	#
	# Description: 
	#
	#
	#****************************************************************************
	
	#
	# Begin: page-specific settings.  Change these. 
	$pageTitle 		= "Contributing Articles to Eclipse Corner";
	$pageKeywords	= "Eclipse, articles, information, help, rcp, Spotted Purple Funky Winkerbeans";
	$pageAuthor		= "Wayne Beaton";
	
	# Add page-specific Nav bars here
	# Format is Link text, link URL (can be http://www.someothersite.com/), target (_self, _blank), level (1, 2 or 3)
	# $Nav->addNavSeparator("My Page Links", 	"downloads.php");
	# $Nav->addCustomNav("My Link", "mypage.php", "_self", 3);
	# $Nav->addCustomNav("Google", "http://www.google.com/", "_blank", 3);

	# End: page-specific settings
	#
	# Paste your HTML content between the EOHTML markers!	
	$html = <<<EOHTML
<div id="maincontent">
	<div id="midcolumn">
		<h1>$pageTitle</h1>
	
		<img src="images/write.png" align="right" />
		
		<h2>Eclipse Corner Articles</h2>
	
	    <p>
	    	Articles appearing on Eclipse Corner have been written by members of
	        the development team and other members of the eclipse community. You too
	        can contribute! Eclipse Corner depends on contributions from people like
	        you.
		</p>
	      
	
		<h2><a name="WritingArticle">How to write an article</a></h2>
	
		<p>
			So you have an idea for an article. Excellent! To make your article writing
	        experience a smooth one here is a little advice...
		</p>
		
		<h2>Before writing the article</h2>
	      
	    <p>
			Before you start churning out the content, take a few minutes to
			search Bugzilla to make sure the subject is not already being covered.
			All requested, proposed, and in-progress articles are listed in the
			Community project, Articles component.
			This is also a good way to find out what articles people want if you're
			trying to decide what to write. If someone else is working on a similar 
			article, consider joining them as a co-author, or adjusting the focus of 
			your article so they don't overlap.
		</p>
		
	    <p>
	    	Next, write down a short outline of your article and the main topics
			you intend to cover. Open a <a href="https://bugs.eclipse.org/bugs/enter_bug.cgi?product=Community&component=Articles">new bugzilla entry</a> for the article if there's 
			not one already and put your outline in the comments there. Indicate a 
			rough idea of when you expect the article to be completed.
		</p>
	
		<p>
			Once your outline is entered in the system, interested parties will provide 
			you with additional ideas and feedback.
		</p>
	    
		<h2>Writing the article</h2>
	    
		<p>
			Assuming the feedback on your outline is positive, then it's time to write 
			the content. To get you started you can extract the following 
			<a href="article-template.zip"><b>document template</b></a> . Be sure to 
			read the <b>readme.txt</b> in its root directory.
		</p>
		
		<p>
			Try and keep it fun and lively, after all, you probably wouldn't want
	        to read a boring article! Keep your article practical and if you have 
			code snippets <b>always</b> have your article link to a zip file containing 
			a plug-in with the code.
		</p>
	    
		<p>
			The last tip is to aim for maximal content with minimum words. Forty
	        page articles could better be published as paper back novels.
		</p>
	    
		<h2>After finishing the article</h2>
	 
	    <p>
			When you have finished your draft article, zip it up and attach the zip 
			to the bugzilla entry.
		</p>
	    
	    <p>
			The Articles Editor and one or more reviewers will work with you (via bugzilla 
			comments and/or email) to finalize the article. As you come up with new 
			revisions, attach them to the bugzilla entry.
		</p>
	
		<p>
			Don't be discouraged if you get lots of feedback. Most articles require at 
			least two drafts before they are finalized. Remember, the reviewers are trying 
			to help you make the article the best thing since the invention of those tiny 
			umbrellas they put in drinks.
		</p>
		  
	    <h2>Posting the article</h2>
	    
	    <p>
	    	After all the kinks are worked out and the final draft is in bugzilla,
	        the article will be posted on the site and the bugzilla entry marked as closed.
	        Stand back and watch as the masses come running to read it.
	    </p>
	 		
		<small>
			Articles submitted to Eclipse Corner are accepted for posting under the
       		&quot;<a href="http://www.eclipse.org/legal/termsofuse.html">Terms of
       		Use</a>&quot;.
		</small>
	 </div>
</div>
EOHTML;

	# Generate the web page
	$App->generatePage($theme, $Menu, $Nav, $pageAuthor, $pageKeywords, $pageTitle, $html);
?>
