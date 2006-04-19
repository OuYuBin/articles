<?xml version='1.0'?>
<!--
        $Id: article.xsl,v 1.3 2006/04/19 17:39:20 wbeaton Exp $
        author: Chris Aniszczyk <zx@us.ibm.com>
        author: Lawrence Mandel <lmandel@ca.ibm.com>
-->
<xsl:stylesheet xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
	version="1.0">
	<xsl:import href="docbook.xsl" />
	
	<xsl:param name="html.stylesheet"
		select="'default_style.css'" />
	<xsl:param name="admon.graphics" select="1" />
	<xsl:param name="admon.graphics.path">images/</xsl:param>
	<xsl:param name="admon.graphics.extension">.png</xsl:param>
	<xsl:param name="suppress.navigation" select="1" />
	<xsl:param name="bibliography.numbered" select="1" />
	<xsl:param name="generate.toc">article toc</xsl:param>
	<xsl:param name="toc.section.depth">5</xsl:param>
	<xsl:param name="ulink.target" select="'_new'"/>
	<xsl:param name="admon.style">
  		<xsl:text>margin-left: 0.38in; margin-right: 0.38in;</xsl:text>
	</xsl:param>

	<!--  supress the releaseinfo and copyright information -->
	<xsl:template match="releaseinfo | copyright"
		mode="titlepage.mode">
	</xsl:template>

	<xsl:template name="user.header.content">
		<div align="right">
			&#160;
			<span class="copy">
				Copyright &#x00A9;<xsl:value-of select="//copyright/year[1]" />&#160;<xsl:value-of select="//copyright/holder[1]" />
			</span>
		</div>
	</xsl:template>
	
	<xsl:template name="article.titlepage.recto">
		<h1><img src="/articles/images/eclipse.png" align="right"/><xsl:value-of select="articleinfo/title"/></h1>
		<p>This document is made available under the Eclipse Public License 1.0 
			(<a href="http://eclipse.org/org/documents/epl-v10.php">EPL</a>).</p>
		
		<blockquote> 
			<xsl:apply-templates mode="article.titlepage.recto.auto.mode" select="articleinfo/abstract" /> 
			<p><xsl:apply-templates mode="article.titlepage.recto.auto.mode" select="articleinfo/authorgroup" /></p> 
		
			<p><xsl:apply-templates mode="article.titlepage.recto.mode" select="articleinfo/date"/></p>
			<xsl:apply-templates mode="article.titlepage.recto.auto.mode" select="articleinfo/revhistory" /> 
 		</blockquote>
	</xsl:template>

	<!-- Override the way that authorgroups are rendered by
		printing a comma-separated list of author names in the regular font. -->

	<xsl:template match="authorgroup" mode="article.titlepage.recto.auto.mode">
		<xsl:if test="count(author) > 0">
			By 
			<xsl:for-each select="author">
				<xsl:if test="count(.) > 1 and position() = last()">and </xsl:if>
  				<xsl:apply-templates select="." mode="article.titlepage.recto.auto.mode" />
				<xsl:if test="position() != last()">, </xsl:if>
    		</xsl:for-each>
    	</xsl:if>
	</xsl:template>
	
	<!-- Author information should include their affiliation -->
	
	<xsl:template match="author" mode="article.titlepage.recto.auto.mode">
  		<xsl:value-of select="firstname"/>&#160;<xsl:value-of select="surname"/> 
  		<xsl:if test="affiliation/orgname">
  			(<xsl:value-of select="affiliation/orgname"/>)
  		</xsl:if>
    </xsl:template>
  

	<xsl:template match="revhistory" mode="titlepage.mode">
		<p class="title"><b>Revision History</b></p>
		<table border="1" summary="Revision history">
			<xsl:apply-templates select="revision" mode="titlepage.mode"/>
		</table>
	</xsl:template>
	
	<xsl:template match="revision" mode="titlepage.mode">
		<tr valign="top">
			<td><xsl:apply-templates select="revnumber" mode="titlepage.mode"/></td>
			<td><xsl:apply-templates select="date" mode="titlepage.mode"/></td>
			<td>
				<xsl:for-each select="author">
					<xsl:apply-templates select="." mode="article.titlepage.recto.auto.mode"/>
					<xsl:if test="position() != last()"><br/></xsl:if>
				</xsl:for-each>
			</td>
					
			<td><xsl:apply-templates select="revdescription" mode="titlepage.mode"/></td>
		</tr>
	</xsl:template>
  
	<xsl:template match="legalnotice"
     	mode="article.titlepage.recto.auto.mode.footnote">
    	<div xsl:use-attribute-sets="article.titlepage.recto.style">
      		<xsl:apply-templates select="."
         		mode="titlepage.mode"/>
    	</div>
  	</xsl:template>
  	
  	<xsl:template name="process.footnotes">
  		<xsl:if test="count(articleinfo/legalnotice)>0">
    		<hr/>
    		<h3>Trademarks</h3>
    		<xsl:apply-templates select="articleinfo/legalnotice"
      			mode="article.titlepage.recto.auto.mode.footnote"/>
      	</xsl:if>
  	</xsl:template>
</xsl:stylesheet>