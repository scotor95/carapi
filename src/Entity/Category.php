<?php


namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Assert\Assert;

/**
 * Advert
 *
 * @ORM\Table(name="category")
 * @ORM\Entity(repositoryClass="App\Repository\CategoryRepository")
 */
class Category
{

    const HOUSES = "Immobilier";
    const CARS = "Automobile";
    const JOBS = "Emploi";

    const CATEGORY_NAMES = [self::HOUSES, self::CARS, self::JOBS];
    /**
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", columnDefinition="ENUM('Emploi','Automobile','Immobilier')", length=255, nullable=false)
     */
    private $name;

    /**
     * @ORM\OneToMany(targetEntity=Advert::class, mappedBy="category")
     */
    private $adverts;

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name): self
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getAdverts()
    {
        return $this->adverts;
    }

    /**
     * @param mixed $adverts
     */
    public function setAdverts($adverts): self
    {
        $this->adverts = $adverts;
        return $this;
    }
}