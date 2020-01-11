<?php

use Illuminate\Database\Migrations\Migration;

class AddRulesPage extends Migration
{
    public function up()
    {
        \App\Page::create([
            'title'     => 'Правила сайта',
            'body'      => view('pages.templates.rules')->render(),
            'is_active' => true,
        ]);
    }

    public function down()
    {
        \App\Page::where('title', 'Правила сайта')->delete();
    }
}
