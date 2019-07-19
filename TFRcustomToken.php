<?php
class TFRcustomToken extends PluginBase {

		protected $storage = 'DbStorage';
		static protected $name = 'TFRcustomToken';
		static protected $description = 'TFRcustomToken plugin';

		public function init()
		{
			/**
			 * Here you should handle subscribing to the events your plugin will handle
			 */
			$this->subscribe('customToken', 'generateCustomToken');

			// Provides survey specific settings.
			$this->subscribe('beforeSurveySettings');

			// Saves survey specific settings.
			$this->subscribe('newSurveySettings');

			// Clean up on deactivate
			$this->subscribe('beforeDeactivate');
		}

		/**
		 * The custom generate function
		 */
		public function generateCustomToken()
		{
			$event = $this->getEvent();
			$iSurveyID=$event->get('surveyId');
			if ($this->get('TFRcustomToken', 'Survey', $iSurveyID) == 0) {
				//echo "<pre>pkugin not active for iSurveyID {$iSurveyID} ".$this->get('TFRcustomToken', 'Survey', $iSurveyID, 0)."</pre>";
				return;
			}
			$iTokenLength = $event->get('iTokenLength');
			if ($this->get('TFRcustomToken', 'Survey', $iSurveyID) == 1) {
				$event->set('generatedToken', randomChars($iTokenLength, '123456789'));
			}
			if ($this->get('TFRcustomToken', 'Survey', $iSurveyID) == 2) {
				$token = str_replace(
					array('~','_','0','o','O','1','l','I'),
					array('a','z','7','p','P','8','k','K'), Yii::app()->securityManager->generateRandomString($iTokenLength));
				$event->set('generatedToken', $token);
			}
			//echo "<pre>iSurveyID = {$iSurveyID} generatedToken ".$event->get('generatedToken')." ".$this->get('TFRcustomToken', 'Survey', $iSurveyID)."</pre>\n";
		}

		/**
		* This event is fired by the administration panel to gather extra settings
		* available for a survey. Example URL in LS 3.17:
		* /index.php/admin/survey/sa/rendersidemenulink/subaction/plugins/surveyid/46159
		*/
		public function beforeSurveySettings()
		{
			$pluginsettings = $this->getPluginSettings(true);

			$event = $this->getEvent();
			$iSurveyID = isset($_SESSION['LEMsid']) ? intval($_SESSION['LEMsid']) : NULL;;
			$event->set("surveysettings.{$iSurveyID}", array(
				'name' => get_class($this),
				'settings' => array(
					'TFRcustomToken' => array(
						'type' => 'select',
						'options'=>array(
							0=>'Not active for this survey',
							1=>'Numeric tokens',
							2=>'Omit ambiguous characters'
							),
						'default' => 0,
						'label' => 'Use plugin for this survey',
						'current' => $this->get('TFRcustomToken', 'Survey', $event->get('survey'))
					)
				)
			));
		}

		/**
		 * Save the settings
		 */
		public function newSurveySettings()
		{
			$event = $this->getEvent();
			foreach ($event->get('settings') as $name => $value)
			{
					$this->set($name, $value, 'Survey', $event->get('survey'));
			}
		}
		
		/**
		 * Clean up the plugin settings table
		 */
		public function beforeDeactivate()
		{
			$sDBPrefix = Yii::app()->db->tablePrefix;
			$sql = "DELETE FROM {$sDBPrefix}plugin_settings WHERE `key` LIKE :key";
			Yii::app()->db->createCommand($sql)->execute(array(':key' => "TFRcustomToken"));
		}

}
