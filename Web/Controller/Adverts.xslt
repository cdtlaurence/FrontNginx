<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">

  <xsl:template match="/">
    <html>
      <body>
        <h2>Adverts</h2>
        <table border="1">
          <tr bgcolor="#9acd32">
            <th style="text-align:left">Title</th>
            <th style="text-align:left">Description</th>
            <th style="text-align:left">InsertedStamp</th>
            <th style="text-align:left">UserId</th>
            <th style="text-align:left">TypeId</th>
            <th style="text-align:left">Location</th>
          </tr>
          <xsl:for-each select="Adverts/Advert">
            <tr>
              <td>
                <xsl:value-of select="Title"/>
              </td>
              <td>
                <xsl:value-of select="Description"/>
              </td>
              <td>
                <xsl:value-of select="InsertedStamp"/>
              </td>
              <td>
                <xsl:value-of select="UserId"/>
              </td>
              <td>
                <xsl:value-of select="TypeId"/>
              </td>
              <td>
                <xsl:value-of select="Location"/>
              </td>
            </tr>
          </xsl:for-each>
        </table>
      </body>
    </html>
  </xsl:template>
</xsl:stylesheet>