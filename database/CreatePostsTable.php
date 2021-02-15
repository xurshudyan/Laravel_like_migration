<?php

namespace App\database;

use Core\SRC\Migration;

class CreatePostsTable extends Migration
{
    public function up()
    {
       return $this->createTable('posts')
            ->id()
            ->timestamps()
            ->run();
    }

    public function down()
    {
        $this->dropIfExists('posts');
    }
}

        