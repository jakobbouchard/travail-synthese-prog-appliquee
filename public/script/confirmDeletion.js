(function () {
  const deleteIcons = document.querySelectorAll(".fa-trash.text-danger");

  deleteIcons.forEach((deleteIcon) => {
    deleteIcon.parentElement.addEventListener("click", confirmIt);
  });

  function confirmIt(e) {
    if (!confirm('Êtes-vous certain de vouloir faire cette action?')) e.preventDefault();
  }
})();
