<?php
require(__DIR__.'/../../config.php');
require_once(__DIR__.'/lib.php');
require_once($CFG->dirroot.'/lib/externallib.php');

global $USER, $DB;

$id = optional_param('id', 0, PARAM_INT);

if ($id) {
    $cm = get_coursemodule_from_id('chatbot', $id, 0, false, MUST_EXIST);
    $course = $DB->get_record('course', array('id' => $cm->course), '*', MUST_EXIST);
    $chatbot = $DB->get_record('chatbot', array('id' => $cm->instance), '*', MUST_EXIST);
} else {
    print_error('missingidandcmid', 'mod_chatbot');
}

require_login($course, true, $cm);

$context = context_module::instance($cm->id);

$userid = $USER->id;
$courseid = $course->id;

$service = $DB->get_record('external_services', array('shortname' => 'moodle_mobile_app'), '*');
if ($service) {
    $token_record = $DB->get_record('external_tokens', array(
        'userid' => $USER->id,
        'externalserviceid' => $service->id,
        'tokentype' => EXTERNAL_TOKEN_PERMANENT
    ));
    
    if (!$token_record) {
        $token_record = external_generate_token(
            EXTERNAL_TOKEN_PERMANENT,
            $service->id,
            $USER->id,
            context_system::instance(),
            time() + 365 * 24 * 60 * 60
        );
    }
    $moodle_token = $token_record->token;
    error_log("CHATBOT DEBUG: Using Moodle token: " . substr($moodle_token, 0, 20) . "...");
} else {
    $moodle_token = sesskey() . '_' . $USER->id;
    error_log("CHATBOT DEBUG: Using sesskey fallback (service not found): " . substr($moodle_token, 0, 20) . "...");
}

$completion = new completion_info($course);
$completion->set_module_viewed($cm);

$PAGE->set_url('/mod/chatbot/view.php', array('id' => $cm->id));
$PAGE->set_title(format_string($chatbot->name));
$PAGE->set_heading(format_string($course->fullname));
$PAGE->set_context($context);

echo $OUTPUT->header();

echo $OUTPUT->heading(format_string($chatbot->name));

if ($chatbot->intro) {
    echo $OUTPUT->box(format_module_intro('chatbot', $chatbot, $cm->id), 'generalbox', 'intro');
}

$iframe_url = 'https://moodle-edu-chatbot.vercel.app/';
$iframe_params = array(
    'token' => $moodle_token,
    'courseid' => $courseid,
    'coursename' => $course->fullname
);
$iframe_src = new moodle_url($iframe_url, $iframe_params);

echo html_writer::start_div('chatbot-container', array('style' => 'position: relative;', 'id' => 'chatbot-wrapper'));
echo html_writer::tag('button', '⛶ ' . get_string('fullscreen', 'chatbot'), array(
    'id' => 'fullscreen-btn',
    'class' => 'btn btn-secondary btn-sm',
    'title' => get_string('fullscreentitle', 'chatbot'),
    'style' => 'position: absolute; top: 10px; right: 10px; z-index: 1000;'
));
echo html_writer::tag('iframe', '', array(
    'src' => $iframe_src->out(false),
    'width' => '100%',
    'height' => '700px',
    'style' => 'border: 1px solid #dee2e6; border-radius: 0.25rem;',
    'title' => get_string('pluginname', 'chatbot'),
    'class' => 'chatbot-iframe',
    'id' => 'chatbot-iframe',
    'allowfullscreen' => 'true'
));
echo html_writer::end_div();

echo html_writer::tag('script', "
var fullscreenText = '" . get_string('fullscreen', 'chatbot') . "';
var exitFullscreenText = '" . get_string('exitfullscreen', 'chatbot') . "';

document.getElementById('fullscreen-btn').addEventListener('click', function() {
    var wrapper = document.getElementById('chatbot-wrapper');
    var iframe = document.getElementById('chatbot-iframe');
    
    if (!document.fullscreenElement) {
        if (wrapper.requestFullscreen) {
            wrapper.requestFullscreen();
        } else if (wrapper.webkitRequestFullscreen) {
            wrapper.webkitRequestFullscreen();
        } else if (wrapper.msRequestFullscreen) {
            wrapper.msRequestFullscreen();
        }
        iframe.style.height = '100vh';
        this.textContent = '✕ ' + exitFullscreenText;
    } else {
        if (document.exitFullscreen) {
            document.exitFullscreen();
        }
        iframe.style.height = '700px';
        this.textContent = '💬 ' + fullscreenText;
    }
});

document.addEventListener('fullscreenchange', function() {
    var iframe = document.getElementById('chatbot-iframe');
    var btn = document.getElementById('fullscreen-btn');
    if (!document.fullscreenElement) {
        iframe.style.height = '700px';
        btn.textContent = '💬 ' + fullscreenText;
    }
});
", array('type' => 'text/javascript'));

echo $OUTPUT->footer();
