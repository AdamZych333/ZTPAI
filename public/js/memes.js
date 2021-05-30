const buttons = document.querySelectorAll("h1>.fa-times-circle");

function deleteMeme(){
    const container = this.parentElement.parentElement.parentElement;
    const slug = container.getAttribute("id");
    fetch(`/meme/${slug}`, {
        method: "DELETE"
    }).then(function (response){
        if(response.status === 200){
            if(window.location.pathname === '/home'){
                container.remove();
            }else {
                window.location.pathname = '/home';
            }
        }
    })
}

buttons.forEach(button => button.addEventListener("click", deleteMeme));
