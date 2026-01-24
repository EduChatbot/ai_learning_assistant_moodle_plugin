<?php
require(__DIR__.'/../../config.php');
require_once(__DIR__.'/lib.php');

$id = required_param('id', PARAM_INT);   // Course ID

$course = $DB->get_record('course', array('id' => $id), '*', MUST_EXIST);

require_course_login($course);

$PAGE->set_url('/mod/chatbot/index.php', array('id' => $id));
$PAGE->set_title(format_string($course->fullname));
$PAGE->set_heading(format_string($course->fullname));
$PAGE->set_context(context_course::instance($course->id));

echo $OUTPUT->header();

$modulenameplural = get_string('modulenameplural', 'chatbot');
echo $OUTPUT->heading($modulenameplural);

if (!$chatbots = get_all_instances_in_course('chatbot', $course)) {
    notice(get_string('nochatbots', 'chatbot'), new moodle_url('/course/view.php', array('id' => $course->id)));
}

$table = new html_table();
$table->attributes['class'] = 'generaltable mod_index';

$table->head = array(get_string('name'), get_string('description'));
$table->align = array('left', 'left');

foreach ($chatbots as $chatbot) {
    $link = html_writer::link(
        new moodle_url('/mod/chatbot/view.php', array('id' => $chatbot->coursemodule)),
        format_string($chatbot->name)
    );
    $description = format_string($chatbot->intro);
    
    $table->data[] = array($link, $description);
}

echo html_writer::table($table);

echo $OUTPUT->footer();
