import { saveToLocalStorage, loadFromLocalStorage } from "../selected/model.js";
class Mod {
  constructor(url) {
    this.urlMonster = url || "./php/newWeb/monsterData.php";
  }
  getDataArmia(data) {
    if (localStorage.getItem(data)) {
      return loadFromLocalStorage(data);
    } else {
      const result = fetch(this.urlMonster, {
        method: "post",
        headers: {
          Accept: "application/json, text/plain, */*",
          "Content-Type": "application/json"
        },
        body: JSON.stringify({ data: data })
      })
        .then(response => response.json())
        .then(armia => {
          saveToLocalStorage(data, armia);
          return armia;
        })
        .catch(err => err);
      return result;
    }
  }
  getSingleArmia(id) {
    const selected = fetch(this.urlMonster, {
      method: "post",
      headers: {
        Accept: "application/json, text/plain, */*",
        "Content-Type": "application/json"
      },
      body: JSON.stringify({ id: id })
    })
      .then(response => response.json())
      .then(singleArmia => singleArmia)
      .catch(err => err);
    return selected;
  }
}

export const Model = new Mod();
