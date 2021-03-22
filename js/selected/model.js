import { storageName, storageNameMonster } from "./controler.js";
import { raport } from "../raport/view.js";
const PrepRaportPHP = "./php/newWeb/prepareDataToRaport.php";
const data = [];

//pozyskanie danych z local storage - model
//Funkcja zapisujaca do local storage

export function deleteSelectedElement(array, id = null) {
  if (id !== null) {
    for (let i = 0; i < array.length; i++) {
      if (array[i].armyId === id) {
        array.splice(i, 1);
      }
    }
  }
  return array;
}

export function serchElementId(data, id) {
  let toSend = null;
  data.forEach(element => {
    if (element.armyId == id) {
      toSend = element;
    }
  });
  return toSend;
}

export function saveTo(storage = [], id = null, data = null, value = null) {
  storage.forEach(element => {
    if (element.armyId == id) {
      element[data] = value;
    }
  });
  return storage;
}

export function ToServer() {
  let armia = loadFromLocalStorage(storageName);
  let monster = loadFromLocalStorage(storageNameMonster);
  let firstAtak = document.querySelector("input[name=firstAtak]:checked").value;
  const raportView = document.getElementById("raport");

  fetch(PrepRaportPHP, {
    method: "post",
    headers: {
      "Content-Type": "application/json"
    },
    body: JSON.stringify({ armia: armia, monster: monster, first: firstAtak })
  })
    .then(response => response.json())
    .then(singleArmia => raport(raportView, singleArmia))
    .catch(
      err =>
        (raportView.innerHTML = `
    <div class="name-section">
      <span>Raport</span>
    </div>
  Zwolnij troszkę muszę policzyć :) 1raport/1s`)
    );
}

export function saveToLocalStorage(name, data) {
  localStorage.setItem(name, JSON.stringify(data));
}

export function loadFromLocalStorage(name) {
  return localStorage.getItem(name) ? JSON.parse(localStorage.getItem(name)) : data;
}
