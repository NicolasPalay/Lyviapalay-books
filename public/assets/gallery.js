window.onload = () => {


    const modale = document.querySelector('.lp-modal-box');
    const close = document.querySelector('.lp-close');
    const links = document.querySelectorAll('.lp-gallery');
    console.log(links);
    for (let link of links) {
        link.addEventListener('click', function (e) {
            e.preventDefault();
            const img = document.querySelector('.lp-full-gallery');
            img.src = this.href;
            modale.classList.add("lp-show-modal");

        })
    }
}