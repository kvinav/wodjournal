<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CommentRepository")
 */
class Comment
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     */
    private $wodId;

    /**
     * @ORM\Column(type="integer")
     */
    private $userId;
    /**
     * @ORM\Column(type="text")
     */
    private $comment;
    /**
     * @ORM\Column(type="string")
     */
    private $userPseudo;
    /**
     * @ORM\Column(type="string")
     */
    private $date;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getWodId(): ?int
    {
        return $this->wodId;
    }

    public function setWodId(int $wodId): self
    {
        $this->wodId = $wodId;

        return $this;
    }

    public function getUserId(): ?int
    {
        return $this->userId;
    }

    public function setUserId(int $userId): self
    {
        $this->userId = $userId;

        return $this;
    }
    public function getComment(): ?string
    {
        return $this->comment;
    }

    public function setComment(string $comment): self
    {
        $this->comment = $comment;

        return $this;
    }
    public function getDate(): ?string
    {
        return $this->date;
    }

    public function setDate(?string $date): self
    {
        $this->date = $date;

        return $this;
    }
    public function getUserPseudo(): ?string
    {
        return $this->userPseudo;
    }

    public function setUserPseudo(?string $userPseudo): self
    {
        $this->userPseudo = $userPseudo;

        return $this;
    }
}
