<?php
/**
 * Company Controller
 * 
 * This controller handles all CRUD operations for companies:
 * - Listing all companies
 * - Creating new companies
 * - Editing existing companies
 * - Deleting companies
 * - Handling file uploads for company logos
 */

use App\Entity\Company;

// Set page title and header for the view
$pageTitle = 'Manage Companies';
$pageHeader = 'Companies';

// Get action and ID parameters from the URL
$action = $_GET['action'] ?? 'list';
$id = $_GET['id'] ?? null;

// Process form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Create or update company
    if (isset($_POST['save_company'])) {
        $name = $_POST['name'] ?? '';
        $email = $_POST['email'] ?? '';
        $website = $_POST['website'] ?? '';
        
        // Validate required fields (name is required)
        if (empty($name)) {
            $errorMessage = 'Company name is required.';
        } else {
            // Get existing company or create new one
            if ($id) {
                $company = $entityManager->find(Company::class, $id);
                if (!$company) {
                    $errorMessage = 'Company not found.';
                }
            } else {
                $company = new Company();
            }
            
            // Update company data with form values
            $company->setName($name);
            $company->setEmail($email ?: null);
            $company->setWebsite($website ?: null);
            
            // Handle logo upload if a file was provided
            if (isset($_FILES['logo']) && $_FILES['logo']['error'] === UPLOAD_ERR_OK) {
                $uploadDir = __DIR__ . '/../../public/uploads/';
                
                // Create upload directory if it doesn't exist
                if (!is_dir($uploadDir)) {
                    try {
                        if (!mkdir($uploadDir, 0777, true)) {
                            $errorMessage = 'Failed to create upload directory.';
                        }
                    } catch (\Exception $e) {
                        $errorMessage = 'Error creating upload directory: ' . $e->getMessage();
                    }
                }
                
                // If directory is valid, proceed with upload
                if (!isset($errorMessage)) {
                    // Get file info and generate unique filename
                    $tmpName = $_FILES['logo']['tmp_name'];
                    $fileName = uniqid() . '_' . $_FILES['logo']['name'];
                    $uploadPath = $uploadDir . $fileName;
                    
                    // Validate image dimensions (minimum 100x100 pixels as per requirements)
                    $imageInfo = getimagesize($tmpName);
                    
                    // Check if image info is valid and meets size requirements
                    if ($imageInfo === false) {
                        $errorMessage = 'Invalid image file. Please upload a valid image.';
                    } elseif ($imageInfo[0] < 100 || $imageInfo[1] < 100) {
                        $errorMessage = 'Logo must be at least 100x100 pixels.';
                    } else {
                        // Move uploaded file to final destination
                        if (move_uploaded_file($tmpName, $uploadPath)) {
                            $company->setLogo('uploads/' . $fileName);
                        } else {
                            $errorMessage = 'Failed to upload logo. Check file permissions.';
                        }
                    }
                }
            }
            
            // Save to database if no errors occurred
            if (!isset($errorMessage)) {
                try {
                    $entityManager->persist($company);
                    $entityManager->flush();
                    $successMessage = 'Company saved successfully.';
                    
                    // Redirect to list view with success message
                    header('Location: index.php?route=companies&success=saved');
                    exit();
                } catch (\Exception $e) {
                    $errorMessage = 'Error saving company: ' . $e->getMessage();
                }
            }
        }
    }
    
    // Delete company
    if (isset($_POST['delete_company'])) {
        $company = $entityManager->find(Company::class, $id);
        if ($company) {
            try {
                // Remove the company from the database
                $entityManager->remove($company);
                $entityManager->flush();
                $successMessage = 'Company deleted successfully.';
                
                // Redirect to list view with success message
                header('Location: index.php?route=companies&success=deleted');
                exit();
            } catch (\Exception $e) {
                $errorMessage = 'Error deleting company: ' . $e->getMessage();
            }
        } else {
            $errorMessage = 'Company not found.';
        }
    }
}

// Handle success messages from redirects
if (isset($_GET['success'])) {
    $successMessage = $_GET['success'] === 'saved' ? 'Company saved successfully.' : 'Company deleted successfully.';
}

// Start output buffer to capture the view content
ob_start();

// Display appropriate view based on the action
switch ($action) {
    case 'create':
        // Show the form for creating a new company
        include __DIR__ . '/../View/company/form.php';
        break;
    
    case 'edit':
        // Find the company and show the edit form
        $company = $entityManager->find(Company::class, $id);
        if (!$company) {
            $errorMessage = 'Company not found.';
            include __DIR__ . '/../View/company/list.php';
        } else {
            include __DIR__ . '/../View/company/form.php';
        }
        break;
    
    case 'delete':
        // Find the company and show the delete confirmation
        $company = $entityManager->find(Company::class, $id);
        if (!$company) {
            $errorMessage = 'Company not found.';
            include __DIR__ . '/../View/company/list.php';
        } else {
            include __DIR__ . '/../View/company/delete.php';
        }
        break;
    
    case 'list':
    default:
        // Get search parameters
        $quickSearch = trim($_GET['search'] ?? '');
        $searchName = trim($_GET['search_name'] ?? '');
        $searchEmail = trim($_GET['search_email'] ?? '');
        $searchWebsite = trim($_GET['search_website'] ?? '');
        
        // Pagination logic for companies (5 per page)
        $page = max(1, (int)($_GET['page'] ?? 1));
        $limit = 5;
        $offset = ($page - 1) * $limit;
        
        // Build search query
        $queryBuilder = $entityManager->getRepository(Company::class)->createQueryBuilder('c');
        
        // Quick search - searches all fields
        if (!empty($quickSearch)) {
            $queryBuilder->andWhere('(c.name LIKE :quickSearch OR c.email LIKE :quickSearch OR c.website LIKE :quickSearch)')
                        ->setParameter('quickSearch', '%' . $quickSearch . '%');
        }
        
        // Advanced search conditions (only if no quick search)
        if (empty($quickSearch)) {
            if (!empty($searchName)) {
                $queryBuilder->andWhere('c.name LIKE :name')
                            ->setParameter('name', '%' . $searchName . '%');
            }
            
            if (!empty($searchEmail)) {
                $queryBuilder->andWhere('c.email LIKE :email')
                            ->setParameter('email', '%' . $searchEmail . '%');
            }
            
            if (!empty($searchWebsite)) {
                $queryBuilder->andWhere('c.website LIKE :website')
                            ->setParameter('website', '%' . $searchWebsite . '%');
            }
        }
        
        // Get total count for pagination (with search filters)
        $countQuery = clone $queryBuilder;
        $totalCompanies = $countQuery->select('COUNT(c.id)')->getQuery()->getSingleScalarResult();
        $totalPages = ceil($totalCompanies / $limit);
        
        // Get companies for current page with search filters
        $companies = $queryBuilder->orderBy('c.id', 'ASC')
                                 ->setFirstResult($offset)
                                 ->setMaxResults($limit)
                                 ->getQuery()
                                 ->getResult();
        
        include __DIR__ . '/../View/company/list.php';
        break;
}

// Get the content from the output buffer
$content = ob_get_clean();

// Include the layout template with the captured content
include __DIR__ . '/../View/layout.php'; 