<?php

namespace App\vendor\core\commands\comandsexecute;

use Core\SRC\Helper;
use Core\SRC\Model;

class Rollback extends Model
{
    use Helper;

    public function rollback($step)
    {
        $lastStep = max($this->from('migration')->select('step')->getAll(7));
        $step = $lastStep - $step;

        $files = $this->from('migration')
            ->select('name')
            ->where('step', '>', $step)
            ->getAll(\PDO::FETCH_COLUMN);

        if (!empty($files)) {
            foreach ($files as $file) {
                $this->pdo->query("delete from migration where step > $step");
                $migrate_class = "App\database\\" . substr($file, 0, -4);
                $migrate = new $migrate_class();
                $migrate->down();
            }
        }
    }
}

