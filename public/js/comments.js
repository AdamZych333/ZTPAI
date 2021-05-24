const buttons = document.querySelectorAll(".fa-times-circle");
const slug = document.querySelector(".meme").id;

function deleteCom(){
    const container = this.parentElement.parentElement.parentElement;
    const id = container.getAttribute("id");
    fetch(`/meme/${slug}/comments/${id}`)
        .then(function (response){
            if(response.status === 200){
                container.remove();
            }
        })
}

buttons.forEach(button => button.addEventListener("click", deleteCom));