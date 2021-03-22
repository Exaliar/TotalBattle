import { lvlClassSpan } from "../selected/view.js";

export function raport(raportId = "", walka = []) {
  let html = `
    <div class="name-section">
      <span>Raport</span>
    </div>
  `;
  let lp = 1;
  if (Array.isArray(walka) && walka.length) {
    walka.forEach(element => {
      html += `
    <section class="defence">
      <span class="lp-raport">${lp}.</span>
      <div class="armia">
        <div class="lvl-raport">
          ${lvlClassSpan(element.lvlLeft)}
        </div>
        <span class="name">${element.nazwaLeft}</span>
        <div class="raport-count">
          <i class="live">${element.iloscLeft}</i>
          ${element.stratyLeft ? `<i class="lost">-${element.stratyLeft}</i>` : ``}
        </div>
        ${element.deathLeft ? `<div class="defended"><i>+</i></div>` : ``}
      </div>

        ${element.action ? `<i class="icon-right-fat atak-arrow"></i>` : `<i class="icon-left-fat obrona-arrow"></i>`}
      
      <div class="monster">
        <div class="lvl-raport">
          ${lvlClassSpan(element.lvlRight)}
        </div>
        <span class="name">${element.nazwaRight}</span>
        <div class="raport-count">
          <i class="live">${element.iloscRight}</i>
          ${element.stratyRight ? `<i class="lost">-${element.stratyRight}</i>` : ``}
        </div>
        ${element.deathRight ? `<div class="defended"><i>+</i></div>` : ``}
      </div>
      <i class="damage"><span>Cios za </span>${element.damage}</i>
    </section>
    ${walka.length > lp ? `<hr class="hr-selected">` : ``}
    `;
      lp++;
    });
  } else {
    html += `Zwolnij troszkę muszę policzyć :)`;
  }
  raportId.innerHTML = html;
}
