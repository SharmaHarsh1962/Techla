const promptModal = document.getElementById('promptModal');
const promptModalTitle = document.getElementById('promptModalTitle');
const promptIdInput = document.getElementById('promptId');
const promptModelSelect = document.getElementById('promptModel');
const promptCategorySelect = document.getElementById('promptCategory');
const promptTitleInput = document.getElementById('promptTitle');
const promptTextInput = document.getElementById('promptText');
const promptSubmitBtn = document.getElementById('promptSubmitBtn');

document.getElementById('addPromptBtn').addEventListener('click', () => {
    promptModalTitle.textContent = 'Add Prompt';
    promptSubmitBtn.textContent = 'Add Prompt';
    promptIdInput.value = '';
    promptModelSelect.selectedIndex = 0;
    promptCategorySelect.selectedIndex = 0;
    promptTitleInput.value = '';
    promptTextInput.value = '';
    promptModal.classList.add('active');
});

document.getElementById('closePromptModalBtn').addEventListener('click', () => {
    promptModal.classList.remove('active');
});

document.querySelectorAll('.prompt-edit-btn').forEach(btn => {
    btn.addEventListener('click', () => {
        promptModalTitle.textContent = 'Edit Prompt';
        promptSubmitBtn.textContent = 'Save Changes';
        promptIdInput.value = btn.dataset.id;
        promptModelSelect.value = btn.dataset.model;
        promptCategorySelect.value = btn.dataset.category;
        promptTitleInput.value = btn.dataset.title;
        promptTextInput.value = btn.dataset.text;
        promptModal.classList.add('active');
    });
});

const websiteModal = document.getElementById('websiteModal');
const websiteModalTitle = document.getElementById('websiteModalTitle');
const websiteIdInput = document.getElementById('websiteId');
const websiteTitleInput = document.getElementById('websiteTitle');
const websiteUrlInput = document.getElementById('websiteUrl');
const websiteDescriptionInput = document.getElementById('websiteDescription');
const websiteSubmitBtn = document.getElementById('websiteSubmitBtn');

document.getElementById('addWebsiteBtn').addEventListener('click', () => {
    websiteModalTitle.textContent = 'Add Website';
    websiteSubmitBtn.textContent = 'Add Website';
    websiteIdInput.value = '';
    websiteTitleInput.value = '';
    websiteUrlInput.value = '';
    websiteDescriptionInput.value = '';
    websiteModal.classList.add('active');
});

document.getElementById('closeWebsiteModalBtn').addEventListener('click', () => {
    websiteModal.classList.remove('active');
});

document.querySelectorAll('.website-edit-btn').forEach(btn => {
    btn.addEventListener('click', () => {
        websiteModalTitle.textContent = 'Edit Website';
        websiteSubmitBtn.textContent = 'Save Changes';
        websiteIdInput.value = btn.dataset.id;
        websiteTitleInput.value = btn.dataset.title;
        websiteUrlInput.value = btn.dataset.url;
        websiteDescriptionInput.value = btn.dataset.description;
        websiteModal.classList.add('active');
    });
});

document.querySelectorAll('.resource-delete-form').forEach(form => {
    form.addEventListener('submit', (e) => {
        if (!confirm('Delete this?')) {
            e.preventDefault();
        }
    });
});