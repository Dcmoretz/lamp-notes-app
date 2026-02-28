<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>New Note — LAMP Notes</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>

<nav class="navbar navbar-dark bg-dark px-4">
    <a href="index.php" class="navbar-brand fw-bold"><i class="bi bi-journal-text me-2"></i>LAMP Notes</a>
</nav>

<div class="container mt-4" style="max-width: 700px;">
    <h4 class="mb-4">Create New Note</h4>

    <div id="alert-box"></div>

    <form id="create-form">
        <div class="mb-3">
            <label for="title" class="form-label fw-semibold">Title</label>
            <input type="text" class="form-control" id="title" maxlength="255" placeholder="Note title..." required>
            <div class="form-text text-end"><span id="title-count">0</span>/255</div>
        </div>
        <div class="mb-3">
            <label for="content" class="form-label fw-semibold">Content</label>
            <textarea class="form-control" id="content" rows="8" placeholder="Write your note here..." required></textarea>
        </div>
        <div class="d-flex gap-2">
            <button type="submit" class="btn btn-primary">
                <i class="bi bi-save me-1"></i>Save Note
            </button>
            <a href="index.php" class="btn btn-outline-secondary">Cancel</a>
        </div>
    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="js/main.js"></script>
<script>
    document.getElementById('title').addEventListener('input', function () {
        document.getElementById('title-count').textContent = this.value.length;
    });

    document.getElementById('create-form').addEventListener('submit', async function (e) {
        e.preventDefault();
        const title   = document.getElementById('title').value.trim();
        const content = document.getElementById('content').value.trim();
        if (!title || !content) {
            showAlert('Title and content are both required.', 'warning');
            return;
        }
        try {
            const res  = await fetch('../api/create.php', {
                method:  'POST',
                headers: { 'Content-Type': 'application/json' },
                body:    JSON.stringify({ title, content })
            });
            const data = await res.json();
            if (data.status === 'success') {
                window.location.href = 'index.php';
            } else {
                showAlert(data.message || 'Failed to create note.', 'danger');
            }
        } catch (err) {
            showAlert('Network error. Is the server running?', 'danger');
        }
    });
</script>
</body>
</html>
