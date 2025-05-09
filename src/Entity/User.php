<?php
/**
 * User Entity
 * 
 * This class represents a user in the system who can log into the admin panel.
 * It provides authentication functionality for the admin dashboard.
 */
namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use DateTime;

// Entity annotation marks this class as a Doctrine entity
#[ORM\Entity]
// Table annotation specifies the database table name
#[ORM\Table(name: 'users')]
// HasLifecycleCallbacks enables the use of lifecycle callbacks like PrePersist
#[ORM\HasLifecycleCallbacks]
class User
{
    /**
     * Primary key identifier for the user
     */
    #[ORM\Id]
    #[ORM\Column(type: 'integer')]
    #[ORM\GeneratedValue]
    private int $id;
    
    /**
     * User's email address, must be unique as it's used for login
     */
    #[ORM\Column(type: 'string', length: 255, unique: true)]
    private string $email;
    
    /**
     * Hashed password for user authentication
     */
    #[ORM\Column(type: 'string', length: 255)]
    private string $password;
    
    /**
     * Timestamp when the user was created
     */
    #[ORM\Column(type: 'datetime')]
    private DateTime $created_at;
    
    /**
     * Timestamp when the user was last updated
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
     * Get the user's ID
     */
    public function getId(): int
    {
        return $this->id;
    }
    
    /**
     * Get the user's email
     */
    public function getEmail(): string
    {
        return $this->email;
    }
    
    /**
     * Set the user's email
     * 
     * @param string $email The email to set
     * @return self For method chaining
     */
    public function setEmail(string $email): self
    {
        $this->email = $email;
        return $this;
    }
    
    /**
     * Get the user's hashed password
     */
    public function getPassword(): string
    {
        return $this->password;
    }
    
    /**
     * Set the user's password (should be pre-hashed)
     * 
     * @param string $password The hashed password to set
     * @return self For method chaining
     */
    public function setPassword(string $password): self
    {
        $this->password = $password;
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
} 