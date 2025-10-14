function openDeleteModal(taskId) {
    const modal = document.getElementById('delete-modal');
    const form = document.getElementById('delete-form');
    form.action = `/tasks/${taskId}`;
    modal.style.display = 'flex';
}

function closeDeleteModal() {
    const modal = document.getElementById('delete-modal');
    modal.style.display = 'none';
}

window.addEventListener('click', (event) => {
    const modal = document.getElementById('delete-modal');
    if (event.target === modal) closeDeleteModal();
});


