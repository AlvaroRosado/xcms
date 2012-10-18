<?xml version="1.0" encoding="utf-8" ?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">

<!--
	dexT to XSLT translation: from dext:ifexists to xsl:if
-->

<xsl:template name="ifexists">
	<xsl:variable name="bool">
		<xsl:call-template name="boolean_expressions">	
			<xsl:with-param name="exp" select="@expr"/>
		</xsl:call-template>
	</xsl:variable>

	<xsl:choose>
		<xsl:when test="local-name(./following-sibling::*[position()=1])='else'">
			<xsl:text disable-output-escaping="yes">&lt;xsl:choose&gt;</xsl:text>

			<xsl:element name="xsl:when" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
				<xsl:attribute name="test"><xsl:value-of select="$bool"/></xsl:attribute>
				<xsl:apply-templates/>
			</xsl:element>
		</xsl:when>
		<xsl:otherwise>
			<xsl:element name="xsl:if" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
				<xsl:attribute name="test"><xsl:value-of select="$bool"/></xsl:attribute>
				<xsl:apply-templates/>
			</xsl:element>
		</xsl:otherwise>
	</xsl:choose>

</xsl:template>

</xsl:stylesheet>
