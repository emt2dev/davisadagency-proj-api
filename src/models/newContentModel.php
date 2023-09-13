<?php
class newContentModel {
    public string $title;
    public string $body;
    public string $keywords;
    public string $author;
    public string $path1;
    public string $path2;

    public function __construct(string $title, string $body, string $keywords, string $author) {
        $this->title = $title;
        $this->body = $body;
        $this->keywords = $keywords;
        $this->author = $author;
    }

    public function addPaths(string $path1, string $path2) {
        $this->path1 = $path1;
        $this->path2 = $path2;
    }
}

?>