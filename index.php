<?php
// This file is part of Moodle - https://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <https://www.gnu.org/licenses/>.

/**
 * Index page for the Files plugin
 *
 * @package     local_files
 * @copyright   2024 Gabriel Diniz gabrieldiniz.contato@gmail.com
 * @license     https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once(__DIR__ . '/../../config.php');
global $DB, $CFG;

$context = context_system::instance();
$PAGE->set_context($context);
$PAGE->set_url('/local/files/index.php');

$fileid = required_param('file_id', PARAM_INT);

$filerecord = $DB->get_record('files', ['id' => $fileid], '*', MUST_EXIST);

if (!$filerecord) {
    throw new moodle_exception('filenotfound', 'error', '', null, 'File not found');
}

$filestorage = get_file_storage();
$file = $filestorage->get_file_by_id($fileid);

if ($file) {
    header('Content-Type: ' . $file->get_mimetype());
    header('Content-Length: ' . $file->get_filesize());
    header('Content-Disposition: inline; filename="' . $file->get_filename() . '"');

    $content = $file->get_content_file_handle();
    fpassthru($content);
    exit;
} else {
    throw new moodle_exception('filenotfound', 'error', '', null, 'File not found');
}
