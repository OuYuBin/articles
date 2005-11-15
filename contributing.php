<?php  																														require_once($_SERVER['DOCUMENT_ROOT'] . "/eclipse.org-common/system/app.class.php");	require_once($_SERVER['DOCUMENT_ROOT'] . "/eclipse.org-common/system/nav.class.php"); 	require_once($_SERVER['DOCUMENT_ROOT'] . "/eclipse.org-common/system/menu.class.php"); 	$App 	= new App();	$Nav	= new Nav();	$Menu 	= new Menu();		include($App->getProjectCommon());    # All on the same line to unclutter the user's desktop'

	#*****************************************************************************
	#
	# template.php
	#
	# Author: 		Denis Roy
	# Date:			2005-06-16
	#
	# Description: Type your page comments here - these are not sent to the browser
	#
	#
	#****************************************************************************
	
	#
	# Begin: page-specific settings.  Change these. 
	$pageTitle 		= "Contributing Articles to Eclipse Corner";
	$pageKeywords	= "Eclipse, articles, information, help, rcp";
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
	
<div id="midcolumn">
	<h1>$pageTitle</h1>
	<h3>Eclipse Corner Articles</h3>

      <p>Articles appearing on Eclipse Corner have been written by members of
        the development team and other members of the eclipse community. You too
        can contribute! Eclipse Corner depends on contributions from people like
        you.
      </p>
      
<h3><a name="WritingArticle">How to write an article</a></h3>

      <p>So you have an idea for an article. Excellent! To make your article writing
        experience a smooth one here is a little advice:</p>
	<div class="homeitem3col">
      <h3>Before writing the article</h3>
      
      <ul class="midlist">
        <li>Before you start churning out the content, take a few minutes to
          search Bugzilla to make sure the subject is not already being covered.
          All requested, proposed, and in-progress articles are listed in the
          Community project, Articles component.
          This is also a good way to find out what articles people want if you're
          trying to decide what to write.
          If someone else is working on a similar article, consider joining them
          as a co-author, or adjusting the focus of your article so they don't overlap.</li>
        <li>Next, write down a short outline of your article and the main topics
          you intend to cover.
          Open a new bugzilla entry for the article if there's not one already and
          put your outline in the comments there.
          Indicate a rough idea of when you expect the article to be completed.</li>
        <li>Once your outline is entered in the system, interested parties will
          provide you with additional ideas and feedback.</li>
      </ul>
	</div>

	<div class="homeitem3col">
      <h3>Writing the article</h3>
      <p>Assuming the feedback on your outline is positive, then it's time to write the
        content.</p>
      <ul class="midlist">
        <li>To get you started you can extract the following <a href="article-template.zip"><b>document
          template</b></a> . Be sure to read the <b>readme.txt</b> in its root
          directory.</li>
        <li>Try and keep it fun and lively, after all, you probably wouldn't want
          to read a boring article! </li>
        <li>Keep your article practical and if you have code snippets <b>always</b>
          have your article link to a zip file containing a plug-in with the code.</li>
        <li>The last tip is to aim for maximal content with minimum words. Forty
          page articles could better be published as paper back novels.</li>
      </ul>
	</div>

	<div class="homeitem3col">
      <h3>After finishing the article</h3>
 
      <ul class="midlist">
        <li>When you have finished your draft article, zip it up and
            attach the zip to the bugzilla entry.
        </li>
        <li>The Articles Editor and one or more reviewers
          will work with you (via bugzilla comments and/or email) to finalize the article.
          As you come up with new revisions, attach them to the bugzilla entry.</li>
        <li>Don't be discouraged if you get lots of feedback.
          Most articles require at least two drafts before they are finalized.
          Remember, the reviewers are trying to help you make the article the best
          thing since the invention of those tiny umbrellas they put in drinks.</li>
      </ul>
     </div>
     
	<div class="homeitem3col">
      <h3>Posting the article</h3>
      <ul class="midlist">
        <li>After all the kinks are worked out and the final draft is in bugzilla,
          the article will be posted on the site and the bugzilla entry marked as closed.
          Stand back and watch as the masses come running to read it.<br>
        </li>
      </ul>
      </div>
      <p>Please direct any question on submitting an article to <a href="mailto:articles_editor@eclipse.org">(Editor)</a>.</p>
  
  
	<div class="homeitem3col">
  		<h3>Legal</h3>
 <p>Articles submitted to Eclipse Corner are accepted for posting under the
        &quot;<a href="http://www.eclipse.org/legal/termsofuse.html">Terms of
        Use</a>&quot;.</p>
	</div>
 </div>
EOHTML;

	# Generate the web page
	$App->generatePage($theme, $Menu, $Nav, $pageAuthor, $pageKeywords, $pageTitle, $html);
?>
