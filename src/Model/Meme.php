<?php

class Meme{
    private string $title;
    private string $image;
    private String $date;
    private int $likes;
    private int $dislikes;
    private int $id;

    public function __construct($title, $image, $date, $id, $likes=0, $dislikes=0){
        $this->title = $title;
        $this->date = $date;
        $this->image = $image;
        $this->id = $id;
        $this->likes = $likes;
        $this->dislikes = $dislikes;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    public function getImage(): string
    {
        return $this->image;
    }

    public function setImage(string $image): void
    {
        $this->image = $image;
    }

    public function getDate(): string
    {
        return $this->date;
    }

    public function setDate(string $date): void
    {
        $this->date = $date;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function getLikes(): mixed
    {
        return $this->likes;
    }

    public function setLikes(mixed $likes): void
    {
        $this->likes = $likes;
    }

    public function getDislikes(): mixed
    {
        return $this->dislikes;
    }

    public function setDislikes(mixed $dislikes): void
    {
        $this->dislikes = $dislikes;
    }


}

