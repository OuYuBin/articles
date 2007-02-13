<?xml version='1.0'?>
<!--
	$Id: article.xsl,v 1.3 2007/02/13 17:46:07 wbeaton Exp $
	author: Chris Aniszczyk <zx@us.ibm.com>
	author: Lawrence Mandel <lmandel@ca.ibm.com>
-->
<xsl:stylesheet xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
	version="1.0">
	<xsl:import href="docbook.xsl" />
	
	<xsl:output doctype-system="http://www.w3.org/TR/html4/loose.dtd" />
	<xsl:output doctype-public="-//W3C//DTD HTML 4.01//EN" />

	<xsl:param name="html.stylesheet" select="'../article.css'" />
	<xsl:param name="admon.graphics" select="1" />
	<xsl:param name="admon.graphics.path">images/</xsl:param>
	<xsl:param name="admon.graphics.extension">.png</xsl:param>
	<xsl:param name="suppress.navigation" select="1" />
	<xsl:param name="bibliography.numbered" select="1" />
	<xsl:param name="generate.toc">article nop</xsl:param>
	<xsl:param name="ulink.target" select="'_new'" />
	<xsl:param name="admon.style">
		<xsl:text>margin-left: 0.38in; margin-right: 0.38in;</xsl:text>
	</xsl:param>

	<!--  supress the releaseinfo and copyright information -->
	<xsl:template match="releaseinfo | copyright"
		mode="titlepage.mode">
	</xsl:template>

	<xsl:template match="article">
		<h1>
			<xsl:value-of select="articleinfo/title" />
		</h1>

		<div class="summary">
			<h2>Summary</h2>
			<p>
				<xsl:value-of select="articleinfo/abstract" />
			</p>

			<div class="author">
				By
				<xsl:for-each select="articleinfo/authorgroup/author">
					<xsl:value-of select="firstname" />&#160;<xsl:value-of select="surname" />,
					<xsl:value-of select="affiliation/orgname" />
					<br />
				</xsl:for-each>
			</div>
			<div class="copyright">
				Copyright &#x00A9;
				<xsl:value-of select="//copyright/year[1]" />&#160;<xsl:value-of select="//copyright/holder[1]" />
			</div>
			<div class="date">
				<xsl:apply-templates mode="article.titlepage.recto.mode"
					select="articleinfo/date" />
			</div>
		</div>

		<div class="content">
			<xsl:apply-templates select="section" />
			<div class="notices">
				<h3>Legal Notices</h3>
				<xsl:apply-templates select="articleinfo/legalnotice/*" />
			</div>
		</div>
	</xsl:template>
	<xsl:template name="graphical.admonition">
		<xsl:variable name="admon.type">
			<xsl:choose>
				<xsl:when test="local-name(.)='note'">Note</xsl:when>
				<xsl:when test="local-name(.)='warning'">
					Warning
				</xsl:when>
				<xsl:when test="local-name(.)='caution'">
					Caution
				</xsl:when>
				<xsl:when test="local-name(.)='tip'">Tip</xsl:when>
				<xsl:when test="local-name(.)='important'">
					Important
				</xsl:when>
				<xsl:otherwise>Note</xsl:otherwise>
			</xsl:choose>
		</xsl:variable>
		<xsl:variable name="alt">
			<xsl:call-template name="gentext">
				<xsl:with-param name="key" select="$admon.type" />
			</xsl:call-template>
		</xsl:variable>
		<div class="note">
			<xsl:if test="$admon.style != ''">
				<xsl:attribute name="style">
					<xsl:value-of select="$admon.style" />
				</xsl:attribute>
			</xsl:if>
			<table class="note-table">
				<tr>
					<td>
						<img alt="[{$alt}]">
							<xsl:attribute name="src">
								<xsl:call-template name="admon.graphic" />
							</xsl:attribute>
						</img>
					</td>
					<td>
						<xsl:apply-templates />
					</td>
				</tr>
			</table>
		</div>
	</xsl:template>

</xsl:stylesheet>