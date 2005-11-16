The process of updating the landing page for articles has been completely
automated. To make use of this, you must provide a file, about.xml, for 
each article. It is assumed that each article exists in a subdirectory of
the "articles" directory. The about.xml file contains all the metadata for 
one article and includes a link (relative to the location of about.xml).

If a file named "articles.html" exists in the "articles/" directory, its 
contents are used for the content area of the landing page. A check is 
currently done against the age of this file; if it is more than 3 months old,
the contents are dynamically generated (i.e. the file is ignored). I'm not 
sure if this is really necessary as it is intended to cover the case where
we forget to update the precomputed page. I imagine that that this feature
will be removed.

The "articles.html" page is updated by pointing the browser to 
"/articles/update_landing_page.php".

Future plans:

Update this document to describe the format of the about.xml file.
Build a schema for the about.xml file.

Improve support for update metadata. Currently, only a date is captured. 
I'd like to capture the identity of the updater as well.