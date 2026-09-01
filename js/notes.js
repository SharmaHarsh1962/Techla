const noteModal = document.getElementById('noteModal');
const noteModalTitle = document.getElementById('noteModalTitle');
const noteForm = document.getElementById('noteForm');
const noteIdInput = document.getElementById('noteId');
const noteTitleInput = document.getElementById('noteTitle');
const noteContentInput = document.getElementById('noteContent');
const noteSubmitBtn = document.getElementById('noteSubmitBtn');

document.getElementById('addNoteBtn').addEventListener('click', () => {
    noteModalTitle.textContent = 'Add New Note';
    noteSubmitBtn.textContent = 'Add Note';
    noteIdInput.value = '';
    noteTitleInput.value = '';
    noteContentInput.value = '';
    noteModal.classList.add('active');
});

document.getElementById('closeNoteModalBtn').addEventListener('click', () => {
    noteModal.classList.remove('active');
});

document.querySelectorAll('.note-edit-btn').forEach(btn => {
    btn.addEventListener('click', () => {
        const card = btn.closest('.note-card');
        noteModalTitle.textContent = 'Edit Note';
        noteSubmitBtn.textContent = 'Save Changes';
        noteIdInput.value = card.dataset.id;
        noteTitleInput.value = card.dataset.title;
        noteContentInput.value = card.dataset.content;
        noteModal.classList.add('active');
    });
});

document.querySelectorAll('.note-delete-form').forEach(form => {
    form.addEventListener('submit', (e) => {
        if (!confirm('Delete this note? This cannot be undone.')) {
            e.preventDefault();
        }
    });
});