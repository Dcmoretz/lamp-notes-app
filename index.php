<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LAMP Notes App</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>

<nav class="navbar navbar-dark bg-dark px-4">
    <span class="navbar-brand fw-bold"><i class="bi bi-journal-text me-2"></i>LAMP Notes</span>
    <a href="create.php" class="btn btn-primary btn-sm">
        <i class="bi bi-plus-lg me-1"></i>New Note
    </a>
</nav>

<div class="container mt-4">

    <div id="alert-box"></div>

    <div id="notes-container" class="row g-3">
        <div class="col-12 text-center text-muted py-5">
            <div class="spinner-border" role="status"></div>
            <p class="mt-2">Loading notes...</p>
        </div>
    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="js/main.js"></script>
<script>
    loadNotes();
</script>
</body>
</html>
