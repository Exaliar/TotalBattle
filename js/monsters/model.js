class Mod {
  constructor(url) {
    this.urlMonster = url || "./php/newWeb/monsterData.php";
  }

  getDataMonster(data) {
    const url = this.urlMonster;

    const result = fetch(url, {
      method: "post",
      headers: {
        Accept: "application/json, text/plain, */*",
        "Content-Type": "application/json"
      },
      body: JSON.stringify({ data: data })
    })
      .then(response => response.json())
      .then(data => data)
      .catch(err => err);
    return result;
  }

  getSingleMonster(typ, id) {
    const monsterJSON = {
      nazwaObiektu: "Rzadki",
      lvlObiektu: 19,
      typObiektu: "Elf",
      jednostkiObiektu: [
        { nazwa: "Jezdziec Pegazow", ilosc: 410, typ: ["Latajaca"], bonus: [60, 50], komu: ["Piechota", "Smok"], atak: 8200, zycie: 24600 },
        { nazwa: "Druid", ilosc: 2800, typ: ["Dalekosiezna"], bonus: [25], komu: ["Piechota"], atak: 900, zycie: 2700 },
        { nazwa: "Lesny Gnom", ilosc: 91000, typ: ["Piechota"], bonus: [10], komu: ["Jezdziec"], atak: 28, zycie: 84 }
      ]
    };
    const url = this.urlMonster;
    const result = fetch(url, {
      method: "post",
      headers: {
        Accept: "application/json, text/plain, */*",
        "Content-Type": "application/json"
      },
      body: JSON.stringify({ typSingle: typ, idSingle: id })
    })
      .then(response => response.json())
      .then(data => data)
      .catch(err => err);
    return result;
  }
}

export const Model = new Mod();
