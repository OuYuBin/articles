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
$Nav->addNavSeparator("Contributing", 	null);
$Nav->addCustomNav("Eclipse Public License", "http://www.eclipse.org/org/documents/epl-v10.php", "_self", 1);
$Nav->addCustomNav("Website Terms of Use", "http://www.eclipse.org/legal/termsofuse.php", "_self", 1);
$Nav->addCustomNav("Article Template", "article-template.zip", "_self", 1);

# End: page-specific settings

ob_start();
?>
<div id="maincontent">
<div id="midcolumn">
<h1><?= $pageTitle ?></h1>

<img src="images/write.png" align="right" />

<h2>Eclipse Corner Articles</h2>

<p>Articles appearing on Eclipse Corner have been written by members of
the development team and other members of the eclipse community. You too
can contribute! Eclipse Corner depends on contributions from people like
you.</p>

<hr />

<h2>Editorial Guidelines</h2>
<p>Before we get to the good stuff, it's probably best that you have
some idea what kind of content we accept at Eclipse Corner.</p>

<p><strong>Articles should be timeless.</strong> Or at least relatively
timeless. That is, articles that contain time-sensitive material or are
likely to expire in a relatively short period of time do not belong on
Eclipse Corner. An article that discusses the ins and outs of some
Eclipse API belongs on Eclipse Corner because APIs are forever. An
article discussing the most recent Eclipse conference or release
probably doesn't fit.</p>
<p>Perhaps a little on the gray side of things are articles that discuss
how to use exemplary products produced by Eclipse products. These aren't
API and so may change and evolve, potentially rendering the content
obsolete. Despite this danger, this sort of article does tend to be
considered acceptable Eclipse Corner content.</p>

<p><strong>Articles must pertain to Eclipse projects.</strong> Ideally,
articles are about how to make Eclipse things (like APIs) work. Eclipse
Corner is not the place for articles that discuss the specifics of
proprietary products or code from other sources. More on the gray side
of things would be an article that discusses how Eclipse APIs are
leveraged to build a commercial product. As long as the article focuses
on how to do great things with Eclipse it's okay; overt advertising of
any commercial product doesn't belong on Eclipse Corner.</p>
<p>Articles should be explicitly associated with one or more Eclipse
projects. Ideally, the Project Management Committee (PMC) or Project
Lead (PL) from the projects to agree that the article is something
valuable that should make it to Eclipse Corner.</p>

<p><strong>Article proposals must be commented on in Bugzilla.</strong>
Comments indicate interest. If there is no community support behind an
article submission, then it might be best to just pass on it. Ideally
three-to-five people, including at least one committer from a pertinent
project, have to believe that the article is appropriate content for
Eclipse Corner.</p>

<p>The editorial staff, at their discretion, may waive this requirement
for articles that they deem to be useful additions to Eclipse Corner
despite an apparent lack of immediate interest by the committee.</p>

<p><strong>Articles are released under the Eclipse Public License (<a
	href="http://www.eclipse.org/org/documents/epl-v10.php">EPL</a>).</strong>
By putting the article on our server, it's subject to the EPL and
Eclipse &quot;<a href="http://www.eclipse.org/legal/termsofuse.php">Terms
of Use</a>&quot;. The content of the article must be licensable under
the EPL. If the article shows code, that code must be compatible with
the EPL along with the rest of the content.</p>

<hr />

<h2>Scheduling</h2>

<p>As a general rule, we endeavour to publish an average of two articles
each month. As articles reach an appropriate level of maturity, we will
schedule a date for publishing by indicating a date in comments to the
Bugzilla entry. This general rule is more of a guideline, and is more a
function of managing the time available to the editorial staff to
perform editorial responsibilities. More simply, articles are published
when they are ready.</p>

<hr />

<h2>Before writing the article</h2>

