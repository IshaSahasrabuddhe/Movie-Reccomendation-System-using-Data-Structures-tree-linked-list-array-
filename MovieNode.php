<?php
class MovieNode {
    public $title;
    public $releaseDate;
    public $overview;
    public $posterUrl;
    public $genre; // Add this line
    public $left;
    public $right;

    public function __construct($title, $releaseDate, $overview, $posterUrl, $genre = null) {
        $this->title = $title;
        $this->releaseDate = $releaseDate;
        $this->overview = $overview;
        $this->posterUrl = $posterUrl;
        $this->genre = $genre; // Add this line
        $this->left = null;
        $this->right = null;
    }
}
?>
