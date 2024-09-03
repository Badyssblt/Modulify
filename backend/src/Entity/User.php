<?php

namespace App\Entity;

    use Doctrine\Common\Collections\ArrayCollection;
    use Doctrine\Common\Collections\Collection;
    use Doctrine\DBAL\Types\Types;
    use Doctrine\ORM\Mapping as ORM;
    use App\Repository\UserRepository;
    use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
    use Symfony\Component\HttpFoundation\File\File;
    use Symfony\Component\Serializer\Attribute\Groups;
    use Vich\UploaderBundle\Mapping\Annotation as Vich;
    use Symfony\Component\Security\Core\User\UserInterface;
    use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
    use Symfony\Component\Validator\Constraints as Assert;


    #[ORM\Entity(repositoryClass: UserRepository::class)]
    #[ORM\UniqueConstraint(name: 'UNIQ_IDENTIFIER_EMAIL', fields: ['email'])]
    #[Vich\Uploadable]
    #[UniqueEntity('email', message: 'Un utilisateur avec cet email existe déjà')]
    class User implements UserInterface, PasswordAuthenticatedUserInterface
    {
        #[ORM\Id]
        #[ORM\GeneratedValue]
        #[ORM\Column]
        #[Groups(['item:user', 'collection:asset', 'item:asset'])]
        private ?int $id = null;

        #[ORM\Column(length: 180)]
        #[Groups(['item:user', 'collection:asset', 'item:asset'])]
        private ?string $email = null;

        /**
         * @var list<string> The user roles
         */
        #[ORM\Column]
        #[Groups(['item:user', 'collection:asset', 'item:asset'])]
        private array $roles = [];

        /**
         * @var string The hashed password
         */
        #[ORM\Column]
        private ?string $password = null;

        #[ORM\Column(length: 255)]
        #[Groups(['item:user', 'collection:asset', 'item:asset'])]
        private ?string $name = null;

        #[ORM\Column]
        #[Groups(['item:user', 'collection:asset'])]
        private ?bool $is_verified = null;

        #[Vich\UploadableField(mapping: 'products', fileNameProperty: 'imageName', size: 'imageSize')]
        private ?File $imageFile = null;

        #[ORM\Column(nullable: true)]
        #[Groups(['collection:asset', 'item:asset'])]
        private ?string $imageName = null;

        #[ORM\Column(nullable: true)]
        private ?int $imageSize = null;

        #[ORM\Column(nullable: true)]
        private ?\DateTimeImmutable $updatedAt = null;

        /**
         * @var Collection<int, Asset>
         */
        #[ORM\ManyToMany(targetEntity: Asset::class, inversedBy: 'followers')]
        #[Groups(['item:user'])]
        private Collection $follow_asset;

        #[ORM\Column(type: Types::TEXT, nullable: true)]
        private ?string $github_token = null;

        /**
         * @var Collection<int, Asset>
         */
        #[ORM\OneToMany(targetEntity: Asset::class, mappedBy: 'author')]
        private Collection $assets;

        public function __construct() {
            $this->is_verified = false;
            $this->imageName = "default";
            $this->follow_asset = new ArrayCollection();
            $this->assets = new ArrayCollection();
        }

        public function getId(): ?int
        {
            return $this->id;
        }

        public function getEmail(): ?string
        {
            return $this->email;
        }

        public function setEmail(string $email): static
        {
            $this->email = $email;

            return $this;
        }

        /**
         * A visual identifier that represents this user.
         *
         * @see UserInterface
         */
        public function getUserIdentifier(): string
        {
            return (string) $this->email;
        }

        /**
         * @see UserInterface
         *
         * @return list<string>
         */
        public function getRoles(): array
        {
            $roles = $this->roles;
            // guarantee every user at least has ROLE_USER
            $roles[] = 'ROLE_USER';

            return array_unique($roles);
        }

        /**
         * @param list<string> $roles
         */
        public function setRoles(array $roles): static
        {
            $this->roles = $roles;

            return $this;
        }

        /**
         * @see PasswordAuthenticatedUserInterface
         */
        public function getPassword(): ?string
        {
            return $this->password;
        }

        public function setPassword(string $password): static
        {
            $this->password = $password;

            return $this;
        }

        /**
         * @see UserInterface
         */
        public function eraseCredentials(): void
        {
            // If you store any temporary, sensitive data on the user, clear it here
            // $this->plainPassword = null;
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

        public function isVerified(): ?bool
        {
            return $this->is_verified;
        }

        public function setVerified(bool $is_verified): static
        {
            $this->is_verified = $is_verified;

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
         * @return Collection<int, Asset>
         */
        public function getFollowAsset(): Collection
        {
            return $this->follow_asset;
        }

        public function addFollowAsset(Asset $followAsset): static
        {
            if (!$this->follow_asset->contains($followAsset)) {
                $this->follow_asset->add($followAsset);
            }

            return $this;
        }

        public function removeFollowAsset(Asset $followAsset): static
        {
            $this->follow_asset->removeElement($followAsset);

            return $this;
        }

        public function getGithubToken(): ?string
        {
            return $this->github_token;
        }

        public function setGithubToken(?string $github_token): static
        {
            $this->github_token = $github_token;

            return $this;
        }

        /**
         * @return Collection<int, Asset>
         */
        public function getAssets(): Collection
        {
            return $this->assets;
        }

        public function addAsset(Asset $asset): static
        {
            if (!$this->assets->contains($asset)) {
                $this->assets->add($asset);
                $asset->setAuthor($this);
            }

            return $this;
        }

        public function removeAsset(Asset $asset): static
        {
            if ($this->assets->removeElement($asset)) {
                // set the owning side to null (unless already changed)
                if ($asset->getAuthor() === $this) {
                    $asset->setAuthor(null);
                }
            }

            return $this;
        }
    }
