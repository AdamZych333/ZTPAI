const searchUser = document.querySelector('input[placeholder="Find user"]');
const userContainer = document.querySelector('.users');

searchUser.addEventListener("keyup", function(event){
    if(event.key === "Enter"){
        window.location.href = `/users?q=` + this.value;
    }
})
