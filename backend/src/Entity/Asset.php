<?php

namespace App\Entity;

use App\Repository\AssetRepository;
use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Serializer\Attribute\Groups;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

#[ORM\Entity(repositoryClass: AssetRepository::class)]
#[Vich\Uploadable]
class Asset
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['collection:asset', 'item:asset', 'item:user', 'collection:admin:asset'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['collection:asset', 'item:asset', 'item:user', 'collection:admin:asset'])]
    private ?string $name = null;

    #[ORM\Column]
    #[Groups(['collection:asset', 'item:asset', 'item:user', 'collection:admin:asset'])]
    private ?int $price = null;

    #[ORM\Column(type: Types::TEXT)]
    #[Groups(['item:asset', 'collection:admin:asset'])]
    private ?string $file = null;

    #[ORM\Column(type: Types::TEXT)]
    #[Groups(['item:asset', 'collection:admin:asset'])]
    private ?string $description = null;

    #[ORM\Column]
    #[Groups(['collection:asset', 'item:asset', 'collection:admin:asset'])]
    private ?\DateTimeImmutable $created_at = null;

    #[ORM\Column]
    #[Groups(['collection:admin:asset'])]
    private ?bool $is_public = null;

    #[Vich\UploadableField(mapping: 'asset', fileNameProperty: 'imageName', size: 'imageSize')]
    private ?File $imageFile = null;

    #[ORM\Column(nullable: true)]
    #[Groups(['collection:asset', 'item:asset', 'item:user', 'collection:admin:asset'])]
    private ?string $imageName = null;

    #[ORM\Column(nullable: true)]
    private ?int $imageSize = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $updatedAt = null;

    #[ORM\Column]
    #[Groups(['collection:asset', 'collection:admin:asset'])]
    private ?int $total_download = null;

    /**
     * @var Collection<int, User>
     */
    #[ORM\ManyToMany(targetEntity: User::class, mappedBy: 'follow_asset')]
    private Collection $followers;

    /**
     * @var Collection<int, Category>
     */
    #[ORM\ManyToMany(targetEntity: Category::class, mappedBy: 'asset')]
    #[Groups(['collection:asset', 'item:asset'])]
    private Collection $categories;

    #[ORM\Column(type: Types::TEXT)]
    #[Groups(['collection:asset', 'item:asset', 'item:user', 'collection:admin:asset'])]
    private ?string $how = null;

    #[ORM\Column(type: Types::TEXT)]
    #[Groups(['collection:asset', 'item:asset', 'item:user', 'collection:admin:asset'])]
    private ?string $version = null;

    #[ORM\ManyToOne(inversedBy: 'assets')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['collection:asset', 'item:asset'])]
    private ?User $author = null;

    public function __construct() {
        $this->created_at = new DateTimeImmutable();
        $this->is_public = false;
        $this->total_download = 0;
        $this->followers = new ArrayCollection();
        $this->categories = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getPrice(): ?int
    {
        return $this->price;
    }

    public function setPrice(int $price): static
    {
        $this->price = $price;

        return $this;
    }

    public function getFile(): ?string
    {
        return $this->file;
    }

    public function setFile(string $file): static
    {
        $this->file = $file;

        return $this;
    }

    public function getdescription(): ?string
    {
        return $this->description;
    }

    public function setdescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->created_at;
    }

    public function setCreatedAt(\DateTimeImmutable $created_at): static
    {
        $this->created_at = $created_at;

        return $this;
    }

    public function isPublic(): ?bool
    {
        return $this->is_public;
    }

    public function setPublic(bool $is_public): static
    {
        $this->is_public = $is_public;

        return $this;
    }

    /**
     * If manually uploading a file (i.e. not using Symfony Form) ensure an instance
     * of 'UploadedFile' is injected into this setter to trigger the update. If this
     * bundle's configuration parameter 'inject_on_load' is set to 'true' this setter
     * must be able to accept an instance of 'File' as the bundle will inject one here
     * during Doctrine hydration.
     *
     * @param File|\Symfony\Component\HttpFoundation\File\UploadedFile|null $imageFile
     */
    public function setImageFile(?File $imageFile = null): void
    {
        $this->imageFile = $imageFile;

        if (null !== $imageFile) {
            // It is required that at least one field changes if you are using doctrine
            // otherwise the event listeners won't be called and the file is lost
            $this->updatedAt = new \DateTimeImmutable();
        }
    }

    public function getImageFile(): ?File
    {
        return $this->imageFile;
    }

    public function setImageName(?string $imageName): void
    {
        $this->imageName = $imageName;
    }

    public function getImageName(): ?string
    {
        return $this->imageName;
    }

    public function setImageSize(?int $imageSize): void
    {
        $this->imageSize = $imageSize;
    }

    public function getImageSize(): ?int
    {
        return $this->imageSize;
    }

    public function getTotalDownload(): ?int
    {
        return $this->total_download;
    }

    public function setTotalDownload(int $total_download): static
    {
        $this->total_download = $total_download;

        return $this;
    }

    /**
     * Get the value of updatedAt
     */ 
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * Set the value of updatedAt
     *
     * @return  self
     */ 
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * @return Collection<int, User>
     */
    public function getFollowers(): Collection
    {
        return $this->followers;
    }

    public function addFollower(User $follower): static
    {
        if (!$this->followers->contains($follower)) {
            $this->followers->add($follower);
            $follower->addFollowAsset($this);
        }

        return $this;
    }

    public function removeFollower(User $follower): static
    {
        if ($this->followers->removeElement($follower)) {
            $follower->removeFollowAsset($this);
        }

        return $this;
    }

    /**
     * @return Collection<int, Category>
     */
    public function getCategories(): Collection
    {
        return $this->categories;
    }

    public function addCategory(Category $category): static
    {
        if (!$this->categories->contains($category)) {
            $this->categories->add($category);
            $category->addAsset($this);
        }

        return $this;
    }

    public function removeCategory(Category $category): static
    {
        if ($this->categories->removeElement($category)) {
            $category->removeAsset($this);
        }

        return $this;
    }

    public function getHow(): ?string
    {
        return $this->how;
    }

    public function setHow(string $how): static
    {
        $this->how = $how;

        return $this;
    }

    public function getVersion(): ?string
    {
        return $this->version;
    }

    public function setVersion(string $version): static
    {
        $this->version = $version;

        return $this;
    }

    public function getAuthor(): ?User
    {
        return $this->author;
    }

    public function setAuthor(?User $author): static
    {
        $this->author = $author;

        return $this;
    }
}
