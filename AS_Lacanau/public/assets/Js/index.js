const header = document.getElementById('header');
const box1 = document.getElementById('box1');
const box2 = document.getElementById('box2');

let lastScroll = 0;

window.addEventListener("scroll", () => {
    if (window.scrollY < lastScroll) {
        header.style.top = '0px';
    } else {
        header.style.top = "-100px";
    }

    lastScroll = window.scrollY;
});

window.addEventListener("scroll", () => {
    scrollValue = (window.innerHeight + window.scrollY) / (document.body.offsetHeight);

    if (scrollValue > 0.65) {
        box1.style.transform = 'none';
        box2.style.transform = 'none';
    } else if(scrollValue < 0.65) {
        box1.style.transform = 'translateX(-800px)';
        box2.style.transform = 'translateX(800px)';
    }
});

