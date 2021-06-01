const buttons = document.querySelectorAll('.sort>div');
const container = document.querySelector('.memes');

function sort(){
    const interval = this.getAttribute('id');
    fetch(`/top10/${interval}`, {
        method: "GET"
    }).then(function (response){
        console.log(response);
        return response.json();
    }).then(function (memes){
        container.innerHTML = "";
        loadMemes(memes);
    })
}

function loadMemes(memes){
    memes.forEach(meme =>{
        console.log(meme);
        createMeme(meme);
    })
}

function createMeme(meme){
    const template = document.querySelector('#meme-template');
    const clone = template.content.cloneNode(true);

    const id = clone.querySelector(".meme");
    id.id = meme.slug;
    const a = clone.querySelectorAll("a");
    a.forEach(href => href.href = `/meme/${meme.slug}`);
    const img = clone.querySelector("img");
    img.src = `/uploads/${meme.image}`;
    const title = clone.querySelector("h1");
    title.innerHTML = meme.title;
    const date = clone.querySelector("#date");
    date.innerHTML = meme.created_at.date.slice(0, 10);
    const email = clone.querySelector('#email');
    email.innerHTML = meme.user;
    const likes = clone.querySelector('.fa-thumbs-up>span');
    likes.innerHTML = meme.likes;
    const dislikes = clone.querySelector('.fa-thumbs-down>span');
    dislikes.innerHTML = meme.dislikes;

    container.appendChild(clone);
}

buttons.forEach(button => button.addEventListener("click", sort));