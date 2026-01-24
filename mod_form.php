<?php
defined('MOODLE_INTERNAL') || die();

require_once($CFG->dirroot.'/course/moodleform_mod.php');

class mod_chatbot_mod_form extends moodleform_mod {

    function definition() {
        global $CFG;
        $mform = $this->_form;

        // Name
        $mform->addElement('text', 'name', get_string('chatbotname', 'chatbot'), array('size'=>'64'));
        if (!empty($CFG->formatstringstriptags)) {
            $mform->setType('name', PARAM_TEXT);
        } else {
            $mform->setType('name', PARAM_CLEANHTML);
        }
        $mform->addRule('name', null, 'required', null, 'client');
        $mform->addRule('name', get_string('maximumchars', '', 255), 'maxlength', 255, 'client');

        // Description
        $this->standard_intro_elements(get_string('chatbotdescription', 'chatbot'));

        // Standard elements
        $this->standard_coursemodule_elements();

        // Buttons
        $this->add_action_buttons();
    }
}
