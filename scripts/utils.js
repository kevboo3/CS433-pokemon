const NUMPKM = 151;
const DITTO = 132;
const TEAMSIZE = 6;
const NUMSTATS = 7;
const NUMMOVES = 4;

class Attributes {
    constructor(total, hp, atk, def, spAtk, spDef, speed, legendary) {
        this.total = total;          // Int
        this.hp = hp;                // Int
        this.atk = atk;              // Int
        this.def = def;              // Int
        this.spAtk = spAtk;          // Int
        this.spDef = spDef;          // Int
        this.speed = speed;          // Int
        this.legendary = legendary   // Bool
    }
    
}

class Pokemon {
    constructor(id, name, types, attr, hp, status, moves, img) {
        this.id = id;          // Int
        this.name = name;      // String
        this.types = types;    // String[2]
        this.attr = attr;      // Attributes
        this.hp = hp;          // Int
        this.status = status;  // String
        this.moves = moves;    // Move()[4]
        this.img = img;        // String
    }
}

class Team {
    constructor(pkm) {
        this.pkm = pkm;  // Pokemon()[6]
    }
}

class Move {
    constructor(name, type, category, power, accuracy, pp, effect, tags) {
        this.name = name;          // String
        this.type = type;          // String
        this.category = category;  // String
        this.power = power;        // Int
        this.accuracy = accuracy;  // Int
        this.pp = pp;              // Int
        this.effect = effect;      // String
        this.tags = tags;          // String[2]
    }
}

function genId() {
    let n;
    do {
        n = Math.ceil(Math.random() * NUMPKM);
    }
    while (n == DITTO);
    return n;
}