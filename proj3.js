const use_move = (caster, target, move) => {

}

const moveHits = (caster, target, move) => {
    return(move.accuracy * caster.accuracy * target.evasion > Math.floor(Math.random() * 256));
}

const damage = (caster, target, move) => {
    var level = 1;
    var critThreshold = move.status.includes("High Crit") ? Math.min(8 * Math.floor(caster.speed / 2), 255) : Math.floor(caster.speed / 2);
    var crit = critThreshold > Math.floor(Math.random() * 256) ? 2 : 1;
    var STAB = target.type1 == move.type || target.type2 == move.type ? 1.5 : 1;
    var attack = move.type == "special" ? caster.specialAttack : caster.attack;
    var defense = move.type == "special" ? caster.specialDefense : caster.defense;
    var damage = (((2 * level / 5 * crit + 2) * move.power * attack / defense)/ 50 + 2) * STAB * getTypeAdvantage(move.type, target.type1) * getTypeAdvantage(move.type, target.type2);
    target.health -= damage;
}
// TODO
const getTypeAdvantage = (attackerType, defenderType) =>{
    return(1);
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

