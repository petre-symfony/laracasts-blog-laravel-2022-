<?php

namespace App\Models;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\File;

class Post {
    public $title;
    public $excerpt;
    public $date;
    public $body;

    public function __construct($title, $excerpt, $date, $body){
        $this->body = $body;
        $this->excerpt = $excerpt;
        $this->date = $date;
        $this->title = $title;
    }

    public static function find($slug) {
        if (! file_exists($path = resource_path("posts/{$slug}.html"))){
            throw new ModelNotFoundException();
        }

        return cache()->remember("posts.{$slug}", now()->addHours(2), fn() => file_get_contents($path));
    }

    public static function all() {
        $files = File::files(resource_path("posts/"));

        return array_map(fn ($file) => $file->getContents()
        , $files);
    }
}
