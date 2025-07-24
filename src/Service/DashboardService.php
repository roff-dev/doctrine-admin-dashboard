<?php
/**
 * Dashboard Service
 * 
 * Generates fake data for dashboard charts and metrics.
 * All data uses UK formatting (pounds, UK date format, etc.)
 */
namespace App\Service;

use DateTime;

class DashboardService
{
    /**
     * Generate monthly revenue data for the last 12 months
     * 
     * @return array
     */
    public function getMonthlyRevenue(): array
    {
        $months = [];
        $revenue = [];
        
        // Generate last 12 months
        for ($i = 11; $i >= 0; $i--) {
            $date = new DateTime();
            $date->modify("-{$i} months");
            $months[] = $date->format('M Y'); // UK format: Jan 2024
            
            // Generate realistic revenue between £15,000 - £85,000
            $baseRevenue = 45000;
            $variation = mt_rand(-30000, 40000);
            $revenue[] = max(15000, $baseRevenue + $variation);
        }
        
        return [
            'labels' => $months,
            'data' => $revenue
        ];
    }
    
    /**
     * Generate employee count by company data
     * 
     * @param array $companies
     * @return array
     */
    public function getEmployeesByCompany(array $companies): array
    {
        $companyNames = [];
        $employeeCounts = [];
        $colors = [
            '#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0', 
            '#9966FF', '#FF9F40', '#FF6384', '#C9CBCF'
        ];
        
        foreach ($companies as $index => $company) {
            $companyNames[] = $company->getName();
            // Generate random employee count between 5-50
            $employeeCounts[] = mt_rand(5, 50);
        }
        
        // Add some fake companies if we don't have enough
        if (count($companies) < 6) {
            $fakeCompanies = [
                'Tesco plc', 'British Airways', 'Rolls-Royce', 
                'Vodafone UK', 'Marks & Spencer', 'John Lewis'
            ];
            
            $needed = 6 - count($companies);
            for ($i = 0; $i < $needed; $i++) {
                if (isset($fakeCompanies[$i])) {
                    $companyNames[] = $fakeCompanies[$i];
                    $employeeCounts[] = mt_rand(8, 45);
                }
            }
        }
        
        return [
            'labels' => $companyNames,
            'data' => $employeeCounts,
            'colors' => array_slice($colors, 0, count($companyNames))
        ];
    }
    
    /**
     * Generate hiring trends for the last 6 months
     * 
     * @return array
     */
    public function getHiringTrends(): array
    {
        $months = [];
        $hired = [];
        $departed = [];
        
        for ($i = 5; $i >= 0; $i--) {
            $date = new DateTime();
            $date->modify("-{$i} months");
            $months[] = $date->format('M Y');
            
            // Generate realistic hiring/departure numbers
            $hired[] = mt_rand(3, 15);
            $departed[] = mt_rand(1, 8);
        }
        
        return [
            'labels' => $months,
            'hired' => $hired,
            'departed' => $departed
        ];
    }
    
    /**
     * Generate department distribution data
     * 
     * @return array
     */
    public function getDepartmentDistribution(): array
    {
        $departments = [
            'Engineering' => mt_rand(25, 45),
            'Sales & Marketing' => mt_rand(15, 30),
            'Human Resources' => mt_rand(5, 15),
            'Finance' => mt_rand(8, 20),
            'Operations' => mt_rand(10, 25),
            'Customer Service' => mt_rand(12, 28)
        ];
        
        return [
            'labels' => array_keys($departments),
            'data' => array_values($departments),
            'colors' => ['#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0', '#9966FF', '#FF9F40']
        ];
    }
    
    /**
     * Generate quarterly performance metrics
     * 
     * @return array
     */
    public function getQuarterlyPerformance(): array
    {
        $quarters = ['Q1 2024', 'Q2 2024', 'Q3 2024', 'Q4 2024'];
        $performance = [];
        
        foreach ($quarters as $quarter) {
            $performance[] = mt_rand(65, 95); // Performance percentage
        }
        
        return [
            'labels' => $quarters,
            'data' => $performance
        ];
    }
    
    /**
     * Generate KPI summary data
     * 
     * @param int $totalCompanies
     * @param int $totalEmployees
     * @return array
     */
    public function getKPISummary(int $totalCompanies, int $totalEmployees): array
    {
        return [
            'total_revenue' => mt_rand(450000, 850000), // Annual revenue in pounds
            'avg_salary' => mt_rand(35000, 65000), // Average salary in pounds
            'employee_satisfaction' => mt_rand(78, 94), // Percentage
            'retention_rate' => mt_rand(85, 96), // Percentage
            'total_companies' => $totalCompanies,
            'total_employees' => $totalEmployees
        ];
    }
    
    /**
     * Format currency in British pounds
     * 
     * @param int $amount
     * @return string
     */
    public function formatCurrency(int $amount): string
    {
        return '£' . number_format($amount, 0, '.', ',');
    }
    
    /**
     * Format percentage
     * 
     * @param int $percentage
     * @return string
     */
    public function formatPercentage(int $percentage): string
    {
        return $percentage . '%';
    }
} 