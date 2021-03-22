class Vie {
  constructor(maxLvl) {
    this.html = ``;
    this.maxLvl = maxLvl || 1;
    this.lvlSkip = 5;
    this.firstMenu = ["Zwykłe", "Rzadkie", "Heroiczne", "Twierdze"];
    this.classMenu = ["zwykle", "rzadkie", "heroiczne", "twierdze"];
    this.monster = [
      "Nieumarly",
      "Elf",
      "Wyklety",
      "Barbarzynca",
      "Piekielny-Demon"
    ];
    this.monsterArray = [];
    this.rowHead = [
      "Lvl",
      "Nieumarły",
      "Elf",
      "Wyklęty",
      "Barbarzyńca",
      "Piekielny"
    ];
    this.flagMenu = true;
    this.flagMonsters = true;
    this.classUsing = "";
    this.countLvl = 1;
    this.arrayStart = 0;
    this.keyObjectArray = "";
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
  rowMonster() {
    let elToSend = ``;

    for (let lvls = 0; lvls < this.lvlSkip; lvls++) {
      let rowToSend = [
        '<span class="brak">1</span>', //lvl
        '<span class="brak">Brak</span>', //Nieumarly
        '<span class="brak">Brak</span>', //Elf
        '<span class="brak">Brak</span>', //Wyklety
        '<span class="brak">Brak</span>', //Barbarzynca
        '<span class="brak">Brak</span>' //Piekielny-Demon
      ];

      for (
        let ele = this.arrayStart;
        ele < this.monsterArray.length;
        ele++, this.arrayStart++
      ) {
        const element = this.monsterArray[ele];
        if (element.lvl == this.countLvl) {
          switch (element.typ) {
            case this.monster[0]:
              rowToSend[1] = `<span class="each-monster" data-monster="${
                this.classUsing
              }" data-id="${element[this.keyObjectArray]}">${
                this.rowHead[1]
              }</span>`;
              break;
            case this.monster[1]:
              rowToSend[2] = `<span class="each-monster" data-monster="${
                this.classUsing
              }" data-id="${element[this.keyObjectArray]}">${
                this.rowHead[2]
              }</span>`;
              break;
            case this.monster[2]:
              rowToSend[3] = `<span class="each-monster" data-monster="${
                this.classUsing
              }" data-id="${element[this.keyObjectArray]}">${
                this.rowHead[3]
              }</span>`;
              break;
            case this.monster[3]:
              rowToSend[4] = `<span class="each-monster" data-monster="${
                this.classUsing
              }" data-id="${element[this.keyObjectArray]}">${
                this.rowHead[4]
              }</span>`;
              break;
            case this.monster[4]:
              rowToSend[5] = `<span class="each-monster" data-monster="${
                this.classUsing
              }" data-id="${element[this.keyObjectArray]}">${
                this.rowHead[5]
              }</span>`;
              break;
          }
        } else {
          rowToSend[0] = `<span class="brak">${this.countLvl}</span>`;
          elToSend += `
          <hr>
          <li>
            ${rowToSend.join("")}
          </li>`;
          this.countLvl++;
          break;
        }
      }
    }
    return elToSend;
  }

  //SecondLvlMenu
  secEle() {
    let li = ``;
    for (let index = 0; index < this.maxLvl; index += this.lvlSkip) {
      li += `
        <li class="second-sub-menu ${this.classUsing} ${this.flagMon()}">
            <span>${index + 1}-${index + this.lvlSkip} lvl</span>
            <div class="monsters">
              <ul class="monster-column">
                ${this.rowHead.map(el => `<span>${el}</span>`).join("")}
                ${this.rowMonster()}
              </ul>
            </div>
        </li>`;
    }

    this.flagMonsters = true;
    return li;
  }

  firstEle(data) {
    let tempo = ``;

    for (let i = 0; i < data.length; i++) {
      let element = this.firstMenu[i];
      this.classUsing = this.classMenu[i];
      this.monsterArray = data[i];
      let keys = Object.keys(this.monsterArray[0]);
      this.keyObjectArray = keys[0];

      tempo += `
      <li class="first-sub-menu ${this.flagMen()}">
        <span>${element}</span>
        <ul class="second-menu ${this.classUsing}">
          ${this.secEle()}
        </ul>
      </li>`;

      this.arrayStart = 0;
      this.countLvl = 1;
    }
    return tempo;
  }

  //FirstMenu argument json witch data from server or localStorage
  templateMonsters(data) {
    if (data.length === this.firstMenu.length) {
      this.html = `
        <nav class="center">
            <ul class="first-menu">
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
