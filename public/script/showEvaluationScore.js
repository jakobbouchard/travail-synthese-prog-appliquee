(function () {
  const radioButtons = document.querySelectorAll(".form-check-input");
  const resultCard = document.querySelector("#resultCard");
  const result = document.querySelector("#result");
  const resetButton = document.querySelector('button[type="reset"]');

  resetButton.addEventListener("click", changeResult);
  radioButtons.forEach((radioButton) => {
    radioButton.addEventListener("change", changeResult);
  });

  function changeResult(e) {
    if (e.target.name == "reset") {
      resultCard.classList.replace("opacity-100", "opacity-0");
    } else {
      let studentResult = 0;
      radioButtons.forEach((radioButton) => {
        studentResult += radioButton.checked ? parseInt(radioButton.value) : 0;
      });
      result.textContent = studentResult;
      resultCard.classList.replace("opacity-0", "opacity-100");
    }
  }
})();
