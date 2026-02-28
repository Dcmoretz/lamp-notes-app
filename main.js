// ─── Load & Render All Notes ──────────────────────────────────────────────────
async function loadNotes() {
    const container = document.getElementById('notes-container');
    if (!container) return;

    try {
        const res  = await fetch('../api/read.php');
        const data = await res.json();

        if (data.status !== 'success') {
            container.innerHTML = `<div class="col-12"><div class="alert alert-danger">Failed to load notes: ${data.message}</div></div>`;
            return;
        }

        const notes = data.data;

        if (notes.length === 0) {
            container.innerHTML = `
                <div class="col-12">
                    <div class="empty-state">
                        <i class="bi bi-journal-x"></i>
                        <h5>No notes yet</h5>
                        <p>Click <strong>New Note</strong> to create your first one.</p>
                    </div>
                </div>`;
            return;
        }

        container.innerHTML = notes.map(note => `
            <div class="col-sm-6 col-lg-4">
                <div class="card note-card h-100">
                    <div class="card-body d-flex flex-column">
                        <h6 class="card-title" title="${escapeHtml(note.title)}">${escapeHtml(note.title)}</h6>
                        <p class="card-text flex-grow-1">${escapeHtml(note.content)}</p>
                        <div class="d-flex justify-content-between align-items-center mt-3">
                            <span class="note-date">
                                <i class="bi bi-clock me-1"></i>${formatDate(note.created_at)}
                            </span>
                            <div class="d-flex gap-1">
                                <a href="edit.php?id=${note.id}" class="btn btn-sm btn-outline-primary" title="Edit">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <a href="delete.php?id=${note.id}" class="btn btn-sm btn-outline-danger" title="Delete">
                                    <i class="bi bi-trash"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        `).join('');

    } catch (err) {
        container.innerHTML = `
            <div class="col-12">
                <div class="alert alert-danger">
                    <strong>Network error.</strong> Could not reach the API. Is Apache running?
                </div>
            </div>`;
    }
}

// ─── Show Alert Banner ─────────────────────────────────────────────────────────
function showAlert(message, type = 'info') {
    const box = document.getElementById('alert-box');
    if (!box) return;
    box.innerHTML = `
        <div class="alert alert-${type} alert-dismissible fade show" role="alert">
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>`;
}

// ─── Escape HTML to prevent XSS ───────────────────────────────────────────────
function escapeHtml(str) {
    if (!str) return '';
    return str
        .replace(/&/g, '&amp;')
        .replace(/</g, '&lt;')
        .replace(/>/g, '&gt;')
        .replace(/"/g, '&quot;')
        .replace(/'/g, '&#039;');
}

// ─── Format timestamp to readable date ────────────────────────────────────────
function formatDate(dateStr) {
    if (!dateStr) return '';
    const date = new Date(dateStr);
    return date.toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' });
}
