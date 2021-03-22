import { Monster } from "./monsters/controller.js";
import { Armia } from "./armia/controller.js";
import { selected, eventOnSelected } from "./selected/controler.js";
import { ToServer } from "./selected/model.js";
export const raportView = document.getElementById("raport");
export const selectedView = document.getElementById("selected");

export function newCalculator() {
  const monsters = document.getElementById("monsters");
  const armia = document.getElementById("armia");
  const select = document.getElementById("selected");

  Monster.menuMonster(monsters);
  Monster.eventOnMonsters();

  Armia.menuArmia(armia);
  Armia.eventOnArmia();

  selected(select);
  eventOnSelected();

  ToServer();
}
