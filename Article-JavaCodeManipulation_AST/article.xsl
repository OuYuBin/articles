<?xml version='1.0'?>
<!--
        $Id: article.xsl,v 1.2 2007/05/02 01:44:44 wbeaton Exp $
        author: Chris Aniszczyk <zx@us.ibm.com>
        author: Lawrence Mandel <lmandel@ca.ibm.com>
-->
<xsl:stylesheet xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
	version="1.0">
	<xsl:import href="docbook.xsl" />
	
	<xsl:param name="html.stylesheet"
		select="'../article.css'" />
	<xsl:param name="admon.graphics" select="1" />
	<xsl:param name="admon.graphics.path">images/</xsl:param>
	<xsl:param name="admon.graphics.extension">.png</xsl:param>
	<xsl:param name="suppress.navigation" select="1" />
	<xsl:param name="bibliography.numbered" select="1" />
	<xsl:param name="generate.toc">article nop</xsl:param>
	<xsl:param name="ulink.target" select="'_new'"/>
	<xsl:param name="admon.style">
  		<xsl:text>margin-left: 0.38in; margin-right: 0.38in;</xsl:text>
	</xsl:param>

	<!--  supress the releaseinfo and copyright information -->
	<xsl:template match="releaseinfo | copyright"
		mode="titlepage.mode">
	</xsl:template>
	
	<xsl:template name="article.titlepage.recto">
		<h1 align="center"><xsl:value-of select="articleinfo/title"/></h1>
 
		<div class="summary"> 
			<h2>Summary</h2>
			<p><xsl:value-of select="articleinfo/abstract"/></p>
	
   			 <div class="author">
   			 	By 
    				<xsl:for-each select="articleinfo/authorgroup/author">
  						<xsl:value-of select="firstname"/>&#160;<xsl:value-of select="surname"/>, <xsl:value-of select="affiliation/orgname"/><br/>
    				</xsl:for-each>
   			 </div>
   			 <div class="copyright">Copyright &#x00A9;<xsl:value-of select="//copyright/year[1]" />&#160;<xsl:value-of select="//copyright/holder[1]" /></div>
   			 <div class="date"><xsl:apply-templates mode="article.titlepage.recto.mode" select="articleinfo/date"/></div>
 		</div>
	</xsl:template>

	<xsl:template match="legalnotice"
     	mode="article.titlepage.recto.auto.mode.footnote">
    	<div xsl:use-attribute-sets="article.titlepage.recto.style">
      		<xsl:apply-templates select="."
         		mode="titlepage.mode"/>
    	</div>
  	</xsl:template>
  	
  	<xsl:template name="process.footnotes">
  		<div class="notices">
    	<h3>Trademarks</h3>
    	<xsl:apply-templates select="articleinfo/legalnotice"
      		mode="article.titlepage.recto.auto.mode.footnote"/>
      	</div>
  	</xsl:template>
</xsl:stylesheet>