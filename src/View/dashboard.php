<?php
/**
 * Dashboard View
 * 
 * This is the main dashboard page that displays summary information about
 * companies and employees with links to manage them, plus analytics charts.
 */

use App\Service\DashboardService;
use App\Entity\Company;

// Set page title and header
$pageTitle = 'Doctrine Dash';
$pageHeader = 'Dashboard';

// Get counts from database for display in dashboard widgets
$companyCount = $entityManager->getRepository(\App\Entity\Company::class)->count([]);
$employeeCount = $entityManager->getRepository(\App\Entity\Employee::class)->count([]);

// Get companies for chart data
$companies = $entityManager->getRepository(Company::class)->findAll();

// Initialize dashboard service and get chart data
$dashboardService = new DashboardService();
$monthlyRevenue = $dashboardService->getMonthlyRevenue();
$employeesByCompany = $dashboardService->getEmployeesByCompany($companies);
$hiringTrends = $dashboardService->getHiringTrends();
$departmentDistribution = $dashboardService->getDepartmentDistribution();
$quarterlyPerformance = $dashboardService->getQuarterlyPerformance();
$kpiData = $dashboardService->getKPISummary($companyCount, $employeeCount);

// Start output buffer to capture the view content
ob_start();
?>
<h1 class="mb-4"><?= $pageHeader ?></h1>

<!-- KPI Summary Cards -->
<div class="row mb-4">
    <div class="col-md-2 col-sm-4 col-6 mb-3">
        <div class="card text-center h-100 border-primary">
            <div class="card-body">
                <h6 class="card-title text-muted">Companies</h6>
                <h4 class="text-primary"><?= $kpiData['total_companies'] ?></h4>
                <a href="index.php?route=companies" class="btn btn-sm btn-outline-primary">Manage</a>
            </div>
        </div>
    </div>
    <div class="col-md-2 col-sm-4 col-6 mb-3">
        <div class="card text-center h-100 border-success">
            <div class="card-body">
                <h6 class="card-title text-muted">Employees</h6>
                <h4 class="text-success"><?= $kpiData['total_employees'] ?></h4>
                <a href="index.php?route=employees" class="btn btn-sm btn-outline-success">Manage</a>
            </div>
        </div>
    </div>
    <div class="col-md-2 col-sm-4 col-6 mb-3">
        <div class="card text-center h-100 border-info">
            <div class="card-body">
                <h6 class="card-title text-muted">Annual Revenue</h6>
                <h4 class="text-info"><?= $dashboardService->formatCurrency($kpiData['total_revenue']) ?></h4>
                <small class="text-muted">Projected</small>
            </div>
        </div>
    </div>
    <div class="col-md-2 col-sm-4 col-6 mb-3">
        <div class="card text-center h-100 border-warning">
            <div class="card-body">
                <h6 class="card-title text-muted">Avg. Salary</h6>
                <h4 class="text-warning"><?= $dashboardService->formatCurrency($kpiData['avg_salary']) ?></h4>
                <small class="text-muted">Per annum</small>
            </div>
        </div>
    </div>
    <div class="col-md-2 col-sm-4 col-6 mb-3">
        <div class="card text-center h-100 border-danger">
            <div class="card-body">
                <h6 class="card-title text-muted">Satisfaction</h6>
                <h4 class="text-danger"><?= $dashboardService->formatPercentage($kpiData['employee_satisfaction']) ?></h4>
                <small class="text-muted">Employee</small>
            </div>
        </div>
    </div>
    <div class="col-md-2 col-sm-4 col-6 mb-3">
        <div class="card text-center h-100 border-dark">
            <div class="card-body">
                <h6 class="card-title text-muted">Retention</h6>
                <h4 class="text-dark"><?= $dashboardService->formatPercentage($kpiData['retention_rate']) ?></h4>
                <small class="text-muted">Rate</small>
            </div>
        </div>
    </div>
</div>

