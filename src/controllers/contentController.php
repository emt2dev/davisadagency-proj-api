<?php
include ('./src/models/newContentModel.php');

class contentController {

    public string $descriptiveOne;
    public string $descriptiveTwo;

    public $contentArray = [];
    public $connection;

    public function __construct($connection) { $this->connection = $connection; }

    public function setDescriptiveOne(string $descriptiveOne) {
        $this->descriptiveOne = $descriptiveOne;
    }

    public function setDescriptiveTwo(string $descriptiveTwo) {
        $this->descriptiveTwo = $descriptiveTwo;
    }

    public function getOne(int $contentId) {
        // begin query
        $query = "SELECT * FROM content WHERE id=$contentId";
        $querySend = mysqli_query($this->connection, $query);

        if ($querySend->num_rows === 0) {

            return 0;
        } else {
            $row = mysqli_fetch_assoc($querySend);

            $content = new contentModel();
            $content->mapRow($row);
    
            array_push($this->contentArray, $content);

            return $content;
        }        
    }

    public function getAll() {
        // begin query
        $query = 'SELECT * FROM content';
        $querySend = mysqli_query($this->connection, $query);
        $data = $querySend->fetch_assoc();

        foreach ($querySend as $row) {
            $content = new contentModel();
            $content->mapRow($row);

            array_push($this->contentArray, $content);
            unset($content);
        }

        return $this->contentArray;
    }

    public function upload(newContentModel $Obj) {
        $query = "INSERT INTO content (title, body, keywords, author, filepath1, filepath2) VALUES (?,?,?,?, ?, ?)";

        $query = $this->connection->prepare($query);

            $query->bind_param("ssss", $a,$b,$c,$d,$e,$f);

                $a = $Obj->title;
                $b = $Obj->body;
                $c = $Obj->keywords;
                $d = $Obj->author;
                $e = $Obj->path1;
                $f = $Obj->path2;

        $query->execute();

        return true;
    }

    public function getAuthorName(int $id) {
        $query = "SELECT * FROM users WHERE id=$id";
        $querySend = mysqli_query($this->connection, $query);

        if ($querySend->num_rows === 0) {

            return false;
        } else {
            $row = mysqli_fetch_assoc($querySend);
    
            return $row['name'];
        }
    }
}

?>