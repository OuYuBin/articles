<?xml version='1.0'?>
<!--
        $Id: article-pdf.xsl,v 1.1 2006/02/14 19:19:51 wbeaton Exp $
        author: Chris Aniszczyk <zx@us.ibm.com>
        author: Lawrence Mandel <lmandel@ca.ibm.com>
-->
<xsl:stylesheet xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
	xmlns:fo="http://www.w3.org/1999/XSL/Format" 
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
	<xsl:param name="hyphenate">false</xsl:param>

	<!--  supress the releaseinfo and copyright information -->
	<xsl:template match="releaseinfo | copyright | legalnotice"
		mode="titlepage.mode">
	</xsl:template>

<!-- 
	<xsl:template name="article.titlepage.recto">
		<xsl:apply-templates match="articleinfo/title" mode="article.titlepage.recto.auto.mode"/>
		<fo:block border-width="1mm">
			This block of output will have a one millimeter border around it.
		</fo:block>
	</xsl:template>
-->	
	<xsl:template match="title" mode="article.titlepage.recto.auto.mode">
		<fo:block>
			Copyright &#x00A9; <xsl:value-of select="//copyright/year[1]" />&#160;<xsl:value-of select="//copyright/holder[1]" />
		</fo:block>
		<fo:block xmlns:fo="http://www.w3.org/1999/XSL/Format" xsl:use-attribute-sets="article.titlepage.recto.style" keep-with-next.within-column="always" font-size="24.8832pt" font-weight="bold">
			<xsl:call-template name="component.title">
				<xsl:with-param name="node" select="ancestor-or-self::article[1]"/>
			</xsl:call-template>
		</fo:block>
	</xsl:template>
	
	<xsl:template match="revhistory" mode="article.titlepage.recto.auto.mode">	<xsl:echo>Revhistory</xsl:echo>
		<fo:block>
			Revision history goes here. I think.
  		</fo:block>
  		<!-- 
  		<fo:table-and-caption>
			<fo:table>
				<fo:table-column column-width="15mm"/>
				<fo:table-column column-width="25mm"/>
				<fo:table-column column-width="35mm"/>
				<fo:table-column column-width="50mm" />
				
				<fo:table-body>
					<fo:table-row>
						<fo:table-cell><fo:block>Junk</fo:block></fo:table-cell>
						<fo:table-cell><fo:block>Junk</fo:block></fo:table-cell>
						<fo:table-cell><fo:block>Junk</fo:block></fo:table-cell>
						<fo:table-cell><fo:block>Junk</fo:block></fo:table-cell>
					</fo:table-row>
					<xsl:apply-templates select="revision"/>
				</fo:table-body>
			</fo:table>
		</fo:table-and-caption>
		-->
	</xsl:template>
	<!-- 
	<xsl:template match="revision" mode="*">
		<fo:table-row>
			<fo:table-cell><fo:block>Junk</fo:block><xsl:apply-templates select="revnumber" mode="titlepage.mode"/></fo:table-cell>
			<fo:table-cell><xsl:apply-templates select="date" mode="titlepage.mode"/></fo:table-cell>
			<fo:table-cell>
				<xsl:for-each select="author">
					<xsl:apply-templates select="." mode="article.titlepage.recto.auto.mode"/>
				</xsl:for-each>
			</fo:table-cell>
					
			<fo:table-cell><xsl:apply-templates select="revdescription" mode="titlepage.mode"/></fo:table-cell>
		</fo:table-row>
	</xsl:template>
	-->
<!-- 
	<xsl:template name="article.titlepage.recto">
		<h1 align="center"><xsl:value-of select="articleinfo/title"/></h1>
		<p align="center">This document is made available under the Eclipse Public License 1.0 
		(<a href="http://eclipse.org/org/documents/epl-v10.php">EPL</a>).</p>
		
		<blockquote> 
			<xsl:apply-templates mode="article.titlepage.recto.auto.mode" select="articleinfo/abstract" /> 
			<p><xsl:apply-templates mode="article.titlepage.recto.auto.mode" select="articleinfo/authorgroup" /></p> 
		
			<p><xsl:apply-templates mode="article.titlepage.recto.mode" select="articleinfo/date"/></p>
			<xsl:apply-templates mode="article.titlepage.recto.auto.mode" select="articleinfo/revhistory" /> 
 		</blockquote>
	</xsl:template>
-->
	<!-- Override the way that authorgroups are rendered by
		printing a comma-separated list of author names in the regular font. -->
<!-- 
	<xsl:template match="authorgroup" mode="article.titlepage.recto.auto.mode">
		<xsl:if test="count(author) > 0">
			By 
			<xsl:for-each select="author">
				<xsl:if test="position() = last()">and </xsl:if>
  				<xsl:apply-templates select="." mode="article.titlepage.recto.auto.mode" />
				<xsl:if test="position() != last()">, </xsl:if>
    		</xsl:for-each>
    	</xsl:if>
	</xsl:template>
-->	
<xsl:template match="authorgroup" mode="article.titlepage.recto.auto.mode">
	<xsl:if test="count(author) > 0">
		<fo:block>
			By 
			<xsl:for-each select="author">
				<xsl:if test="count(.) > 1 and position() = last()">and </xsl:if>
  				<xsl:apply-templates select="." mode="article.titlepage.recto.auto.mode" />
				<xsl:if test="position() != last()">, </xsl:if>
    		</xsl:for-each>    		 
		</fo:block>
    </xsl:if>
</xsl:template>
	<!-- Author information should include their affiliation -->
 	
	<xsl:template match="author" mode="article.titlepage.recto.auto.mode">
  		<xsl:value-of select="firstname"/>&#160;<xsl:value-of select="surname"/> 
  			<xsl:if test="affiliation/orgname">
  				(<xsl:value-of select="affiliation/orgname"/>)
  			</xsl:if>
    </xsl:template>

<!-- 
	<xsl:template match="revhistory" mode="titlepage.mode">
		<p class="title"><b>Revision History</b></p>
		<table border="1" summary="Revision history">
			<xsl:apply-templates select="revision" mode="titlepage.mode"/>
		</table>
	</xsl:template>
-->
<!-- 	
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
-->
<!--  
	<xsl:template match="legalnotice"
     	mode="article.titlepage.recto.auto.mode.footnote">
    	<div xsl:use-attribute-sets="article.titlepage.recto.style">
      		<xsl:apply-templates select="."
         		mode="titlepage.mode"/>
    	</div>
  	</xsl:template>
-->
 	
  	<xsl:template name="process.footnotes">
  		<fo:block>
  			Footnotes go here.
  		</fo:block>
  	<!-- 
  		<xsl:if test="count(articleinfo/legalnotice)>0">
    		<hr/>
    		<h3>Trademarks</h3>
    		<xsl:apply-templates select="articleinfo/legalnotice"
      			mode="article.titlepage.recto.auto.mode.footnote"/>
      	</xsl:if> 
    -->
  	</xsl:template>

</xsl:stylesheet>