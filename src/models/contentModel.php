<?php

class contentModel {
    public int $id;
    public string $title;
    public string $body;
    public string $keywords;
    public string $author;

    public function __construct() {}

    public function mapRow($row) {
        $this->id = $row['id'];
        $this->title = $row['title'];
        $this->body = $row['body'];
        $this->keywords = $row['keywords'];
        $this->author = $row['author'];
    }
}

?>