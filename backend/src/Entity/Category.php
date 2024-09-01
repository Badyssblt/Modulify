<?php

namespace App\Entity;

use App\Repository\CategoryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups;

#[ORM\Entity(repositoryClass: CategoryRepository::class)]
class Category
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['collection:category', 'collection:asset', 'item:asset'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['collection:category', 'collection:asset', 'item:asset'])]
    private ?string $title = null;

    /**
     * @var Collection<int, Asset>
     */
    #[ORM\ManyToMany(targetEntity: Asset::class, inversedBy: 'categories')]
    private Collection $asset;

    public function __construct()
    {
        $this->asset = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): static
    {
        $this->title = $title;

        return $this;
    }

    /**
     * @return Collection<int, Asset>
     */
    public function getAsset(): Collection
    {
        return $this->asset;
    }

    public function addAsset(Asset $asset): static
    {
        if (!$this->asset->contains($asset)) {
            $this->asset->add($asset);
        }

        return $this;
    }

    public function removeAsset(Asset $asset): static
    {
        $this->asset->removeElement($asset);

        return $this;
    }
}
