<?php

/**************************************************************************************************
**	file:	heater.php
**
**		This file is the default page that is loaded by index.php.  It prints out all of the
**	announcements that are in the database and allows new ones to be added.  
**
***************************************************************************************************
	**
	**	author:	JD Bottorf
	**	date:	11/19/01
	***********************************************************************************************
			**
			**	Copyright (C) 2001  <JD Bottorf>
			**
			**		This program is free software; you can redistribute it and/or
			**		modify it under the terms of the GNU General Public
			**		License as published by the Free Software Foundation; either
			**		version 2.1 of the License, or (at your option) any later version.
			**
			**		This program is distributed in the hope that it will be useful,
			**		but WITHOUT ANY WARRANTY; without even the implied warranty of
			**		MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU
			**		General Public License for more details.
			**
			**		You should have received a copy of the GNU General Public
			**		License along with This program; if not, write to the Free Software
			**		Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
			**
			***************************************************************************************/

require_once "common/common.php";

startTable($lang_CloudControl, "center");

?>

<script type="text/javascript" src="//cdn.jsdelivr.net/particle-api-js/5/particle.min.js">

 	
var Particle = require('particle-api-js');
var particle = new Particle();

particle.login({username: 'phorrack@casaria.net', password: 'adv57130'});

</script>
<TD class=back>Qantas Club Lounge Rooftop water heater
<div class="librato-display-media"
     data-chart_id="1700901"
     data-duration="3600"
     data-width="600"
     data-height="277"
     data-source="*">
</div>
<script type="text/javascript"
        src="https://sdk.librato.com/librato-sdk-v1.0.0-min.js"
        charset="utf-8"
        data-librato_email="phorrack@gmail.com"
        data-librato_token="cd2ca555165433ec973855807508288322fde76427734eded4d2529d408e3dee">
</script>

<iframe src="http://an.casaria.net:3000/dashboard-solo/db/new-dashboard?panelId=2&theme=light" width="650" height="260" frameborder="0"></iframe><iframe src="http://play.grafana.org/dashboard-solo/db/annotations?panelId=1&from=1459666834304&to=1459677634304&theme=light" width="600" height="200" frameborder="0"></iframe>
<?php
endTable();

?>
