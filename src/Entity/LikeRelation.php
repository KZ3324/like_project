<?php

namespace App\Entity;

use App\Repository\LikeRelationRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: LikeRelationRepository::class)]
class LikeRelation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'integer')]
    private $id_liker;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'id_like')]
    private $user;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIdLiker(): ?int
    {
        return $this->id_liker;
    }

    public function setIdLiker(int $id_liker): self
    {
        $this->id_liker = $id_liker;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }
}
