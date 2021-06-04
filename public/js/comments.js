const comButtons = document.querySelectorAll(".info>.fa-times-circle");
const slug = document.querySelector(".meme").id;

function deleteCom(){
    const container = this.parentElement.parentElement.parentElement;
    const id = container.getAttribute("id");

    fetch(`/meme/${slug}/comments/${id}`, {
        method: "DELETE"
    }).then(function (response){
        if(response.status === 204){
            container.remove();
        }
    })
}

comButtons.forEach(button => button.addEventListener("click", deleteCom));