<!-- Charts Section -->
<div class="row">
    <!-- Monthly Revenue Chart -->
    <div class="col-lg-8 col-md-12 mb-4">
        <div class="card h-100">
            <div class="card-header">
                <h5 class="mb-0"><i class="bi bi-graph-up"></i> Monthly Revenue Trends</h5>
            </div>
            <div class="card-body">
                <canvas id="revenueChart" height="100"></canvas>
            </div>
        </div>
    </div>
    
    <!-- Employees by Company Chart -->
    <div class="col-lg-4 col-md-6 mb-4">
        <div class="card h-100">
            <div class="card-header">
                <h5 class="mb-0"><i class="bi bi-people"></i> Employees by Company</h5>
                <small class="text-muted">Workforce distribution</small>
            </div>
            <div class="card-body d-flex align-items-center justify-content-center">
                <canvas id="employeesChart" style="max-height: 280px;"></canvas>
            </div>
        </div>
    </div>
    
    <!-- Hiring Trends Chart -->
    <div class="col-lg-6 col-md-6 mb-4">
        <div class="card h-100">
            <div class="card-header">
                <h5 class="mb-0"><i class="bi bi-person-plus"></i> Hiring Trends</h5>
                <small class="text-muted">Monthly hiring vs departures</small>
            </div>
            <div class="card-body d-flex align-items-center justify-content-center">
                <canvas id="hiringChart" style="max-height: 300px;"></canvas>
            </div>
        </div>
    </div>
    
    <!-- Department Distribution Chart -->
    <div class="col-lg-6 col-md-6 mb-4">
        <div class="card h-100">
            <div class="card-header">
                <h5 class="mb-0"><i class="bi bi-diagram-3"></i> Department Distribution</h5>
                <small class="text-muted">Employee count by department</small>
            </div>
            <div class="card-body d-flex align-items-center justify-content-center">
                <canvas id="departmentChart" style="max-height: 300px;"></canvas>
            </div>
        </div>
    </div>
    
    <!-- Quarterly Performance Chart -->
    <div class="col-12 mb-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="bi bi-bar-chart"></i> Company Performance Scores</h5>
                <small class="text-muted">Overall business performance by quarter (target: 80%+)</small>
            </div>
            <div class="card-body" style="height: 400px;">
                <canvas id="performanceChart"></canvas>
            </div>
        </div>
    </div>
</div>

