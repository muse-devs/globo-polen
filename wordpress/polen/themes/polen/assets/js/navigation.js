var menu = document.querySelector(".dropdown");
var menu_button = document.querySelector(".dropbtn");
var menu_content = document.querySelector(".dropdown-content");
var menu_overlay = document.querySelector("#menu-bg");
var menu_close = document.querySelector(".menu-close");

jQuery(document).ready(function () {
	if (!menu) {
		return;
	}
	menu.addEventListener("mouseover", function () {
		if (screen.width < 540) {
			return;
		}
		showMenu();
	});

	menu_button.addEventListener("click", function () {
		showMenu();
	});

	menu.addEventListener("mouseout", function () {
		if (screen.width < 540) {
			return;
		}
		hideMenu();
	});

  menu_close.addEventListener("click", function () {
		hideMenu();
	});

	menu_overlay.addEventListener("click", function () {
		hideMenu();
	});
});

function showMenu() {
  if (screen.width < 540) {
    document.getElementsByTagName("html")[0].classList.add("no-scroll");
    document.body.classList.add("no-scroll");
  }
	menu_content.classList.add("show");
  menu_overlay.classList.add("show");
}

function hideMenu() {
  document.getElementsByTagName("html")[0].classList.remove("no-scroll");
  document.body.classList.remove("no-scroll");
	menu_content.classList.remove("show");
  menu_overlay.classList.remove("show");
}
