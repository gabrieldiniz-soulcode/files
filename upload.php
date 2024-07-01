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
 * Upload page for the Files plugin
 *
 * @package     local_files
 * @copyright   2024 Gabriel Diniz gabrieldiniz.contato@gmail.com
 * @license     https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once(__DIR__ . '/../../config.php');
require_once($CFG->libdir . '/filelib.php');
require_once($CFG->libdir . '/gradelib.php');

$context = context_system::instance();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_FILES['file'])) {
    global $DB;

    $fileinfo = $_FILES['file'];
    $userid = $_POST['user_id'];
    $component = $_POST['component'];
    $itemid = $_POST['item_id'];
    $filearea = $_POST['file_area'];

    if (!$userid || !$component || !$itemid || !$filearea) {
        die("Parâmetros inválidos.");
    }

    $user = $DB->get_record('user', ['id' => $userid]);

    $author = $user->firstname . ' ' . $user->lastname;

    $fs = get_file_storage();
    $filerecord = [
        'contextid' => $context->id,
        'component' => $component,
        'filearea' => $filearea,
        'itemid' => $itemid,
        'filepath' => '/',
        'filename' => $fileinfo['name'],
        'userid' => $userid,
        'filesize' => $fileinfo['size'],
        'mimetype' => $fileinfo['type'],
        'status' => 0,
        'author' => $author,
    ];

    $file = $fs->create_file_from_pathname($filerecord, $fileinfo['tmp_name']);

    if ($file) {
        echo "Arquivo enviado com sucesso!";
    } else {
        echo "Falha ao enviar o arquivo.";
    }
} else {
    echo "Nenhum arquivo enviado ou método de requisição inválido.";
}
