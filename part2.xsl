<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
    <xsl:output method="html" indent="yes"/>

    <xsl:template match="/">
        <html>
            <head>
                <title>Hotel Bookings</title>
                <link rel="stylesheet" type="text/css" href="part2.css"/>
            </head>
            <body>
                <h2>Hotel Booking Details</h2>
                <table>
                    <tr>
                        <th>First Name</th>
                        <th>Last Name</th>
                        <th>Room Number</th>
                        <th>Room Type</th>
                        <th>Special Requirements</th>
                        <th>Duration (Days)</th>
                    </tr>
                    <xsl:for-each select="hotelBookings/booking">
                        <tr>
                            <td><xsl:value-of select="firstName"/></td>
                            <td><xsl:value-of select="lastName"/></td>
                            <td><xsl:value-of select="roomDetails/roomNumber"/></td>
                            <td><xsl:value-of select="roomDetails/roomType"/></td>
                            <td><xsl:value-of select="roomDetails/specialRequirements"/></td>
                            <td><xsl:value-of select="duration"/></td>
                        </tr>
                    </xsl:for-each>
                </table>
            </body>
        </html>
    </xsl:template>
</xsl:stylesheet>
