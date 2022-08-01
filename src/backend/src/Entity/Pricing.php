<?php

namespace App\Entity;

use App\Repository\PricingRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\Index;

#[ORM\Entity(repositoryClass: PricingRepository::class)]
#[Index(name: "idx_model", columns: ["model"])]
#[Index(name: "idx_brand", columns: ["brand"])]
#[Index(name: "idx_ram", columns: ["ram"])]
#[Index(name: "idx_ramtype", columns: ["ramtype"])]
#[Index(name: "idx_storage", columns: ["storage"])]
#[Index(name: "idx_storagetype", columns: ["storagetype"])]
#[Index(name: "idx_storagetxt", columns: ["storagetxt"])]
#[Index(name: "idx_location", columns: ["location"])]
#[Index(name: "idx_city", columns: ["city"])]
#[Index(name: "idx_currency", columns: ["currency"])]
#[Index(name: "idx_price", columns: ["price"])]
class Pricing
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 100)]
    private ?string $model = null;

    #[ORM\Column(length: 50)]
    private ?string $brand = null;

    #[ORM\Column]
    private ?int $ram = null;

    #[ORM\Column(length: 50)]
    private ?string $ramtype = null;

    #[ORM\Column]
    private ?int $storage = null;

    #[ORM\Column(length: 50)]
    private ?string $storagetype = null;

    #[ORM\Column(length: 100)]
    private ?string $storagetxt = null;

    #[ORM\Column(length: 10)]
    private ?string $location = null;

    #[ORM\Column(length: 50)]
    private ?string $city = null;

    #[ORM\Column(length: 10)]
    private ?string $currency = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 2)]
    private ?string $price = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getModel(): ?string
    {
        return $this->model;
    }

    public function setModel(string $model): self
    {
        $this->model = $model;

        return $this;
    }

    public function getBrand(): ?string
    {
        return $this->brand;
    }

    public function setBrand(string $brand): self
    {
        $this->brand = $brand;

        return $this;
    }

    public function getRam(): ?int
    {
        return $this->ram;
    }

    public function setRam(int $ram): self
    {
        $this->ram = $ram;

        return $this;
    }

    public function getRamtype(): ?string
    {
        return $this->ramtype;
    }

    public function setRamtype(string $ramtype): self
    {
        $this->ramtype = $ramtype;

        return $this;
    }

    public function getStorage(): ?int
    {
        return $this->storage;
    }

    public function setStorage(int $storage): self
    {
        $this->storage = $storage;

        return $this;
    }

    public function getStoragetype(): ?string
    {
        return $this->storagetype;
    }

    public function setStoragetype(string $storagetype): self
    {
        $this->storagetype = $storagetype;

        return $this;
    }

    public function getStoragetxt(): ?string
    {
        return $this->storagetxt;
    }

    public function setStoragetxt(string $storagetxt): self
    {
        $this->storagetxt = $storagetxt;

        return $this;
    }

    public function getLocation(): ?string
    {
        return $this->location;
    }

    public function setLocation(string $location): self
    {
        $this->location = $location;

        return $this;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(string $city): self
    {
        $this->city = $city;

        return $this;
    }

    public function getCurrency(): ?string
    {
        return $this->currency;
    }

    public function setCurrency(string $currency): self
    {
        $this->currency = $currency;

        return $this;
    }

    public function getPrice(): ?string
    {
        return $this->price;
    }

    public function setPrice(string $price): self
    {
        $this->price = $price;

        return $this;
    }
}