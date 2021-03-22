import { displaySelectedAll, displaySelectedApHp } from "./view.js";
import { saveTo, saveToLocalStorage, loadFromLocalStorage, serchElementId, deleteSelectedElement, ToServer } from "./model.js";
import { raport } from "../raport/view.js";
import { Armia } from "../armia/controller.js";
import { selectedView } from "../script.js";

export const storageName = "selected";
export const storageNameMonster = "monsters";
const storageNameArmia = "armia";

const firstAtakChange = document.querySelector("input[name=firstAtak]");

//POTRZEBNE

export function selected(select) {
  let monsters = loadFromLocalStorage(storageNameMonster);
  let armia = loadFromLocalStorage(storageName);
  let selectedHTML = displaySelectedAll(armia, monsters);
  select.innerHTML = selectedHTML; //funkcja wyswietlajaca dane//
}

export function eventOnSelected() {
  document.addEventListener("input", event => {
    //Manipulacja Max ilosc
    if (event.target.matches("#armyIlosc")) {
      let valueTarget = event.target.value == "" ? 0 : parseInt(event.target.value);
      let idTarget = event.target.parentElement.parentElement.dataset.id;
      let data = loadFromLocalStorage(storageName);

      //Zmiany InputRange = Max, Value i SPAN display
      event.target.parentElement.childNodes[17].childNodes[3].max = valueTarget;
      event.target.parentElement.childNodes[17].childNodes[3].value = valueTarget;
      event.target.parentElement.childNodes[17].childNodes[1].innerHTML = valueTarget;

      // Funkcja zapisujaca local storage
      data = saveTo(data, idTarget, "armyIlosc", valueTarget);
      saveToLocalStorage(storageName, data);

      // Funkcja wyswietlajaca obliczenia
      event.target.parentElement.childNodes[19].childNodes[5].innerHTML = displaySelectedApHp(serchElementId(data, idTarget));

      // Funkcja wysylajaca zmiane na server
      ToServer();
    }

    //Manipulacja Inputem RANGE
    if (event.target.matches(".army-count")) {
      let valueTarget = event.target.value == "" ? 0 : parseInt(event.target.value);
      let idTarget = event.target.parentElement.parentElement.parentElement.dataset.id;
      let data = loadFromLocalStorage(storageName);

      //Zmiany InputRange = SPAN display
      event.target.parentElement.childNodes[1].innerHTML = event.target.value;

      // Funkcja zapisujaca local storage
      data = saveTo(data, idTarget, "armyIlosc", valueTarget);
      saveToLocalStorage(storageName, data);

      // Funkcja wyswietlajaca obliczenia
      event.target.parentElement.parentElement.childNodes[19].childNodes[5].innerHTML = displaySelectedApHp(serchElementId(data, idTarget));

      // Funkcja wysylajaca zmiane na server
      // Ujete w innym evencie
    }

    //Manipulacja BonusAP
    if (event.target.matches("#armyApBonus")) {
      let valueTarget = event.target.value == "" ? 0 : parseFloat(event.target.value);
      let idTarget = event.target.parentElement.parentElement.dataset.id;
      let data = loadFromLocalStorage(storageName);

      // Funkcja zapisujaca local storage
      data = saveTo(data, idTarget, "armyApBonus", valueTarget);
      saveToLocalStorage(storageName, data);

      // Funkcja wyswietlajaca obliczenia
      event.target.parentElement.childNodes[19].childNodes[5].innerHTML = displaySelectedApHp(serchElementId(data, idTarget));

      //Funkcja wysylajaca zmiane na server
      ToServer();
    }

    //Manipulacja BonusHP
    if (event.target.matches("#armyHpBonus")) {
      let valueTarget = event.target.value == "" ? 0 : parseFloat(event.target.value);
      let idTarget = event.target.parentElement.parentElement.dataset.id;
      let data = loadFromLocalStorage(storageName);

      // Funkcja zapisujaca local storage
      data = saveTo(data, idTarget, "armyHpBonus", valueTarget);
      saveToLocalStorage(storageName, data);

      // Funkcja wyswietlajaca obliczenia
      event.target.parentElement.childNodes[19].childNodes[5].innerHTML = displaySelectedApHp(serchElementId(data, idTarget));

      //Funkcja wysylajaca zmiane na server
      ToServer();
    }
  });

  //pokazanie menu armii
  document.addEventListener("click", event => {
    //Dodanie nowej jednostki input button
    if (event.target.matches(".btn-add")) {
      const armia = document.getElementById("armia");
      armia.style.display = "flex";
    }

    //Usuniecie jednostki
    if (event.target.matches(".btn-danger")) {
      let targetId = parseInt(event.target.parentElement.parentElement.dataset.id);
      if (confirm("Usunąć jednostke?")) {
        let data = loadFromLocalStorage(storageName);
        let newData = deleteSelectedElement(data, targetId);
        let monsters = loadFromLocalStorage(storageNameMonster);
        saveToLocalStorage(storageName, newData);
        let selectElement = document.querySelector("#selected");
        let selectedHTML = displaySelectedAll(newData, monsters);
        selectElement.innerHTML = selectedHTML;
        ToServer();
      }
    }

    //Edytowanie jednostki
    if (event.target.matches(".btn-neutral")) {
      //Dodanie elementu w przyszlosci
    }

    //zmiana we wszystkich inputach bonus ataku
    if (event.target.matches(".btn-minus-ap") || event.target.matches(".btn-plus-ap")) {
      let valueToAdd = document.getElementById("iloscAp").value;
      let selectElement = event.target.parentElement.parentElement.parentElement;
      let data = loadFromLocalStorage(storageName);

      if (event.target.matches(".btn-minus-ap")) {
        data.forEach(each => {
          if (parseFloat(each.armyApBonus) - parseFloat(valueToAdd) < 0) {
            each.armyApBonus = 0;
          } else {
            each.armyApBonus = parseFloat(each.armyApBonus) - parseFloat(valueToAdd);
          }
        });
      }

      if (event.target.matches(".btn-plus-ap")) {
        data.forEach(each => {
          each.armyApBonus = parseFloat(each.armyApBonus) + parseFloat(valueToAdd);
        });
      }

      saveToLocalStorage(storageName, data);

      selected(selectElement);

      ToServer();
    }

    //zmiana we wszystkich inputach bonus zycia
    if (event.target.matches(".btn-minus-hp") || event.target.matches(".btn-plus-hp")) {
      let valueToAdd = document.getElementById("iloscHp").value;
      let selectElement = event.target.parentElement.parentElement.parentElement;
      let data = loadFromLocalStorage(storageName);

      if (event.target.matches(".btn-minus-hp")) {
        data.forEach(each => {
          if (parseFloat(each.armyHpBonus) - parseFloat(valueToAdd) < 0) {
            each.armyHpBonus = 0;
          } else {
            each.armyHpBonus = parseFloat(each.armyHpBonus) - parseFloat(valueToAdd);
          }
        });
      }

      if (event.target.matches(".btn-plus-hp")) {
        data.forEach(each => {
          each.armyHpBonus = parseFloat(each.armyHpBonus) + parseFloat(valueToAdd);
        });
      }

      saveToLocalStorage(storageName, data);

      selected(selectElement);

      ToServer();
    }

    //pokazanie menu potworow
    if (event.target.matches(".btn-monster")) {
      const monsters = document.getElementById("monsters");
      monsters.style.display = "block";
    }

    //reset all delete local storage
    if (event.target.matches(".btn-reset-all")) {
      if (confirm("Czy napewno chcesz wyczyścić wszystkie wybrane pozycje?")) {
        const select = document.getElementById("selected");
        const raporte = document.getElementById("raport");
        const armia = document.getElementById("armia");

        localStorage.removeItem(storageName);
        localStorage.removeItem(storageNameMonster);
        localStorage.removeItem(storageNameArmia);

        // let inHtmlSelect = displaySelectedAll();
        select.innerHTML = displaySelectedAll();

        raport(raporte);

        // armia.innerHTML = "";
        Armia.menuArmia(armia);
        // Armia.eventOnArmia();
        // raporte.innerHTML = inHtmlRaport;
      }
    }
  });

  document.addEventListener("change", event => {
    if (event.target.matches(".new-calculator-change-atak")) {
      ToServer();
    }
  });

  document.addEventListener("mouseup", event => {
    if (event.target.matches(".army-count")) {
      ToServer();
    }
  });
  document.addEventListener("keyup", event => {
    if (event.code === "Enter") ToServer();
  });
}
