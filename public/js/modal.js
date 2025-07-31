document.addEventListener('DOMContentLoaded', function () {
  const modals = document.querySelectorAll('[id^="editModal"]');

  modals.forEach(modal => {
    modal.addEventListener('hidden.bs.modal', function () {
      const inputs = modal.querySelectorAll('input[type="text"]');
      const selects = modal.querySelectorAll('select');

      inputs.forEach(input => {
        input.value = '';
      });

      selects.forEach(select => {
        select.selectedIndex = 0;
      });
    });
  });
});
