<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Dynamic page title from controller or default to 'Doctrine Dash' -->
    <title><?= $pageTitle ?? 'Doctrine Dash' ?></title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
</head>
<body>
    <!-- Navigation bar with responsive collapsing -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container-fluid">
            <!-- Brand/logo -->
            <a class="navbar-brand" href="index.php">Doctrine Dash</a>
            <!-- Mobile toggle button for responsive design -->
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <!-- Main navigation links -->
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <!-- Active class is applied based on current route -->
                        <a class="nav-link <?= $route === 'dashboard' ? 'active' : '' ?>" href="index.php">Dashboard</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= $route === 'companies' ? 'active' : '' ?>" href="index.php?route=companies">Companies</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= $route === 'employees' ? 'active' : '' ?>" href="index.php?route=employees">Employees</a>
                    </li>
                </ul>
                <!-- User account dropdown on right side -->
                <ul class="navbar-nav ms-auto">
                    <?php if (isset($_SESSION['user_email'])): ?>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown">
                                <?= htmlspecialchars($_SESSION['user_email']) ?>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li><a class="dropdown-item" href="index.php?route=logout">Logout</a></li>
                            </ul>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Main content container -->
    <div class="container mt-4">
        <!-- Success message alert if set -->
        <?php if (isset($successMessage)): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert" id="successAlert">
                <?= $successMessage ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>
        
        <!-- Error message alert if set -->
        <?php if (isset($errorMessage)): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <?= $errorMessage ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>
        
        <!-- Main content from the view -->
        <?= $content ?? '' ?>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Custom JavaScript for auto-hiding success messages -->
    <script>
        // Auto-hide success messages after 5 seconds
        document.addEventListener('DOMContentLoaded', function() {
            const successAlert = document.getElementById('successAlert');
            if (successAlert) {
                setTimeout(function() {
                    const alertInstance = new bootstrap.Alert(successAlert);
                    alertInstance.close();
                }, 5000); // 5 seconds
            }
        });
    </script>
</body>
</html> 