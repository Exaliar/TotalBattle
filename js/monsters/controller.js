import { Model } from "./model.js";
import { View } from "./view.js";
import { selected, storageNameMonster } from "../selected/controler.js";
import { saveToLocalStorage, ToServer } from "../selected/model.js";

class Controller {
  constructor(model, view) {
    this.model = model;
    this.view = view;
    this.monsters = "monsters";
  }
  async singleDataMonster(typ, id) {
    const singleDataMonster = await this.model.getSingleMonster(typ, id);
    return singleDataMonster;
  }

  async dataMonster() {
    const dataMonster = await this.model.getDataMonster(this.monsters);
    return dataMonster;
  }

  async menuMonster(monsters) {
    const data = await this.dataMonster();
    const menuMonster = await this.view.templateMonsters(data);
    monsters.innerHTML = menuMonster;
  }

  eventOnMonsters() {
    document.addEventListener("click", event => {
      if (event.target.parentElement.matches(".first-sub-menu")) {
        let parent = event.target.parentElement.offsetParent.children;

        for (let item of parent) {
          item.classList.remove("first-activ");
        }
        event.target.parentElement.classList.add("first-activ");
      }

      if (event.target.parentElement.matches(".second-sub-menu")) {
        let parent = event.target.parentElement.offsetParent.children;

        for (let item of parent) {
          item.classList.remove("second-activ");
        }
        event.target.parentElement.classList.add("second-activ");
      }

      if (event.target.matches(".each-monster")) {
        const monster = this.singleDataMonster(event.target.getAttribute("data-monster"), event.target.getAttribute("data-id"));
        const selectedView = document.getElementById("selected");
        const monsters = document.getElementById("monsters");
        monsters.style.display = "none";

        monster
          .then(response => saveToLocalStorage(storageNameMonster, response))
          .then(function() {
            selected(selectedView);
            ToServer();
          });
      }

      if (event.target.matches(".center")) {
        let monsters = document.getElementById("monsters");
        monsters.style.display = "none";
      }
    });
  }
}

export const Monster = new Controller(Model, View);
