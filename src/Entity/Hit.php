<?php

namespace App\Entity;

use App\Repository\HitRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=HitRepository::class)
 * @ORM\Table(name="hits")
 */
class Hit
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="datetime")
     */
    private $batch;

    /**
     * @ORM\Column(type="integer")
     */
    private $bloc;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $input;

    /**
     * @ORM\Column(type="string", length=8)
     */
    private $correct_key;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $hash;

    /**
     * @ORM\Column(type="integer")
     */
    private $attempts;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getBatch(): ?\DateTime
    {
        return $this->batch;
    }

    public function setBatch(\DateTimeInterface $batch): self
    {
        $this->batch = $batch;

        return $this;
    }

    public function getBloc(): ?int
    {
        return $this->bloc;
    }

    public function setBloc(int $bloc): self
    {
        $this->bloc = $bloc;

        return $this;
    }

    public function getInput(): ?string
    {
        return $this->input;
    }

    public function setInput(string $input): self
    {
        $this->input = $input;

        return $this;
    }

    public function getCorrectKey(): ?string
    {
        return $this->correct_key;
    }

    public function setCorrectKey(string $correct_key): self
    {
        $this->correct_key = $correct_key;

        return $this;
    }

    public function getHash(): ?string
    {
        return $this->hash;
    }

    public function setHash(string $hash): self
    {
        $this->hash = $hash;

        return $this;
    }

    public function getAttempts(): ?int
    {
        return $this->attempts;
    }

    public function setAttempts(int $attempts): self
    {
        $this->attempts = $attempts;

        return $this;
    }
}
