class Vie {
  constructor(maxLvl = null) {
    this.html = ``;
    this.firstMenu = ["Gwardziści", "Specjaliści", "Korpus inżynierów", "Smoki", "Elementale", "Olbrzymy", "Bestie", "Market"];
    this.classMenu = ["Gwardzista", "Specjalista", "Korpus Inzynierow", "Smok", "Elemental", "Olbrzym", "Bestia", "Autorytet"];
    this.flagMenu = true;
    this.flagMonsters = true;
    this.classUsing = "";
  }

  flagMen() {
    if (this.flagMenu) {
      this.flagMenu = false;
      return "first-activ";
    } else {
      return "";
    }
  }

  flagMon() {
    if (this.flagMonsters) {
      this.flagMonsters = false;
      return "second-activ";
    } else {
      return "";
    }
  }

  //ThirdGropuMenu
  rowArmia(groupArmy) {
    const lvlsArmy = ["I", "II", "III", "IV", "V", "VI", "VII", "VIII", "IX", "X"];
    let elToSend = ``;
    let checked = "checked";
    groupArmy.sort((a, b) => a.lvl - b.lvl);
    groupArmy.forEach(e => {
      let lvl = e.lvl;
      elToSend += ` <input type="radio" id="${e.idarmia}" name="${e.nazwa}" value="${e.nazwa}" ${checked}>
                    <label for="${e.idarmia}" class="${lvlsArmy[lvl - 1]}">${lvlsArmy[lvl - 1]}</label>`;
      checked = "";
    });

    return elToSend;
  }

  //SecondLvlMenu
  secEle(typArray) {
    let li = ``;
    let uniqElement = Array.from(
      new Set(
        typArray.map(el => {
          return el.nazwa;
        })
      )
    );

    uniqElement.forEach(oneUniqElement => {
      let groupArmy = typArray.filter(filteredElement => {
        if (filteredElement.nazwa === oneUniqElement) return filteredElement;
      });

      li += `
        <li class="second-sub-menu-armia ${this.classUsing} ${this.flagMon()}">
          <ul class="armia-column">
              <span>${oneUniqElement}</span>
              <form class="armia-row">
                ${this.rowArmia(groupArmy)}
                <input type="button" class="btn btn-radio" value="Ok">
              </form>
          </ul>
          <hr class="hr-armia">
        </li>`;
    });

    this.flagMonsters = true;
    return li;
  }

  firstEle(data) {
    let tempo = ``;
    for (let index = 0; index < this.classMenu.length; index++) {
      this.classUsing = this.classMenu[index].replace(" ", "-").toLowerCase();
      let typArray = [];

      data.filter(e => {
        if (e.typ === this.classMenu[index]) {
          typArray.push(e);
        }
      });
      tempo += `
      <li class="first-sub-menu-armia ${this.flagMen()}">
        <span>${this.classMenu[index]}</span>
        <ul class="second-menu-armia ${this.classUsing}">
          ${this.secEle(typArray)}
        </ul>
      </li>`;
    }
    return tempo;
  }

  //FirstMenu argument json witch data from server or localStorage
  // <span class="close-army">&times;</span>
  templateArmia(data) {
    if (data) {
      this.html = `
        <nav class="center">
          <ul class="first-menu-armia">
            ${this.firstEle(data)}
          </ul>
        </nav>`;
    } else {
      this.html = "ERROR";
    }
    return this.html;
  }
}

export const View = new Vie(50);