<!-- Chart.js Configuration Script -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Chart.js default configuration
    Chart.defaults.responsive = true;
    Chart.defaults.maintainAspectRatio = false;
    Chart.defaults.plugins.legend.display = true;
    
    // Monthly Revenue Line Chart
    const revenueCtx = document.getElementById('revenueChart').getContext('2d');
    new Chart(revenueCtx, {
        type: 'line',
        data: {
            labels: <?= json_encode($monthlyRevenue['labels']) ?>,
            datasets: [{
                label: 'Monthly Revenue (£)',
                data: <?= json_encode($monthlyRevenue['data']) ?>,
                borderColor: '#007bff',
                backgroundColor: 'rgba(0, 123, 255, 0.1)',
                borderWidth: 3,
                fill: true,
                tension: 0.4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: false,
                    ticks: {
                        callback: function(value) {
                            return '£' + value.toLocaleString();
                        }
                    }
                }
            },
            plugins: {
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            return 'Revenue: £' + context.parsed.y.toLocaleString();
                        }
                    }
                }
            }
        }
    });
    
    // Employees by Company Doughnut Chart
    const employeesCtx = document.getElementById('employeesChart').getContext('2d');
    new Chart(employeesCtx, {
        type: 'doughnut',
        data: {
            labels: <?= json_encode($employeesByCompany['labels']) ?>,
            datasets: [{
                data: <?= json_encode($employeesByCompany['data']) ?>,
                backgroundColor: <?= json_encode($employeesByCompany['colors']) ?>,
                borderWidth: 2,
                borderColor: '#fff',
                hoverOffset: 4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            layout: {
                padding: 20
            },
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        padding: 15,
                        usePointStyle: true,
                        font: {
                            size: 11
                        }
                    }
                }
            }
        }
    });
    
    // Hiring Trends Bar Chart
    const hiringCtx = document.getElementById('hiringChart').getContext('2d');
    new Chart(hiringCtx, {
        type: 'bar',
        data: {
            labels: <?= json_encode($hiringTrends['labels']) ?>,
            datasets: [{
                label: 'Hired',
                data: <?= json_encode($hiringTrends['hired']) ?>,
                backgroundColor: '#28a745',
                borderRadius: 5,
                borderWidth: 1,
                borderColor: '#1e7e34'
            }, {
                label: 'Departed',
                data: <?= json_encode($hiringTrends['departed']) ?>,
                backgroundColor: '#dc3545',
                borderRadius: 5,
                borderWidth: 1,
                borderColor: '#bd2130'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            layout: {
                padding: {
                    top: 10,
                    bottom: 10
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    max: Math.max(...<?= json_encode($hiringTrends['hired']) ?>, ...<?= json_encode($hiringTrends['departed']) ?>) + 2,
                    ticks: {
                        stepSize: 2,
                        font: {
                            size: 12
                        }
                    },
                    grid: {
                        color: '#e9ecef'
                    }
                },
                x: {
                    ticks: {
                        font: {
                            size: 11
                        }
                    },
                    grid: {
                        display: false
                    }
                }
            },
            plugins: {
                legend: {
                    position: 'top',
                    labels: {
                        usePointStyle: true,
                        padding: 15,
                        font: {
                            size: 12
                        }
                    }
                }
            }
        }
    });
    
    // Department Distribution Pie Chart
    const departmentCtx = document.getElementById('departmentChart').getContext('2d');
    new Chart(departmentCtx, {
        type: 'pie',
        data: {
            labels: <?= json_encode($departmentDistribution['labels']) ?>,
            datasets: [{
                data: <?= json_encode($departmentDistribution['data']) ?>,
                backgroundColor: <?= json_encode($departmentDistribution['colors']) ?>,
                borderWidth: 2,
                borderColor: '#fff',
                hoverOffset: 4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            layout: {
                padding: 20
            },
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        padding: 15,
                        usePointStyle: true,
                        font: {
                            size: 11
                        }
                    }
                }
            }
        }
    });
    
    // Quarterly Performance Bar Chart
    const performanceCtx = document.getElementById('performanceChart').getContext('2d');
    new Chart(performanceCtx, {
        type: 'bar',
        data: {
            labels: <?= json_encode($quarterlyPerformance['labels']) ?>,
            datasets: [{
                label: 'Performance Score',
                data: <?= json_encode($quarterlyPerformance['data']) ?>,
                backgroundColor: function(context) {
                    const value = context.parsed.y;
                    if (value >= 90) return '#28a745'; // Excellent - Green
                    if (value >= 80) return '#ffc107'; // Good - Yellow
                    if (value >= 70) return '#fd7e14'; // Fair - Orange
                    return '#dc3545'; // Poor - Red
                },
                borderRadius: 8,
                borderSkipped: false,
                borderWidth: 1,
                borderColor: function(context) {
                    const value = context.parsed.y;
                    if (value >= 90) return '#1e7e34';
                    if (value >= 80) return '#e0a800';
                    if (value >= 70) return '#dc6502';
                    return '#bd2130';
                }
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            layout: {
                padding: {
                    top: 20,
                    left: 20,
                    right: 20,
                    bottom: 20
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    max: 100,
                    ticks: {
                        callback: function(value) {
                            return value + '%';
                        },
                        stepSize: 10,
                        font: {
                            size: 12
                        }
                    },
                    grid: {
                        color: '#e9ecef'
                    }
                },
                x: {
                    ticks: {
                        font: {
                            size: 12
                        }
                    },
                    grid: {
                        display: false
                    }
                }
            },
            plugins: {
                legend: {
                    display: false
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            const value = context.parsed.y;
                            let status = '';
                            if (value >= 90) status = ' (Excellent)';
                            else if (value >= 80) status = ' (Good)';
                            else if (value >= 70) status = ' (Fair)';
                            else status = ' (Needs Improvement)';
                            return 'Performance: ' + value + '%' + status;
                        }
                    }
                }
            }
        }
    });
});
</script>

<?php
// Get the content from the output buffer
$content = ob_get_clean();

// Include the layout template with the captured content
include __DIR__ . '/layout.php'; 