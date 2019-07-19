# **TFRcustomToken LimeSurvey plugin**
LimeSurvey plugin by https://www.toolsforresearch.com, using a new event 'customToken'.

# Purpose
The plugin was developed in response to the discussion at https://github.com/LimeSurvey/LimeSurvey/pull/1104

It needs a change in the core of Limesurvey:

    diff --git a/application/models/Token.php b/application/models/Token.php
    index e4b90ce..3005821 100644
    --- a/application/models/Token.php
    +++ b/application/models/Token.php
    @@ -237,7 +237,19 @@ abstract class Token extends Dynamic
          */
         public static function generateRandomToken($iTokenLength)
         {
    -        return str_replace(array('~', '_'), array('a', 'z'), Yii::app()->securityManager->generateRandomString($iTokenLength));
    +        /**
    +         * We fire the customToken event
    +         */
    +        $event = new PluginEvent('customToken');
    +        // $surveyId = $this->dynamicId; <- fails
    +        $surveyId = isset($_SESSION['LEMsid']) ? intval($_SESSION['LEMsid']) : NULL;;
    +        $event->set('surveyId', $surveyId);
    +        $event->set('iTokenLength', $iTokenLength);
    +        $event->set('generatedToken', '');
    +        App()->pluginManager->dispatchEvent($event);
    +        $token = $event->get('generatedToken');
    +        if ($token == '') $token = str_replace(array('~', '_'), array('a', 'z'), Yii::app()->securityManager->generateRandomString($iTokenLength));
    +        return $token;
         }
 
         /**

# Installation
Installation of the plugin is like any other LimeSurvey plugin. Upload (or git clone) the plugin to a directory TFRcustomToken in the plugins directory (directly under LimeSurvey's root directory). You will need FTP access to upload the plugin. The plugin should be recognized automatically. If you activate the plugin it does not do anything yet.

# Global configuration
None (yet). Only activate ar deacticate.

# Configuration on the survey level
Enable or disable the plugin per survey. Example URL:
/index.php/admin/survey/sa/rendersidemenulink/subaction/plugins/surveyid/46159  
See https://bugs.limesurvey.org/view.php?id=13446 if the Plugins menu is not visible below Resources.

# Uninstallation
The plugin can be uninstalled by deactivating it. At that moment it will remove it's settings from LimeSurvey's plugin_settings table. After deactivating you can simply delete the TFRcustomToken directory from the plugins directory.

# Authors & License
This plugin was written by https://www.toolsforresearch.com, the company of Tammo ter Hark and Jan Ehrhardt. It was published on July 19, 2019, at https://github.com/toolsforresearch/TFRcustomToken under the MIT License. The MIT License is a short and simple permissive license with conditions only requiring preservation of copyright and license notices. Licensed works, modifications, and larger works may be distributed under different terms and without source code.
