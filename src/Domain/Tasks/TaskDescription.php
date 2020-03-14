<?php


namespace App\Domain\Tasks;


class TaskDescription {
    private string $title;
    private string $text;

    public function __construct(string $title, string $text = "") {
        // @todo Validate title & description
        $this->title = $title;
        $this->text = $text;
    }

    public function getTitle(): string {
        return $this->title;
    }

    public function getText(): string {
        return $this->text;
    }

}