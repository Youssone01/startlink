<?php
class Quiz
{
    private $titre;
    private $description;
    private $duree;
    private $questions;
    private $id_formation;
    private $correct_answer;

    public function __construct($titre, $description, $duree, $questions, $id_formation, $correct_answer)
    {
        $this->titre = $titre;
        $this->description = $description;
        $this->duree = $duree;
        $this->questions = $questions;
        $this->id_formation = $id_formation;
        $this->correct_answer = $correct_answer;
    }

    public function getTitre()
    {
        return $this->titre;
    }

    public function getDescription()
    {
        return $this->description;
    }

    public function getDuree()
    {
        return $this->duree;
    }

    public function getQuestions()
    {
        return $this->questions;
    }

    public function getIdFormation()
    {
        return $this->id_formation;
    }

    public function getCorrectAnswer()
    {
        return $this->correct_answer;
    }
}
?>
