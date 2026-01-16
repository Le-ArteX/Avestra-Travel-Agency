(function () {
  const hotelForm = document.getElementById("hotelForm");
  const formAction = document.getElementById("formAction");
  const hotelId = document.getElementById("hotelId");
  const hotelName = document.getElementById("hotelName");
  const hotelLocation = document.getElementById("hotelLocation");
  const hotelRooms = document.getElementById("hotelRooms");
  const hotelStatus = document.getElementById("hotelStatus");

  const openAddHotel = document.getElementById("openAddHotel");
  const cancelBtn = document.getElementById("cancelBtn");

  // Search
  const searchInput = document.getElementById("hotelSearch");
  const searchBtn = document.getElementById("searchBtn");
  const hotelGrid = document.getElementById("hotelGrid");

  // Confirm modal
  const confirmModal = document.getElementById("confirmModal");
  const confirmMsg = document.getElementById("confirmModalMessage");
  const confirmYes = document.getElementById("confirmYesBtn");
  const confirmNo = document.getElementById("confirmNoBtn");
  let pendingFormToSubmit = null;

  function resetForm() {
    formAction.value = "add";
    hotelId.value = "";
    hotelName.value = "";
    hotelLocation.value = "";
    hotelRooms.value = "";
    hotelStatus.value = "Active";

    // clear star radios
    document.querySelectorAll('input[name="rating"]').forEach(r => r.checked = false);
  }

  function setStars(rating) {
    const r = Math.max(1, Math.min(5, parseInt(rating || "1", 10)));
    const radio = document.getElementById(`star${r}`);
    if (radio) radio.checked = true;
  }

  // Add hotel button
  if (openAddHotel) {
    openAddHotel.addEventListener("click", () => {
      resetForm();
    });
  }

  // Cancel in modal
  if (cancelBtn) {
    cancelBtn.addEventListener("click", (e) => {
      e.preventDefault();
      resetForm();
      window.location.hash = ""; // close :target modal
    });
  }

  // Edit buttons fill modal
  document.querySelectorAll(".edit-btn").forEach((btn) => {
    btn.addEventListener("click", () => {
      formAction.value = "edit";
      hotelId.value = btn.dataset.id || "";
      hotelName.value = btn.dataset.name || "";
      hotelLocation.value = btn.dataset.location || "";
      hotelRooms.value = btn.dataset.rooms || "";
      hotelStatus.value = btn.dataset.status || "Active";
      setStars(btn.dataset.rating || "1");
    });
  });

  // Confirm modal helpers (used for toggle/delete forms)
  function showConfirm(message, formEl) {
    pendingFormToSubmit = formEl;
    confirmMsg.textContent = message;
    confirmModal.style.display = "flex";
    confirmModal.setAttribute("aria-hidden", "false");
  }

  function hideConfirm() {
    pendingFormToSubmit = null;
    confirmModal.style.display = "none";
    confirmModal.setAttribute("aria-hidden", "true");
  }

  if (confirmNo) confirmNo.addEventListener("click", hideConfirm);
  if (confirmModal) {
    confirmModal.addEventListener("click", (e) => {
      if (e.target === confirmModal) hideConfirm();
    });
  }

  if (confirmYes) {
    confirmYes.addEventListener("click", () => {
      if (pendingFormToSubmit) pendingFormToSubmit.submit();
    });
  }

  // Attach confirm to buttons that have data-confirm
  document.querySelectorAll('form.inline-form button[data-confirm]').forEach((btn) => {
    btn.addEventListener("click", (e) => {
      e.preventDefault(); // stop immediate submit, we submit after confirm yes
      const msg = btn.getAttribute("data-confirm") || "Are you sure?";
      const form = btn.closest("form");
      if (form) showConfirm(msg, form);
    });
  });

  // Front-end search filter (no API)
  function runSearch() {
    const q = (searchInput?.value || "").toLowerCase().trim();
    const cards = hotelGrid?.querySelectorAll(".hotel-card") || [];

    cards.forEach(card => {
      const name = (card.dataset.name || "").toLowerCase();
      const loc  = (card.dataset.location || "").toLowerCase();
      const match = name.includes(q) || loc.includes(q);
      card.style.display = match ? "" : "none";
    });
  }

  if (searchBtn) searchBtn.addEventListener("click", runSearch);
  if (searchInput) searchInput.addEventListener("input", runSearch);

  // IMPORTANT: we do NOT preventDefault on hotelForm submit.
  // Save Hotel will submit normally to PHP controller.
})();
