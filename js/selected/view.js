export function lvlClassSpan(lvl) {
  switch (lvl) {
    case 0:
      return `<span title="lvl jednostki" class="lvl lvl-O">0</span>`;
    case 1:
      return `<span title="lvl jednostki" class="lvl lvl-I">I</span>`;
    case 2:
      return `<span title="lvl jednostki" class="lvl lvl-II">II</span>`;
    case 3:
      return `<span title="lvl jednostki" class="lvl lvl-III">III</span>`;
    case 4:
      return `<span title="lvl jednostki" class="lvl lvl-IV">IV</span>`;
    case 5:
      return `<span title="lvl jednostki" class="lvl lvl-V">V</span>`;
    case 6:
      return `<span title="lvl jednostki" class="lvl lvl-VI">VI</span>`;
    case 7:
      return `<span title="lvl jednostki" class="lvl lvl-VII">VII</span>`;
    default:
      return ``;
  }
}

function monsterTyp(monster) {
  switch (monster) {
    case "Zwykły":
      return "xII";
    case "Rzadki":
      return "xIII";
    case "Heroiczny":
      return "xIV";
    case "Twierdza":
      return "xVI";
    default:
      return "";
  }
}

function bodyMonsterBonus(bonus, komu) {
  let bonusToSend = `<span class="body-monster-span">-------------------</span>`;
  for (let index = 0; index < bonus.length && bonus[index] !== null && bonus[index] !== undefined; index++) {
    bonusToSend += `
      <span class="body-monster-span">
        ${bonus[index]}% ${komu[index]}
      </span>
    `;
  }
  return bonusToSend;
}

function bodyMonsterAtak(ilosc, atak, bonus) {
  let atakToSend = `<span class="body-monster-span">${(parseInt(ilosc) * parseInt(atak)).toFixed(1)}</span>`;
  for (let index = 0; index < bonus.length && bonus[index] !== null && bonus[index] !== undefined; index++) {
    atakToSend += `
      <span class="body-monster-span">
        ${(parseInt(ilosc) * ((parseInt(atak) / 100) * (100 + (bonus[index] == 0 ? 0 : parseFloat(bonus[index]))))).toFixed(1)}
      </span>
    `;
  }
  return atakToSend;
}

function displayEachSelectedMonster(monsterArray) {
  let monstersToSend = ``;
  monsterArray.forEach(eachMonster => {
    monstersToSend += `
    <div class="body-monster-each">
      <div class="body-monster-jednostki">
        <span class="body-monster-span">${eachMonster.nazwa}</span>
        ${lvlClassSpan(parseInt(eachMonster.lvl))}
      </div>
      <div class="body-monster-ilosc">
        <span class="body-monster-span">${eachMonster.ilosc}</span>
      </div>
      <div class="body-monster-typ">
        <span class="body-monster-span">Podstawa</span>
        <span class="body-monster-span">${eachMonster.typ[0] === undefined || eachMonster.typ[0] === null ? "" : eachMonster.typ[0]}</span>
        <span class="body-monster-span">${eachMonster.typ[1] === undefined || eachMonster.typ[1] === null ? "" : eachMonster.typ[1]}</span>
        <span class="body-monster-span">${eachMonster.typ[2] === undefined || eachMonster.typ[2] === null ? "" : eachMonster.typ[2]}</span>
      </div>
      <div class="body-monster-bonus">
        ${bodyMonsterBonus(eachMonster.bonus, eachMonster.komu)}
      </div>
      <div class="body-monster-atak">
        ${bodyMonsterAtak(eachMonster.ilosc, eachMonster.atak, eachMonster.bonus)}
      </div>
      <div class="body-monster-zycie">
        <span class="body-monster-span">${(parseInt(eachMonster.ilosc) * parseInt(eachMonster.zycie)).toFixed(0)}</span>
      </div>
    </div>`;
  });
  return monstersToSend;
}
function displaySelectedMonster(monster) {
  if (typeof monster === "object" && monster !== null && monster !== undefined && Object.keys(monster).length > 0) {
    let typ = monsterTyp(monster.nazwaObiektu);
    return `
    <section class="selected-row selected-row-monster" data-id="">
      <input type="checkbox" class="test" id="monster" />
      <label for="monster"><i class="demo-icon icon-angle-double-down down-arrow"></i></label>
      <section class="expand-row expand-row-monster ${typ}">
        <span title="Nazwa jednostki" class="name">Rodzaj: ${monster.nazwaObiektu} Lvl: ${monster.lvlObiektu} Typ: ${monster.typObiektu}</span>

        <input type="button" class="btn btn-neutral btn-monster" value="Edit"/>
        <div class="display-data-container display-data-container-monster">
          <div class="heder-monster">
              <span class="jednostki-monster">Jednostki</span>
              <span class="ilosc-monster">Ilość</span>
              <span class="typ-monster">Typ</span>
              <span class="bonus-monster">Bonus</span>
              <span class="atak-monster">Atak</span>
              <span class="zycie-monster">Życie</span>
          </div>
          <div class="body-monster">
            ${displayEachSelectedMonster(monster.jednostkiObiektu)}
          </div>
        </div>
      </section>
    </section>
    `;
  } else {
    return `
    <section class="selected-row selected-row-monster" data-id="">
      <section class="expand-row expand-row-monster">
        <span title="Nazwa jednostki" class="name">Brak wybranego</span>
        <input type="button" class="btn btn-neutral btn-monster" value="Edit" />
      </section>
    </section>
    <hr class="hr-selected">
    `;
  }
}

