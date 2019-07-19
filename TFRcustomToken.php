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
		}

		/**
		 * This event is fired by the administration panel to gather extra settings
		 * available for a survey.
		 * The plugin should return setting meta data.
		 * @param PluginEvent $event
		 */
		public function generateCustomToken()
		{
			$event = $this->getEvent();
			$iSurveyID=$event->get('surveyId');
			if (!$this->get('enabled', 'Survey', $iSurveyID, false)) {
				echo "<pre>pkugin not active for iSurveyID {$iSurveyID} ".$this->get('enabled', 'Survey', $iSurveyID, false)."</pre>";
				return;
			}
			$iTokenLength = $event->get('iTokenLength');
			$event->set('generatedToken', randomChars($iTokenLength, '123456789'));
			echo "<pre>iSurveyID = {$iSurveyID} generatedToken ".$event->get('generatedToken')." ".$this->get('enabled', 'Survey', $iSurveyID, false)."</pre>\n";
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
					'enabled' => array(
						'type' => 'select',
						'options'=>array(0=>'No',
							1=>'Yes'),
						'default' => 0,
						'label' => 'Use plugin for this survey',
						'current' => $this->get('enabled', 'Survey', $event->get('survey'))
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
}
