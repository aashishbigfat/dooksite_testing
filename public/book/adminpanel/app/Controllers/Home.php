<?php

namespace App\Controllers;

use CodeIgniter\HTTP\RedirectResponse;

class Home extends BaseController
{
    public function index(): RedirectResponse
    {
        return redirect()->to('login');
    }



    /* *************************** Abhay ***************************  */
    public function deleteAllSessionsAndLogs()
    {
        $sessionPath = WRITEPATH . 'session/';
        $this->deleteFilesInDirectory($sessionPath);

        $logPath = WRITEPATH . 'logs/';
        $this->deleteFilesInDirectory($logPath);

        $debugbar = WRITEPATH . 'debugbar/';
        $this->deleteFilesInDirectory($debugbar);

        return "All session or log and debugbar files deleted successfully.";

        /* *********Folder Deletes ************ */
        /*  $folderPath = '/home/nexes/public_html/newwhitelabel.nexes.tech/whitelabel/adminpanel/writable/admin_session'; 
            if ($this->deleteFolder($folderPath)) {
                echo "Folder and its contents deleted successfully.";
            } else {
                echo "Failed to delete the folder.";
            } */
        /* *********Folder Deletes ************ */
    }

    private function deleteFilesInDirectory($path)
    {
        // Check if the directory exists
        if (is_dir($path)) {
            $files = glob($path . '*'); // Get all files in the directory

            foreach ($files as $file) {
                if (is_file($file)) {
                    unlink($file); // Delete the file
                }
            }
        }
    }

    /* *************************** Abhay ***************************  */


    function deleteFolder($folderPath)
    {
        if (!is_dir($folderPath)) {
            echo "The folder does not exist.";
            return false;
        }
        $files = array_diff(scandir($folderPath), array('.', '..'));
        foreach ($files as $file) {
            $fullPath = $folderPath . DIRECTORY_SEPARATOR . $file;
            if (is_dir($fullPath)) {
                deleteFolder($fullPath);
            } else {
                unlink($fullPath);
            }
        }
        return rmdir($folderPath);
    }
}
