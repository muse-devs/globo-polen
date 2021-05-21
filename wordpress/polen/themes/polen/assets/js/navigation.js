var menu = document.querySelector(".dropdown");
var menu_button = document.querySelector(".dropbtn");
var menu_content = document.querySelector(".dropdown-content");
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
});

function showMenu() {
	menu_content.classList.add("show");
}

function hideMenu() {
	menu_content.classList.remove("show");
}