<p>Before you start churning out the content, take a few minutes to
search <a
	href="https://bugs.eclipse.org/bugs/buglist.cgi?query_format=advanced&short_desc_type=allwordssubstr&short_desc=&classification=Eclipse+Foundation&product=Community&component=Articles&long_desc_type=allwordssubstr&long_desc=&bug_file_loc_type=allwordssubstr&bug_file_loc=&status_whiteboard_type=allwordssubstr&status_whiteboard=&keywords_type=allwords&keywords=&emailtype1=substring&email1=&emailtype2=substring&email2=&bugidtype=include&bug_id=&votes=&chfieldfrom=&chfieldto=Now&chfieldvalue=&cmdtype=doit&order=Reuse+same+sort+as+last+time&field0-0-0=noop&type0-0-0=noop&value0-0-0=">Bugzilla</a>
to make sure the subject is not already being covered. All requested,
proposed, and in-progress articles are listed in the Community project,
Articles component. This is also a good way to find out what articles
people want if you're trying to decide what to write. If someone else is
working on a similar article, consider joining them as a co-author, or
adjusting the focus of your article so they don't overlap.</p>

<p>Next, write down a short outline of your article and the main topics
you intend to cover. Open a <a
	href="https://bugs.eclipse.org/bugs/enter_bug.cgi?product=Community&component=Articles">new
Bugzilla entry</a> for the article if there's not one already and put
your outline in the comments there. Indicate a rough idea of when you
expect the article to be completed. Also, explicitly state the Eclipse
projects that you believe most closely align with your content (don't
assume that this is obvious).</p>

<p>Once your outline is entered in the system, interested parties will
provide you with additional ideas and feedback. Feedback is good. No
feedback is not-so-good. As a general rule, the editorial staff will
solicit feedback from appropriate projects on your article. Consider
soliciting feedback from your community (blog, newsgroups, mailing
lists, etc.) if you don't receive feedback on your article within a week
or so. Be aware that only those who explicitly express interest in
hearing about Eclipse Corner proposals will be automatically notified by
the Bugzilla system. In general, this does not include developers from
Eclipse projects.</p>

<hr />

<h2>Writing the article</h2>

<p>Assuming the feedback on your outline is positive, then it's time to
write the content. To get you started you can extract the following <a
	href="article-template.zip"><b>document template</b></a>. Be sure to
read the <b>readme.txt</b> in its root directory.</p>

<p>Try and keep it fun and lively, after all, you probably wouldn't want
to read a boring article! Keep your article practical and if you have
code snippets <b>always</b> have your article link to a zip file
containing a plug-in with the code.</p>

<p>The last tip is to aim for maximal content with minimum words. Forty
page articles could better be published as paper back novels.</p>

<hr />

<h2>After finishing the article</h2>

<p>When you have finished your draft article, zip it up and attach the
zip to the Bugzilla entry.</p>

<p>The Articles Editor and one or more reviewers will work with you (via
Bugzilla comments and/or email) to finalise the article. As you come up
with new revisions, attach them to the Bugzilla entry.</p>

<p>Don't be discouraged if you get lots of feedback. Most articles
require at least two drafts before they are finalised. Remember, the
reviewers are trying to help you make the article the best thing since
the invention of those tiny umbrellas they put in drinks.</p>

<p>Before the article is posted on Eclipse Corner, the editors will ask
you to certify that the content is all original and/or can legally be
published under the <a
	href="http://www.eclipse.org/org/documents/epl-v10.php">EPL</a>. If you
cannot certify your content, it cannot be published.</p>

<hr />

<h2>Posting the article</h2>

<p>After all of the kinks are worked out and the final draft is in
Bugzilla, the article will be posted on the site and the Bugzilla entry
marked as closed. Stand back and watch as the masses come running to
read it.</p>

<small> Articles submitted to Eclipse Corner are accepted for posting
under the &quot;<a href="http://www.eclipse.org/legal/termsofuse.php">Terms
of Use</a>&quot;. </small></div>
</div>
<?php
$html = ob_get_contents();
ob_end_clean();
$App->generatePage($theme, $Menu, $Nav, $pageAuthor, $pageKeywords, $pageTitle, $html);
?>
