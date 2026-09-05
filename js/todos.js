const todoModal = document.getElementById('todoModal');
const todoModalTitle = document.getElementById('todoModalTitle');
const todoIdInput = document.getElementById('todoId');
const todoTaskInput = document.getElementById('todoTask');
const todoProjectSelect = document.getElementById('todoProject');
const todoSubmitBtn = document.getElementById('todoSubmitBtn');

document.getElementById('addTodoBtn').addEventListener('click', () => {
    todoModalTitle.textContent = 'Add New To-Do';
    todoSubmitBtn.textContent = 'Add To-Do';
    todoIdInput.value = '';
    todoTaskInput.value = '';
    todoProjectSelect.value = '';
    todoModal.classList.add('active');
});

document.getElementById('closeTodoModalBtn').addEventListener('click', () => {
    todoModal.classList.remove('active');
});

document.querySelectorAll('.todo-edit-btn').forEach(btn => {
    btn.addEventListener('click', () => {
        const item = btn.closest('.todo-item');
        todoModalTitle.textContent = 'Edit To-Do';
        todoSubmitBtn.textContent = 'Save Changes';
        todoIdInput.value = item.dataset.id;
        todoTaskInput.value = item.dataset.task;
        todoProjectSelect.value = item.dataset.project;
        todoModal.classList.add('active');
    });
});

document.querySelectorAll('.todo-delete-form').forEach(form => {
    form.addEventListener('submit', (e) => {
        if (!confirm('Delete this to-do?')) {
            e.preventDefault();
        }
    });
});