<?php
/**
 * Employee Entity
 * 
 * This class represents an employee in the system.
 * Employees can be associated with a company and store personal information like name, email, and phone.
 */
namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use DateTime;

// Entity annotation marks this class as a Doctrine entity
#[ORM\Entity]
// Table annotation specifies the database table name
#[ORM\Table(name: 'employees')]
// HasLifecycleCallbacks enables the use of lifecycle callbacks like PrePersist
#[ORM\HasLifecycleCallbacks]
class Employee
{
    /**
     * Primary key identifier for the employee
     */
    #[ORM\Id]
    #[ORM\Column(type: 'integer')]
    #[ORM\GeneratedValue]
    private int $id;
    
    /**
     * Employee's first name (required field)
     */
    #[ORM\Column(type: 'string', length: 255)]
    private string $first_name;
    
    /**
     * Employee's last name (required field)
     */
    #[ORM\Column(type: 'string', length: 255)]
    private string $last_name;
    
    /**
     * Company that the employee works for (optional)
     * Many employees can belong to one company (Many-to-One relationship)
     * The onDelete="SET NULL" ensures that when a company is deleted, 
     * this field is set to NULL rather than preventing the deletion
     */
    #[ORM\ManyToOne(targetEntity: Company::class, inversedBy: 'employees')]
    #[ORM\JoinColumn(nullable: true, onDelete: "SET NULL")]
    private ?Company $company = null;
    
    /**
     * Employee's email address (optional)
     */
    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private ?string $email = null;
    
    /**
     * Employee's phone number (optional)
     */
    #[ORM\Column(type: 'string', length: 20, nullable: true)]
    private ?string $phone = null;
    
    /**
     * Timestamp when the employee was created
     */
    #[ORM\Column(type: 'datetime')]
    private DateTime $created_at;
    
    /**
     * Timestamp when the employee was last updated
     */
    #[ORM\Column(type: 'datetime')]
    private DateTime $updated_at;
    
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
     * Get the employee's ID
     */
    public function getId(): int
    {
        return $this->id;
    }
    
    /**
     * Get the employee's first name
     */
    public function getFirstName(): string
    {
        return $this->first_name;
    }
    
    /**
     * Set the employee's first name
     * 
     * @param string $first_name The first name to set
     * @return self For method chaining
     */
    public function setFirstName(string $first_name): self
    {
        $this->first_name = $first_name;
        return $this;
    }
    
    /**
     * Get the employee's last name
     */
    public function getLastName(): string
    {
        return $this->last_name;
    }
    
    /**
     * Set the employee's last name
     * 
     * @param string $last_name The last name to set
     * @return self For method chaining
     */
    public function setLastName(string $last_name): self
    {
        $this->last_name = $last_name;
        return $this;
    }
    
    /**
     * Get the company that the employee works for
     */
    public function getCompany(): ?Company
    {
        return $this->company;
    }
    
    /**
     * Set the company that the employee works for
     * 
     * @param Company|null $company The company to set
     * @return self For method chaining
     */
    public function setCompany(?Company $company): self
    {
        $this->company = $company;
        return $this;
    }
    
    /**
     * Get the employee's email
     */
    public function getEmail(): ?string
    {
        return $this->email;
    }
    
    /**
     * Set the employee's email
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
     * Get the employee's phone number
     */
    public function getPhone(): ?string
    {
        return $this->phone;
    }
    
    /**
     * Set the employee's phone number
     * 
     * @param string|null $phone The phone number to set
     * @return self For method chaining
     */
    public function setPhone(?string $phone): self
    {
        $this->phone = $phone;
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
     * Get the employee's full name (first name + last name)
     * Helper method used in views to display the complete name
     */
    public function getFullName(): string
    {
        return $this->first_name . ' ' . $this->last_name;
    }
} 