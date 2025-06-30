const use_move = (caster, target, move) => {
}

const move_effect = (caster, target, effect1, effect2) => {


    if (effect1 == "Paralyze/Burn/Freeze") {
        move_effect(caster, target, "Paralyze", "Maybe");
        move_effect(caster, target, "Burn", "Maybe");
        move_effect(caster, target, "Freeze", "Maybe");
    }
    if (effect2 == "Double") {
        value = 2;
    }
    if (effect2 == "Maybe") {
        if (Math.random() < 0.2) {
            return (null);
        }
    }
    if (effect1 == "A buff") {
        caster.attack += value * 20;
    }
    if (effect1 == "A debuff") {
        target.attack -= Math.max(value * 20, 0);
    }
    if (effect1 == "Ac debuff") {
        target.accuracy -= Math.max(value * 20, 0);
    }
    if (effect1 == "Burn") {
        target.burn = true;
    }
    if (effect1 == "Charge") {

    }
    if (effect1 == "Confuse") {
        target.confuse = true;
    }
    if (effect1 == "D buff") {
        caster.defense = value * 20;
    }
    if (effect1 == "D debuff") {
        target.defense -= Math.max(value * 20, 0);
    }
    if (effect1 == "DOT") {

    }
    if (effect1 == "E buff") {
        caster.evasion = value * 20;
    }
    if (effect1 == "Flat") {
        if (effect2 > 1){
            target.health -= effect2;
        }
        else{
            target.health -= effect2 * target.health;
        }
    }
    if (effect1 == "Flinch") {
        target.flinch = true;
    }
    if (effect1 == "Freeze") {
        target.freeze = true;
    }
    if (effect1 == "Heal") {

    }
    if (effect1 == "High Crit") {

    }
    if (effect1 == "Insta") {
        target.health = 0;
    }
    if (effect1 == "Multihit") {

    }

    if (effect1 == "Paralyze") {
        target.paralyze = true;
    }
    if (effect1 == "Poison") {
        target.poison = true;
    }
    if (effect1 == "Recoil") {
        caster.health = caster.health - caster.health * effect2;
    }
    if (effect1 == "S buff") {
        caster.speed = value * 20;
    }
    if (effect1 == "S debuff") {
        target.speed -= Math.max(value * 20, 0);
    }
    if (effect1 == "SA buff") {
        caster.specialAttack = value * 20;
    }
    if (effect1 == "SD buff") {
        caster.specialDefense = value * 20;
    }
    if (effect1 == "SD debuff") {
        target.specialDefense -= Math.max(value * 20, 0);
    }
    if (effect1 == "Sleep") {
        target.sleep = true;
    }

}

const moveHits = (caster, target, move) => {
    return (move.accuracy * caster.accuracy * target.evasion > Math.floor(Math.random() * 256));
}

const damage = (caster, target, move) => {
    var level = 1;
    var critThreshold = move.status.includes("High Crit") ? Math.min(8 * Math.floor(caster.speed / 2), 255) : Math.floor(caster.speed / 2);
    var crit = critThreshold > Math.floor(Math.random() * 256) ? 2 : 1;
    var STAB = target.type1 == move.type || target.type2 == move.type ? 1.5 : 1;
    var attack = move.type == "special" ? caster.specialAttack : caster.attack;
    var defense = move.type == "special" ? caster.specialDefense : caster.defense;
    var damage = (((2 * level / 5 * crit + 2) * move.power * attack / defense) / 50 + 2) * STAB * getTypeAdvantage(move.type, target.type1) * getTypeAdvantage(move.type, target.type2);
    target.health -= damage;
}
// TODO
const getTypeAdvantage = (attackerType, defenderType) => {
    return (1);
}

/**
 * Frozen
 * Burned
 * Confused
 * Poisoned
 * Asleep
 * Paralyized
 * Heal
 * Buff Attack
 * 
 */
/**
 * Move Structure
 * Target
 * Status effect
 * Type
 */

/**
 * Move Targets
 * Self
 * Opponent
 */

/**
 * Move Status effects
 * Heal
 * Attack
 * Freeze
 * Confuse
 * Stat Buff
 * Stat Debuff
 * Poison
 * Multi Hit
 * Charge Attack
 * High Crit
 * Burn
 * Flinch
 * Paralyze
 * Sleep
 * Damage Over Time
 * Suicide
 * Dodge
 * Defend
 * Insta kill
 * Recoil
 */

