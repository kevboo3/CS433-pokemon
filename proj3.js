const do_turn = (player) => {

}

const activate_effects = (pokemon) => {
    if (pokemon.burn) {
        pokemon.health = Math.max(0, pokemon.health - pokemon.health / 16);
    }
    if (pokemon.charge) {
        pokemon.health = Math.max(0, pokemon.health - pokemon.charge);
        pokemon.charge = 0;
    }

    if (pokemon.DOT > 0) {
        pokemon.health = Math.max(0, pokemon.health - pokemon.DOT_damage);
        pokemon.DOT -= 1;
    }
    if (pokemon.poison) {
        pokemon.health = Math.max(1, pokemon.health - pokemon.health / 16);
    }


}

const use_move = (caster, target, move) => {
    if (caster.sleep > 0) {
        caster.sleep -= 1;
        return(null);
    }
    if (caster.flinch) {
        caster.flinch = false;
        return (null)
    }
    if (caster.confuse && Math.random() <= 0.50) {
        //TODO: get the move pound instead of a string
        caster.health -= damage(caster, caster, "Pound")
    }
    if (pokemon.freeze) {
        return (null)
    }
    if (caster.paralyze && Math.random() <= 0.25) {
        return (null);
    }
    if (move.effect1 == "Multihit") {
        for (i = 0; i < move.effect2; i++) {
            if (moveHits(caster, target, move)) {
                move_effect(caster, target, move)
            }
        }
    }
    else if (moveHits(caster, target, move)) {
        move_effect(caster, target, move)
    }
}

const move_effect = (caster, target, move) => {
    effect1 = move.effect1;
    effect2 = move.effect2;

    if (effect1 == "Paralyze/Burn/Freeze") {
        if (Math.random() < 0.2) {
            target.paralyze = 0.2;
        }
        if (Math.random() < 0.2) {
            target.burn = 0.2;
        }
        if (Math.random() < 0.2) {
            target.freeze = 0.2;
        }
    }
    if (effect2 == "Double") {
        value = 2;
    }
    if (effect2 == "Maybe") {
        if (Math.random() < 0.2) {
            damage(caster, target, move);
            target.health -= damage(caster, target, move);
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
        target.freeze = false;
    }
    if (effect1 == "Charge") {
        target.charge += damage(caster, target, move);
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
        target.DOT = 4 + Math.floor(Math.random() * 2)
        target.DOT_damage = move.power
    }
    if (effect1 == "E buff") {
        caster.evasion = value * 20;
    }
    if (effect1 == "Flat") {
        if (effect2 > 1) {
            target.health -= effect2;
        }
        else {
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
        if (effect2 > 1) {
            caster.health += effect2;
        }
        else {
            caster.health += effect2 * target.health;
        }
    }
    if (effect1 == "Insta") {
        target.health = 0;
    }
    if (effect1 == "Paralyze") {
        target.paralyze = true;
        if (target.paralyzeSpeedDecrease == false) {
            target.paralyzeSpeedDecrease = true;
            target.speed = target.speed * 0.25;
        }
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
        var rand_num = Math.random();
        if (rand_num < 1 / 8) {
            target.sleep = Math.max(target, sleep, 1);
        }
        else if (rand_num < 1 / 8 + 1 / 4) {
            target.sleep = Math.max(target, sleep, 2);
        }
        else {
            target.sleep = Math.max(target.sleep, Math.floor(Math.random() * 5) + 2)
        }
        target.sleep = true;
    }
    target.health = Math.max(0, target.health - damage(caster, target, move));
}

const moveHits = (caster, target, move) => {
    return (move.accuracy * caster.accuracy * target.evasion > Math.floor(Math.random() * 256));
}

const damage = (caster, target, move) => {
    var level = 1;
    var critThreshold = move.effect1 == "High Crit" || move.effect2 == "High Crit" ? Math.min(8 * Math.floor(caster.speed / 2), 255) : Math.floor(caster.speed / 2);
    var crit = critThreshold > Math.floor(Math.random() * 256) ? 2 : 1;
    var STAB = target.type1 == move.type || target.type2 == move.type ? 1.5 : 1;
    var attack = move.type == "special" ? caster.specialAttack : caster.attack;
    var defense = move.type == "special" ? caster.specialDefense : caster.defense;
    var damage = (((2 * level / 5 * crit + 2) * move.power * attack / defense) / 50 + 2) * STAB * getTypeAdvantage(move.type, target.type1) * getTypeAdvantage(move.type, target.type2);
    return (damage);
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

