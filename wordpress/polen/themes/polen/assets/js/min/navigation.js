var menu=document.querySelector(".dropdown"),menu_button=document.querySelector(".dropbtn"),menu_content=document.querySelector(".dropdown-content"),menu_close=document.querySelector(".menu-close");function showMenu(){menu_content.classList.add("show")}function hideMenu(){menu_content.classList.remove("show")}jQuery(document).ready((function(){menu.addEventListener("mouseover",(function(){screen.width<540||showMenu()})),menu_button.addEventListener("click",(function(){showMenu()})),menu.addEventListener("mouseout",(function(){screen.width<540||hideMenu()})),menu_close.addEventListener("click",(function(){hideMenu()}))}));