export function displaySelectedApHp(element) {
  if (element == null || element.armyIlosc == 0) {
    return `
    <div class="sila data-container">
      <span class="name-row">Bonus AP</span>
      <span class="sila-row">Brak Danych</span>
      <span class="sila-row">lub</span>
      <span class="sila-row">Błąd servera</span>
    </div>
    <div class="zycie data-container">
      <span class="name-row">Bonus HP</span>
      <span class="zycie-row">Brak Danych</span>
      <span class="zycie-row">lub</span>
      <span class="zycie-row">Błąd servera</span>
    </div>
    `;
  } else {
    return `
    <div class="sila data-container">
      <span class="name-row">Bonus AP</span>
      <span class="sila-row">
        ${(parseInt(element.armyIlosc) * ((parseInt(element.sila) / 100) * (100 + (element.armyApBonus == 0 ? 0 : parseFloat(element.armyApBonus))))).toFixed(2)}
      </span>
      <span class="sila-row">
        ${
          element.bonusIle1 == null
            ? ""
            : (parseInt(element.armyIlosc) * ((parseInt(element.sila) / 100) * (100 + (element.armyApBonus == 0 ? 0 : parseFloat(element.armyApBonus)) + parseFloat(element.bonusIle1)))).toFixed(2)
        }
      </span>
      <span class="sila-row">
        ${
          element.bonusIle2 == null
            ? ""
            : (parseInt(element.armyIlosc) * ((parseInt(element.sila) / 100) * (100 + (element.armyApBonus == 0 ? 0 : parseFloat(element.armyApBonus)) + parseFloat(element.bonusIle2)))).toFixed(2)
        }
      </span>
      <span class="sila-row">
        ${
          element.bonusIle3 == null
            ? ""
            : (parseInt(element.armyIlosc) * ((parseInt(element.sila) / 100) * (100 + (element.armyApBonus == 0 ? 0 : parseFloat(element.armyApBonus)) + parseFloat(element.bonusIle3)))).toFixed(2)
        }
      </span>
    </div>
    <div class="zycie data-container">
      <span class="name-row">Bonus HP</span>
      <span class="zycie-row">
        ${(parseInt(element.armyIlosc) * ((parseInt(element.zycie) / 100) * (100 + (element.armyHpBonus == 0 ? 0 : parseFloat(element.armyHpBonus))))).toFixed(2)}
      </span>
      <span class="zycie-row"></span>
      <span class="zycie-row"></span>
      <span class="zycie-row"></span>
    </div>`;
  }
}

