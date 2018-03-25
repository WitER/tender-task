<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Class Migrate
 *
 * @property CI_Migration $migration
 */
class Migrate extends CI_Controller
{

    public function index()
    {
        if (!is_cli()) {
            show_404();
        }
        $this->load->library('migration');

        if ($this->migration->current() === FALSE)
        {
            show_error($this->migration->error_string());
        }

        $currentVersion = $this->migration->current();
        $latestVersion = (int)$this->migration->latest();

        if ($currentVersion == $latestVersion) {
            echo 'Nothing to update'. PHP_EOL;
            return;
        }

        echo sprintf('Current version: %s'.PHP_EOL, $currentVersion);
        echo sprintf('Latest version: %s'.PHP_EOL, $latestVersion);

        for ($i = $currentVersion + 1; $i < $latestVersion; $i++) {
            $version = str_pad($i, 3, '0', STR_PAD_LEFT);
            echo sprintf("\tApply vertsion: %s".PHP_EOL, $version);
            if ($this->migration->version($version) == FALSE) {
                show_error($this->migration->error_string());
                break;
            }
        }
    }

}