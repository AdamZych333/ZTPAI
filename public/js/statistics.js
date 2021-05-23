const likeButtons = document.querySelectorAll(".fa-thumbs-up");
const dislikeButtons = document.querySelectorAll(".fa-thumbs-down");

function giveLike() {
    const likes = this;
    const container = likes.parentElement.parentElement.parentElement;
    const id = container.getAttribute("id");

    fetch(`/meme/${id}/like`)
        .then(function (response){
            if(response.status === 201){
                likes.querySelector("span").innerHTML = parseInt(likes.querySelector("span").innerHTML) + 1;
            }else if(response.status === 200){
                likes.querySelector("span").innerHTML = parseInt(likes.querySelector("span").innerHTML) - 1;
            }
        });
}

function giveDislike() {
    const dislikes = this;
    const container = dislikes.parentElement.parentElement.parentElement;
    const id = container.getAttribute("id");

    fetch(`/meme/${id}/dislike`)
        .then(function (response){
            if(response.status === 201) {
                dislikes.querySelector("span").innerHTML = parseInt(dislikes.querySelector("span").innerHTML) + 1;
            }else if(response.status === 200){
                dislikes.querySelector("span").innerHTML = parseInt(dislikes.querySelector("span").innerHTML) - 1;
            }
        });
}

likeButtons.forEach(button => button.addEventListener("click", giveLike));
dislikeButtons.forEach(button => button.addEventListener("click", giveDislike));