# ha-helper
PHP application framework to connect a JSON API to various command-line linux applications.<br/>

<b>WARNING: This is my first shared git, so there are bound to be mistakes in how I use it. YMMV</b><br/>

<h2>Background</h2>
This little framework accomplishes the simple task of API-zing some of the linux command-line utilities I use in my home automation systems (Home Assistant plus AppDaemon/HADashboard). For some of my controls, I found it simpler just to integrate into HADashboard rather than creating an HA entity, etc, etc. Using this framework, I can make a simple PHP (Apache) web GET call and have comman-line utilities perform the heavy lifting.<br/><br/>
I've used this framework to connect my HADashboard to TiVo, my Onkyo home theater receiver, and my IP2IR remote blaster (connector pending). It's designed to be extensible, so creating new devices is pretty simple (I think). My goal is to publish and update this framework, and to integrate other connectors designed by the community.<br/><br/>
The advantages of doing your HADashboard this way are:<br/><br/>
<ul>
  <li>Simplicity - HADashboard requires some custom coding (css/javascript) to create custom widgets -- OR you must use entities as defined in Home Assistant. With ha-helper, you can leverage existing linux command-line tools, attach them to this framework, then put them on your dashboard using the javascript widget. Simple.</li>
  <li>Fast - The javascript widget allows you to use the FETCH command to call an arbitrary URL. Because the FETCH happens on client side, there is no page refresh. The client-side FETCH calls the server-side API which, in turn, runs the server-side command to reach the target device.</li>
  <li>Well-integrated - Using a native javascript widget in HADashboard is much more developed and more interface-consistent than using an iframe widget.</li>
</ul><br/>
Now, it's not all rainbows and unicorns. The javascript widget doesn't allow for feedback like the other widgets, so buttons created with this widget are basically write-only. That is, there is no indication of current state built into the button. For example, a POWER button created using this framework is equivalent to a power button on a TV remote. The remote is completely unaware of current state and pushing the button simply sends the appropriate stateless command to the TV. Also, there are probably better (read: more elegant) ways to do this, but this is the method I chose primarily because I couldn't find a suitable way to integrate my TiVo controls into HA(Dashboard). Once I did it for the TiVo, the Onkyo was next and (thus) a framework was born.<br/><br/>
Additionally, I would consider this an internal-only type API. It is not built to be super-secure and will likely expose your system to root compromise through its use of user-supplied parameters for shell scripts. Some attempt has been made to sanitize the user input, but I'm sure it has tons of holes in it.<br/><br/>
<h2>Installation</h2>
Download the package, unzip, run the install script from the unzipped directory: ha-helper-install.sh<br/><br>
The shell script will create the required directories (connectors are installed to /var/lib/ha-helper/connectors) and will install ha-helper.php to the root of your apache directory (/var/www/html).<br/><br/>
This was developed and tested on Ubuntu 18.04. It <i>should</i> work in other distros, but default directories may be different.<br/><br/>
<h2>Dependencies</h2>
The php script itself does not have any dependencies other than PHP (Apache). The connectors, however, will often depend on other command-line utilities which may have dependenices themselves. For example, the Onkyo connector depends on the onkyo python script, <a href='https://github.com/miracle2k/onkyo-eiscp'>Onkyo eISCP Control</a>. I leave it to connector builder to document the connector dependencies as needed.
