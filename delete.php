<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Delete Note — LAMP Notes</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>

<nav class="navbar navbar-dark bg-dark px-4">
    <a href="index.php" class="navbar-brand fw-bold"><i class="bi bi-journal-text me-2"></i>LAMP Notes</a>
</nav>

<div class="container mt-4" style="max-width: 600px;">

    <div id="alert-box"></div>

    <div id="confirm-card" class="card border-danger">
        <div class="card-body text-center py-5">
            <i class="bi bi-trash3 text-danger" style="font-size: 3rem;"></i>
            <h5 class="mt-3">Delete this note?</h5>
            <p class="text-muted" id="note-title-preview">Loading...</p>
            <p class="text-muted small">This action cannot be undone.</p>
            <div class="d-flex justify-content-center gap-3 mt-4">
                <button id="confirm-delete" class="btn btn-danger">
                    <i class="bi bi-trash me-1"></i>Yes, Delete
                </button>
                <a href="index.php" class="btn btn-outline-secondary">Cancel</a>
            </div>
        </div>
    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="js/main.js"></script>
<script>
    const params = new URLSearchParams(window.location.search);
    const noteId = parseInt(params.get('id'));

    if (!noteId || noteId <= 0) {
        showAlert('Invalid note ID. <a href="index.php">Go back</a>', 'danger');
        document.getElementById('confirm-card').style.display = 'none';
    } else {
        // Load note title for confirmation display
        fetch(`../api/read.php?id=${noteId}`)
            .then(res => res.json())
            .then(data => {
                if (data.status === 'success') {
                    document.getElementById('note-title-preview').textContent = `"${data.data.title}"`;
                } else {
                    showAlert('Note not found. <a href="index.php">Go back</a>', 'danger');
                    document.getElementById('confirm-card').style.display = 'none';
                }
            })
            .catch(() => showAlert('Failed to load note.', 'danger'));
    }

    document.getElementById('confirm-delete').addEventListener('click', async function () {
        this.disabled = true;
        this.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span>Deleting...';

        try {
            const res  = await fetch(`../api/delete.php?id=${noteId}`, { method: 'DELETE' });
            const data = await res.json();

            if (data.status === 'success') {
                window.location.href = 'index.php';
            } else {
                showAlert(data.message || 'Failed to delete note.', 'danger');
                this.disabled = false;
                this.innerHTML = '<i class="bi bi-trash me-1"></i>Yes, Delete';
            }
        } catch (err) {
            showAlert('Network error. Is the server running?', 'danger');
            this.disabled = false;
        }
    });
</script>
</body>
</html>
