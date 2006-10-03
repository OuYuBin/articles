<?xml version="1.0" encoding="UTF-8"?>

<xsl:stylesheet xmlns:xsl="http://www.w3.org/1999/XSL/Transform" version="1.0">
	<xsl:template match="article">
		<html>
		<head>
			<title><xsl:value-of select="title"/></title>
			<link href="../default_style.css" rel="stylesheet"/>
			<link rel="stylesheet" type="text/css" href="article.css" />
		</head>
		<body>
			<div align="right">© Copyright <xsl:value-of select="copyright/@year"/> 
			<xsl:value-of select="copyright" /></div>
			<h1><xsl:value-of select="title"/></h1>
			<xsl:apply-templates select="section"/>
		</body>
		</html>
	</xsl:template>
	
	<xsl:template match="section">
		<xsl:if test="@title">
			<h2><xsl:value-of select="@title"/></h2>
		</xsl:if>
		<xsl:apply-templates />
	</xsl:template>
	
	<xsl:template match="itemized-list">
		<ul>
		<xsl:for-each select="item">
			<li><xsl:value-of select="@title"/></li>
		</xsl:for-each>
		</ul>
		<xsl:value-of select="segue"/>
		<xsl:apply-templates />
	</xsl:template>
	
	<xsl:template match="item">
		<h3><xsl:value-of select="@title"/></h3>
		<xsl:apply-templates/>
	</xsl:template>
	
	<xsl:template match="para">
		<p><xsl:apply-templates /></p>
	</xsl:template>
	
	<xsl:template match="xref-listing">
		<xsl:variable name="target_id"><xsl:value-of select="@id"/></xsl:variable>
		<a href="#{@id}">Listing 
		<xsl:for-each select="./xref-listing">
			<xsl:if test="@id = $target_id">
				<xsl:value-of select="position()"/>
			</xsl:if>
		</xsl:for-each>
		</a>
	</xsl:template>
		
	<xsl:template match="xref-figure">
		<a href="#{@id}">Figure XX</a>
	</xsl:template>
	
	<xsl:template match="xref-link">
		<a href="#{@href}">Listing XX</a>
	</xsl:template>
		
	<xsl:template match="a">
		<a href="#{@href}"><xsl:apply-templates/></a>
	</xsl:template>
	
	<xsl:template match="listing">
		<div class="listing">
		<h3><a name="{@id}"/>Listing XX: <xsl:value-of select="caption"/></h3>
		<pre><xsl:value-of select="code"/></pre>
		</div>
	</xsl:template>
</xsl:stylesheet>