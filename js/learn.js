const resourceModal = document.getElementById('resourceModal');
const resourceModalTitle = document.getElementById('resourceModalTitle');
const resourceIdInput = document.getElementById('resourceId');
const resourceTitleInput = document.getElementById('resourceTitle');
const resourceUrlInput = document.getElementById('resourceUrl');
const resourceDescriptionInput = document.getElementById('resourceDescription');
const resourceCategorySelect = document.getElementById('resourceCategory');
const resourceSubmitBtn = document.getElementById('resourceSubmitBtn');

document.getElementById('addResourceBtn').addEventListener('click', () => {
    resourceModalTitle.textContent = 'Add Resource';
    resourceSubmitBtn.textContent = 'Add Resource';
    resourceIdInput.value = '';
    resourceTitleInput.value = '';
    resourceUrlInput.value = '';
    resourceDescriptionInput.value = '';
    resourceCategorySelect.selectedIndex = 0;
    resourceModal.classList.add('active');
});

document.getElementById('closeResourceModalBtn').addEventListener('click', () => {
    resourceModal.classList.remove('active');
});

document.querySelectorAll('.resource-edit-btn').forEach(btn => {
    btn.addEventListener('click', () => {
        resourceModalTitle.textContent = 'Edit Resource';
        resourceSubmitBtn.textContent = 'Save Changes';
        resourceIdInput.value = btn.dataset.id;
        resourceTitleInput.value = btn.dataset.title;
        resourceUrlInput.value = btn.dataset.url;
        resourceDescriptionInput.value = btn.dataset.description;
        resourceCategorySelect.value = btn.dataset.category;
        resourceModal.classList.add('active');
    });
});

document.querySelectorAll('.resource-delete-form').forEach(form => {
    form.addEventListener('submit', (e) => {
        if (!confirm('Delete this resource?')) {
            e.preventDefault();
        }
    });
});