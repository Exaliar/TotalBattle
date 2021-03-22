import { Model } from "./model.js";
import { View } from "./view.js";
import { displaySelectedAll } from "../selected/view.js";
import { saveToLocalStorage, loadFromLocalStorage, ToServer } from "../selected/model.js";
import { storageName, storageNameMonster } from "../selected/controler.js";

// export const strorageNameArmia = "armia";

class Controller {
  constructor(model, view) {
    this.model = model;
    this.view = view;
    this.armia = "armia";
  }

  async dataArmia() {
    const dataArmia = await this.model.getDataArmia(this.armia);
    // console.log(dataArmia);

    return dataArmia;
  }

  async singleArmia(id) {
    const singleArmia = await this.model.getSingleArmia(id);
    // console.log(singleArmia);

    const selectedView = document.getElementById("selected");
    const single = {
      armyId: parseInt(singleArmia[0].idarmia),
      name: singleArmia[0].nazwa,
      lvl: parseInt(singleArmia[0].lvl),
      typ: singleArmia[0].typ,
      typ1: singleArmia[0].typ1,
      typ2: singleArmia[0].typ2,
      typ3: singleArmia[0].typ3,
      sila: parseInt(singleArmia[0].sila),
      zycie: parseInt(singleArmia[0].zycia),
      bonusIle1: singleArmia[0].bonus_ile1 == null ? null : parseInt(singleArmia[0].bonus_ile1),
      bonusKomu1: singleArmia[0].bonus_komu1,
      bonusIle2: singleArmia[0].bonus_ile2 == null ? null : parseInt(singleArmia[0].bonus_ile2),
      bonusKomu2: singleArmia[0].bonus_komu2,
      bonusIle3: singleArmia[0].bonus_ile3 == null ? null : parseInt(singleArmia[0].bonus_ile3),
      bonusKomu3: singleArmia[0].bonus_komu3,
      armyIlosc: 0,
      armyHpBonus: 0,
      armyApBonus: 0
    };
    let data = loadFromLocalStorage(storageName);
    let monsters = loadFromLocalStorage(storageNameMonster);
    let flagaInArray = false;
    console.log(typeof data);
    console.log(typeof monsters);
    if (typeof monsters !== "object") {
      monsters = {};
    }
    if (typeof data === "object") {
      data.forEach(e => {
        if (e.armyId == single.armyId) {
          flagaInArray = true;
        }
      });
      if (flagaInArray) {
        alert("Jednostka została już wybrana! Wybierz inna.");
      } else {
        data.push(single);
        saveToLocalStorage(storageName, data);
        selectedView.innerHTML = displaySelectedAll(data, monsters);
      }
    }
    // console.log(data);
  }

  async menuArmia(armia) {
    const data = await this.dataArmia();
    const menuArmia = await this.view.templateArmia(data);
    armia.innerHTML = menuArmia;
  }

  async eventOnArmia() {
    document.addEventListener("click", event => {
      if (event.target.parentElement.matches(".first-sub-menu-armia")) {
        let parent = event.target.parentElement.offsetParent.children;
        // console.log(parent);
        for (let item of parent) {
          item.classList.remove("first-activ");
        }
        event.target.parentElement.classList.add("first-activ");
      }

      if (event.target.matches(".btn-radio")) {
        let target = event.target.parentElement.childNodes;
        // console.log(target);
        target.forEach(element => {
          if (element.checked) {
            this.singleArmia(element.id);
            const armia = document.getElementById("armia");
            armia.style.display = "none";
            ToServer();
          }
        });
      }

      if (event.target.matches(".center") || event.target.matches(".close-army")) {
        const armia = document.getElementById("armia");
        armia.style.display = "none";
      }
    });
  }
}

export const Armia = new Controller(Model, View);
