
/***************** Hidden submenu nav *****************/ 

const SUB_MENU = document.getElementById("subMenu");
const AVATAR = document.querySelector(".avatar");
// console.log(SUB_MENU);
// console.log(AVATAR);

AVATAR.addEventListener("click", function(){
    SUB_MENU.classList.toggle("open-menu");
})
