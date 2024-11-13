const closeBtn = document.querySelector(".close")
const aside = document.querySelector(".aside")
const toggle = document.querySelector(".toggle-nav")
closeBtn.addEventListener("click", (e)=>{
    aside.className = "aside hide"
})
toggle.addEventListener("click", (e)=>{
    aside.className = "aside"
})