export function displaySelectedAll(data = [], monster = {}) {
  let html = `
  <div class="name-section">
      <span>wybrany przeciwnik</span>
    </div>
    ${displaySelectedMonster(monster)}
    <div class="name-section">
      <span>wybrane jednostki</span>
    </div>
  `;
  let lp = 1;

  data.forEach(element => {
    let lvl = lvlClassSpan(element.lvl);
    html += `
    <section class="selected-row" data-id="${element.armyId}">
      <input type="checkbox" class="test" id="test${element.armyId}" />
      <label for="test${element.armyId}"><i class="demo-icon icon-angle-double-down down-arrow"></i></label>
      <section class="expand-row">
        <i class="lp">${lp}.</i>
        ${lvl}
        <span title="Nazwa jednostki" class="name">${element.name}</span>
        <input title="Ilość jednostek" type="number" class="army-ilosc" id="armyIlosc" value="${element.armyIlosc}" placeholder="Ilość Max" />
        <input title="Bonus ataku" type="number" class="army-ap-bonus" id="armyApBonus" value="${element.armyApBonus}" placeholder="Bonus AP" />
        <input title="Bonus życia" type="number" class="army-hp-bonus" id="armyHpBonus" value="${element.armyHpBonus}" placeholder="Bonus HP" />
        <input type="button" class="btn btn-neutral" value="Edit" disabled/>
        <input type="button" class="btn btn-danger" value="Delete" />
        <div class="range-div">
          <span class="min">${element.armyIlosc}</span>
          <input class="army-count" type="range" min="0" max="${element.armyIlosc}" step="1" value="${element.armyIlosc}" name="armyCount" id="armyCount" />
        </div>
        <div class="display-data-container">
          <div class="typ data-container">
            <span class="name-row">Typ</span>
            <span class="typ-row">Podstawa</span>
            <span class="typ-row">${element.typ1 == null ? "" : element.typ1}</span>
            <span class="typ-row">${element.typ2 == null ? "" : element.typ2}</span>
            <span class="typ-row">${element.typ3 == null ? "" : element.typ3}</span>
          </div>
          <div class="bonus data-container">
            <span class="name-row">Bonus %</span>
            <span class="bonus-row">-------------------</span>
            <span class="bonus-row">${element.bonusIle1 == null ? `` : `+${element.bonusIle1}% ${element.bonusKomu1}`}</span>
            <span class="bonus-row">${element.bonusIle2 == null ? `` : `+${element.bonusIle2}% ${element.bonusKomu2}`}</span>
            <span class="bonus-row">${element.bonusIle3 == null ? `` : `+${element.bonusIle3}% ${element.bonusKomu3}`}</span>
          </div>
          <div class="display-ap-hp">
            ${displaySelectedApHp(element)}
          </div>
        </div>
      </section>
    </section>
    <hr class="hr-selected">
    `;

    lp++;
  });
  html += `
  <section class="selected-row">
    <section class="expand-row">

      <div class="first-atak">
        <span>Pierwszy atak</span>
        <div>
          <div>
            <input type="radio" id="gracz" class="new-calculator-change-atak" name="firstAtak" value="gracz">
            <label for="gracz">Gracz</label>
          </div>
          <div>
            <input type="radio" id="potwory" class="new-calculator-change-atak" name="firstAtak" value="potwory" checked>
            <label for="potwory">Potwory</label>
          </div>
      </div>
      </div>
      <span class="zmiana-auto">Zmien we wszystkich powyzej bous Ataku/Życia</span>
      <input type="button" class="btn btn-minus-ap" id="minusAp" value="-" />
      <input type="number" class="ilosc-ap" id="iloscAp" title="Dodaj/usuń bonus ataku do wszystkich jednostek" value="25"/>
      <input type="button" class="btn btn-plus-ap" id="plusAp" value="+" />

      <input type="button" class="btn btn-minus-hp" id="minusHp" value="-" />
      <input type="number" class="ilosc-hp" id="iloscHp" title="Dodaj/usuń bonus życia do wszystkich jednostek" value="25"/>
      <input type="button" class="btn btn-plus-hp" id="plusHp" value="+" />

      <input type="button" class="btn btn-reset-all" value="Reset All" />
      <input type="button" class="btn btn-add" value="Add" />

    </section>
  </section>
        `;

  return html;
}
