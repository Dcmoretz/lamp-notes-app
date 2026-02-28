<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Note — LAMP Notes</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>

<nav class="navbar navbar-dark bg-dark px-4">
    <a href="index.php" class="navbar-brand fw-bold"><i class="bi bi-journal-text me-2"></i>LAMP Notes</a>
</nav>

<div class="container mt-4" style="max-width: 700px;">
    <h4 class="mb-4">Edit Note</h4>

    <div id="alert-box"></div>

    <form id="edit-form">
        <input type="hidden" id="note-id">
        <div class="mb-3">
            <label for="title" class="form-label fw-semibold">Title</label>
            <input type="text" class="form-control" id="title" maxlength="255" required>
            <div class="form-text text-end"><span id="title-count">0</span>/255</div>
        </div>
        <div class="mb-3">
            <label for="content" class="form-label fw-semibold">Content</label>
            <textarea class="form-control" id="content" rows="8" required></textarea>
        </div>
        <div class="d-flex gap-2">
            <button type="submit" class="btn btn-success">
                <i class="bi bi-check-lg me-1"></i>Update Note
            </button>
            <a href="index.php" class="btn btn-outline-secondary">Cancel</a>
        </div>
    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="js/main.js"></script>
<script>
    // Get note ID from URL: edit.php?id=5
    const params = new URLSearchParams(window.location.search);
    const noteId = parseInt(params.get('id'));

    if (!noteId || noteId <= 0) {
        showAlert('Invalid note ID. <a href="index.php">Go back</a>', 'danger');
    } else {
        // Load existing note data
        fetch(`../api/read.php?id=${noteId}`)
            .then(res => res.json())
            .then(data => {
                if (data.status === 'success') {
                    document.getElementById('note-id').value       = data.data.id;
                    document.getElementById('title').value         = data.data.title;
                    document.getElementById('content').value       = data.data.content;
                    document.getElementById('title-count').textContent = data.data.title.length;
                } else {
                    showAlert('Note not found. <a href="index.php">Go back</a>', 'danger');
                }
            })
            .catch(() => showAlert('Failed to load note.', 'danger'));
    }

    document.getElementById('title').addEventListener('input', function () {
        document.getElementById('title-count').textContent = this.value.length;
    });

    document.getElementById('edit-form').addEventListener('submit', async function (e) {
        e.preventDefault();

        const id      = document.getElementById('note-id').value;
        const title   = document.getElementById('title').value.trim();
        const content = document.getElementById('content').value.trim();

        if (!title || !content) {
            showAlert('Title and content are both required.', 'warning');
            return;
        }

        try {
            const res  = await fetch('../api/update.php', {
                method:  'PUT',
                headers: { 'Content-Type': 'application/json' },
                body:    JSON.stringify({ id: parseInt(id), title, content })
            });
            const data = await res.json();

            if (data.status === 'success') {
                window.location.href = 'index.php';
            } else {
                showAlert(data.message || 'Failed to update note.', 'danger');
            }
        } catch (err) {
            showAlert('Network error. Is the server running?', 'danger');
        }
    });
</script>
</body>
</html>
