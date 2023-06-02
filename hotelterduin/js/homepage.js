// selecteer alle slides
const slides = document.querySelectorAll(".slide");

// maximum aantal slides
let maxSlide = slides.length - 1;

// maximum aantal slides
let curSlide = 0;

// geef elke slide een traanslatex attribuut van index * 100%
// dus slide-1 0%, slide-2 100%, slide-3 200% etc
slides.forEach((slide, indx) => {
  slide.style.transform = `translateX(${indx * 100}%)`;
});

// selecteer next-slide-button
let nextSlide = document.querySelector("#btn-next");

// voeg een click-event-listener om de slides naar links te verplaatsen
nextSlide.addEventListener("click", function () {
  // als slide-counter = max set counter = 0
  if (curSlide === maxSlide) {
    curSlide = 0;
  } else {
    curSlide++;
  }

  // verplaats alle slides -100% links
  slides.forEach((slide, indx) => {
    slide.style.transform = `translateX(${100 * (indx - curSlide)}%)`;
  });
});

// selecteer prev-slide-button
const prevSlide = document.querySelector("#btn-prev");

// voeg een click-event-listener om de slides naar rechts te verplaatsen
prevSlide.addEventListener("click", function () {
  // als slide-counter = 0 set counter = max
  if (curSlide === 0) {
    curSlide = maxSlide;
  } else {
    curSlide--;
  }

  // verplaats alle slides 100% rechts
  slides.forEach((slide, indx) => {
    slide.style.transform = `translateX(${100 * (indx - curSlide)}%)`;
  });
});

/* zoekbalk */
let contacten = [];
contacten.push({
  naam: "Dylan Huisden",
  telefoon: "0612345678",
});
contacten.push({
  naam: "Nitin Bosman",
  telefoon: "0612345333",
});
contacten.push({
  naam: "Joseph Demirel",
  telefoon: "0632145678",
});
contacten.push({
  naam: "Akash Kabli",
  telefoon: "0662345666",
});

function zoekContacten(zoektekst) {
  zoektekst = zoektekst.toUpperCase();
  let myGrid =
    "<div class='cell'><b>Naam</b></div><div class='cell'><b>Telefoon</b></div>";
  for (let x = 0; x < contacten.length; x++) {
    if (contacten[x].naam.toUpperCase().includes(zoektekst)) {
      myGrid += '<div class="cell">' + contacten[x].naam + "</div>";
      myGrid += '<div class="cell">' + contacten[x].telefoon + "</div>";
    }
  }
  document.getElementById("grid").innerHTML = myGrid;
}
