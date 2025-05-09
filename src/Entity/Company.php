<?php
/**
 * Company Entity
 * 
 * This class represents a company in the system.
 * Companies can have multiple employees and store information like name, email, logo, and website.
 * The logo must be at least 100x100 pixels as per requirements.
 */
namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use DateTime;

// Entity annotation marks this class as a Doctrine entity
#[ORM\Entity]
// Table annotation specifies the database table name
#[ORM\Table(name: 'companies')]
// HasLifecycleCallbacks enables the use of lifecycle callbacks like PrePersist
#[ORM\HasLifecycleCallbacks]
class Company
{
    /**
     * Primary key identifier for the company
     */
    #[ORM\Id]
    #[ORM\Column(type: 'integer')]
    #[ORM\GeneratedValue]
    private int $id;
    
    /**
     * Company name (required field)
     */
    #[ORM\Column(type: 'string', length: 255)]
    private string $name;
    
    /**
     * Company email address (optional)
     */
    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private ?string $email = null;
    
    /**
     * Path to company logo image (optional)
     * Must be at least 100x100 pixels (validation handled in controller)
     */
    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private ?string $logo = null;
    
    /**
     * Company website URL (optional)
     */
    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private ?string $website = null;
    
    /**
     * Timestamp when the company was created
     */
    #[ORM\Column(type: 'datetime')]
    private DateTime $created_at;
    
    /**
     * Timestamp when the company was last updated
     */
    #[ORM\Column(type: 'datetime')]
    private DateTime $updated_at;
    
    /**
     * Collection of employees associated with this company
     * One company can have many employees (One-to-Many relationship)
     * When a company is deleted, set the company_id of associated employees to NULL
     */
    #[ORM\OneToMany(targetEntity: Employee::class, mappedBy: 'company', orphanRemoval: false)]
    private Collection $employees;
    
    /**
     * Constructor initializes the employees collection
     */
    public function __construct()
    {
        $this->employees = new ArrayCollection();
    }
    
    /**
     * Automatically set created_at and updated_at timestamps before entity is persisted
     */
    #[ORM\PrePersist]
    public function onPrePersist(): void
    {
        $this->created_at = new DateTime();
        $this->updated_at = new DateTime();
    }
    
    /**
     * Automatically update the updated_at timestamp before entity is updated
     */
    #[ORM\PreUpdate]
    public function onPreUpdate(): void
    {
        $this->updated_at = new DateTime();
    }
    
    // Getters and setters
    /**
     * Get the company's ID
     */
    public function getId(): int
    {
        return $this->id;
    }
    
    /**
     * Get the company's name
     */
    public function getName(): string
    {
        return $this->name;
    }
    
    /**
     * Set the company's name
     * 
     * @param string $name The name to set
     * @return self For method chaining
     */
    public function setName(string $name): self
    {
        $this->name = $name;
        return $this;
    }
    
    /**
     * Get the company's email
     */
    public function getEmail(): ?string
    {
        return $this->email;
    }
    
    /**
     * Set the company's email
     * 
     * @param string|null $email The email to set
     * @return self For method chaining
     */
    public function setEmail(?string $email): self
    {
        $this->email = $email;
        return $this;
    }
    
    /**
     * Get the path to the company's logo
     */
    public function getLogo(): ?string
    {
        return $this->logo;
    }
    
    /**
     * Set the path to the company's logo
     * 
     * @param string|null $logo The logo path to set
     * @return self For method chaining
     */
    public function setLogo(?string $logo): self
    {
        $this->logo = $logo;
        return $this;
    }
    
    /**
     * Get the company's website URL
     */
    public function getWebsite(): ?string
    {
        return $this->website;
    }
    
    /**
     * Set the company's website URL
     * 
     * @param string|null $website The website URL to set
     * @return self For method chaining
     */
    public function setWebsite(?string $website): self
    {
        $this->website = $website;
        return $this;
    }
    
    /**
     * Get the creation timestamp
     */
    public function getCreatedAt(): DateTime
    {
        return $this->created_at;
    }
    
    /**
     * Get the last update timestamp
     */
    public function getUpdatedAt(): DateTime
    {
        return $this->updated_at;
    }
    
    /**
     * Get the collection of employees associated with this company
     */
    public function getEmployees(): Collection
    {
        return $this->employees;
    }
    
    /**
     * Add an employee to this company
     * 
     * @param Employee $employee The employee to add
     * @return self For method chaining
     */
    public function addEmployee(Employee $employee): self
    {
        if (!$this->employees->contains($employee)) {
            $this->employees[] = $employee;
            $employee->setCompany($this);
        }
        
        return $this;
    }
    
    /**
     * Remove an employee from this company
     * 
     * @param Employee $employee The employee to remove
     * @return self For method chaining
     */
    public function removeEmployee(Employee $employee): self
    {
        if ($this->employees->removeElement($employee)) {
            // set the owning side to null (unless already changed)
            if ($employee->getCompany() === $this) {
                $employee->setCompany(null);
            }
        }
        
        return $this;
    }
} 