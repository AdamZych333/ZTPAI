const likeButtons = document.querySelectorAll(".fa-thumbs-up");
const dislikeButtons = document.querySelectorAll(".fa-thumbs-down");

function giveLike() {
    const likes = this;
    const container = likes.parentElement.parentElement.parentElement;
    const id = container.getAttribute("id");

    fetch(`/meme/${id}/like`, {
        method: "POST"
    })
        .then(function (response){
            rate(response, likes);
        });
}

function giveDislike() {
    const dislikes = this;
    const container = dislikes.parentElement.parentElement.parentElement;
    const id = container.getAttribute("id");

    fetch(`/meme/${id}/dislike`, {
        method: "POST"
    })
        .then(function (response){
            rate(response, dislikes);
        });
}

function rate(response, action){
    if(response.status === 201) {
        action.querySelector("span").innerHTML = parseInt(action.querySelector("span").innerHTML) + 1;
    }else if(response.status === 200){
        action.querySelector("span").innerHTML = parseInt(action.querySelector("span").innerHTML) - 1;
    }
}

likeButtons.forEach(button => button.addEventListener("click", giveLike));
dislikeButtons.forEach(button => button.addEventListener("click", giveDislike));