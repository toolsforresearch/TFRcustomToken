# **TFRcustomToken LimeSurvey plugin**
LimeSurvey plugin by [Tools for Research](https://www.toolsforresearch.com), using a new event 'afterGenerateToken'.

# Purpose
The plugin was developed in response to the discussion at [github.com/LimeSurvey/LimeSurvey/pull/1104](https://github.com/LimeSurvey/LimeSurvey/pull/1104)  
It needs a change in the core of Limesurvey: see [New Feature: Add an event to generate custom tokens](https://github.com/LimeSurvey/LimeSurvey/pull/1307) for details.

# Installation
Installation of the plugin is like any other LimeSurvey plugin. Upload (or git clone) the plugin to a directory TFRcustomToken in the plugins directory (directly under LimeSurvey's root directory). You will need FTP or SSH access to upload the plugin. The plugin should be recognized automatically.

# Global configuration
None (yet). Only activate or deactivate. If you activate the plugin it does not do anything yet (without settings on the survey level).

# Configuration on the survey level
You can select one out of four options on the survey level:  
* No custom function  
* Numeric tokens  
* Without ambiguous characters  
* CAPITALS ONLY  

Example URL if the 'Simple plugins' menu is not visible:  
/index.php/admin/survey/sa/rendersidemenulink/subaction/plugins/surveyid/46159  
See https://bugs.limesurvey.org/view.php?id=14604#c52946 for a question in relation to the missing menu

# Uninstallation
The plugin can be uninstalled by deactivating it. At that moment it will remove it's settings from LimeSurvey's plugin_settings table. After deactivating you can simply delete the TFRcustomToken directory.

# Authors & License
This plugin was written by [Tools for Research](https://www.toolsforresearch.com), the company of Tammo ter Hark and Jan Ehrhardt.  
It was published July 19, 2019, on [github.com/toolsforresearch/TFRcustomToken](https://github.com/toolsforresearch/TFRcustomToken) under the MIT License. The MIT License is a short and simple permissive license with conditions only requiring preservation of copyright and license notices. Licensed works, modifications, and larger works may be distributed under different terms and without source code.
