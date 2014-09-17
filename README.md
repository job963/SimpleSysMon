# Piwik Simple System Monitor Plugin

## Description

This plugin shows how much load your webserver does have, where Piwik (and maybe your main website) is running. Additionally the free or the used memory will be displayed.

The display will be refreshed automatically as often as you like. This can be setup by yourself in the plugin settings.


## Screenshots
**Widget-Preview**  
![](https://github.com/job963/SimpleSysMon/raw/master/screenshots/widgetLiveSysLoad-EN.png)

**Plugin Settings**  
![](https://github.com/job963/SimpleSysMon/raw/master/screenshots/settingLiveSysLoad-EN.png)


## Installation

Install it via [Piwik Marketplace](http://plugins.piwik.org/).

OR 

Install manually:

1. Clone the plugin into the plugins directory of your Piwik installation.

   ```
   cd plugins/
   git clone https://github.com/job963/SimpleSysMon.git SimpleSysMon
   ```

2. Login as superuser into your Piwik installation and activate the plugin under Settings -> Plugins

3. Goto Settings -> Plugin Settings an setup the values for the widget.

4. You will now find the widget under the Simple System Monitor -> Live System Load.

## Changelog

* **0.1.0 Initial release**
  * Display of CPU load
  * Display of free or used memory    

* **0.1.0 Initial release**
  * Corrections and error trapping for shared websites where the some values aren't accessible 


## FAQ

**How is the CPU load calculated?**  
For the CPU load the PHP function sys_getloadavg() is used and divided by the number of cores.

**Why is there a difference between free and used memory?**  
There are three "memory parts" under Linux:   

* Used memory
* Cache
* Free memory

The sum of these three parts will be equal to the total memory. But only `used memory` and `free memory` are available as options.

**Does the plugin work on a shared webspace?**  
In the most cases a shared webspace doesn't have access to system information. Therefore in these cases, the plugin cannot be used.

**Does the plugin work on Windows system?**  
If the server where Piwik is running is using Windows as OS, the plugin doesn't work yet.  
If just your browser is running under Windows (and the server runs under Linux) this plugin works well.

## License

GPL v3 or later

## Support

Please report any issues directly in [Github](https://github.com/job963/SimpleSysMon/issues). 

## Contribute 

If you are interested in contributing to this plugin, feel free to send pull requests!

