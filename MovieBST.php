<?php
include_once 'MovieNode.php';

class MovieBST {
    private $root;

    public function __construct() {
        $this->root = null;
    }

    public function insert($title, $releaseDate, $overview, $posterUrl, $genre) {
        $newNode = new MovieNode($title, $releaseDate, $overview, $posterUrl, $genre); // Pass genre
        if ($this->root === null) {
            $this->root = $newNode;
        } else {
            $this->insertNode($this->root, $newNode);
        }
    }
    

    private function insertNode($currentNode, $newNode) {
        if ($newNode->title < $currentNode->title) {
            if ($currentNode->left === null) {
                $currentNode->left = $newNode;
            } else {
                $this->insertNode($currentNode->left, $newNode);
            }
        } else {
            if ($currentNode->right === null) {
                $currentNode->right = $newNode;
            } else {
                $this->insertNode($currentNode->right, $newNode);
            }
        }
    }

    public function search($title) {
        return $this->searchNode($this->root, $title);
    }

    private function searchNode($currentNode, $title) {
        if ($currentNode === null) {
            return null;
        }
        if ($currentNode->title === $title) {
            return $currentNode; // Movie found
        }
        if ($title < $currentNode->title) {
            return $this->searchNode($currentNode->left, $title);
        } else {
            return $this->searchNode($currentNode->right, $title);
        }
    }

    public function recommend($genre) {
        $recommendations = [];
        // In-order traversal to gather movies of the specified genre
        $this->inOrderRecommendation($this->root, $genre, $recommendations);
        return $recommendations;
    }
    
    private function inOrderRecommendation($node, $genre, &$recommendations) {
        if ($node) {
            $this->inOrderRecommendation($node->left, $genre, $recommendations);
            if ($node->genre === $genre) {
                $recommendations[] = $node; // Add movie to recommendations if genre matches
            }
            $this->inOrderRecommendation($node->right, $genre, $recommendations);
        }
    }
    
    
    private function findSimilarMoviesByGenre($node, $genre, &$recommendations, $excludeTitle) {
        if ($node === null) {
            return;
        }
    
        // Check if the movie matches the genre and is not the excluded movie
        if ($node->genre === $genre && $node->title !== $excludeTitle) {
            $recommendations[] = $node; // Add to recommendations
        }
    
        // Continue traversing left and right
        $this->findSimilarMoviesByGenre($node->left, $genre, $recommendations, $excludeTitle);
        $this->findSimilarMoviesByGenre($node->right, $genre, $recommendations, $excludeTitle);
    }
    

    private function traverse($node, &$recommendations) {
        if ($node !== null) {
            $recommendations[] = $node; // Add current node to recommendations
            $this->traverse($node->left, $recommendations); // Traverse left
            $this->traverse($node->right, $recommendations); // Traverse right
        }
    }
}
?>
