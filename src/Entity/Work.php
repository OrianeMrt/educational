<?php

namespace App\Entity;

use App\Entity\Account\User;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * @ORM\Entity(repositoryClass="App\Repository\WorkRepository")
 * @Vich\Uploadable
 */
class Work {

  /**
   * @ORM\Id()
   * @ORM\GeneratedValue()
   * @ORM\Column(type="integer")
   */
  private $id;

  /**
   * @ORM\Column(type="string", length=255)
   */
  private $title;

  /**
   * @ORM\Column(type="text", nullable=true)
   */
  private $description;

  /**
   * @ORM\Column(type="string", length=255)
   */
  private $file;


  /**
   * @Vich\UploadableField(mapping="work_file", fileNameProperty="file")
   * @var File
   */
  private $fileFile;

  /**
   * @ORM\Column(type="datetime")
   */
  private $updatedAt;

  /**
   * @ORM\ManyToOne(targetEntity="App\Entity\Account\User", inversedBy="works")
   * @ORM\JoinColumn(nullable=false)
   */
  private $user;

  /**
   * @ORM\ManyToMany(targetEntity="App\Entity\Account\User", inversedBy="works_liked")
   * @ORM\JoinTable(name="likes")
   */
  private $likes;

  /**
   * @ORM\Column(type="integer", nullable=true)
   */
  private $mark;

  public function __construct()
  {
      $this->likes = new ArrayCollection();
  }

  public function getId(): ?int {
    return $this->id;
  }

  public function getTitle(): ?string {
    return $this->title;
  }

  public function setTitle(string $title): self {
    $this->title = $title;

    return $this;
  }

  public function getDescription(): ?string {
    return $this->description;
  }

  public function setDescription(?string $description): self {
    $this->description = $description;

    return $this;
  }

  public function getFile(): ?string {
    return $this->file;
  }

  public function setFile(string $file): self {
    $this->file = $file;

    return $this;
  }

  public function setFileFile(File $file = null)
  {
    $this->fileFile = $file;
    if ($file) {
      $this->updatedAt = new \DateTime('now');
    }
  }

  public function getFileFile()
  {
    return $this->fileFile;
  }

  public function getUpdatedAt(): ?\DateTimeInterface {
    return $this->updatedAt;
  }

  public function setUpdatedAt(\DateTimeInterface $updatedAt): self {
    $this->updatedAt = $updatedAt;

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

  /**
   * @return Collection|User[]
   */
  public function getLikes(): Collection
  {
      return $this->likes;
  }

  public function addLike(User $like): self
  {
      if (!$this->likes->contains($like)) {
          $this->likes[] = $like;
      }

      return $this;
  }

  public function removeLike(User $like): self
  {
      if ($this->likes->contains($like)) {
          $this->likes->removeElement($like);
      }

      return $this;
  }

  public function getMark(): ?int
  {
      return $this->mark;
  }

  public function setMark(?int $mark): self
  {
      $this->mark = $mark;

      return $this;
  }
}
