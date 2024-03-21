<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
  
  <!-- Link to external CSS file -->
  <xsl:output method="html" version="1.0" encoding="UTF-8" doctype-public="-//W3C//DTD XHTML 1.0 Transitional//EN" doctype-system="http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd"/>
  <xsl:template match="/">
    <html>
      <head>
        <link rel="stylesheet" type="text/css" href="./style.css"/>
      </head>
      <body>
        <xsl:apply-templates select="employees"/>
      </body>
    </html>
  </xsl:template>

  <!-- Template for employees -->
  <xsl:template match="employees">
    <table>
      <thead>
        <tr>
          <th>Name</th>
          <th>Email</th>
          <th>Phones</th>
          <th>Addresses</th>
        </tr>
      </thead>
      <tbody>
        <xsl:apply-templates select="employee"/>
      </tbody>
    </table>
  </xsl:template>

  <!-- Template for each employee -->
  <xsl:template match="employee">
    <tr>
      <td><xsl:value-of select="name"/></td>
      <td><xsl:value-of select="email"/></td>
      <td>
        <xsl:apply-templates select="phones/phone"/>
      </td>
      <td>
        <xsl:apply-templates select="addresses/address"/>
      </td>
    </tr>
  </xsl:template>

  <!-- Template for phones -->
  <xsl:template match="phone">
    <xsl:value-of select="."/><br/>
  </xsl:template>

  <!-- Template for addresses -->
  <xsl:template match="address">
    <xsl:value-of select="concat(street, ', ', building_number, ', ', region, ', ', city, ', ', country)"/><br/>
  </xsl:template>

</xsl:stylesheet>
