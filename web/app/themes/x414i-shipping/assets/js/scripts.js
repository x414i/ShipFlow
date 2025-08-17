document.addEventListener("DOMContentLoaded", function () {
  const sidebar = document.getElementById("sidebar");
  const toggleSidebar = document.getElementById("toggleSidebar");
  const mobileToggle = document.getElementById("mobileToggle");
  const overlay = document.getElementById("overlay");
  const mainContent = document.getElementById("mainContent");

  // Function to toggle sidebar for desktop
  const handleToggleSidebar = () => {
    document.body.classList.toggle("sidebar-collapsed");
  };

  // Function to toggle sidebar for mobile
  const handleMobileToggle = () => {
    sidebar.classList.toggle("active");
    overlay.classList.toggle("active");
    document.body.classList.toggle("no-scroll");
  };

  // Event Listeners
  if (toggleSidebar) {
    toggleSidebar.addEventListener("click", handleToggleSidebar);
  }

  if (mobileToggle) {
    mobileToggle.addEventListener("click", handleMobileToggle);
  }

  if (overlay) {
    overlay.addEventListener("click", handleMobileToggle);
  }

  // Optional: Close sidebar when clicking a link inside it on mobile
  const sidebarLinks = document.querySelectorAll(".sidebar-nav a");
  sidebarLinks.forEach((link) => {
    link.addEventListener("click", () => {
      if (window.innerWidth <= 992 && sidebar.classList.contains("active")) {
        handleMobileToggle();
      }
    });
  });
});
