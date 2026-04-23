<?php

namespace App\Entity;

use App\Repository\ImageRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: ImageRepository::class)]
#[Vich\Uploadable]
class Image
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 100)]
    private ?string $src = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $date = null;

    #[Vich\UploadableField(mapping: 'portfolio_images', fileNameProperty: 'src')]
    #[Assert\File(
        maxSize: '100M',
        mimeTypes: ['image/jpeg', 'image/png', 'image/webp', 'image/jpg', 'image/svg'],
        mimeTypesMessage: "Déchargez une image valide (JPEG, PNG, or WEBP)."
    )]
    private ?File $imageFile = null;

    public function __construct() {
        $this->date = new \DateTimeImmutable();
    }

    public function setImageFile(?File $imageFile = null): void
    {
        $this->imageFile = $imageFile;

        if ($imageFile) {
            $this->src = "/imgs/portfolio/" . $this->getSrc();
            $this->date = new \DateTimeImmutable(); 
        }
    }

    public function getImageFile(): ?File
    {
        return $this->imageFile;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSrc(): ?string
    {
        return $this->src;
    }

    public function setSrc(string $src): static
    {
        $this->src = $src;

        return $this;
    }

    public function getDate(): ?\DateTimeImmutable
    {
        return $this->date;
    }

    public function setDate(\DateTimeImmutable $date): static
    {
        $this->date = $date;

        return $this;
    }
}
