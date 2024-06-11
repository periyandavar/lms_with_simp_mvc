class Bookinput extends HTMLElement {
    constructor() {
        super();
        let book, author;
        const imgCard = document.createElement('div');
        const imgElem = document.createElement('img');
        const overlayElem = document.createElement('div');
        const details = document.createElement('div');
        imgCard.setAttribute('class', 'card-image img-container')
        imgElem.setAttribute('alt', 'cover picture');
        imgElem.src = this.hasAttribute('cover') ? this.getAttribute("cover") : "";
        overlayElem.setAttribute('class', 'overlay');
        details.setAttribute('class', 'details');
        book = this.hasAttribute('book') ? this.getAttribute("book") : "";
        author = this.hasAttribute('author') ? this.getAttribute("author") : "";
        details.innerHTML = book + " by " + author;
        overlayElem.appendChild(details);
        imgCard.appendChild(imgElem);
        imgCard.appendChild(overlayElem);
        this.insertBefore(imgCard, this.childNodes[0]);
    }

}

// Define the new element
customElements.define('book-element', Bookinput);