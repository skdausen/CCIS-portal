 const editToggleBtn = document.getElementById('editToggleBtn');
    const editToggleText = document.getElementById('editToggleText');
    const profileForm = document.getElementById('profileForm');
    const saveBtnContainer = document.getElementById('saveBtnContainer');

    editToggleBtn.addEventListener('click', () => {
      const isEditing = editToggleText.textContent === 'cancel';

      profileForm.querySelectorAll('input, select').forEach(el => {
        if (el.name === 'username' || el.name === 'email') return;

        if (isEditing) {
          el.setAttribute('readonly', '');
          el.setAttribute('disabled', '');
          el.classList.add('bg-gray-100', 'cursor-not-allowed');
        } else {
          el.removeAttribute('readonly');
          el.removeAttribute('disabled');
          el.classList.remove('bg-gray-100', 'cursor-not-allowed');
        }
      });

      saveBtnContainer.classList.toggle('hidden', isEditing);
      editToggleText.textContent = isEditing ? 'edit' : 'cancel';
    });