const search = document.querySelector('input[placeholder="Search"]');

search.addEventListener("keyup", function(event){
    if(event.key === "Enter"){
        window.location.href = `/search?q=` + this.value;
    }
})
