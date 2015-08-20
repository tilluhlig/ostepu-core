<?php
/**
 * @file index.php
 * Generates a page that shows an overview of a user's courses.
 *
 * @author Felix Schmidt
 * @author Florian Lücke
 * @author Ralf Busch
 */

include_once dirname(__FILE__) . '/include/Boilerplate.php';
include_once dirname(__FILE__) . '/../Assistants/LArraySorter.php';

$langTemplate='index_Controller';Language::loadLanguageFile('de', $langTemplate, 'json', dirname(__FILE__).'/');

// load user data from the database
$databaseURI = $databaseURI . "/user/user/{$uid}";
$user = http_get($databaseURI, false);
$user = json_decode($user, true);

if (is_null($user)) {
    $user = array();
}

$menu = MakeNavigationElement($user,
                              PRIVILEGE_LEVEL::STUDENT,true,true
                              );

// construct a new header
$h = Template::WithTemplateFile('include/Header/Header.template.html');
$h->bind($user);
$h->bind(array("name" => Language::Get('main','title', $langTemplate),
               "hideBackLink" => "true",
               "notificationElements" => $notifications,
               "navigationElement" => $menu));

// sort courses by semester
if (isset($user['courses']) && is_array($user['courses'])){
    foreach ($user['courses'] as &$course){
        $course['semesterInt'] = substr($course['course']['semester'],-4)*2;
        if (substr($course['course']['semester'],0,2)=='WS')
            $course['semesterInt']--;
        
    }
    $user['courses'] = LArraySorter::orderBy($user['courses'], 'semesterInt', SORT_DESC, 'name', SORT_ASC);
}
               
$pageData = array('uid' => isset($user['id']) ? $user['id'] : null,
                  'courses' => isset($user['courses']) ? $user['courses'] : null,
                  'sites' => PRIVILEGE_LEVEL::$SITES,
                  'statusName' => PRIVILEGE_LEVEL::$NAMES);

// construct a login element
$courseSelect = Template::WithTemplateFile('include/CourseSelect/CourseSelect.template.html');
$courseSelect->bind($pageData);

// wrap all the elements in some HTML and show them on the page
$w = new HTMLWrapper($h, $courseSelect);
$w->set_config_file('include/configs/config_default.json');
$w->show();

