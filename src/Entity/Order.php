<?php

namespace App\Entity;

use App\Repository\OrderRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=OrderRepository::class)
 * @ORM\Table(name="`order`")
 */
class Order
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
    private $date;

    /**
     * @ORM\OneToMany(targetEntity=Commandline::class, mappedBy="orderid")
     */
    private $commandlines;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="orderid")
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity=Address::class, inversedBy="orderid")
     */
    private $address;

    public function __construct()
    {
        $this->commandlines = new ArrayCollection();
        $this->setDate(new \DateTime()); 
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }

    /**
     * @return Collection<int, Commandline>
     */
    public function getCommandlines(): Collection
    {
        return $this->commandlines;
    }

    public function addCommandline(Commandline $commandline): self
    {
        if (!$this->commandlines->contains($commandline)) {
            $this->commandlines[] = $commandline;
            $commandline->setOrderid($this);
        }

        return $this;
    }

    public function removeCommandline(Commandline $commandline): self
    {
        if ($this->commandlines->removeElement($commandline)) {
            // set the owning side to null (unless already changed)
            if ($commandline->getOrderid() === $this) {
                $commandline->setOrderid(null);
            }
        }

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

    public function getAddress(): ?Address
    {
        return $this->address;
    }

    public function setAddress(?Address $address): self
    {
        $this->address = $address;

        return $this;
    }
}
