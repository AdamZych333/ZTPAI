<?php

class User
{
    private string $name;
    private string $surname;
    private string $joinedDate;
    private string $lastOnline;
    private string $image;
    private int $id;

    public function __construct($name, $surname, $joinedDate, $id, $image, $lastOnline)
    {
        $this->name = $name;
        $this->joinedDate = $joinedDate;
        $this->surname = $surname;
        $this->id = $id;
        $this->image = $image;
        $this->lastOnline = $lastOnline;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getSurname(): string
    {
        return $this->surname;
    }

    public function setSurname(string $surname): void
    {
        $this->surname = $surname;
    }

    public function getJoinedDate(): string
    {
        return $this->joinedDate;
    }

    public function setJoinedDate(string $joinedDate): void
    {
        $this->joinedDate = $joinedDate;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function getImage(): string
    {
        return $this->image;
    }

    public function setImage(string $image): void
    {
        $this->image = $image;
    }

    public function getLastOnline(): string
    {
        return $this->lastOnline;
    }

    public function setLastOnline(string $lastOnline): void
    {
        $this->lastOnline = $lastOnline;
    }





}