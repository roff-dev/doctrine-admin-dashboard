<?php
/**
 * Employee Controller
 * 
 * This controller handles all CRUD operations for employees:
 * - Listing all employees
 * - Creating new employees
 * - Editing existing employees
 * - Deleting employees
 * - Managing employee-company relationships
 */

use App\Entity\Employee;
use App\Entity\Company;

// Set page title and header for the view
$pageTitle = 'Manage Employees';
$pageHeader = 'Employees';

// Get action and ID parameters from the URL
$action = $_GET['action'] ?? 'list';
$id = $_GET['id'] ?? null;

// Process form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Create or update employee
    if (isset($_POST['save_employee'])) {
        $firstName = $_POST['first_name'] ?? '';
        $lastName = $_POST['last_name'] ?? '';
        $email = $_POST['email'] ?? '';
        $phone = $_POST['phone'] ?? '';
        $companyId = $_POST['company_id'] ?? null;
        
        // Validate required fields (first name and last name are required)
        if (empty($firstName) || empty($lastName)) {
            $errorMessage = 'First name and last name are required.';
        } else {
            // Get existing employee or create new one
            if ($id) {
                $employee = $entityManager->find(Employee::class, $id);
                if (!$employee) {
                    $errorMessage = 'Employee not found.';
                }
            } else {
                $employee = new Employee();
            }
            
            // Update employee data with form values
            $employee->setFirstName($firstName);
            $employee->setLastName($lastName);
            $employee->setEmail($email ?: null);
            $employee->setPhone($phone ?: null);
            
            // Set company relationship if a company was selected
            if ($companyId) {
                $company = $entityManager->find(Company::class, $companyId);
                if ($company) {
                    $employee->setCompany($company);
                }
            } else {
                // Clear company relationship if no company was selected
                $employee->setCompany(null);
            }
            
            // Save to database
            try {
                $entityManager->persist($employee);
                $entityManager->flush();
                $successMessage = 'Employee saved successfully.';
                
                // Redirect to list view with success message
                header('Location: index.php?route=employees&success=saved');
                exit();
            } catch (\Exception $e) {
                $errorMessage = 'Error saving employee: ' . $e->getMessage();
            }
        }
    }
    
    // Delete employee
    if (isset($_POST['delete_employee'])) {
        $employee = $entityManager->find(Employee::class, $id);
        if ($employee) {
            try {
                // Remove the employee from the database
                $entityManager->remove($employee);
                $entityManager->flush();
                $successMessage = 'Employee deleted successfully.';
                
                // Redirect to list view with success message
                header('Location: index.php?route=employees&success=deleted');
                exit();
            } catch (\Exception $e) {
                $errorMessage = 'Error deleting employee: ' . $e->getMessage();
            }
        } else {
            $errorMessage = 'Employee not found.';
        }
    }
}

// Handle success messages from redirects
if (isset($_GET['success'])) {
    $successMessage = $_GET['success'] === 'saved' ? 'Employee saved successfully.' : 'Employee deleted successfully.';
}

// Get all companies for the company dropdown in the employee form
$companies = $entityManager->getRepository(Company::class)->findAll();

// Start output buffer to capture the view content
ob_start();

// Display appropriate view based on the action
switch ($action) {
    case 'create':
        // Show the form for creating a new employee
        include __DIR__ . '/../View/employee/form.php';
        break;
    
    case 'edit':
        // Find the employee and show the edit form
        $employee = $entityManager->find(Employee::class, $id);
        if (!$employee) {
            $errorMessage = 'Employee not found.';
            include __DIR__ . '/../View/employee/list.php';
        } else {
            include __DIR__ . '/../View/employee/form.php';
        }
        break;
    
    case 'delete':
        // Find the employee and show the delete confirmation
        $employee = $entityManager->find(Employee::class, $id);
        if (!$employee) {
            $errorMessage = 'Employee not found.';
            include __DIR__ . '/../View/employee/list.php';
        } else {
            include __DIR__ . '/../View/employee/delete.php';
        }
        break;
    
    case 'list':
    default:
        // Get search parameters
        $quickSearch = trim($_GET['search'] ?? '');
        $searchName = trim($_GET['search_name'] ?? '');
        $searchEmail = trim($_GET['search_email'] ?? '');
        $searchPhone = trim($_GET['search_phone'] ?? '');
        $searchCompany = trim($_GET['search_company'] ?? '');
        
        // Pagination logic for employees (10 per page)
        $page = max(1, (int)($_GET['page'] ?? 1));
        $limit = 10;
        $offset = ($page - 1) * $limit;
        
        // Build search query with company join
        $queryBuilder = $entityManager->getRepository(Employee::class)->createQueryBuilder('e')
                                     ->leftJoin('e.company', 'c');
        
        // Quick search - searches all relevant fields
        if (!empty($quickSearch)) {
            $queryBuilder->andWhere('(e.first_name LIKE :quickSearch OR e.last_name LIKE :quickSearch OR e.email LIKE :quickSearch OR e.phone LIKE :quickSearch OR c.name LIKE :quickSearch)')
                        ->setParameter('quickSearch', '%' . $quickSearch . '%');
        }
        
        // Advanced search conditions (only if no quick search)
        if (empty($quickSearch)) {
            if (!empty($searchName)) {
                $queryBuilder->andWhere('(e.first_name LIKE :name OR e.last_name LIKE :name)')
                            ->setParameter('name', '%' . $searchName . '%');
            }
            
            if (!empty($searchEmail)) {
                $queryBuilder->andWhere('e.email LIKE :email')
                            ->setParameter('email', '%' . $searchEmail . '%');
            }
            
            if (!empty($searchPhone)) {
                $queryBuilder->andWhere('e.phone LIKE :phone')
                            ->setParameter('phone', '%' . $searchPhone . '%');
            }
            
            if (!empty($searchCompany)) {
                $queryBuilder->andWhere('c.name LIKE :company')
                            ->setParameter('company', '%' . $searchCompany . '%');
            }
        }
        
        // Get total count for pagination (with search filters)
        $countQuery = clone $queryBuilder;
        $totalEmployees = $countQuery->select('COUNT(e.id)')->getQuery()->getSingleScalarResult();
        $totalPages = ceil($totalEmployees / $limit);
        
        // Get employees for current page with search filters
        $employees = $queryBuilder->orderBy('e.id', 'ASC')
                                 ->setFirstResult($offset)
                                 ->setMaxResults($limit)
                                 ->getQuery()
                                 ->getResult();
        
        include __DIR__ . '/../View/employee/list.php';
        break;
}

// Get the content from the output buffer
$content = ob_get_clean();

// Include the layout template with the captured content
include __DIR__ . '/../View/layout.php'; 