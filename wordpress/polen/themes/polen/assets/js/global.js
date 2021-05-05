function copyToClipboard(text) {
	var copyText = document.getElementById("share-input");
	copyText.value = text;
	copyText.select();
	copyText.setSelectionRange(0, 99999); /* For mobile devices */

	document.execCommand("copy");
	alert("Link copiado para Área de transferência");
}

function changeHash(hash) {
	window.location.hash = hash || "";
}

function truncatedItems() {
	const ps = document.querySelectorAll(".truncate");
	const observer = new ResizeObserver((entries) => {
		for (let entry of entries) {
			entry.target.classList[
				entry.target.scrollHeight > entry.contentRect.height
					? "add"
					: "remove"
			]("truncated");
		}
	});

	ps.forEach((p) => {
		observer.observe(p);
	});
}

jQuery(document).ready(function () {
	truncatedItems();
});
