<?php
defined('MOODLE_INTERNAL') || die();

/**
 * List of features supported in chatbot module
 * @param string $feature FEATURE_xx constant for requested feature
 * @return mixed True if module supports feature, false if not, null if doesn't know
 */
function chatbot_supports($feature) {
    switch($feature) {
        case FEATURE_MOD_ARCHETYPE:           return MOD_ARCHETYPE_RESOURCE;
        case FEATURE_GROUPS:                  return false;
        case FEATURE_GROUPINGS:               return false;
        case FEATURE_MOD_INTRO:               return true;
        case FEATURE_COMPLETION_TRACKS_VIEWS: return true;
        case FEATURE_GRADE_HAS_GRADE:         return false;
        case FEATURE_GRADE_OUTCOMES:          return false;
        case FEATURE_BACKUP_MOODLE2:          return true;
        case FEATURE_SHOW_DESCRIPTION:        return true;

        default: return null;
    }
}

/**
 * Add chatbot instance
 * @param object $data
 * @return int new chatbot instance id
 */
function chatbot_add_instance($data) {
    global $DB;
    
    $data->timemodified = time();
    $data->timecreated = time();
    
    return $DB->insert_record('chatbot', $data);
}

/**
 * Update chatbot instance
 * @param object $data
 * @return bool true
 */
function chatbot_update_instance($data) {
    global $DB;
    
    $data->timemodified = time();
    $data->id = $data->instance;
    
    return $DB->update_record('chatbot', $data);
}

/**
 * Delete chatbot instance
 * @param int $id
 * @return bool true
 */
function chatbot_delete_instance($id) {
    global $DB;
    
    if (!$chatbot = $DB->get_record('chatbot', array('id' => $id))) {
        return false;
    }
    
    $DB->delete_records('chatbot', array('id' => $chatbot->id));
    
    return true;
}
