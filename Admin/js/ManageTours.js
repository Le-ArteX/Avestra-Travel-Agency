// Custom stylish message
function showCustomMessage(message, type = 'success') {
  var msgDiv = document.getElementById('customMessage');
  var msgText = document.getElementById('customMessageText');
  var msgBox = document.getElementById('customMessageBox');
  if (msgDiv && msgText && msgBox) {
    msgText.textContent = message;
    msgBox.style.background = (type === 'success') ? '#4fc3f7' : '#c62828';
    msgDiv.style.display = 'block';
    setTimeout(function() { msgDiv.style.display = 'none'; }, 3500);
  }
}

// Override deleteTour to show custom confirm modal
window.deleteTour = function(id, name) {
  showCustomMessage('Delete clicked for tour ID: ' + id, 'success');
  showConfirmModal('Are you sure you want to delete "' + name + '"? This action cannot be undone.', function(tourId) {
    showCustomMessage('Deleting tour...', 'success');
    submitDeleteTour(tourId);
  }, [id]);
}

// Show success message after adding/editing/deleting
document.addEventListener('DOMContentLoaded', function() {
  var successMsg = document.querySelector('.alert-success');
  var errorMsg = document.querySelector('.alert-error');
  if (successMsg) {
    showCustomMessage(successMsg.textContent, 'success');
    successMsg.style.display = 'none';
  }
  if (errorMsg) {
    showCustomMessage(errorMsg.textContent, 'error');
    errorMsg.style.display = 'none';
  }
});
// ManageTours.js - Custom JS for ManageTours.php

function resetForm() {
  document.getElementById('tourForm').reset();
  document.getElementById('formAction').value = 'add';
  document.getElementById('tourId').value = '';
  document.getElementById('modalTitle').innerHTML = '<i class="fas fa-plus-circle"></i> Add Tour';
  window.location.hash = '';
}

// Edit tour - populate form with existing data
document.addEventListener('DOMContentLoaded', function() {
  document.querySelectorAll('.edit-btn').forEach(btn => {
    btn.addEventListener('click', function(e) {
      const id = this.dataset.id;
      const name = this.dataset.name;
      const destination = this.dataset.destination;
      const duration = this.dataset.duration;
      const price = this.dataset.price;
      const status = this.dataset.status;
      document.getElementById('formAction').value = 'edit';
      document.getElementById('tourId').value = id;
      document.getElementById('tourName').value = name;
      document.getElementById('tourDestination').value = destination;
      document.getElementById('tourDuration').value = duration;
      document.getElementById('tourPrice').value = price;
      document.getElementById('tourStatus').value = status;
      document.getElementById('modalTitle').innerHTML = '<i class="fas fa-pen-to-square"></i> Edit Tour';
    });
  });

  // Reset form when clicking "Add Tour" button
  document.querySelector('.add-tour-btn').addEventListener('click', function() {
    resetForm();
  });

  // Close modal and reset form
  document.querySelector('.modal-close').addEventListener('click', function() {
    resetForm();
  });
});

// Custom modal confirmation
let confirmAction = null;
let confirmArgs = [];

function showConfirmModal(message, action, args) {
  confirmAction = action;
  confirmArgs = args;
  document.getElementById('confirmModalMessage').textContent = message;
  document.getElementById('confirmModal').style.display = 'flex';
}

function hideConfirmModal() {
  document.getElementById('confirmModal').style.display = 'none';
  confirmAction = null;
  confirmArgs = [];
}

function handleConfirmModalYes() {
  if (confirmAction) {
    confirmAction.apply(null, confirmArgs);
  }
  hideConfirmModal();
}

function toggleTour(id) {
  showConfirmModal('Are you sure you want to change the status of this tour?', submitToggleTour, [id]);
}

// AJAX for Delete Tour (no page redirect, just show message)
function submitDeleteTour(id) {
  const formData = new FormData();
  formData.append('action', 'delete');
  formData.append('id', id);
  fetch('../controller/ManageToursController.php', {
    method: 'POST',
    body: formData
  })
  .then(res => res.json())
  .then(data => {
    if (data.success) {
      showCustomMessage(data.message, 'success');
      setTimeout(() => window.location.reload(), 1200);
    } else {
      showCustomMessage(data.message || 'Error deleting tour', 'error');
    }
  })
  .catch(() => showCustomMessage('Network error', 'error'));
}

// AJAX for Toggle Status (no page redirect, just show message)
function submitToggleTour(id) {
  const formData = new FormData();
  formData.append('action', 'toggle');
  formData.append('id', id);
  fetch('../controller/ManageToursController.php', {
    method: 'POST',
    body: formData
  })
  .then(res => res.json())
  .then(data => {
    if (data.success) {
      showCustomMessage(data.message, 'success');
      setTimeout(() => window.location.reload(), 1200);
    } else {
      showCustomMessage(data.message || 'Error updating status', 'error');
    }
  })
  .catch(() => showCustomMessage('Network error', 'error'));
}

// Expose functions to global scope for inline event handlers
window.toggleTour = toggleTour;
window.deleteTour = deleteTour;
window.handleConfirmModalYes = handleConfirmModalYes;
window.hideConfirmModal = hideConfirmModal;
window.resetForm = resetForm;